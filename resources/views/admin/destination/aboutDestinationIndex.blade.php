@extends('admin.layout.app')
@section('page-title', $destination->destination_name . '/' .'About Destination')

@section('section')

<section class="content">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row mb-3">
                                <div class="col-md-6 text-left">
                                    <a href="{{ route('admin.destination.list.all')}}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-chevron-left"></i> Back
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    @if(!$aboutDestination)
                                    <a href="{{ route('admin.destination.aboutDestiCreate', ['destination_id' => $destination->id])}}" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Create</a>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <form action="" method="get">
                                        <div class="d-flex justify-content-end">
                                            {{-- <div class="form-group ml-2">
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
                                            </div> --}}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Content</th>
                                        <th style="width: 100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($aboutDestination)
                                        <tr>
                                            <td class="text-center">
                                                <div>
                                                    {!! $aboutDestination->content !!}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.destination.aboutDestiEdit', $aboutDestination->id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <a href="javascript: void(0)" class="btn btn-sm btn-dark mr-1" onclick="deleteAboutDesti({{$aboutDestination->id}})" data-toggle="tooltip" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="100%" class="text-center">No records found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection

@section('script')
<script>
    function deleteAboutDesti(aboutDesId) {
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
                  url: "{{ route('admin.destination.aboutDestiDelete')}}",
                  type: 'POST',
                  data: {
                      "id": aboutDesId,
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
