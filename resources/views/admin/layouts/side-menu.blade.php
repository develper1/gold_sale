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

      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'coupons' ? ' active open' : '' }}">
        <a href="{{ route('admin.coupons.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-smart-home"></i>
          <div data-i18n="Coupons">Coupons</div>
        </a>
      </li>

      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'shipping' ? ' active open' : '' }}">
        <a href="{{ route('admin.shipping.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-currency-dollar"></i>
          <div data-i18n="Shipping Fee">Shipping Fee</div>
        </a>
      </li>
      
      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'services' ? ' active open' : '' }}">
        <a href="{{ route('admin.services.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-currency-dollar"></i>
          <div data-i18n="Services Fee">Services Fee</div>
        </a>
      </li>

      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'categories' ? ' active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-tags"></i>
          <div data-i18n="Categories">Categories</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'categories' && !request()->segment(3) ? ' active open' : '' }}">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
              <div data-i18n="List">List</div>
            </a>
          </li>
          <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'categories' && request()->segment(3) == 'create' ? ' active open' : '' }}">
            <a href="{{ route('admin.categories.create') }}" class="menu-link">
              <div data-i18n="Create Category">Create Category</div>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'sub-categories' ? ' active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-tags"></i>
          <div data-i18n="Sub Categories">Sub Categories</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'sub-categories' && !request()->segment(3) ? ' active open' : '' }}">
            <a href="{{ route('admin.sub-categories.index') }}" class="menu-link">
              <div data-i18n="List">List</div>
            </a>
          </li>
          <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'sub-categories' && request()->segment(3) == 'create' ? ' active open' : '' }}">
            <a href="{{ route('admin.sub-categories.create') }}" class="menu-link">
              <div data-i18n="Create Sub Category">Create Sub Category</div>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'statefee' ? ' active open' : '' }}">
        <a href="{{ route('admin.statefee.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-currency-dollar"></i>
          <div data-i18n="State Fee">State Fee</div>
        </a>
      </li>

      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'price-tier-ranges' ? ' active open' : '' }}">
        <a href="{{ route('admin.price-tier-ranges.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-currency-dollar"></i>
          <div data-i18n="Price Tier Ranges">Price Tier Ranges</div>
        </a>
      </li>
      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'spot-tier-prices' ? ' active open' : '' }}">
        <a href="{{ route('admin.spot-tier-prices.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-currency-dollar"></i>
          <div data-i18n="Spot Tier Pricing">Spot Tier Pricing</div>
        </a>
      </li>

      <li class="menu-item {{ request()->segment(1) == 'admin' && request()->segment(2) == 'settings' ? ' active open' : '' }}">
        <a href="{{ route('admin.settings.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-settings"></i>
          <div data-i18n="Settings">Settings</div>
        </a>
      </li>

    </ul>
    
    
  
  </aside>