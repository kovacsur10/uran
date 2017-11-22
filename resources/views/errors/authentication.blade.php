@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('error.error')</div>
                <div class="panel-body">
                    <p>@lang('error.insufficient_permissions')</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
