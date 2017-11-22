@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('/ecnet/users') }}">@lang('general.admin_panel') - @lang('ecnet.list_users')</a></div>
                <div class="panel-body">				
					@yield('listcontent')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
