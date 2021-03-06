@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('ecadmin/user/list') }}">@lang('user.user')</a></div>
                <div class="panel-body">
				@if($layout->user()->permitted('user_handling'))
					<?php $user = $target->getUserData($target->user()->id()); ?>
				
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecadmin/user/show/'.$user->id()) }}">
						{!! csrf_field() !!}
					
						<div class="panel panel-default">
							<div class="panel-heading">@lang('ecadmin.system_data')</div>
							<div class="panel-body">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.identifier'):</div>
											<div class="col-xs-8">{{ $user->id() }}</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.username'):</div>
											<div class="col-xs-8">{{ $user->username() }}</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.registration_date'):</div>
											<div class="col-xs-8">{{ $layout->formatDate($user->registrationDate()) }}</div>
										</div>
									</li>
								</ul>
							</div>
						</div>					
						
						<div class="panel panel-default">
							<div class="panel-heading">@lang('user.user_data')</div>
							<div class="panel-body">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.name'):</div>
											<div class="col-xs-8">{{ $user->name() }}</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.date_of_birth'):</div>
											<div class="col-xs-8">{{ $user->collegistData() === null ? __('TODO') : $user->collegistData()->dateOfBirth() }}</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.place_of_birth'):</div>
											<div class="col-xs-8">{{ $user->collegistData() === null ? __('TODO') : $user->collegistData()->cityOfBirth() }}</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.name_of_mother'):</div>
											<div class="col-xs-8">{{ $user->collegistData() === null ? __('TODO') : $user->collegistData()->nameOfMother() }}</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading">@lang('ecadmin.contact_informations')</div>
							<div class="panel-body">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.email_address'):</div>
											<div class="col-xs-8"><input class="form-control" type="text" name="email" value="{{ $user->email() }}"></div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.phone_number'):</div>
											<div class="col-xs-8"><input class="form-control" type="text" name="phone" value="{{ $user->phoneNumber() }}"></div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading">@lang('user.address')</div>
							<div class="panel-body">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.country'):</div>
											<div class="col-xs-8">
												<select class="form-control"  name="country"  id="country_select" required="true" autocomplete="off">
												@foreach($layout->base()->countries() as $country)
													<option {{ $user->country() === $country->name() ? "selected" : "" }} value="{{ $country->id() }}">@lang('country.'.$country->id())</option>
												@endforeach
												</select>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.shire'):</div>
											<div class="col-xs-8"><input class="form-control" type="text" name="shire" value="{{ $user->shire() }}"></div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.city'):</div>
											<div class="col-xs-8"><input class="form-control" type="text" name="city" value="{{ $user->city() }}"></div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.postalcode'):</div>
											<div class="col-xs-8"><input class="form-control" type="text" name="postalcode" value="{{ $user->postalCode() }}"></div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.address'):</div>
											<div class="col-xs-8"><input class="form-control" type="text" name="address" value="{{ $user->address() }}"></div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading">@lang('ecadmin.school_data')</div>
							<div class="panel-body">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.high_school'):</div>
											<div class="col-xs-8">{{ $user->collegistData() === null ? __('TODO') : $user->collegistData()->highSchool() }}</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.year_of_leaving_exam'):</div>
											<div class="col-xs-8">{{ $user->collegistData() === null ? __('TODO') : $user->collegistData()->leavingExamYear() }}</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.from_year'):</div>
											<div class="col-xs-8">{{ $user->collegistData() === null ? __('TODO') : $user->collegistData()->admissionYear() }}</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.neptun'):</div>
											<div class="col-xs-8">{{ $user->collegistData() === null ? __('TODO') : $user->collegistData()->neptun() }}</div>
										</div>
									</li>
									<?php /* <li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.faculty')</div>
											<div class="col-xs-8">
												<select class="form-control"  name="faculty"  id="faculty_select" required="true" autocomplete="off">
												@foreach($layout->base()->faculties() as $faculty)
													<option {{ $user->faculty_id === $faculty->id() ? "selected" : "" }} value="{{ $faculty->id() }}">{{ $faculty->name() }}</option>
												@endforeach
												</select>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.workshop')</div>
											<div class="col-xs-8">
												<select class="form-control"  name="workshop"  id="workshop_select" required="true" autocomplete="off">
												@foreach($layout->base()->workshops() as $workshop)
													<option {{ $user->workshop_id === $workshop->id() ? "selected" : "" }} value="{{ $workshop->id() }}">{{ $workshop->name() }}</option>
												@endforeach
												</select>
											</div>
										</div>
									</li> */ ?>
									<li class="list-group-item">
										<div class="row">
											<div class="col-xs-4">@lang('user.status')</div>
											<div class="col-xs-8">
												<select class="form-control"  name="status"  id="status_select" required="true" autocomplete="off">
												@foreach($layout->base()->statusCodes() as $status)
													<option {{ $user->status()->id() === $status->id() ? "selected" : "" }} value="{{ $status->id() }}">{{ $status->statusName() }}</option>
												@endforeach
												</select>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</form>
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
