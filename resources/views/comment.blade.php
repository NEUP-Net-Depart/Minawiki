@if($paginator->currentPage() == 1 && $order == "mostpopular" && $paginator->count() > 0)
    <li class="collection-item" style="border-top: 1px solid #e0e0e0">
        <center>～～～精彩评论～～～</center>
    </li>
@elseif($paginator->currentPage() == 1 && $order == "latest")
    <li class="collection-item" style="border-top: 1px solid #e0e0e0">
        <center>——— 最新评论 ———</center>
    </li>
@endif
@foreach($paginator as $item)
    <li class="collection-item">
        <div class="row" style="margin-bottom: 5px">
            <div class="col" style="padding-right: 0">
                <img src="http://www.gravatar.com/avatar/{{ md5($item->id) }}?s=36&d=identicon" alt=""
                     class="circle avatar-circle">
            </div>
            <div class="col">
                <a href="#!" id="{{ $item->id }}_comment_signature">{{ $item->signature }}</a>
                <p style="margin: 0 0 0 0"><label>{{ $item->updated_at }}</label></p>
            </div>
            <div class="col right">
                <a href="#!" class="secondary-content"><i class="material-icons">&#xE80D;<!--share--></i></a>
                <a href="#!" class="secondary-content"><i class="material-icons">&#xE83A;<!--star_half--></i>
                    @if($item->star_num != 0)
                        <span class="star-badge">{{ $item->star_num }}</span>
                    @endif
                </a>
                <a href="javascript: replying('{{ $item->id }}')" class="secondary-content"><i class="material-icons">
                        &#xE15E;<!--reply--></i></a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 0">
            <div class="col s12">
                <div class="markdown-body-strict" id="{{ $item->id }}_comment_content">{!! $item->content !!}</div>
                @if(isset($item->reply_id) && $item->reply_id != null)
                    <div class="col s12 reply markdown-body-strict">
                        {!! $item->replyTarget->content !!}
                    </div>
                @endif
            </div>
        </div>
    </li>
@endforeach
@if($paginator->lastPage() > 1 && $paginator->currentPage() != $paginator->lastPage())
    <a href="javascript: loadComments('{{ $order }}','{{ strval($paginator->currentPage()+1) }}')"
       class="collection-item loadmore">
        <center>加载更多</center>
    </a>
@elseif($order == "latest")
    <li class="collection-item">
        <center>没有更多评论了</center>
    </li>
@endif