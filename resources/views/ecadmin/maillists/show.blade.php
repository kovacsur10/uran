@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading"><a href="{{ url('ecadmin/maillist/list') }}">{{ $layout->language('mailing_lists') }}</a></div>
				<div class="panel-body">
				@if($layout->user()->permitted('mailing_lists_handling'))
					<label class="row">{{ $list_name }}</label>
					<div class="col-md-6">
						@foreach($members as $member)
							<span class="row">{{ $member->name()." <".$member->email().">" }}</option>
						@endforeach
					</div>
				@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
