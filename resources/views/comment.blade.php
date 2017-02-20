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
    <li id="{{ $item->id }}_comment_box" class="collection-item">
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
                <!-- Dropdown Trigger -->
                <a href="#!" class="dropdown-button secondary-content" data-activates='{{ $item->id }}_dropdown'><i
                            class="material-icons">&#xE5D4;<!--more_vert--></i></a>
                <!-- Dropdown Structure -->
                <ul id='{{ $item->id }}_dropdown' class='dropdown-content'>
                    <li><a class="copyTriggerButton">
                            <table>
                                <td class="no-padding"><i class="material-icons">&#xE14D;<!--content_copy--></i></td>
                                <td class="no-padding">复制评论</td>
                            </table>
                        </a>
                    </li>
                    <li><a href="#!">
                            <table>
                                <td class="no-padding"><i class="material-icons">&#xE80D;<!--share--></i></td>
                                <td class="no-padding">分享评论</td>
                            </table>
                        </a>
                    </li>
                    @if(isset($uid) && $uid == $item->user_id)
                        <li><a href="javascript: deleteComment({{ $item->id }})">
                                <table>
                                    <td class="no-padding"><i class="material-icons">&#xE92B;<!--delete_forever--></i>
                                    </td>
                                    <td class="no-padding">删除评论</td>
                                </table>
                            </a>
                        </li>
                    @else
                        <li><a href="#!">
                                <table>
                                    <td class="no-padding"><i class="material-icons">&#xE160;<!--report--></i></td>
                                    <td class="no-padding">举报评论</td>
                                </table>
                            </a>
                        </li>
                        @if(isset($power) && $power > 0)
                            <li><a href="javascript: deleteComment({{ $item->id }})">
                                    <table>
                                        <td class="no-padding"><i class="material-icons">&#xE14B;<!--block--></i></td>
                                        <td class="no-padding">屏蔽评论</td>
                                    </table>
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
                @if(!isset($uid))
                    <a href="/auth/login?continue={{ urlencode($continue) }}" class="secondary-content"><i id="{{ $item->id }}_star" class="material-icons">
                            &#xE83A;<!--star_border--></i>
                @elseif($item->user_star_num == 0)
                    <input type="hidden" style="display: none" id="{{ $item->id }}_star_casenum" value="0">
                    <a href="javascript: star('{{ $item->id }}')" class="secondary-content"><i id="{{ $item->id }}_star" class="material-icons">
                            &#xE83A;<!--star_border--></i>
                @elseif($item->user_star_num == 1)
                    <input type="hidden" style="display: none" id="{{ $item->id }}_star_casenum" value="1">
                    <a href="javascript: star('{{ $item->id }}')" class="secondary-content"><i id="{{ $item->id }}_star" class="material-icons">
                            &#xE838;<!--star--></i>
                @elseif($item->user_star_num == 2)
                    <input type="hidden" style="display: none" id="{{ $item->id }}_star_casenum" value="2">
                    <a href="javascript: star('{{ $item->id }}')" class="secondary-content"><i id="{{ $item->id }}_star" class="material-icons">
                            &#xE838;<!--star--></i>
                @endif
                        <span id="{{ $item->id }}_star_badge" class="star-badge"
                           @if($item->star_num == 0)
                               style="display: none"
                           @endif
                    >{{ $item->star_num }}</span>
                     </a>
                                    <a href="javascript: replying('{{ $item->id }}')" class="secondary-content"><i
                                                class="material-icons">
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
<form id="Comment_fm" style="display: none">
    {!! csrf_field() !!}
</form>
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