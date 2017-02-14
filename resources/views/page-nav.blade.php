@if(isset($power) && $power > 1)
    <ul class="collection theme-word-dark theme-sec-i">
        @foreach($left_data as $item)
            <li class="collection-item">
                <div>
                    <form style="margin-bottom: 0">
                        <a href="/{{ $item->title }}">{{ $item->title }}</a>
                        <input type="hidden" id="{{ $item->title }}_id" value="{{ $item->id }}">
                        <input type="hidden" id="{{ $item->title }}_title" value="{{ $item->title }}">
                        <input type="hidden" id="{{ $item->title }}_father_id" value="{{ $item->father_id }}">
                        <input type="hidden" id="{{ $item->title }}_is_folder" value="{{ $item->is_folder }}">
                        <input type="hidden" id="{{ $item->title }}_is_notice" value="{{ $item->is_notice }}">
                        <input type="hidden" id="{{ $item->title }}_protect_children" value="{{ $item->protect_children }}">
                        <input type="hidden" id="{{ $item->title }}_power" value="{{ $item->power }}">
                        <a href="javascript: showDelPageModal('{{ $item->title }}')" class="secondary-content"><i
                                    class="material-icons">
                                &#xE872;<!--delete--></i></a><a href="javascript: showMovePageModal('{{ $item->title }}')"
                                                                class="secondary-content"><i
                                    class="material-icons">&#xE89F;<!--open_with--></i></a><a
                                href="javascript: showEditPageModal('{{ $item->title }}')"
                                class="secondary-content"><i
                                    class="material-icons">&#xE150;<!--create--></i></a>
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
    <div class="collection theme-word-dark">
        @foreach($left_data as $item)
            @if($item->id == $current_page->id)
                <a href="/{{ $item->title }}" class="collection-item active"><span
                            class="badge">1</span>{{ $item->title }}</a>
            @else
                <a href="/{{ $item->title }}" class="collection-item"><span
                            class="badge">1</span>{{ $item->title }}</a>
            @endif
        @endforeach
        @if(!$left_data_page->protect_children)
            <a href="javascript: showAddPageModal()" class="collection-item modal-trigger"
               style="text-align: center"><i
                        class="material-icons">
                    &#xE147;</i></a>
        @endif
    </div>
@endif