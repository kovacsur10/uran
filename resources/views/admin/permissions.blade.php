@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Jogok kezelése</div>
                <div class="panel-body">
				@if($layout->user()->permitted('permission_admin'))
					<div class="panel panel-default">
						<div class="panel-heading">Jogok hozzárendelése</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/permissions') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="user">Felhasználó</label>
									<div class="col-md-6">
										<select class="form-control"  name="user"  id="user">
											@foreach($layout->user()->users() as $user)
												<option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }})</option>
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
											Módosítás kezdése
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">Jogok listázása</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/permissions/list') }}">
								{!! csrf_field() !!}
								
								<div class="form-group">
									<label  class="col-md-4 control-label" for="permission">Jog</label>
									<div class="col-md-6">
										<select class="form-control" name="permission" id="permission">
											@foreach($layout->user()->getAvailablePermissions() as $permission)
												<option value="{{ $permission->id }}">{{ $permission->description }} ({{ $permission->permission_name }})</option>
											@endforeach
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Listázás
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
