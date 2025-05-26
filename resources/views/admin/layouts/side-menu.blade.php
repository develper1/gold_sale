<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  
    <div class="app-brand demo ">
      <a href="{{route('admin.home')}}" class="app-brand-link">
          <img src="{{ asset('landingPageAseets/assets/img/logo.png') }}" alt="logo" class="img-fluid app-brand-logo">
      </a>
  
      {{-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
      </a> --}}
    </div>
  
    <div class="menu-inner-shadow"></div>
  
    
    
    <ul class="menu-inner py-1">
      <!-- Dashboards -->
      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'home' ? ' active open' : '' }}">
        <a href="{{ route('admin.home') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-smart-home"></i>
          <div data-i18n="Dashboards">Dashboards</div>
          
        </a>
       
      </li>
      
      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'users' ? ' active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-users"></i>
          <div data-i18n="Users">Users</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'users' ? ' active open' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="menu-link">
              <div data-i18n="List">List</div>
            </a>
          </li>
  
          
        </ul>
      </li>

      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'products' ? ' active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-tags"></i>
          <div data-i18n="Products">Products</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'products' && !request()->segment(3) ? ' active open' : '' }}">
            <a href="{{ route('admin.products.index') }}" class="menu-link">
              <div data-i18n="List">List</div>
            </a>
          </li>
          <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'products' && request()->segment(3) == 'create' ? ' active open' : '' }}">
            <a href="{{ route('admin.products.create') }}" class="menu-link">
              <div data-i18n="Create Product">Create Product</div>
            </a>
          </li>
  
          
        </ul>
      </li>

      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'subscribers' ? ' active open' : '' }}">
        <a href="{{ route('admin.subscribers.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-smart-home"></i>
          <div data-i18n="Subscribers">Subscribers</div>
        </a>
      </li>

    </ul>
    
    
  
  </aside>