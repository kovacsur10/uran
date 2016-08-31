@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('registration') }}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register/guest') }}">
						<input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('username') }}</label>

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
							<p>{{ $layout->language('register_username_can_contain_description') }}</p>
						</div>
                        
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('name') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="true">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('email_address') }}</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required="true">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="alert alert-warning">
							{{ $layout->language('register_email_advice_description') }}
						</div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('password') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" required="true">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="alert alert-warning">
							{{ $layout->language('register_password_can_contain_description') }}
						</div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('confirm_password') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation" required="true">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('phone_number') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('address') }}</div>
							<div class="panel-body">
								<div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="country_select">{{ $layout->language('country') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="country"  id="country_select" required="true">
											@foreach($layout->base()->countryCodes() as $country))
												@if(old('country') == $country || (old('country') == null && $country == 'HUN'))
													<option value="{{ $country }}" selected>{{ $layout->language($country) }}</option>
												@else
													<option value="{{ $country }}">{{ $layout->language($country) }}</option>
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
									<label class="col-md-4 control-label">{{ $layout->language('shire') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="shire" value="{{ old('shire') }}" required="true">

										@if ($errors->has('shire'))
											<span class="help-block">
												<strong>{{ $errors->first('shire') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('postalcode') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('postalcode') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="postalcode" value="{{ old('postalcode') }}" required="true">

										@if ($errors->has('postalcode'))
											<span class="help-block">
												<strong>{{ $errors->first('postalcode') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('city') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="city" value="{{ old('city') }}" required="true">

										@if ($errors->has('city'))
											<span class="help-block">
												<strong>{{ $errors->first('city') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('address') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="address" value="{{ old('address') }}" required="true">

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
                            <label class="col-md-4 control-label">{{ $layout->language('reason_of_registration') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="reason" value="{{ old('reason') }}" required="true">

                                @if ($errors->has('reason'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('reason') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="alert alert-warning">
							{{ $layout->language('register_reason_advice_description') }}
						</div>
						
						<div class="form-group{{ $errors->has('accept') ? ' has-error' : '' }}">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="checkbox">
									<label><input type="checkbox" required name="accept" value="accepted">{{ $layout->language('accept_rules_with_submit_description') }}</label>
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>{{ $layout->language('register') }}
                                </button>
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
