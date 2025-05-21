
@extends('admin.layouts.app')

@section('content')


<div class="container-xxl flex-grow-1 container-p-y">
        
        


      <!-- Products List Table -->
      <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Products</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="{{ route('admin.products.create') }}" target="_blank" class="btn btn-primary mt-3" style="margin-right: 5px;">Create Product</a>
                </div>
            </div>
        </div>
        
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                       
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  
                 
                  @foreach($products as  $data)
                      <tr >
                          <td>{{ $data->id }}</td>
                          <td>{{ $data->name }}</td>
                          <td>{{ $data->description }}</td>
                          {{-- <td>
                              <div class="dropdown">
                                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                      <i class="ti ti-dots-vertical"></i>
                                  </button>
                                  <div class="dropdown-menu">
                                      <a class="dropdown-item delete-btn" data-id="{{ $data->id }}" href="javascript:void(0)">
                                          <i class="fa-solid fa-trash me-1"></i> Delete
                                      </a>
                                      <a class="dropdown-item" data-id="{{ $data->id }}" >
                                          <i class="fa-solid fa-edit me-1"></i> Edit
                                      </a>
                                  </div>
                              </div>
                          </td> --}}
                      </tr>
                  @endforeach
              
                </tbody>
            </table>
        </div>
    </div>


</div>

    
@endsection

@push('scripts')

{{-- <script src="{{ asset('assets/js/app-user-list.js') }}"></script> --}}
    
@endpush