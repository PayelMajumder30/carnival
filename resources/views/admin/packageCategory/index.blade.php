@extends('admin.layout.app')
@section('page-title', 'Package Category')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
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
                        <!-- Header Row -->
                        <div class="row font-weight-bold text-center border-bottom py-2 bg-light">
                            <div class="col-1">#</div>
                            <div class="col-6">Title</div>
                            <div class="col-2">Status</div>
                            <div class="col-3">Action</div>
                        </div>

                        <!-- Sortable List -->
                        <ul class="list-group sortable-list" id="sortable">
                            @forelse($data as $index => $item)
                                <li class="list-group-item sortable-item py-3" data-id="{{ $item->id }}">
                                    <div class="row align-items-center text-center">
                                        <div class="col-1">{{ $index + $data->firstItem() }}</div>
                                        <div class="col-6">{{ $item->title }}</div>
                                        <div class="col-2">
                                            <div class="custom-control custom-switch d-inline-block" data-toggle="tooltip" title="Toggle status">
                                                <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{$item->id}}" {{ ($item->status == 1) ? 'checked' : '' }} onchange="statusAllToggle('{{ route('admin.packageCategory.status', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                                </div>              
                                        </div>
                                        </div>
                                        
                                        <div id="item-{{ $item->id }}" class="col-3 d-flex justify-content-center align-items-center mb-2">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-info edit-btn"
                                                data-id="{{ $item->id }}" 
                                                data-title="{{ $item->title }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="javascript:void(0)" class="btn btn-sm btn-dark" onclick="deletePackage({{ $item->id }})" data-toggle="tooltip" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>

                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center">No records found</li>
                            @endforelse
                        </ul>

                        <!-- Pagination -->
                        <div class="pagination-container mt-3">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4>New Package Category</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.packageCategory.store')}}" method="post">@csrf                           
                              <div class="form-group">
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter title.." value="{{ old('title') }}">
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>                            
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.packageCategory.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Package Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="form-group">
                            <label for="edit-title">Title</label>
                            <input type="text" class="form-control" name="title" id="edit-title" required>
                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="{{ asset('backend-assets/js/jquery-ui.min.js') }}"></script> 
<script>
 
    //for sorting
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
                    url: "{{ route('admin.packageCategory.sort') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function(response) {
                        $('#ajax-message').html(`
                            <div class="alert alert-success alert-dismissible fade show mt-2 text-dark" role="alert">
                                ${response.message}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `);

                        // Reload after a short delay
                        setTimeout(function () {
                            location.reload();
                        }, 1000); 
                    },
                    error: function () {
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

    // for delete
    function deletePackage(packageId) {
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
                    url: "{{ route('admin.packageCategory.delete')}}",
                    type: 'POST',
                    data: {
                        "id": packageId,
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



    //script for modal
    $(document).ready(function () {
        $('.edit-btn').on('click', function () {
            const id = $(this).data('id');
            const title = $(this).data('title');

            $('#edit-id').val(id);
            $('#edit-title').val(title);
        });
    });


</script>
@endsection