@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('permission_group_handling') }}</div>
                <div class="panel-body">
				@if($layout->user()->permitted('permission_admin'))
					<div class="panel panel-default">
		                <div class="panel-heading">{{ $layout->language('permission_group_handling') }}</div>
		                <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/groups/modify') }}">
								{!! csrf_field() !!}
										
								<div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="group">{{ $layout->language('permission_group') }}</label>
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
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('start_of_modification') }}
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
					<div class="panel panel-default">
		                <div class="panel-heading">{{ $layout->language('assign_permission_groups') }}</div>
		                <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/groups/user') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="user">{{ $layout->language('user') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="user"  id="user">
											@foreach($layout->user()->users() as $user)
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
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('start_of_modification') }}
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