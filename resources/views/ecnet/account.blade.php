@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('printing_account') }}</div>
                <div class="panel-body">
					<div class="well well-sm">{{ $layout->language('available_money') }}: {{ $layout->user()->ecnetUser()->money() }} HUF</div>
					<div class="alert alert-info">
						<strong>{{ $layout->language('note') }}:</strong> {{ $layout->language('money_upload_note_description') }}
					</div>
					
					@if($layout->user()->permitted('ecnet_set_print_account'))
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('admin_panel') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/addmoney') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('money') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('money_to_add') }}</label>

									<div class="col-md-6">
										<input type="number" class="form-control" min="0" step="1" value="0" name="money" required="true" value="{{ old('money') }}">

										@if ($errors->has('money'))
											<span class="help-block">
												<strong>{{ $errors->first('money') }}</strong>
											</span>
										@endif
									</div>
								</div>
									
								<div class="form-group{{ $errors->has('reset') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('money_on_account') }}</label>

									<div class="col-md-6">
										<input type="number" class="form-control" min="0" step="1" value="0" name="reset" required="true" value="{{ old('reset') }}">

										@if ($errors->has('reset'))
											<span class="help-block">
												<strong>{{ $errors->first('reset') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="country_select">{{ $layout->language('user') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="account"  id="country_select" required="true">
											@foreach($users as $us)
											<option value="{{ $us->id() }}">{{ $us->name() }} ({{ $us->username() }})</option>
											@endforeach
										</select>

										@if ($errors->has('account'))
											<span class="help-block">
												<strong>{{ $errors->first('account') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											<i class="fa fa-btn fa-money"></i>{{ $layout->language('modify') }}
										</button>
									</div>
								</div>
							</form>
							
							<div class="alert alert-warning">
								{{ $layout->language('money_add_admin_note_description') }}
							</div>
						</div>
					</div>
					@endif
					<!-- TODO: history -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
