@php
    $current_route=request()->route()->getName();
@endphp

<div class="row pt-2 bg-gray rounded">
    <div class="col-sm-10">
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-app {{$current_route=='dashboard'?'active':''}}">
                <i class="fas fa-th"></i> Dashboard
            </a>
            
            <a href="{{ route('ppeRead') }}" class="btn btn-app {{ request()->is('view*') ? 'active' : '' }}">
                <i class="fas fa-list"></i> View
            </a>

            <a href="{{ route('purchaseREAD') }}" class="btn btn-app {{ request()->is('purchases*') ? 'active' : '' }}">
                <i class="fas fa-server"></i> Purchases
            </a>

            <a href="{{ route('inventoryRead') }}" class="btn btn-app {{ request()->is('inventory*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Inventory
            </a>

            @if(auth()->user()->role=='Administrator')
            <a href="{{ route('userRead') }}" class="btn btn-app {{$current_route=='userRead' || $current_route=='userEdit' ?'active':''}}">
                <i class="fas fa-users"></i> Users
            </a>
            @endif

            <a href="{{ route('user_settings') }}" class="btn btn-app {{ request()->is('settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>
    
    <div class="col-sm-2" style="text-align: right;" >
        <div>
            <a href="{{ route('logout') }}" class="btn btn-app pull-right">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </div>
</div>