@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('modules_handling') }}</div>
                <div class="panel-body">
				@if($layout->user()->permitted('module_admin'))
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('module_activate') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/modules/activate') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('module') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="module">{{ $layout->language('module') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="module"  id="module">
											@foreach($layout->modules()->getInactives() as $module)
												<option value="{{ $module->id }}">{{ $module->name }}</option>
											@endforeach
										</select>

										@if($errors->has('module'))
											<span class="help-block">
												<strong>{{ $errors->first('module') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('activate') }}
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('module_deactivate') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/modules/deactivate') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('module') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="module">{{ $layout->language('module') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="module"  id="module">
											@foreach($layout->modules()->getActives() as $module)
												<option value="{{ $module->id }}">{{ $module->name }}</option>
											@endforeach
										</select>

										@if($errors->has('module'))
											<span class="help-block">
												<strong>{{ $errors->first('module') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('deactivate') }}
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('modules') }}</div>
						<div class="panel-body">
							<ul class="list-group">
								@foreach($layout->modules()->get() as $module)
									<li class="list-group-item list-group-item-{{ $layout->modules()->isActivatedById($module->id) ? 'success' : 'danger' }}">{{ $module->name }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
