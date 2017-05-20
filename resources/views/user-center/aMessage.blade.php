@foreach($paginator as $item)

    @if($item['is_read'] == false)
        <li class="collection-item unread aMessage" id="{{ $item['id'] }}_comment">
    @else
        <li class="collection-item read aMessage" id="{{ $item['id'] }}_comment">
            @endif
            <span>
            @if ($item['is_read'] == false)
                <a href="javascript: setRead('{{ strval($item['id']) }}')" class="setRead">已读</a>
            @endif

            <span>{{ $item['username'] }}
            @if ($item['type'] == 'star')
                赞了你的评论 :{{ $item['commentText'] }}
                @if ($item['type'] == 'star' and $item['times'] == 2)
                        两次</span>
                @endif
            @else
                评论了你:
                <ul class="collection" id="{{ $item['id'] }}_reply"></ul>
                <script>
                    $('#{{ $item['id'] }}_reply').append(loadAComment('{{ strval($item['id']) }}'));
                </script>
            @endif
        </li>
        @endforeach