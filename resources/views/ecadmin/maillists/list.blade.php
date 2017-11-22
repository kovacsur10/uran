@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('ecadmin.mailing_lists')</div>
                <div class="panel-body">
				@if($layout->user()->permitted('mailing_lists_handling'))
					<div class="panel panel-default">
						<div class="panel-heading">@lang('ecadmin.mailing_lists')</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecadmin/maillist/show') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('mailing_lists') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="mailing_lists">@lang('ecadmin.mailing_list')</label>
									<div class="col-md-6">
										<select class="form-control"  name="mailing_lists"  id="mailing_lists">
											@foreach($mailing_lists as $listname)
												<option value="{{ $listname }}">{{ $listname }}</option>
											@endforeach
										</select>
			
										@if($errors->has('mailing_lists'))
											<span class="help-block">
												<strong>{{ $errors->first('mailing_lists') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">@lang('ecadmin.list')</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">@lang('ecadmin.mailing_lists_diff')</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecadmin/maillist/showdiff') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('mailing_lists') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="mailing_lists">@lang('ecadmin.mailing_list')</label>
									<div class="col-md-6">
										<select class="form-control"  name="mailing_lists"  id="mailing_lists">
											@foreach($mailing_lists as $listname)
												<option value="{{ $listname }}">{{ $listname }}</option>
											@endforeach
										</select>
			
										@if($errors->has('mailing_lists'))
											<span class="help-block">
												<strong>{{ $errors->first('mailing_lists') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('mailing_lists_textarea') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label" for="mailing_lists_textarea">@lang('ecadmin.mailing_list_actual_content')</label>
									<div class="col-md-6">
										<textarea class="form-control" rows="5" id="mailing_lists_textarea" name="mailing_lists_textarea"></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">@lang('ecadmin.list')</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
