@php
    $curr_route = request()->route()->getName();
    $ppeActive = in_array($curr_route, ['ppeRead', 'ppeEdit']) ? 'active' : '';
    $lvActive = in_array($curr_route, ['lvRead', 'lvEdit']) ? 'active' : '';
    $hvActive = in_array($curr_route, ['hvRead', 'hvEdit']) ? 'active' : '';
    $unitActive = in_array($curr_route, ['unitRead', 'unitEdit']) ? 'active' : '';
    $itemtActive = in_array($curr_route, ['itemRead', 'itemEdit']) ? 'active' : '';
    $officeActive = in_array($curr_route, ['officeRead', 'officeEdit']) ? 'active' : '';
@endphp

<ul class="nav nav-pills nav-sidebar nav-compact flex-column">
    <li class="nav-item mb-1">
        <a href="#propertySubmenu" data-toggle="collapse" aria-expanded="false" class="nav-link2 {{ ($ppeActive || $lvActive || $hvActive) ? 'active' : '' }}" style="color: #000;" onclick="toggleSubmenu(this)">
            Property Type <i class="fas fa-angle-down right float-right"></i> 
        </a>
        <ul class="collapse list-unstyled" id="propertySubmenu">
            <li class="nav-item mb-1 mt-1">
                <a href="{{ route('ppeRead') }}" class="nav-link2 {{ $ppeActive }}" style="color: #000;">
                    <i class="fas fa-minus nav-icon"></i> PPE
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('lvRead') }}" class="nav-link2 {{ $lvActive }}" style="color: #000;">
                    <i class="fas fa-minus nav-icon"></i> Low Value
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('hvRead') }}" class="nav-link2 {{ $hvActive }}" style="color: #000;">
                    <i class="fas fa-minus nav-icon"></i> High Value
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('unitRead') }}" class="nav-link2 {{ $unitActive }}" style="color: #000;">
            Unit
        </a>
    </li>

    <li class="nav-item mb-1">
        <a href="{{ route('itemRead') }}" class="nav-link2 {{ $itemtActive }}" style="color: #000;">
            Items
        </a>
    </li>
    
    <li class="nav-item mb-1">
        <a href="{{ route('officeRead') }}" class="nav-link2 {{ $officeActive }}" style="color: #000;">
            Offices
        </a>
    </li>
</ul>

