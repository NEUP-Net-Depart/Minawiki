<div class="col s12">
    <ul class="collection with-header collapsible" style="box-shadow: 0 0 0 0; border-bottom: 0" data-collapsible="accordion">
        <li class="collection-header"><h4>历史版本</h4></li>
        @foreach($paginator as $item)
        <li>
            <input class="hidden" style="display: none" name="id" value="{{ $item->id }}">
            <div class="collapsible-header">
                <div class="row" style="margin-bottom: 0">
                    <div class="col">
                        #{{ $item->number }}
                    </div>
                    <div class="col">
                        作者 #{{ $item->user_id }}
                    </div>
                    <div class="col">
                        @if($item->is_little)
                            <b>【小编辑】</b>
                        @endif
                        {{ $item->message }}
                    </div>
                    <div class="col">
                        <label>{{ $item->updated_at }}</label>
                    </div>
                </div>
            </div>
            <div class="collapsible-body ct_div"><span class="markdown-body"></span></div>
            <form id="restore_fm" style="display: none">{!! csrf_field() !!}</form>
        </li>
        @endforeach
    </ul>
    @if ($paginator->lastPage() > 1)
        <ul class="pagination">
            <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }} waves-effect">
                <a href="{{ ($paginator->currentPage() == 1) ? '#': 'javascript: showPageHistory(\'1\')' }}"><i
                            class="material-icons">chevron_left</i></a>
            </li>
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    <a href="{{ ($paginator->currentPage() == $i) ? '#' : 'javascript: showPageHistory(\''. $i . '\')' }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }} waves-effect">
                <a href="{{ ($paginator->currentPage() == $paginator->lastPage()) ? '#' : "javascript: showPageHistory('" . strval($paginator->currentPage()+1) . "')" }}"><i
                            class="material-icons">chevron_right</i></a></li>
            </li>
        </ul>
    @endif
</div>
