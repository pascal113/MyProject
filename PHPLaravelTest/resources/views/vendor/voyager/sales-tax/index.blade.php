@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Sales Tax')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $icon_class }}"></i> Sales Tax
        </h1>
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                        class="form-edit-add"
                        action="{{ route('voyager.sales-tax.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

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

                            @section('submit-buttons')
                                <button type="button" onclick="window.history.go(-1);" class="btn btn-warning btn-lg cancel pull-right" style="margin-left: 5px;">{{ __('voyager::generic.cancel') }}</button>
                                <button type="submit" class="btn btn-primary btn-lg save pull-right" style="margin-left: 5px;">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')

                            <div style="clear: both;"></div>

                            @php
                                $row = (object)[
                                    'field' => 'sales_tax',
                                    'display_name' => 'Global Sales Tax',
                                    'type' => 'number',
                                    'required' => true,
                                    'details' => (object)[
                                        'helperText' => 'Enter the sales tax percentage to be applied to all products ordered on the site.',
                                    ],
                                ];
                                $options = (object)[
                                    'min' => 0,
                                    'max' => 100,
                                ];
                                $dataTypeContent = (object)['sales_tax' => $value];
                            @endphp
                            <div class="form-group col-md-12 {{ $errors->has($row->field) ? 'has-error' : '' }}">
                                <label class="control-label" for="name">{{ $row->display_name }}</label>
                                <p id="{{ $row->field }}HelpBlock" class="form-text text-muted">{{ $row->details->helperText }}</p>

                                <input type="number"
                                    class="form-control"
                                    name="{{ $row->field }}"
                                    type="number"
                                    @if($row->required == 1) required @endif
                                    @if(isset($options->min)) min="{{ $options->min }}" @endif
                                    @if(isset($options->max)) max="{{ $options->max }}" @endif
                                    step="{{ $options->step ?? 'any' }}"
                                    placeholder="{{ old($row->field, $row->display_name) }}"
                                    {!! outputAriaForHelpterText($row) !!}
                                    value="{{ old($row->field, $dataTypeContent->{$row->field} ?? '') }}"
                                >

                                @if ($errors->has($row->field))
                                    @foreach ($errors->get($row->field) as $error)
                                        <span class="help-block">{{ $error }}</span>
                                    @endforeach
                                @endif
                            </div>

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @yield('submit-buttons')
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
