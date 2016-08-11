@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('default_permissions_handling') }}</div>
                <div class="panel-body">
				@if($layout->user()->permitted('permission_admin'))
					<div class="alert alert-warning">
						{{ $layout->language('whats_are_default_permissions_description') }}
					</div>
							
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('default_permissions_collegist') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/permissions/default/collegist') }}">
								{!! csrf_field() !!}
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-3">
										@foreach($layout->permissions()->getAllPermissions() as $permission)
											<div class="checkbox">
												<label><input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $layout->permissions()->hasCollegistsDefaultPermission($permission->id) !== null ? 'checked' : '' }}> {{ $permission->description }} ({{ $permission->permission_name }})</label>
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
					
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('default_permissions_guest') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/permissions/default/guest') }}">
								{!! csrf_field() !!}
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-3">
										@foreach($layout->permissions()->getAllPermissions() as $permission)
											<div class="checkbox">
												<label><input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $layout->permissions()->hasGuestsDefaultPermission($permission->id) !== null ? 'checked' : '' }}> {{ $permission->description }} ({{ $permission->permission_name }})</label>
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
