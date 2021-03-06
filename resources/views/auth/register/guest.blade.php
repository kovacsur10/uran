@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('auth.registration')</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register/guest') }}">
						<input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">@lang('user.username')</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="username" id="username"  value="{{ old('username') }}" required="true">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="alert alert-warning">
							<p>@lang('auth.register_username_can_contain_description')</p>
						</div>
                        
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">@lang('user.name')</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required="true">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">@lang('user.email_address')</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required="true">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="alert alert-warning">
							@lang('auth.register_email_advice_description')
						</div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">@lang('auth.password')</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" id="password" required="true">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="alert alert-warning">
							@lang('auth.register_password_can_contain_description')
						</div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">@lang('auth.confirm_password')</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation" id="password_again" required="true">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">@lang('user.phone_number')</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

						<div class="panel panel-default">
							<div class="panel-heading">@lang('user.address')</div>
							<div class="panel-body">
								<div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="country_select">@lang('user.country')</label>
									<div class="col-md-6">
										<select class="form-control"  name="country"  id="country_select" required="true">
											@foreach($layout->base()->countryCodes() as $country))
												@if(old('country') == $country || (old('country') == null && $country == 'HUN'))
													<option value="{{ $country }}" selected>@lang($country)</option>
												@else
													<option value="{{ $country }}">@lang($country)</option>
												@endif
											@endforeach
										</select>

										@if ($errors->has('country'))
											<span class="help-block">
												<strong>{{ $errors->first('country') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('shire') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">@lang('user.shire')</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="shire" id="shire" value="{{ old('shire') }}" required="true">

										@if ($errors->has('shire'))
											<span class="help-block">
												<strong>{{ $errors->first('shire') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('postalcode') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">@lang('user.postalcode')</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="postalcode" id="postalcode" value="{{ old('postalcode') }}" required="true">

										@if ($errors->has('postalcode'))
											<span class="help-block">
												<strong>{{ $errors->first('postalcode') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">@lang('user.city')</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="city" id="city" value="{{ old('city') }}" required="true">

										@if ($errors->has('city'))
											<span class="help-block">
												<strong>{{ $errors->first('city') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">@lang('user.address')</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}" required="true">

										@if ($errors->has('address'))
											<span class="help-block">
												<strong>{{ $errors->first('address') }}</strong>
											</span>
										@endif
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">@lang('auth.reason_of_registration')</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="reason" id="reason" value="{{ old('reason') }}" required="true">

                                @if ($errors->has('reason'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('reason') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="alert alert-warning">@lang('auth.register_reason_advice_description')</div>
						
						<div class="form-group{{ $errors->has('accept') ? ' has-error' : '' }}">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
									<input class="col-xs-1" type="checkbox" required name="accept" id="accept" value="accepted">
									<label class="col-xs-11">@lang('auth.accept_rules_with_submit_description')</label>
								</div>

                                @if ($errors->has('accept'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('accept') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" id="registerButton" class="btn btn-primary"><i class="fa fa-btn fa-user"></i>@lang('auth.register')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/view/auth/register/guest.js"></script>
@endsection
