@extends('admin.layout.app')
@section('page-title', ucwords($itinerary->title) . '/' .'Galleries')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.itenaries.list.all')}}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Image</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($gallery as $index => $item)
                                    <tr id="gallery_section_{{$item->id}}">
                                        <td class="text-center">{{ $index + $gallery->firstItem() }}</td>
                                        <td>
                                            <div class="text-center">
                                                @if (!empty($item->image) && file_exists(public_path($item->image)))
                                                    <img src="{{ asset($item->image) }}" alt="image_gallery" style="height: 50px; width: 70px; object-position: center;" class="img-thumbnail mr-2">
                                                @else
                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="image-gallery" style="height: 50px" class="mr-2">
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{route('admin.itenaries.galleryEdit',($item->id))}}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="javascript: void(0)" class="btn btn-sm btn-dark" onclick="deleteGallery({{$item->id}})" data-toggle="tooltip" title="Delete">
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

                        <div class="pagination-container">
                            {{$gallery->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Upload Gallery</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.itenaries.galleryStore') }}" method="post" enctype="multipart/form-data">@csrf
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="image">Image(Multiple) <span style="color: red;">*</span></label>
                                    <input type="file" class="form-control" name="image[]" id="image" multiple>
                                    @error('image.*') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <input type="hidden" name="itinerary_id" value="{{ $itinerary->id }}">
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
    function deleteGallery(galleryId) {
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
                    url: "{{ route('admin.itenaries.galleryDelete')}}",
                    type: 'POST',
                    data: {
                        "id": galleryId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function (data){
                        if (data.status != 200) {
                            toastFire('error', data.message);
                        } else {
                            toastFire('success', data.message);
                            location.reload();
                        }
                    }
                });
            }
        });
    }
</script>

@endsection