@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('registration') }}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register/collegist') }}">
						<input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('username') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required="true">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="alert alert-warning">
							<p>{{ $layout->language('collegist_username_advice_description') }}</p>
							<p>{{ $layout->language('register_username_can_contain_description') }}</p>
						</div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('email_address') }}</label>

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
							{{ $layout->language('register_email_advice_description') }}
						</div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('password') }}</label>

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
							{{ $layout->language('register_password_can_contain_description') }}
						</div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('confirm_password') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation" id="password_again" required="true">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('user_data') }}</div>
							<div class="panel-body">
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('name') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required="true">

										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('city_of_birth') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('place_of_birth') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="city_of_birth" id="city_of_birth" value="{{ old('city_of_birth') }}">

										@if ($errors->has('city_of_birth'))
											<span class="help-block">
												<strong>{{ $errors->first('city_of_birth') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('date_of_birth') }}</label>

									<div class="col-md-6">
										<div class='input-group date' data-date-format="yyyy.mm.dd." id='datepicker_add_new_task'>
											<input type="text" readonly class="form-control" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}">
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										@if ($errors->has('date_of_birth'))
											<span class="help-block">
												<strong>{{ $errors->first('date_of_birth') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('name_of_mother') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('name_of_mother') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="name_of_mother" id="name_of_mother" value="{{ old('name_of_mother') }}">

										@if ($errors->has('name_of_mother'))
											<span class="help-block">
												<strong>{{ $errors->first('name_of_mother') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('phone_number') }}</label>
		
									<div class="col-md-6">
										<input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}">
		
										@if ($errors->has('phone'))
											<span class="help-block">
												<strong>{{ $errors->first('phone') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
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
										<input type="text" class="form-control" name="shire" id="shire" value="{{ old('shire') }}" required="true">

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
										<input type="text" class="form-control" name="postalcode" id="postalcode" value="{{ old('postalcode') }}" required="true">

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
										<input type="text" class="form-control" name="city" id="city" value="{{ old('city') }}" required="true">

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
						
						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('studies_data') }}</div>
							<div class="panel-body">
								<div class="form-group{{ $errors->has('year_of_leaving_exam') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('year_of_leaving_exam') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="year_of_leaving_exam" id="year_of_leaving_exam" value="{{ old('year_of_leaving_exam') }}">

										@if ($errors->has('year_of_leaving_exam'))
											<span class="help-block">
												<strong>{{ $errors->first('year_of_leaving_exam') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('high_school') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('high_school') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="high_school" id="high_school" value="{{ old('high_school') }}">

										@if ($errors->has('high_school'))
											<span class="help-block">
												<strong>{{ $errors->first('high_school') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('neptun') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('neptun') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="neptun" id="neptun" value="{{ old('neptun') }}" required="true">

										@if ($errors->has('neptun'))
											<span class="help-block">
												<strong>{{ $errors->first('neptun') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('from_year') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="from_year_select">{{ $layout->language('from_year') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="from_year"  id="from_year_select" required="true">
											@foreach($layout->base()->admissionYears() as $fromYear)
												<option value="{{ $fromYear }}" {{ old('from_year') == $fromYear ? 'selected' : '' }}>{{ $fromYear }}</option>
											@endforeach
										</select>
										
										@if ($errors->has('from_year'))
											<span class="help-block">
												<strong>{{ $errors->first('from_year') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('faculties') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="faculties">{{ $layout->language('faculty') }}</label>
									<div class="col-md-6">
										@foreach($layout->base()->faculties() as $faculty)
											<div class="checkbox">
												<label><input type="checkbox" name="faculties[]" value="{{ $faculty->id() }}" {{ old('faculties') !== null && in_array($faculty->id(), old('faculties')) ? 'checked' : '' }}>{{ $faculty->name() }}</label>
											</div>
										@endforeach
										@if ($errors->has('faculties'))
											<span class="help-block">
												<strong>{{ $errors->first('faculties') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('workshops') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="workshops">{{ $layout->language('workshop') }}</label>
									<div class="col-md-6">
										@foreach($layout->base()->workshops() as $workshop)
											<div class="checkbox">
												<label><input type="checkbox" name="workshops[]" value="{{ $workshop->id() }}" {{ old('workshops') !== null && in_array($workshop->id(), old('workshops')) ? 'checked' : '' }}>{{ $workshop->name() }}</label>
											</div>
										@endforeach
										@if ($errors->has('workshops'))
											<span class="help-block">
												<strong>{{ $errors->first('workshops') }}</strong>
											</span>
										@endif
									</div>
								</div>
							</div>
						</div>
					
						<div class="form-group{{ $errors->has('accept') ? ' has-error' : '' }}">
                            <div class="col-md-10 col-md-offset-1">
								<div class="form-group">
									<input class="col-xs-1" type="checkbox" required name="accept" id="accept" value="accepted">
									<label class="col-xs-11">{{ $layout->language('accept_rules_with_submit_description') }}</label>
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
                                <button type="submit" id="registerButton" class="btn btn-primary">
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

<script type="text/javascript" src="/js/view/auth/register/collegist.js"></script>
<!-- Datepicker script -->
<script type="text/javascript">
	$(function(){
		$('#datepicker_add_new_task').datepicker({
			format: 'yyyy.mm.dd.',
			autoclose: true,
			clearBtn: true
		})
		.on('hide', function(e) {
			collegistValidator.performCheck(['#date_of_birth']);
		});
	});
</script>
@endsection
