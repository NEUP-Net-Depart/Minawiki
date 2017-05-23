@foreach($paginator as $item)

    @if($item -> is_read == false)
        <li class="collection-item unread aMessage" id="{{ $item -> id }}_comment">
    @else
        <li class="collection-item read aMessage" id="{{ $item -> id }}_comment">
            @endif
            <span>
            @if ($item['is_read'] == false)
                <a href="javascript: setRead('{{ strval($item -> id) }}')" class="setRead">已读</a>
            @endif

            <span> 匿名用户
                <!--
                {{ $type = get_class($item) }}
                -->
            @if ($type == 'App\StarMessage')
                赞了你的评论 :{{ $item -> comment_id -> content }}
                @if ($item -> times == 2)
                        两次</span>
                @endif
            @elseif ($type == 'App\CommentMessage')
                评论了你:
                <ul class="collection" id="{{ $item -> id }}_reply"></ul>
                <script>
                    $('#{{ $item -> id }}_reply').append(loadAComment('{{ strval($item -> comment_id -> id) }}'));
                </script>
            @endif
        </li>
        @endforeach