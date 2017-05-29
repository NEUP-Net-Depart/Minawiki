@foreach($paginator as $item)

    @if($item -> is_read == true)
        <li class="aStarItem read collection-item">
    @else
        <li class="aStarItem unread collection-item">
            @endif

            #{{ $item -> user -> id }}赞了你的评论 <p>{{$item -> comment -> content}}</p>
            @if($item -> times == 2)
                两次
            @endif

        </li>

        @endforeach

        @if( $paginator -> lastPage() > 1 && $paginator -> currentPage() != $paginator -> lastPage())
            <li class="collection-item loadMore"><a
                        href="javascript: loadMore('starMe', '{{ strval($paginator -> currentPage() + 1) }}')"> 加载更多</a></li>
        @else
            <li class="collection-item">没有更多消息了</li>
        @endif