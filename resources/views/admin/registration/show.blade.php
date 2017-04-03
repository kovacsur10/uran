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
								<li class="list-group-item list-group-item-{{ $userData->verified() ? 'success' : 'danger' }}">
									<div class="row">
										<div class="col-md-6">
											@if($userData->collegistData() !== null)
												{{ $layout->language('collegist_registration') }}
											@else
												{{ $layout->language('guest_registration') }}
											@endif
										</div>
										<div class="col-md-6">
											@if($userData->verified())
												{{ $layout->language('email_address_was_verified_at_this_date') }} {{ $userData->verificationDate() }}
											@else
												{{ $layout->language('email_address_not_yet_verified') }}
											@endif
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('identifier') }}</div>
										<div class="col-md-6"><input class="form-control" type="text" name="id" value="{{ $userData->id() }}" readonly></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('username') }}</div>
										<div class="col-md-6"><input class="form-control" type="text" name="username" value="{{ $userData->username() }}" readonly></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('email_address') }}</div>
										<div class="col-md-6"><input class="form-control" type="text" name="email" value="{{ $userData->email() }}" readonly></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('name') }}</div>
										<div class="col-md-6"><input class="form-control" type="text" name="name" value="{{ $userData->name() }}" readonly></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('place_of_birth') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->collegistData() !== null ? 'info' : 'danger' }}" type="text" name="city_of_birth" value="{{ $userData->collegistData() !== null ? $userData->collegistData()->cityOfBirth() : '' }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('date_of_birth') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->collegistData() !== null ? 'info' : 'danger' }}" type="text" name="date_of_birth" value="{{ $userData->collegistData() !== null ? $userData->collegistData()->dateOfBirth() : '' }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('name_of_mother') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->collegistData() !== null ? 'info' : 'danger' }}" type="text" name="name_of_mother" value="{{ $userData->collegistData() !== null ? $userData->collegistData()->nameOfMother() : '' }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('phone_number') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="phone" value="{{ $userData->phoneNumber() }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('year_of_leaving_exam') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->collegistData() !== null ? 'info' : 'danger' }}" type="text" name="year_of_leaving_exam" value="{{ $userData->collegistData() !== null ? $userData->collegistData()->leavingExamYear() : '' }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('high_school') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->collegistData() !== null ? 'info' : 'danger' }}" type="text" name="high_school" value="{{ $userData->collegistData() !== null ? $userData->collegistData()->highSchool() : '' }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('country') }}</div>
										<div class="col-md-6">
											<select class="form-control alert-info"  name="country"  id="country_select" required="true">
											@foreach($layout->base()->countries() as $country)
												<option {{ $userData->country() == $country->id() ? "selected" : "" }} value="{{ $country->id() }}">{{ $layout->language($country->id()) }}</option>
											@endforeach
											</select>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('shire') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="shire" value="{{ $userData->shire() }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('postalcode') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="postalcode" value="{{ $userData->postalCode() }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('city') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="city" value="{{ $userData->city() }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('address') }}</div>
										<div class="col-md-6"><input class="form-control alert-info" type="text" name="address" value="{{ $userData->address() }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('reason_of_registration') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->collegistData() !== null ? 'danger' : 'info' }}" type="text" name="reason" value="{{ $userData->collegistData() !== null ? '' : $userData->reason() }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('neptun') }}</div>
										<div class="col-md-6"><input class="form-control alert-{{ $userData->collegistData() !== null ? 'info' : 'danger' }}" type="text" name="neptun" value="{{ $userData->collegistData() !== null ? $userData->collegistData()->neptun() : '' }}"></div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('from_year') }}</div>
										<div class="col-md-6">
											<select class="form-control alert-{{ $userData->collegistData() !== null ? 'info' : 'danger' }}"  name="from_year"  id="country_from_year" required="true">
											@foreach($layout->base()->admissionYears() as $fromYear)
												<option {{ ($userData->collegistData() !== null && $userData->collegistData()->admissionYear() === $fromYear) ? "selected" : "" }} value="{{ $fromYear }}">{{ $fromYear }}</option>
											@endforeach
											</select>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('faculty') }}</div>
										<div class="col-md-6">
											@foreach($layout->base()->faculties() as $faculty)
												<div class="checkbox alert-{{ $userData->collegistData() !== null ? 'info' : 'danger' }}">
													<label><input type="checkbox" name="faculties[]" value="{{ $faculty->id() }}" {{ ($userData->collegistData() !== null && in_array($faculty, $userData->collegistData()->faculties())) ? 'checked' : '' }}>{{ $faculty->name() }}</label>
												</div>
											@endforeach
										</div>
									</div>
								</li>
								
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6">{{ $layout->language('workshop') }}</div>
										<div class="col-md-6">
											@foreach($layout->base()->workshops() as $workshop)
												<div class="checkbox alert-{{ $userData->collegistData() !== null ? 'info' : 'danger' }}">
													<label><input type="checkbox" name="workshops[]" value="{{ $workshop->id() }}" {{ ($userData->collegistData() !== null && in_array($workshop, $userData->collegistData()->workshops())) ? 'checked' : '' }}>{{ $workshop->name() }}</label>
												</div>
											@endforeach
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-md-6"><a href="{{ url('/admin/registration/reject/'.$userData->id()) }}" class="btn btn-danger" role="button">{{ $layout->language('reject_user_registration') }}</a></div>
										<div class="col-md-6"><button class="btn btn-primary" type="submit" name="register_user">{{ $layout->language('accept_user_registration') }}</button></div>
									</div>
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
