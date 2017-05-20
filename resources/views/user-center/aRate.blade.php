@foreach($paginator as $item)

    <li id="{{ $item['id'] }}_rating_box" class="collection-item"
        style="text-align: left; list-style: none;" >
        <div class="row" style="margin-bottom:5px;">
            <div class="col rating_img" style="padding-right: 0;">
                <!-- 头像 -->
                <img src="http://www.gravatar.com/avatar/{{ md5($item['id']) }}?s=36&d=identicon" alt=""
                     class="circle avatar-circle">
            </div>
            <div class="col rating_content">
                <div class="row mark_title">
                    <a id="{{ $item[ 'id']}}_page" href="/{{ $item[ 'page_id']}}">{{ $item[ 'page_id']}}</a>
                    <span> : </span>
                    <a id="{{ $item[ 'id']}}_rateitem">{{ $item[ 'rateitem']}}</a>
                </div>
                <div class="row">
                    <div class="rate_mark" class="markArea">
                        <p class="markNum theme-word-dark">{{ $item[ 'mark']}}/{{ $item[ 'full_mark']}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="timeDate" style="text-align: right;">
                        <label>{{ $item['time']}}</label>
                    </div>
                </div>
            </div>
        </div>
    </li>

@endforeach

<form id="Comment_fm" style="display: none">
    {!! csrf_field() !!}
</form>

@if($noMore == true)
    <li class="collection-item" style="text-align: center"> 没有更多评分了 </li>
    @else
    <li class="collection-item loadmore" style="text-align: center">
        <a href="javascript:loadMyRating('{{  strval($now + 1) }}')" >加载更多评分</a>
    </li>
    @endif

