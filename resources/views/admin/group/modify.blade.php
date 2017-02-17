@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('/admin/groups/list') }}">{{ $layout->language('permission_group_handling') }}</a></div>
                <div class="panel-body">
				@if($layout->user()->permitted('permission_admin'))
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/groups/modify_values') }}">
						{!! csrf_field() !!}
						
						<div class="form-group">
							<label  class="col-md-4 control-label" for="group">{{ $layout->language('permission_group') }}</label>
							<div class="col-md-6">
								<select class="form-control" name="group" id="group">
									<option value="{{ $group->id() }}" selected>{{ $group->name() }}</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-6 col-md-offset-3">
								@foreach($layout->permissions()->getAllPermissions() as $permission)
									<div class="checkbox">
										<label><input type="checkbox" name="permissions[]" value="{{ $permission->id() }}" {{ in_array($permission, $group->permissions()) ? 'checked' : '' }}> {{ $permission->description() }} ({{ $permission->name() }})</label>
									</div>
								@endforeach
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									{{ $layout->language('set_permission_groups') }}
								</button>
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
