<!--PAGINATION-->
<div class="row">
    <div class="col-sm-3" style="margin: 20px 0px;">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">@lang('ecouncil.choose_visible_row_count')
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ url('ecnet/account/10/'.$firstRecord) }}">10</a></li>
                <li><a href="{{ url('ecnet/account/20/'.$firstRecord) }}">20</a></li>
                <li><a href="{{ url('ecnet/account/50/'.$firstRecord) }}">50</a></li>
                <li><a href="{{ url('ecnet/account/100/'.$firstRecord) }}">100</a></li>
            </ul>
        </div>
    </div>
    <div class="col-sm-6">
        <ul class="pagination">
            @if(0 < $firstRecord)
                <li><a href="{{ url('ecnet/account/'.$recordsToShow.'/'.($firstRecord- $recordsToShow >= 0 ? $firstRecord - $recordsToShow : 0)) }}">&laquo;</a></li>
            @else
                <li class="disabled"><a href="#">&laquo;</a></li>
            @endif
            @foreach($layout->base()->getPagination($firstRecord, $recordsToShow, count($layout->printJobs()->get())) as $id => $page)
                @if($page === 'middle')
                    <li class="active"><span>{{ $id }}</span></li>
                @elseif($page === 'disabled')
                    <li class="disabled"><span>&nbsp;&nbsp;&nbsp;</span></li>
                @else
                    <li><a href="{{ url('ecnet/account/'.$recordsToShow.'/'.$page) }}">{{ $id < 10 ? '&nbsp;'.$id : $id }}</a></li>
                @endif
            @endforeach
            @if($firstRecord+$recordsToShow < count($layout->records()->getRecords()))
                <li><a href="{{ url('ecnet/account/'.$recordsToShow.'/'.($firstRecord+$recordsToShow)) }}">&raquo;</a></li>
            @else
                <li class="disabled"><a href="#">&raquo;</a></li>
            @endif
        </ul>
    </div>
</div>

<!--CONTENT -->
<div class="panel panel-default">
    <div class="panel-heading">@lang('ecnet.print_queue') </div>
    <div class="panel-body">

        <div class="well well-sm">
            <div class="row">
                <div class="col-sm-2">@lang('ecnet.user') </div>
                <div class="col-sm-4">@lang('ecnet.filename') </div>
                <div class="col-sm-2">@lang('ecnet.date') </div>
                <div class="col-sm-2">@lang('ecnet.cost') </div>
                <div class="col-sm-2">@lang('ecnet.state') </div>
            </div>
        </div>
        @if($layout->printJobs()->printJobsToPages($firstRecord, $recordsToShow) != null)
            @foreach($layout->printJobs()->printJobsToPages($firstRecord, $recordsToShow) as $printJob)
                <div class="well well-sm">
                    <div class="row" >
                        <div class="col-sm-2">{{ $layout->user()->getUserData($printJob->user_id())->name() }}</div>
                        <div class="col-sm-4">{{ $printJob->filename() }}</div>
                        <div class="col-sm-2">{{ substr($printJob->date(), 0, 16) }}</div>
                        <div class="col-sm-2" title="{{ $printJob->costExplanation() }}">{{ $printJob->cost() }} HUF</div>
                        <div class="col-sm-2">
                            @if($printJob->state() == "DONE")
                                <span class="badge" style="background: #5cb85c;">@lang('ecnet.state_done')</span>
                            @elseif ($printJob->state() == "ERROR")
                                <span class="badge" style="background: #d9534f;">@lang('ecnet.state_error')</span>
                            @elseif ($printJob->state() == "PRINTING")
                                <span class="badge badge-primary" style="background: #337ab7;">@lang('ecnet.state_printing')</span>
                            @elseif ($printJob->state() == "QUEUED")
                                <span class="badge badge-warning" style="background: #f0ad4e;">@lang('ecnet.state_queued')</span>
                            @elseif ($printJob->state() == "CANCELED")
                                <span class="badge badge-warning" style="background: #f0ad4e;">@lang('ecnet.state_canceled')</span>
                            @elseif ($printJob->state() == "ONHOLD")
                                <span class="badge badge-warning" style="background: #f0ad4e;">@lang('ecnet.state_onhold')</span>
                            @else
                                <span class="badge badge-warning" style="background: #f0ad4e;">{{ $printJob->state() }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!--PAGINATION-->
<div class="row">
    <div class="col-sm-3" style="margin: 20px 0px;">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"> @lang('ecouncil.choose_visible_row_count')
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ url('ecnet/account/10/'.$firstRecord) }}">10</a></li>
                <li><a href="{{ url('ecnet/account/20/'.$firstRecord) }}">20</a></li>
                <li><a href="{{ url('ecnet/account/50/'.$firstRecord) }}">50</a></li>
                <li><a href="{{ url('ecnet/account/100/'.$firstRecord) }}">100</a></li>
            </ul>
        </div>
    </div>
    <div class="col-sm-6">
        <ul class="pagination">
            @if(0 < $firstRecord)
                <li><a href="{{ url('ecnet/account/'.$recordsToShow.'/'.($firstRecord- $recordsToShow >= 0 ? $firstRecord - $recordsToShow : 0)) }}">&laquo;</a></li>
            @else
                <li class="disabled"><a href="#">&laquo;</a></li>
            @endif
            @foreach($layout->base()->getPagination($firstRecord, $recordsToShow, count($layout->printJobs()->get())) as $id => $page)
                @if($page === 'middle')
                    <li class="active"><span>{{ $id }}</span></li>
                @elseif($page === 'disabled')
                    <li class="disabled"><span>&nbsp;&nbsp;&nbsp;</span></li>
                @else
                    <li><a href="{{ url('ecnet/account/'.$recordsToShow.'/'.$page) }}">{{ $id < 10 ? '&nbsp;'.$id : $id }}</a></li>
                @endif
            @endforeach
            @if($firstRecord+$recordsToShow < count($layout->records()->getRecords()))
                <li><a href="{{ url('ecnet/account/'.$recordsToShow.'/'.($firstRecord+$recordsToShow)) }}">&raquo;</a></li>
            @else
                <li class="disabled"><a href="#">&raquo;</a></li>
            @endif
        </ul>
    </div>
</div>