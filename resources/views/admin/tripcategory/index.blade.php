@extends('admin.layout.app')
@section('page-title', 'Trip Categories')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.tripcategory.create') }}" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Create</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"></div>
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
                    <div id="ajax-message"></div>
                    <div class="card-body">
                        {{-- <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>status</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr>
                                        <td>{{ $index + $data->firstItem() }}</td>
                                        <td>
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->title }}</p>
                                            </div>
                                        </td>

                                        <td> 
                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{$item->id}}" {{ ($item->status == 1) ? 'checked' : '' }} onchange="statusToggle('{{ route('admin.tripcategory.status', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                            </div>
                                        </td>

                                        <td class="d-flex">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.tripcategory.edit', $item->id)}}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.tripcategory.delete', $item->id)}}" class="btn btn-sm btn-dark" onclick="return confirm('Are you sure ?')" data-toggle="tooltip" title="Delete">
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
                        </table> --}}

                        <div class="d-flex font-weight-bold text-center border-bottom py-2 bg-light">
                            <div class="col-1">#</div>
                            <div class="col-3 text-left">Title</div>
                            <div class="col-5">Status</div>
                            <div class="col-3">Action</div>
                        </div>
                        
                        <ul class="list-group sortable-list" id="sortable">
                            @forelse($data as $index => $item)
                                <li class="list-group-item sortable-item py-3" data-id="{{ $item->id }}">
                                    <div class="d-flex text-center align-items-center">
                                        <div class="col-1">{{ $index + $data->firstItem() }}</div>
                                        <div class="col-3 text-left">{{ $item->title }}</div>
                                        <div class="col-5">
                                            <div class="custom-control custom-switch" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="customSwitch{{$item->id}}"
                                                    {{ ($item->status == 1) ? 'checked' : '' }}
                                                    onchange="statusToggle('{{ route('admin.tripcategory.status', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                            </div>
                                        </div>
                                        <div class="col-3 d-flex justify-content-center">
                                            <a href="{{ route('admin.tripcategory.edit', $item->id) }}" class="btn btn-sm btn-info mr-1" data-toggle="tooltip" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                    
                                            <a href="javascript: void(0)" class="btn btn-sm btn-dark mr-1" onclick="deleteTrip({{$item->id}})" data-toggle="tooltip" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <a href="{{ route('admin.tripcategorybanner.list.all', ['trip_cat_id' => $item->id] )}}" class="btn btn-sm btn-primary mr-1" data-toggle="tooltip" title="Banner">
                                                Banner
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center">No records found</li>
                            @endforelse
                        </ul>

                        <div class="pagination-container">
                            {{$data->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    $(function () {
        $('#sortable').sortable({
            update: function (event, ui) {
                let order = [];
                $('.sortable-item').each(function (index, element) {
                    order.push({
                        id: $(this).data('id'),
                        position: index + 1
                    });
                });
    
                $.ajax({
                    url: "{{ route('admin.tripcategory.sort')}}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function(response) {
                        //console.log('Sorting Updated');
                        //alert('Update successfully');
                        $('#ajax-message').html(`
                            <div class="alert alert-success alert-dismissible fade show mt-2 text-dark" role="alert">
                                ${response.message}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `);
                    },
                    error: function () {
                        //alert('Sorting failed. Please try again');
                        $('#ajax-message').html(`
                            <div class="alert alert-danger fade show mt-2" role="alert">
                                Sorting failed. Please try again.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `);
                    }
                });
            }
        });
    }); 
    
    function deleteTrip(tripId) {
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
                    url: "{{ route('admin.tripcategory.delete')}}",
                    type: 'POST',
                    data: {
                        "id": tripId,
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