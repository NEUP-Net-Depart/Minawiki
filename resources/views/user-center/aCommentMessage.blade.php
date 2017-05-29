@foreach($paginator as $item)
    @if ($item -> is_read == false)
        <li class="message comment_message collection-item unread" id="comment_{{ $item -> id }}">
    @else
        <li class="message comment_message collection-item read" id="comment_{{ $item -> id }}" >
        @endif
        <!-- 第一行显示头像星星和删除按钮 -->
            <div class="row" style="margin-bottom:5px;">
                <div class="col" style="padding-right: 0;">
                    <!-- 头像 -->
                    <img src="http://www.gravatar.com/avatar/{{ md5($item->id) }}?s=36&d=identicon" alt=""
                         class="circle avatar-circle">
                </div>
                <div class="col">
                    <a id="my_comment"
                       href="/{{$item -> comment -> page -> title}}">{{ $item -> comment -> page -> title }} </a>
                    <span style="margin: 0 0 0 0; display: block;"><label>{{ $item -> updated_at }}</label></span>
                </div>
                <div class="col right">
                    <!-- 星星和已读 -->
                    <a class=" secondary-content"><i class="material-icons">star</i><span
                                class="star-badge">{{ $item -> comment -> star_num }}</span></a>
                    <a class="setRead" href="javascript: setRead('{{ strval('comment_'.$item -> id) }}')">已读</a>
                </div>
            </div>
            <div class="row" style="margin-bottom: 0;">
                <!-- 第二行显示评论正文 -->
                <div class="col s12">
                    <div class="col s12 markdown-body-strict" id="{{ $item -> id }}_comment_content"
                         style="margin-bottom: 5px;">
                        {!! $item -> comment -> content !!}</div>
                    @if (isset($item -> comment -> reply_id) && $item ->  comment -> reply_id != null)
                        <div class="col s12 reply markdown-body-strict" id="{{ $item -> id }}_reply_content"
                             style=" margin-bottom: 0;">
                            @if(isset($item -> comment -> replyTarget) && $item ->  comment -> replyTarget != null)
                                <div>{!! $item -> comment -> replyTarget -> content !!}</div>
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
                <a href="javascript: loadMore('commentMe', '{{strval($paginator->currentPage()+1) }}')"
                   class="collection-item loadmore">
                    <center>加载更多</center>
                </a>
            @else
                <li class="collection-item">
                    <center>没有更多评论了</center>
                </li>
            @endif

        @endif