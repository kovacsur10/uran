@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('/admin/permissions') }}">{{ $layout->language('permissions_handling') }}</a></div>
                <div class="panel-body">
				@if($layout->user()->permitted('permission_admin'))
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('assign_permissions') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/permissions/set') }}">
								{!! csrf_field() !!}
								
								<div class="form-group">
									<label  class="col-md-4 control-label" for="user">{{ $layout->language('user') }}</label>
									<div class="col-md-6">
										<select class="form-control" name="user" id="user">
											<option value="{{ $userid }}" selected>{{ $layout->user()->getUserData($userid)->name }} ({{ $layout->user()->getUserData($userid)->username }})</option>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-3">
										@foreach($layout->user()->getAvailablePermissions() as $permission)
											<div class="checkbox">
												<label><input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $layout->permissions()->permitted($userid, $permission->permission_name) ? 'checked' : '' }}> {{ $permission->description }} ({{ $permission->permission_name }})</label>
											</div>
										@endforeach
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('set_permissions') }}
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
