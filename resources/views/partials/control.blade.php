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
                <i class="fas fa-shopping-cart"></i> Purchases
            </a>

            <a href="{{ route('inventoryREAD') }}" class="btn btn-app {{ request()->is('inventory*') ? 'active' : '' }}">
                <i class="fas fa-server"></i> Inventory
            </a>

            <a href="{{ route('rpcppeOption') }}" class="btn btn-app {{ request()->is('reports*') ? 'active' : '' }}">
                <i class="fas fa-file-pdf"></i> Reports
            </a>

            <a href="{{ route('rpcppeOption') }}" class="btn btn-app disabled {{ request()->is('technician*') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Technician
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