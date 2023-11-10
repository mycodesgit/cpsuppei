@php
    $curr_route = request()->route()->getName();
    $allActive = in_array($curr_route, ['purchaseREAD', 'purchaseEdit']) ? 'active' : '';
    $ppeActive = in_array($curr_route, ['purchaseppeREAD', 'purchaseEdit']) ? 'active' : '';
    $highActive = in_array($curr_route, ['purchasehighREAD', 'purchaseEdit']) ? 'active' : '';
    $lowActive = in_array($curr_route, ['purchaselowREAD', 'purchaseEdit']) ? 'active' : '';
    $blankStickerActive = in_array($curr_route, ['purchaseStickerTemplate']) ? 'active' : '';
@endphp

<ul class="nav nav-pills nav-sidebar nav-compact flex-column">
    <li class="nav-item mb-1">
        <a href="{{ route('purchaseREAD') }}" class="nav-link2 {{ $allActive }}">
            All
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('purchaseppeREAD') }}" class="nav-link2 {{ $ppeActive }}">
            PPE
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('purchasehighREAD') }}" class="nav-link2 {{ $highActive }}">
            High Value
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('purchaselowREAD') }}" class="nav-link2 {{ $lowActive }}">
            Low Value
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('purchaseStickerTemplate') }}" class="nav-link2 {{ $blankStickerActive }}">
            Blank Sticker
        </a>
    </li>
</ul>

