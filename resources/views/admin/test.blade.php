@extends('admin::layouts.master')

@section('page_title')
{{ __('admin::app.dashboard.title') }}
@stop

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>{{ __('admin::app.dashboard.title') }}</h1>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
@endpush
