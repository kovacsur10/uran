@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('/admin/permissions') }}">@lang('permissions.permissions_handling')</a></div>
                <div class="panel-body">
				@if($layout->user()->permitted('permission_admin'))
					<div class="panel panel-default">
						<div class="panel-heading">@lang('permissions.assign_permissions')</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/permissions/set') }}">
								{!! csrf_field() !!}
								
								<div class="form-group">
									<label  class="col-md-4 control-label" for="user">@lang('user.user')</label>
									<div class="col-md-6">
										<select class="form-control" name="user" id="user">
											<option value="{{ $userid }}" selected>{{ $layout->user()->getUserData($userid)->name() }} ({{ $layout->user()->getUserData($userid)->username() }})</option>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-8 col-md-offset-2">
										@foreach($layout->permissions()->getAllPermissions() as $permission)
											<div class="checkbox">
												<label class="checkbox-inline">
													<input type="checkbox" disabled="disabled" name="permissionsFromGroups[]" value="{{ $permission->id() }}" {{ $layout->permissions()->permittedFromGroups($userid, $permission->name()) ? 'checked' : '' }}>&nbsp;
												</label>
												<label class="checkbox-inline">
													<input type="checkbox" name="permissions[]" value="{{ $permission->id() }}" {{ $layout->permissions()->permittedExplicitly($userid, $permission->name()) ? 'checked' : '' }}>
													{{ $permission->description() }} ({{ $permission->name() }})
												</label>
											</div>
										@endforeach
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-3">
										<button type="submit" class="btn btn-primary">@lang('permissions.set_permissions')</button>
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
