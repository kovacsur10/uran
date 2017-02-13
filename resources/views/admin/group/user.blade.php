@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('/admin/groups/list') }}">{{ $layout->language('permission_group_handling') }}</a></div>
                <div class="panel-body">
				@if($layout->user()->permitted('permission_admin'))
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('assign_permission_groups') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('admin/groups/user_modification') }}">
								{!! csrf_field() !!}
								
								<div class="form-group">
									<label  class="col-md-4 control-label" for="user">{{ $layout->language('user') }}</label>
									<div class="col-md-6">
										<select class="form-control" name="user" id="user">
											<option value="{{ $userid }}" selected>{{ $layout->user()->getUserData($userid)->name() }} ({{ $layout->user()->getUserData($userid)->username() }})</option>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-8 col-md-offset-2">
										@foreach($layout->permissions()->getPermissionGroups() as $group)
											<div class="checkbox">
												<label>
													<input type="checkbox" name="groups[]" value="{{ $group->id() }}" {{ $layout->permissions()->memberOfPermissionGroups($userid, $group->id()) ? 'checked' : '' }}>
													{{ $group->name() }}
												</label>
											</div>
										@endforeach
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-3">
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('set_permission_groups') }}
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
