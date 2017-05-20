@foreach($paginator as $item)

    <li id="{{ $item -> id }}_comment_box" class="collection-item"
        style="text-align: left; list-style: none;" >
        <!-- 第一行显示头像星星和删除按钮 -->
        <div class="row" style="margin-bottom:5px;">
            <div class="col" style="padding-right: 0;">
                <!-- 头像 -->
                <img src="http://www.gravatar.com/avatar/{{ md5($item->id) }}?s=36&d=identicon" alt=""
                     class="circle avatar-circle">
            </div>
            <div class="col">
                <a id="my_comment" href="/{{$item->page_id}}">{{ $item -> page_id }} </a>
                <p id="{{ $item -> id }}_update" style="margin: 0 0 0 0;"><label>{{ $item -> updated_at }}</label></p>
            </div>
            <div class="col right">
                <!-- 星星和删除 -->
                @if(isset($canDelete))
                <a class="material-icons secondary-content" style="color:red;"
                   href="javascript: showDeleteCommentModal({!! $item -> id !!})">delete</a>
                @endif
                <a class=" secondary-content"><i class="material-icons">star</i><span class="star-badge">{{ $item -> star_num }}</span></a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 0;">
            <!-- 第二行显示评论正文 -->
            <div class="col s12">
                <div class="col s12 markdown-body-strict" id="{{ $item -> id }}_comment_content"
                style="margin-bottom: 5px;">
                    {!! $item -> content !!}</div>
                @if (isset($item -> reply_id) && $item -> reply_id != null)
                    <div class="col s12 reply markdown-body-strict" id="{{ $item -> id }}_reply_content"
                         style=" margin-bottom: 0;">
                        @if(isset($item -> replyTarget) && $item -> replyTarget != null)
                            <div>{!! $item -> replyTarget -> content !!}</div>
                        @else
                            <div>该评论已被删除</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </li>

@endforeach

<form id="Comment_fm" style="display: none">
    {!! csrf_field() !!}
</form>

@if(!isset($dontShowFooter))

@if($paginator->lastPage() > 1 && $paginator->currentPage() != $paginator->lastPage())
    <a href="javascript: loadMyComments('{{ strval($paginator->currentPage()+1) }}')"
       class="collection-item loadmore">
        <center>加载更多</center>
    </a>
@else
    <li class="collection-item">
        <center>没有更多评论了</center>
    </li>
@endif

    @endif