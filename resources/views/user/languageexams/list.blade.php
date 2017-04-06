@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('language_exams') }}</div>
                <div class="panel-body">
					@if($layout->user()->user()->collegistData() !== null)
						@if($layout->user()->user()->collegistData()->languageExams() === [])
							<span>{{ $layout->language('user_has_no_fonal_language_requirement') }}</span>
						@else
							<table class="table table-bordered">
								<thead>
							    	<tr>
							        	<th>{{ $layout->language('language') }}</th>
							        	<th>{{ $layout->language('state') }}</th>
							        	<th>{{ $layout->language('actions') }}</th>
							      	</tr>
							    </thead>
							    <tbody>
								@foreach($layout->user()->user()->collegistData()->languageExams() as $exam)
									<tr class="{{ $exam->resolved() ? 'success' : 'danger' }}">
										<td>{{ $exam->language() }}</td>
										<td>
										@if($exam->resolved())
											{{ $layout->language('already_resolved') }}
										@else
											{{ $layout->language('not_resolved_yet') }}
										@endif
										</td>
										<td>
										@if(!$exam->resolved())
											<a href="{{ url('data/languageexam/upload/'.$exam->id()) }}" class="btn btn-default">{{ $layout->language('upload_new_language_exam') }}</a>
										@endif
										@foreach($exam->pictures() as $picture)
											<a href="{{ url('data/languageexam/uploaded/'.$picture) }}"><i class="fa fa-file-image-o" style= "margin-left:5px;" aria-hidden="true"></i></a>
										@endforeach
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						@endif
					@else
						<span>{{ $layout->language('user_has_no_fonal_language_requirement') }}</span>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
