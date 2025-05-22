
@extends('admin.layouts.app')

@section('content')


<div class="container-xxl flex-grow-1 container-p-y">
        
        


      <!-- Users List Table -->
      <div class="card">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-header">Subscribers</h5>
            </div>
            <div class="col-md-6">
                <div style="text-align: end;">
                    <a href="create-product.php" target="_blank" class="btn btn-primary mt-3" style="margin-right: 5px;">Create User</a>
                </div>
            </div>
        </div>
        
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                       
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  
                 
                  @foreach($users as  $data)
                        <tr >
                          <td>{{ $data->id }}</td>
                          <td>{{ $data->name }}</td>
                          <td>{{ $data->email }}</td>
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