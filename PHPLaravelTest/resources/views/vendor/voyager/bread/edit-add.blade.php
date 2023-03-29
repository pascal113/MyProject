@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style type="text/css">
        .address-updated {
            background-color: yellow;
        }
        .files-save-helper-text {
            width: 350px;
            margin-left: auto;
            text-align: right;
            font-size: 11px;
            line-height: auto;
        }
    </style>
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @if ($dataType->name === 'orders' && $dataTypeContent->getKey())
        @can('read', $dataTypeContent)
            <a href="{{ route('voyager.'.$dataType->slug.'.print', ['id' => $dataTypeContent->getKey() ]) }}" class="btn btn-success" target="_blank">
                <i class="voyager-receipt"></i> <span>Print Invoice</span>
            </a>
        @endcan
    @endif
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp
                            @section('files-save-helper-text')
                                @if ($dataType->slug === 'files')
                                    <div>
                                        <p class="files-save-helper-text">
                                            Click Save to complete the file upload. Please do not click save twice. When the upload is done you will be redirected to list view.
                                        </p>
                                    </div>
                                @endif
                            @stop
                            @yield('files-save-helper-text')

                            @section('submit-buttons')
                                <button type="button" onclick="window.history.go(-1);" class="btn btn-warning btn-lg cancel pull-right" style="margin-left: 5px;">{{ __('voyager::generic.cancel') }}</button>
                                <button type="submit" class="btn btn-primary btn-lg save pull-right" style="margin-left: 5px;">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')

                            @if ($edit && $dataTypeContent->url)
                                <div class="view-page-wrapper pull-right">
                                    <a
                                        href="{{ $dataTypeContent->url }}"
                                        target="_blank"
                                        class="btn btn-success btn-lg view-page-btn"
                                        style="margin-left: 5px;"
                                    >
                                        View {{ $dataType->getTranslatedAttribute('display_name_singular') }}
                                    </a>
                                </div>
                            @else
                                <div style="clear: both;"></div>
                            @endif

                            @if ($dataType->name === 'products')
                                <h2>Product Information</h2>
                            @endif

                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                @if ($row->field === 'meta_title')
                                    <h2>Meta Data</h2>
                                @endif

                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    @if ($row->field === 'product_belongstomany_product_shipping_prices_relationship')
                                        <hr style="border-top: solid 3px #ccc;" />
                                    @endif
                                    @if ($row->field === 'location_belongstomany_service_relationship' || $row->field === 'product_belongstomany_product_shipping_prices_relationship')
                                        <h2>{{ $row->getTranslatedAttribute('display_name') }}</h2>
                                    @else
                                        <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>{!! !$row->required ? ' <i>(optional)</i>' : '' !!}
                                    @endif
                                    @if (isset($row->details->helperText))
                                        <p id="{{ $row->field }}HelpBlock" class="form-text text-muted">{{ $row->details->helperText }}</p>
                                    @endif

                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if (isset($row->details->view))
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add')])
                                    @elseif ($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
                                    @elseif ($row->field === 'minimum_cart_total')
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input
                                                type="number"
                                                class="form-control"
                                                name="minimum_cart_total"
                                                value="{{ $dataTypeContent->{$row->field} }}"
                                                step="any"
                                                placeholder="Minimum Cart Total"
                                                required
                                            />
                                        </div>
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                    @if ($errors->has($row->field))
                                        @foreach ($errors->get($row->field) as $error)
                                            <span class="help-block">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @yield('files-save-helper-text')
                            @yield('submit-buttons')
                            @if ($edit && Auth::user()->hasPermission('delete_'.$dataType->name))
                                <button type="button" class="btn btn-danger btn-lg delete" style="margin-left: 0;" data-id="{{ $dataTypeContent->getKey() }}">{{ __('voyager::generic.delete') }}</button>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                            enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                                 onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>

    @include('voyager::bread.partials.delete', ['dataType' => $dataType])
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        @if ($dataType->name === 'locations')
            function updateAddress(eventType, data) {
                if (eventType === 'placeChanged' && data.place.address_components) {
                    var streetNumber = getAddressComponentValue(data.place.address_components, 'street_number');
                    var street = getAddressComponentValue(data.place.address_components, 'route');
                    var city = getAddressComponentValue(data.place.address_components, 'locality');
                    var state = getAddressComponentValue(data.place.address_components, 'administrative_area_level_1');
                    var zip = getAddressComponentValue(data.place.address_components, 'postal_code');

                    var addressLine1 = ((streetNumber && streetNumber.long_name+' ') || '')+((street && street.long_name) || '');
                    var addressLine2 = ((city && city.long_name+', ') || '')+((state && state.short_name+' ') || '')+((zip && zip.long_name) || '');

                    $('input[name=address_line_1]').val(addressLine1).addClass('address-updated');
                    $('input[name=address_line_2]').val(addressLine2).addClass('address-updated');
                    setTimeout(function() {
                        $('input[name=address_line_1]').removeClass('address-updated');
                        $('input[name=address_line_2]').removeClass('address-updated');
                    }, 1000);
                }
            }
            function getAddressComponentValue(addressComponents, key) {
                var item = addressComponents.filter(function(component) {
                    return component.types[0] === key;
                });

                return item ? item[0] : null;
            }
        @endif

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.type != 'date' || elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
