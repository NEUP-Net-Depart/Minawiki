@if(isset($power) && $power > 1)
    <ul class="collection theme-word-dark theme-sec-i">
        @foreach($left_data as $item)
            <li class="collection-item">
                <div>
                    <form style="margin-bottom: 0">
                        <a href="/{{ $item->title }}">{{ $item->title }}</a>
                        <input type="hidden" id="{{ $item->id }}_id" value="{{ $item->id }}">
                        <input type="hidden" id="{{ $item->id }}_title" value="{{ $item->title }}">
                        <input type="hidden" id="{{ $item->id }}_father_id" value="{{ $item->father_id }}">
                        <input type="hidden" id="{{ $item->id }}_is_folder" value="{{ $item->is_folder }}">
                        <input type="hidden" id="{{ $item->id }}_is_notice" value="{{ $item->is_notice }}">
                        <input type="hidden" id="{{ $item->id }}_protect_children"
                               value="{{ $item->protect_children }}">
                        <input type="hidden" id="{{ $item->id }}_power" value="{{ $item->power }}">
                        @if(isset($realLogined) && $realLogined)
                            <a href="javascript: showDelPageModal('{{ $item->id }}')" class="secondary-content"><i
                                        class="material-icons">
                                    &#xE872;<!--delete--></i></a>
                        @else
                            <a href="/auth/confirm?continue={{ urlencode($continue) }}" class="secondary-content"><i
                                        class="material-icons">
                                    &#xE872;<!--delete--></i></a>
                        @endif
                        <a href="javascript: showMovePageModal('{{ $item->id }}')"
                           class="secondary-content"><i
                                    class="material-icons">&#xE89F;<!--open_with--></i></a><a
                                href="javascript: showEditPageModal('{{ $item->id }}')"
                                class="secondary-content"><i class="material-icons">&#xE3C9;<!--edit--></i></a>
                    </form>
                </div>
            </li>
        @endforeach
        <a href="javascript: showAddPageModal()" class="collection-item modal-trigger" style="text-align: center"><i
                    class="material-icons">
                &#xE147;</i></a>
    </ul>
@else
    <!--User-->
    {{--<div id="left-nav-ul" class="collection theme-word-dark">--}}
    <ul class="collection theme-word-dark">
        @foreach($left_data as $item)
            @if($item->id == $current_page->id)

                <li class="collection">
                    <a href="/{{ $item->title }}" class="collection-item active"><span
                                class="badge">{{ $item->comments->count() > 0 ? $item->comments->count() : '' }}</span>{{ $item->title }}
                    </a>
                </li>
            @else
                <li class="collection">
                    <a href="/{{ $item->title }}" class="collection-item"><span
                                class="badge">{{ $item->comments->count() > 0 ? $item->comments->count() : '' }}</span>{{ $item->title }}
                    </a>
                </li>
            @endif
        @endforeach
        @if(!$left_data_page->protect_children)
            @if(isset($uid))
                <li class="collection">
                    <a href="javascript: showAddPageModal()" class="collection-item modal-trigger"
                       style="text-align: center"><i
                                class="material-icons">
                            &#xE147;</i></a>
                </li>
            @else
                <li class="collection">
                    <a href="/auth/login?continue={{ urlencode($continue) }}" class="collection-item modal-trigger"
                       style="text-align: center"><i
                                class="material-icons">
                            &#xE147;</i></a>
                </li>
            @endif
        @endif
    </ul>
    </div>
@endif

<script>
    $(document).ready(function () {
        Materialize.showStaggeredList('ul');
    });
</script>