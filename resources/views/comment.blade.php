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
            <div class="col">
                <img src="http://www.gravatar.com/avatar/{{ md5($item->id) }}?s=36&d=identicon" alt=""
                     class="circle avatar-circle">
            </div>
            <div class="col">
                <a href="#!">{{ $item->signature }}</a>
                <p style="margin: 0 0 0 0"><label>{{ $item->updated_at }}</label></p>
            </div>
            <div class="col right">
                <a href="#!" class="secondary-content"><i class="material-icons">&#xE83A;<!--star_half--></i></a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 0">
            <div class="col s12">
                <div class="markdown-body-strict">{!! $item->content !!}</div>
                <div class="col s12 reply">
                    这里当然是回复的内容啦！
                </div>
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