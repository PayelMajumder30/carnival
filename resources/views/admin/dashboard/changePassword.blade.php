@extends('admin.layout.app')
@section('page-title', 'Update Password')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.dashboard')}}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                  
                    <div class="card-body">
                        <form action="{{ route('admin.dashboard.updatePassword') }}" method="post">
                            @csrf
                            <div class="form-group position-relative">
                                <label>New Password:</label>
                                <input type="password" name="password" id="password" class="form-control">
                                <span class="toggle-password" data-toggle="#password">
                                    <i class="fa fa-eye position-absolute" style="right: 10px; top: 38px; cursor: pointer;"></i>
                                </span>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                
                            <div class="form-group position-relative">
                                <label>Confirm Password:</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                <span class="toggle-password" data-toggle="#password_confirmation">
                                    <i class="fa fa-eye position-absolute" style="right: 10px; top: 38px; cursor: pointer;"></i>
                                </span>
                                @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update Password</button>                   
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script')

<script>
    $(document).ready(function () {
        $('.toggle-password').on('click', function() {
            var input = $($(this).data('toggle'));
            var icon  = $(this).find('i');

            if(input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
@endsection