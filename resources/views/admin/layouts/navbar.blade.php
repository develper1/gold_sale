<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
        

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="ti ti-menu-2 ti-sm"></i>
      </a>
    </div>
    

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

      
   
      
      

      

      <ul class="navbar-nav flex-row align-items-center ms-auto">
        
        <!-- Quick links  -->
        <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
          
          <div class="dropdown-menu dropdown-menu-end py-0">
            
            <div class="dropdown-shortcuts-list scrollable-container">
              <div class="row row-bordered overflow-visible g-0">
                <div class="dropdown-shortcuts-item col">
                  <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                    <i class="ti ti-calendar fs-4"></i>
                  </span>
                  <a href="app-calendar.html" class="stretched-link">Calendar</a>
                  <small class="text-muted mb-0">Appointments</small>
                </div>
                <div class="dropdown-shortcuts-item col">
                  <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                    <i class="ti ti-file-invoice fs-4"></i>
                  </span>
                  <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
                  <small class="text-muted mb-0">Manage Accounts</small>
                </div>
              </div>
              <div class="row row-bordered overflow-visible g-0">
                <div class="dropdown-shortcuts-item col">
                  <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                    <i class="ti ti-users fs-4"></i>
                  </span>
                  <a href="app-user-list.html" class="stretched-link">User App</a>
                  <small class="text-muted mb-0">Manage Users</small>
                </div>
                <div class="dropdown-shortcuts-item col">
                  <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                    <i class="ti ti-lock fs-4"></i>
                  </span>
                  <a href="app-access-roles.html" class="stretched-link">Role Management</a>
                  <small class="text-muted mb-0">Permission</small>
                </div>
              </div>
              <div class="row row-bordered overflow-visible g-0">
                <div class="dropdown-shortcuts-item col">
                  <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                    <i class="ti ti-chart-bar fs-4"></i>
                  </span>
                  <a href="index.html" class="stretched-link">Dashboard</a>
                  <small class="text-muted mb-0">User Profile</small>
                </div>
                <div class="dropdown-shortcuts-item col">
                  <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                    <i class="ti ti-settings fs-4"></i>
                  </span>
                  <a href="pages-account-settings-account.html" class="stretched-link">Setting</a>
                  <small class="text-muted mb-0">Account Settings</small>
                </div>
              </div>
              <div class="row row-bordered overflow-visible g-0">
                <div class="dropdown-shortcuts-item col">
                  <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                    <i class="ti ti-help fs-4"></i>
                  </span>
                  <a href="pages-faq.html" class="stretched-link">FAQs</a>
                  <small class="text-muted mb-0">FAQs & Articles</small>
                </div>
                <div class="dropdown-shortcuts-item col">
                  <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                    <i class="ti ti-square fs-4"></i>
                  </span>
                  <a href="modal-examples.html" class="stretched-link">Modals</a>
                  <small class="text-muted mb-0">Useful Popups</small>
                </div>
              </div>
            </div>
          </div>
        </li>
        <!-- Quick links -->

       

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar avatar-online">
              <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle">
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="pages-account-settings-account.html">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-online">
                      <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle">
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="fw-medium d-block"> {{ Auth::user()->name }}</span>
                    <small class="text-muted">Admin</small>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <a class="dropdown-item" href="pages-profile-user.html">
                <i class="ti ti-user-check me-2 ti-sm"></i>
                <span class="align-middle">My Profile</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="pages-account-settings-account.html">
                <i class="ti ti-settings me-2 ti-sm"></i>
                <span class="align-middle">Settings</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="pages-account-settings-billing.html">
                <span class="d-flex align-items-center align-middle">
                  <i class="flex-shrink-0 ti ti-credit-card me-2 ti-sm"></i>
                  <span class="flex-grow-1 align-middle">Billing</span>
                  <span class="flex-shrink-0 badge badge-center rounded-pill bg-label-danger w-px-20 h-px-20">2</span>
                </span>
              </a>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <a class="dropdown-item" href="pages-faq.html">
                <i class="ti ti-help me-2 ti-sm"></i>
                <span class="align-middle">FAQ</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="pages-pricing.html">
                <i class="ti ti-currency-dollar me-2 ti-sm"></i>
                <span class="align-middle">Pricing</span>
              </a>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <a class="dropdown-item"  href="{{ route('admin.logout') }}"
              onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" >
                <i class="ti ti-logout me-2 ti-sm"></i>
                <span class="align-middle">Log Out</span>
              </a>
              <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            </li>
          </ul>
        </li>
        <!--/ User -->
        


      </ul>
    </div>

    
    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper  d-none">
      <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..." aria-label="Search...">
      <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
    
    

</nav>