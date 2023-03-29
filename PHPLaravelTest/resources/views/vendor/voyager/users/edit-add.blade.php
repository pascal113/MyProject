@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <form class="form-edit-add" role="form"
              action="@if(!is_null($dataTypeContent->getKey())){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-bordered">
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

                            @foreach($dataTypeRows as $row)
                                @if ($row->field === 'avatar')
                                    @continue
                                @endif

                                @if ($row->field === 'password')
                                    <div class="form-group">
                                        <label for="password">{{ __('voyager::generic.password') }}</label>
                                        @if(isset($dataTypeContent->password))
                                            <br>
                                            <small>{{ __('voyager::profile.password_hint') }}</small>
                                        @endif
                                        <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password">
                                    </div>

                                    @continue;
                                @endif

                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if ($row->details->legend->text ?? null)
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                @if ($row->field === 'meta_title')
                                    <h2>Meta Data</h2>
                                @endif
                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    @if ($row->field === 'location_belongstomany_service_relationship')
                                        <h2>{{ $row->getTranslatedAttribute('display_name') }}</h2>
                                    @else
                                        <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>{!! !$row->required ? ' <i>(optional)</i>' : '' !!}
                                    @endif
                                    @if ($row->details->helperText ?? null)
                                        <p id="{{ $row->field }}HelpBlock" class="form-text text-muted">{{ $row->details->helperText }}</p>
                                    @endif

                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if ($row->details->view ?? null)
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add')])
                                    @elseif ($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
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
                        </div>

                        <div class="panel-footer">
                            @yield('submit-buttons')
                            @if ($edit && Auth::user()->hasPermission('delete_'.$dataType->name))
                                <button type="button" class="btn btn-danger btn-lg delete" style="margin-left: 0;" data-id="{{ $dataTypeContent->getKey() }}">{{ __('voyager::generic.delete') }}</button>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel panel-bordered panel-warning">
                        <div class="panel-body">
                            <div class="form-group">
                                @if(isset($dataTypeContent->avatar))
                                    <img src="{{ filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Voyager::image( $dataTypeContent->avatar ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" data-name="avatar" name="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
        </form>
    </div>

    @include('voyager::bread.partials.delete', ['dataType' => $dataType])
@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
        });
    </script>
@stop
