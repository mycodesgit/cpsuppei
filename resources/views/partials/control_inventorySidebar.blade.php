@php
    $curr_route = request()->route()->getName();
    $allActive = in_array($curr_route, ['inventoryREAD', 'inventoryEdit']) ? 'active' : '';
    $ppeActive = in_array($curr_route, ['inventoryppeREAD', 'inventoryEdit']) ? 'active' : '';
    $highActive = in_array($curr_route, ['inventoryhighREAD', 'inventoryEdit']) ? 'active' : '';
    $lowActive = in_array($curr_route, ['inventorylowREAD', 'inventoryEdit']) ? 'active' : '';
    $intActive = in_array($curr_route, ['inventoryintangibleREAD']) ? 'active' : '';
    $blankStickerActive = in_array($curr_route, ['inventoryStickerTemplate']) ? 'active' : '';
@endphp

<ul class="nav nav-pills nav-sidebar nav-compact flex-column">
    <li class="nav-item mb-1">
        <a href="{{ route('inventoryREAD') }}" class="nav-link2 {{ $allActive }}" id="allButton">
            All
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('inventoryppeREAD') }}" class="nav-link2 {{ $ppeActive }}" id="ppeButton">
            PPE
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('inventoryhighREAD') }}" class="nav-link2 {{ $highActive }}" id="highButton">
            High Value
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('inventorylowREAD') }}" class="nav-link2 {{ $lowActive }}" id="lowButton">
            Low Value
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('inventoryintangibleREAD') }}" class="nav-link2 {{ $intActive }}" id="lowButton">
            Intangible Value
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('inventoryStickerTemplate') }}" class="nav-link2 {{ $blankStickerActive }}">
            Blank Sticker
        </a>
    </li>
</ul>

