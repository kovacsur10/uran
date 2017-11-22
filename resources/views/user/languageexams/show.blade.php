@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">@lang('languageexams.language_exams')</div>
				<div class="panel-body">
				@if($layout->errors()->has('upload'))
                	<div class="alert alert-danger">
						{{$layout->errors()->get('upload')}}
					</div>
                @endif
				@if($exam !== null)
					<form class="form-horizontal" role="form" method="POST" action="{{ url('data/languageexam/upload/'.$exam->id()) }}" enctype="multipart/form-data">
						<input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('exampicture') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">@lang('languageexams.image')</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control" name="exampicture" accept=".pdf,.jpg,.jpeg,.png,.bmp,application/pdf,image/jpg,image/jpeg,image/png,image/bmp" value="{{ old('exampicture') }}">

                                @if ($errors->has('exampicture'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('exampicture') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">@lang('languageexams.upload')</button>
                            </div>
                        </div>
                    </form>
                    <div class="alert alert-info">
						<strong>@lang('general.note'):</strong> @lang('languageexams.language_exam_must_be_under_2_mbs_description')
					</div>
					<div class="alert alert-info">
						<strong>@lang('general.note'):</strong> @lang('languageexams.language_exam_extensions_description')
					</div>
				@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
