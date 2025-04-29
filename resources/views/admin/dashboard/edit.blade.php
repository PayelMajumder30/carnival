@extends('admin.layout.app')
@section('page-title', 'Profile')
@section('section')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{route('admin.dashboard')}}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.dashboard.update') }}" method="POST">
                            @csrf
                    
                            <div class="form-group mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" 
                                    value="{{ old('name', $admin->name) }}" required>
                            </div>
                    
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" 
                                    value="{{ old('email', $admin->email) }}" required>
                            </div>
                    
                            <div class="form-group mb-3">
                                <label>Mobile Number</label>
                                <input type="number" name="mobile_no" class="form-control" 
                                    value="{{ old('mobile_no', $admin->mobile_no) }}">
                                    @error('mobile_no')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                            </div>
                    
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection




