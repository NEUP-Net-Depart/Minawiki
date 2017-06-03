@foreach($paginator as $item)

    @if($item -> is_read == true)
        <li class="message read collection-item star_message" id="star_{{ $item -> id }}">
    @else
        <li class="message unread collection-item star_message" id="star_{{ $item -> id }}">
            <a class="setRead" href="javascript: setRead('{{ 'star_'.strval($item -> id) }}')">
                <i class="material-icons">done</i></a>
            @endif
            <span>

            匿名用户赞了你的评论 {!!$item -> comment -> content!!}
            @if($item -> times == 2)
                两次
            @endif
            </span>

        </li>

        @endforeach

        <div id="Comment_fm">
        {!! csrf_field() !!}
        </div>

        @if( $paginator -> lastPage() > 1 && $paginator -> currentPage() != $paginator -> lastPage())
            <li class="collection-item loadMore center"><a
                        href="javascript: loadMore('starMe', '{{ strval($paginator -> currentPage() + 1) }}')"> 加载更多</a></li>
        @else
            <li class="collection-item center">没有更多消息了</li>
        @endif