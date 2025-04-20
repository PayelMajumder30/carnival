@extends('admin.layout.app')
@section('page-title', $trip->title . '/' .'Category Banner List')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.tripcategory.list.all')}}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                            <div class="col-md-6">
                                <form action="" method="get">
                                    <div class="d-flex justify-content-end">
                                        <div class="form-group ml-2">
                                            <input type="search" class="form-control form-control-sm" name="keyword" id="keyword" value="{{ request()->input('keyword') }}" placeholder="Search something...">
                                        </div>
                                        <div class="form-group ml-2">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-filter"></i>
                                                </button>
                                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-toggle="tooltip" title="Clear filter">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>status</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($banner as $index => $item)
                                    <tr id="banner_section_{{$item->id}}">
                                        <td class="text-center">{{ $index + $banner->firstItem() }}</td>
                                        <td>
                                            <div class="text-center">
                                                @if (!empty($item->image) && file_exists(public_path($item->image)))
                                                    <img src="{{ asset($item->image) }}" alt="tripcategorybanner-image" style="height: 50px" class="img-thumbnail mr-2">
                                                @else
                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="tripcategorybanner-image" style="height: 50px" class="mr-2">
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{$item->id}}" {{ ($item->status == 1) ? 'checked' : '' }} onchange="statusAllToggle('{{ route('admin.tripcategory.bannerstatus', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{route('admin.tripcategory.banneredit',base64_encode($item->id))}}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="javascript: void(0)" class="btn btn-sm btn-dark" onclick="deleteBanner({{$item->id}})" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- <div class="pagination-container">
                            {{$catbanner->appends($_GET)->links()}}
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4>New Banner</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.tripcategory.bannerstore') }}" method="post" enctype="multipart/form-data">@csrf
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="image">Image <span style="color: red;">*</span></label>
                                    <input type="file" class="form-control" name="image" id="image">
                                    <p class="small text-muted">Size: less than 1 mb </p>
                                    @error('image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <input type="hidden" name="trip_cat_id" value="{{$trip->id}}">
                            <button type="submit" class="btn btn-primary">Upload</button>
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
    function deleteBanner(bannerId) {
        Swal.fire({
            icon: 'warning',
            title: "Are you sure you want to delete this?",
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Delete",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.tripcategory.bannerDelete')}}",
                    type: 'POST',
                    data: {
                        "id": bannerId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function (data){
                        if (data.status != 200) {
                            toastFire('error', data.message);
                        } else {
                            toastFire('success', data.message);
                            location.reload();
                            // $("#banner_section_" + bannerId).hide();
                        }
                    }
                });
            }
        });
    }
    </script>

@endsection