@extends('voyager::master')

@section('page_title', $dataType->getTranslatedAttribute('display_name_plural') . ' ' . __('voyager::bread.order'))

@section('page_header')
<h1 class="page-title">
    <i class="voyager-list"></i>{{ $dataType->getTranslatedAttribute('display_name_plural') }} {{ __('voyager::bread.order') }}
</h1>
@stop

@section('content')
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body" style="padding:30px;">
                    <div class="dd">
                        <form id="my_form" action="{{ route('voyager.files.order') }}" method="get">
                            <div class="form-group">
                                <label for="file_category_id">Choose a Category</label>
                                <select id="file_category_id" class="form-control">
                                    <option value="">Choose one...</option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#file_category_id').change(function() {
                $('#my_form').attr('action', $('#my_form').attr('action')+'/'+$(this).val()).submit();
            });
        });
    </script>
@stop
