@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin panel - felhasználók listázása</div>
                <div class="panel-body">				
					@yield('listcontent')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
