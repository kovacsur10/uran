@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('my_data') }}</div>
                <div class="panel-body">
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('base_data') }}</div>
						<div class="panel-body form-horizontal">
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ $layout->language('name') }}</label>
							    <div class="col-sm-8">
							    	<div class="form-control">{{ $layout->user()->user()->name() }}</div>
							    </div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ $layout->language('username') }}</label>
							    <div class="col-sm-8">
							    	<div class="form-control">{{ $layout->user()->user()->username() }}</div>
							    </div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ $layout->language('email_address') }}</label>
							    <div class="col-sm-8">
							    	<div class="form-control">{{ $layout->user()->user()->email() }}</div>
							    </div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ $layout->language('registration_date') }}</label>
							    <div class="col-sm-8">
							    	<div class="form-control">{{ $layout->formatDate($layout->user()->user()->registrationDate()) }}</div>
							    </div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ $layout->language('address') }}</label>
							    <div class="col-sm-8">
							    	<textarea class="form-control" rows="2" disabled>{{ $layout->language($layout->user()->user()->country()) }}, {{ $layout->user()->user()->shire() }} megye, {{ $layout->user()->user()->postalCode() }} {{ $layout->user()->user()->city() }}, {{ $layout->user()->user()->address() }}</textarea>
							    </div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ $layout->language('status') }}</label>
							    <div class="col-sm-8">
							    	<div class="form-control">{{ $layout->language("status_".$layout->user()->user()->status()->statusName()) }}</div>
							    	@if($layout->user()->user()->status()->statusName() === "alumni")
							    		@if($layout->user()->user()->subscribedToAlumniList())
							    			<div class="well">{{ $layout->language('subscribed_to_the_alumni_list') }}</div>
							    		@else
							    			<div class="well">{{ $layout->language('unsubscribed_from_the_alumni_list') }}</div>
							    		@endif
							    	@endif
							    </div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ $layout->language('default_webpage_language') }}</label>
							    <div class="col-sm-8">
							    	<div class="form-control">{{ $layout->language("language_".$layout->user()->user()->language()) }}</div>
							    </div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('user_data') }}</div>
						<div class="panel-body form-horizontal">
							@if($layout->user()->user()->collegistData() !== null)
								<div class="form-group">
									<label class="col-sm-4 control-label">{{ $layout->language('place_of_birth') }}</label>
								    <div class="col-sm-8">
								    	<div class="form-control">{{ $layout->user()->user()->collegistData()->cityOfBirth() }}</div>
								    </div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">{{ $layout->language('date_of_birth') }}</label>
								    <div class="col-sm-8">
								        <div class="form-control">{{ $layout->formatDate($layout->user()->user()->collegistData()->dateOfBirth(), true) }}</div>
								    </div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">{{ $layout->language('name_of_mother') }}</label>
								    <div class="col-sm-8">
								        <div class="form-control">{{ $layout->user()->user()->collegistData()->nameOfMother() }}</div>
								    </div>
								</div>
							@endif
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ $layout->language('phone_number') }}</label>
							    <div class="col-sm-8">
							        <div class="form-control">{{ $layout->user()->user()->phoneNumber() }}</div>
							    </div>
							</div>
						</div>
					</div>
					@if($layout->user()->user()->collegistData() !== null)
						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('studies_data') }}</div>
							<div class="panel-body form-horizontal">
								<div class="form-group">
									<label class="col-sm-4 control-label">{{ $layout->language('high_school') }}</label>
								    <div class="col-sm-8">
								        <div class="form-control">{{ $layout->user()->user()->collegistData()->highSchool() }}</div>
								    </div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">{{ $layout->language('year_of_leaving_exam') }}</label>
								    <div class="col-sm-8">
								        <div class="form-control">{{ $layout->user()->user()->collegistData()->leavingExamYear() }}</div>
								    </div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">{{ $layout->language('neptun') }}</label>
								    <div class="col-sm-8">
								        <div class="form-control">{{ $layout->user()->user()->collegistData()->neptun() }}</div>
								    </div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">{{ $layout->language('from_year') }}</label>
								    <div class="col-sm-8">
								        <div class="form-control">{{ $layout->user()->user()->collegistData()->admissionYear() }}</div>
								    </div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">{{ $layout->language('faculty') }}</label>
								    <div class="col-sm-8">
								        @foreach($layout->base()->faculties() as $faculty)
											<div class="checkbox">
												<label><input type="checkbox" disabled="disabled" name="faculties[]" value="{{ $faculty->id() }}" {{ ($layout->user()->user()->collegistData() !== null && in_array($faculty, $layout->user()->user()->collegistData()->faculties())) ? 'checked' : '' }}>{{ $faculty->name() }}</label>
											</div>
										@endforeach
								    </div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">{{ $layout->language('workshop') }}</label>
								    <div class="col-sm-8">
								        @foreach($layout->base()->workshops() as $workshop)
											<div class="checkbox">
												<label><input type="checkbox" disabled="disabled" name="workshops[]" value="{{ $workshop->id() }}" {{ ($layout->user()->user()->collegistData() !== null && in_array($workshop, $layout->user()->user()->collegistData()->workshops())) ? 'checked' : '' }}>{{ $workshop->name() }}</label>
											</div>
										@endforeach
								    </div>
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
