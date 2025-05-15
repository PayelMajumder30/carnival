@extends('admin.layout.app')
@section('page-title', 'Banner Title')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-md-6">
                                {{-- <a href="{{ route('admin.banner.list.all')}}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a> --}}
                            </div>
                            {{-- <div class="col-md-6">
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
                            </div> --}}
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Title</th>
                                    {{-- <th style="width: 100px">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + $data->firstItem() }}</td>
                                        <td class="text-center">
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ ucwords($item->title) }}</p>
                                            </div>
                                        </td>
                                        {{-- <td class="d-flex">
                                            <div class="btn-group">
                                                <a href="" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                               
                                                <a href="javascript: void(0)" class="btn btn-sm btn-dark mr-1" onclick="deleteBanner({{$item->id}})" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td> --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="pagination-container">
                            {{$data->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                       <h4>Update Banner Title</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.banner.store') }}" method="post" enctype="multipart/form-data">@csrf
                                         
                                <div class="form-group">
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <textarea class="form-control" name="title" id="title" rows="4">{{ ucwords($item->title) }}</textarea>
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            
                            <!-- Button in a separate row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
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
                  url: "{{ route('admin.banner.delete')}}",
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
                      }
                  }
              });
          }
      });
  }
</script>