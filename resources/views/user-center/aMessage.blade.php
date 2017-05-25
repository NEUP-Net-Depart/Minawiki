@foreach($paginator as $item)

    <!--
    {{ $type = get_class($item) }}
    {{ $comment_id = $item -> id }}
    {{ $item_id = '' }}
    @if($type == 'App\StarMessage')
        {{ $item_id = 'star_'.$item -> id }}
    @elseif($type == 'App\CommentMessage')
        {{ $item_id = 'comment_'.$item-> id }}
    @else
        <p>未知类型 {{ $type }}</p>
        @endif
            -->

    @if($item -> is_read == false)
        <li class="collection-item unread aMessage" id="{{ $item_id }}">
            <a href="javascript: setRead('{{ strval($item_id) }}')" class="setRead">已读</a>
    @else
        <li class="collection-item read aMessage" id="{{ $item_id }}">
            @endif
            <span class="message"> 匿名用户

                @if ($type == 'App\StarMessage')

                    <span id="{{ $item_id }}">
                赞了你的评论 :{!! $item -> content !!}
                        @if ($item -> times == 2)
                            两次
                        @endif
                    </span>
                @elseif ($type == 'App\CommentMessage')
                    @if ($item['is_read'] == false)
                        <a href="javascript: setRead('{{ strval($item_id) }}')" class="setRead">已读</a>
                    @endif
                    评论了你:
                    <ul class="collection" id="{{ $item_id }}_reply"></ul>
                    <script>
                    $('#{{ $item_id }}_reply').append(loadAComment('{{ strval($comment_id) }}'));
                </script>
                @endif
            </span>
        </li>


        @endforeach


        @if($paginator -> lastPage() > 1 && $paginator -> currentPage() != $paginator -> lastPage())
            <a href="javascript:loadMessage('{{ strval($paginator -> currentPage() + 1) }}')">
                <li style="text-align: center" class="collection-item loadMore">加载更多</li>
            </a>
        @else
            <li style="text-align:center" class="collection-item">没有更多消息了</li>
        @endif