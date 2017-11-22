@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('home.home')</div>

                <div class="panel-body">
				@if($layout->logged())
                    @lang('home.logged_in_home_message')
				@else
					@lang('home.not_logged_in_home_message')
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
