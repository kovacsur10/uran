@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading"><a href="{{ url('ecadmin/maillist/list') }}">{{ $list_name }}</a></div>
				<div class="panel-body">
				@if($layout->user()->permitted('mailing_lists_handling'))
					@if($members['alreadyMember'] !== [])
						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('members') }}</div>
							<div class="panel-body">
								<ul class="list-group">
									@foreach($members['alreadyMember'] as $member)
										<li class="list-group-item">{{ $member->name()." <".$member->email().">" }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					@endif
					@if($members['new'] !== [])
						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('newMembers') }}</div>
							<div class="panel-body">
								<ul class="list-group">
									@foreach($members['new'] as $member)
										<li class="list-group-item">{{ $member->name()." <".$member->email().">" }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					@endif
					@if($members['remove'] !== [])
						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('removableMembers') }}</div>
							<div class="panel-body">
								<ul class="list-group">
									@foreach($members['remove'] as $member)
										<li class="list-group-item">{{ $member->name()." <".$member->email().">" }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					@endif
				@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
