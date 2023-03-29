@php
    $isInternal = true;
@endphp

@extends('emails.layouts.text')

@section('content')
    The {{ $unpublishedPageTitle }} page has been un-published, which broke links to that page. The following pages have been affected by this:

    @foreach($affectedPages as $page)
        * {{ $page->title }} ({{ URL::to(\FPCS\FlexiblePageCms\Services\CmsRoute::getPathByPage($page)) }}) | Edit this page: {{ URL::to('/admin/pages/'. $page->id .'/edit') }}
    @endforeach
@endsection
