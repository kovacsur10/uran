@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('accept_user_registration') }}</div>
                <div class="panel-body">
				@if($layout->user()->permitted('accept_user_registration'))
					<div class="panel panel-default">
						<div class="panel-heading"><a href="{{ url('/admin/registration/show') }}">{{ $layout->language('users_list') }}</a></div>
						<div class="panel-body">
							<?php $userData = $layout->registrations()->getRegistrationUser(); ?>
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/registration/accept') }}">
							{!! csrf_field() !!}
							
							<ul class="list-group">
								<li class="list-group-item list-group-item-{{ $userData->verified ? 'success' : 'danger' }}">
									<div class="row">
										<div class="col-md-6">
											@if($userData->neptun)
												{{ $layout->language('collegist_registration') }}
											@else
												{{ $layout->language('guest_registration') }}
											@endif
										</div>
										<div class="col-md-6">
											@if($userData->verified)
												{{ $layout->language('email_address_was_verified_at_this_date') }} {{ $userData->verification_date }}
											@else
												{{ $layout->language('email_address_not_yet_verified') }}
											@endif
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('identifier') }}</div>
										<div class="col-md-6"><input class="form-control" type="text" name="id" value="{{ $userData->id }}" readonly></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('username') }}</div>
										<div class="col-md-6"><input class="form-control" type="text" name="username" value="{{ $userData->username }}" readonly></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('email_address') }}</div>
										<div class="col-md-6"><input class="form-control" type="text" name="email" value="{{ $userData->email }}" readonly></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('name') }}</div>
										<div class="col-md-6"><input class="form-control" type="text" name="name" value="{{ $userData->name }}" readonly></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('place_of_birth') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->neptun ? 'info' : 'danger' }}" type="text" name="city_of_birth" value="{{ $userData->city_of_birth }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('date_of_birth') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->neptun ? 'info' : 'danger' }}" type="text" name="date_of_birth" value="{{ $userData->date_of_birth }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('name_of_mother') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->neptun ? 'info' : 'danger' }}" type="text" name="name_of_mother" value="{{ $userData->name_of_mother }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('phone_number') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="phone" value="{{ $userData->phone }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('year_of_leaving_exam') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->neptun ? 'info' : 'danger' }}" type="text" name="year_of_leaving_exam" value="{{ $userData->year_of_leaving_exam }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('high_school') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->neptun ? 'info' : 'danger' }}" type="text" name="high_school" value="{{ $userData->high_school }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('country') }}</div>
										<div class="col-md-6">
											<select class="form-control alert-info"  name="country"  id="country_select" required="true">
											@foreach($layout->base()->countries() as $country)
												<option {{ $userData->country == $country->id ? "selected" : "" }} value="{{ $country->id }}">{{ $country->name }}</option>
											@endforeach
											</select>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('shire') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="shire" value="{{ $userData->shire }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('postalcode') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="postalcode" value="{{ $userData->postalcode }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('city') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="city" value="{{ $userData->city }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('address') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="address" value="{{ $userData->address }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('reason_of_registration') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->neptun ? 'danger' : 'info' }}" type="text" name="reason" value="{{ $userData->reason }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('neptun') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->neptun ? 'info' : 'danger' }}" type="text" name="neptun" value="{{ $userData->neptun }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('from_year') }}</div>
										<div class="col-md-6">
											<select class="form-control alert-{{ $userData->neptun ? 'info' : 'danger' }}"  name="from_year"  id="country_from_year" required="true">
											@foreach($layout->base()->admissionYears() as $fromYear)
												<option {{ $userData->from_year == $fromYear->year ? "selected" : "" }} value="{{ $fromYear->year }}">{{ $fromYear->year }}</option>
											@endforeach
											</select>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('faculty') }}</div>
										<div class="col-md-6">
											<select class="form-control alert-{{ $userData->neptun ? 'info' : 'danger' }}"  name="faculty"  id="faculty_select" required="true">
											@foreach($layout->base()->faculties() as $faculty)
												<option {{ $userData->faculty == $faculty->id ? "selected" : "" }} value="{{ $faculty->id }}">{{ $faculty->name }}</option>
											@endforeach
											</select>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('workshop') }}</div>
										<div class="col-md-6">
											<select class="form-control alert-{{ $userData->neptun ? 'info' : 'danger' }}"  name="workshop"  id="workshop_select" required="true">
											@foreach($layout->base()->workshops() as $workshop)
												<option {{ $userData->workshop == $workshop->id ? "selected" : "" }} value="{{ $workshop->id }}">{{ $workshop->name }}</option>
											@endforeach
											</select>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<button class="btn btn-primary" type="submit" name="register_user">{{ $layout->language('accept_user_registration') }}</button>
								</li>
							</ul>
							</form>
							
							<div class="alert alert-info">
								{{ $layout->language('accept_user_registration_needed_fields_description') }}
							</div>
							
							<div class="alert alert-danger">
								{{ $layout->language('accept_user_registration_not_needed_fields_description') }}
							</div>
							
							<div class="alert alert-warning">
								{{ $layout->language('accept_user_registration_informations') }}
							</div>
						</div>
					</div>
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
