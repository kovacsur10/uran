@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('permissions.permission_group_handling')</div>
                <div class="panel-body">
				@if($layout->user()->permitted('permission_admin'))
					<div class="panel panel-default">
		                <div class="panel-heading">@lang('permissions.permission_group_handling')</div>
		                <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/groups/modify') }}">
								{!! csrf_field() !!}
										
								<div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="group">@lang('permissions.permission_group')</label>
									<div class="col-md-6">
										<select class="form-control"  name="group"  id="group">
											@foreach($layout->permissions()->getPermissionGroups() as $group)
												<option value="{{ $group->id() }}">{{ $group->name() }}</option>
											@endforeach
										</select>
										@if($errors->has('group'))
											<span class="help-block">
												<strong>{{ $errors->first('group') }}</strong>
											</span>
										@endif
									</div>
								</div>
										
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">@lang('permissions.start_of_modification')</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
					<div class="panel panel-default">
		                <div class="panel-heading">@lang('permissions.assign_permission_groups')</div>
		                <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/groups/user') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="user">@lang('user.user')</label>
									<div class="col-md-6">
										<select class="form-control"  name="user"  id="user">
											@foreach($layout->user()->users(0, -1) as $user)
												<option value="{{ $user->id() }}">{{ $user->name() }} ({{ $user->username() }})</option>
											@endforeach
										</select>

										@if($errors->has('user'))
											<span class="help-block">
												<strong>{{ $errors->first('user') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">@lang('permissions.start_of_modification')</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">@lang('permissions.list_permission_groups')</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/groups/users') }}">
								{!! csrf_field() !!}
								
								<div class="form-group">
									<label  class="col-md-4 control-label" for="permission">@lang('permissions.permission_group')</label>
									<div class="col-md-6">
										<select class="form-control" name="group" id="group">
											@foreach($layout->permissions()->getPermissionGroups() as $group)
												<option value="{{ $group->id() }}">{{ $group->name() }}</option>
											@endforeach
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">@lang('permissions.list')</button>
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
