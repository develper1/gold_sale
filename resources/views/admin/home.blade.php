
@extends('admin.layouts.app')

@section('content')


<div class="container-xxl flex-grow-1 container-p-y">
        
        

  <div class="row">
    <div class="col-sm-6 col-lg-4 mb-4">
      <div class="card card-border-shadow-success">
        <div class="card-body position-relative">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-success"><i class='ti ti-users ti-md'></i></span>
            </div>
            <h4 class="ms-1 mb-0">{{ number_format($totalUsers) }}</h4>
          </div>
          <p class="mb-1">Total Users</p>
          <p class="mb-0"><small class="text-muted">All non-admin users</small></p>
          <a href="{{ route('admin.users.index') }}" class="stretched-link" aria-label="View users"></a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-4 mb-4">
      <div class="card card-border-shadow-danger">
        <div class="card-body position-relative">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-danger"><i class='ti ti-package ti-md'></i></span>
            </div>
            <h4 class="ms-1 mb-0">{{ number_format($totalProducts) }}</h4>
          </div>
          <p class="mb-1">Total Products</p>
          <p class="mb-0"><small class="text-muted">All products in catalog</small></p>
          <a href="{{ route('admin.products.index') }}" class="stretched-link" aria-label="View products"></a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-4 mb-4">
      <div class="card card-border-shadow-info">
        <div class="card-body position-relative">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-info"><i class='ti ti-shopping-cart ti-md'></i></span>
            </div>
            <h4 class="ms-1 mb-0">{{ number_format($totalOrders) }}</h4>
          </div>
          <p class="mb-1">Total Orders</p>
          <p class="mb-0"><small class="text-muted">All orders placed</small></p>
          <a href="{{ route('admin.orders.index') }}" class="stretched-link" aria-label="View orders"></a>
        </div>
      </div>
    </div>

  </div>


</div>

    
@endsection

@push('scripts')
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

@endpush