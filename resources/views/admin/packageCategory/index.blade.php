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
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>status</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr id="{{$item->id}}">
                                        <td>
                                            <div class = "text-center">
                                                {{ $index + $data->firstItem() }}</td>
                                            </div>
                                        <td>
                                           <div class="text-center">
                                                <p class="text-muted mb-0">{{ ucwords($item->title) }}</p>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{$item->id}}" {{ ($item->status == 1) ? 'checked' : '' }} onchange="statusAllToggle('{{ route('admin.packageCategory.status', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-info edit-btn" data-id="{{$item->id}}" 
                                                    data-title="{{ $item->title }}" data-toggle="modal" data-target="#editModal" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-dark delete-btn">
                                                    <input type="hidden" class="hidden-id" value="{{ $item->id }}">
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

                        {{-- Edit modal for title --}}

                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{ route('admin.packageCategory.update') }}">
                                @csrf
                                <input type="hidden" name="id" id="edit-id">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title">Edit Package Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="edit-title">Title</label>
                                            <input type="text" name="title" id="edit-title" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Package category</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>

                        <div class="pagination-container">
                            {{$data->appends($_GET)->links()}}
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
        </div>
    </div>
</section>

@endsection

@section('script')
<script>
 
    $(document).ready(function () {
        $('.delete-btn').click(function () {
            let itemId = $(this).find('.hidden-id').val(); // get hidden input value

            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.packageCategory.delete') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: itemId
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                toastFire('Deleted!', response.message, 'success');
                                // Optionally remove the row
                                $('#' + itemId).remove();
                            } else {
                                toastFire('Error', 'Something went wrong', 'error');
                            }
                        },
                        error: function () {
                            toastFire('Error', 'Server error occurred', 'error');
                        }
                    });
                }
            });
        });
    });


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