@foreach($paginator as $item)
    @if ($item -> is_read == false)
        <li class="message comment_message collection-item unread" id="comment_{{ $item -> id }}">
            <a class="setRead secondary-content"
               href="javascript: setRead('{{ strval('comment_'.$item -> id) }}')">
                <i class="material-icons">done</i>
            </a>
    @else
        <li class="message comment_message collection-item read" id="comment_{{ $item -> id }}">
        @endif
        <!-- 第一行显示头像星星和删除按钮 -->
            <div class="row" style="margin-bottom:5px;">
                <div class="col" style="padding-right: 0;">
                    <!-- 头像 -->
                    <img src="http://www.gravatar.com/avatar/{{ md5($item->id) }}?s=36&d=identicon" alt=""
                         class="circle avatar-circle">
                </div>
                <div class="col">
                    <a id="comment_page_{{ $item -> comment -> id }}"
                       href="/{{$item -> comment -> page -> title}}">{{ $item -> comment -> page -> title }}</a>
                    <span style="margin: 0 0 0 0; display: block;"><label>{{ $item -> updated_at }}</label></span>
                </div>
                <div class="col right theme-dark-a">
                    <!-- 星星和已读 -->
                    @if($item -> user_star_num == 0)
                        <input id="{{ $item -> comment -> id }}_star_casenum" style="display: none" value="0">
                        <a class="secondary-content"
                           href="javascript: star('{{$item -> comment -> page -> title}}', '{{ $item -> comment -> id }}')"><i
                                    class="material-icons" id="{{$item -> comment -> id}}_star">&#xE83A;</i><span
                                    class="star-badge" id="{{ $item -> comment ->id }}_star_badge" style="display: none;">0</span></a>
                    @elseif($item -> user_star_num == 1)
                        <input id="{{ $item -> comment -> id }}_star_casenum" style="display: none" value="1">
                        <a class="secondary-content"
                           href="javascript: star('{{$item -> comment -> page -> title}}', '{{ $item -> comment -> id }}')"><i
                                    class="material-icons" id="{{$item -> comment -> id}}_star">&#xE838;</i><span
                                    class="star-badge" id="{{ $item -> comment ->id }}_star_badge">1</span></a>
                    @elseif($item -> user_star_num == 2)
                        <input id="{{ $item -> comment -> id }}_star_casenum" style="display: none" value="2">
                        <a class="secondary-content"
                           href="javascript: star('{{$item -> comment -> page -> title}}', '{{ $item -> comment -> id }}')"><i
                                    class="material-icons" id="{{$item -> comment -> id}}_star">&#xE838;</i><span
                                    class="star-badge" id="{{ $item -> comment ->id }}_star_badge">2</span></a>
                    @endif


                    <a class="secondary-content" href="javascript: replying('{{ $item -> id }}', '{{ $item -> comment -> id }}')">
                        <i class="material-icons">&#xE15E;</i></a>
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