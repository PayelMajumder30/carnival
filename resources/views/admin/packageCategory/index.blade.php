@extends('admin.layout.app')
@section('page-title', 'Package Category')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- List Section -->
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                    
                            <!-- Search Form on the Right -->
                            <div class="col-md-6"></div>
                            <div class="col-md-6 text-right">
                                <form action="" method="get" class="d-inline-block">
                                    <div class="d-flex justify-content-end">
                                        <div class="form-group mr-2 mb-0">
                                            <input type="search" class="form-control form-control-sm" name="keyword" id="keyword"
                                                value="{{ request()->input('keyword') }}" placeholder="Search something...">
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Filter">
                                                    <i class="fa fa-filter"></i>
                                                </button>
                                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-toggle="tooltip"
                                                    title="Clear filter">
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
                        <table class="table table-sm table-hover text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($packageCategories as $index => $item)
                                    <tr>
                                        <td>{{ $index + $packageCategories->firstItem() }}</td>
                                        <td>{{ ucwords($item->title) }}</td>
                                        <td>
                                             <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{$item->id}}" {{ ($item->status == 1) ? 'checked' : '' }} onchange="statusToggle('{{ route('admin.packageCategory.packageCategoryStatus', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-info edit-title-btn"
                                                    data-toggle="modal" data-target="#editTitleModal"
                                                    data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                                                    title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-dark" onclick="deletePackage({{ $item->id }})"
                                                    title="Delete"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4">No records found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="pagination-container">{{ $packageCategories->appends($_GET)->links() }}</div>
                    </div>
                </div>
            </div>

            <!-- Create Section -->
            <div class="col-4">
                <div class="card">
                    <div class="card-header"><h4>Create Package Category</h4></div>
                    <div class="card-body">
                        <form action="{{ route('admin.packageCategory.packageCategoryStore') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Enter title" value="{{ old('title') }}">
                                @error('title') <p class="text-danger small">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Modal -->
<div class="modal fade" id="editTitleModal" tabindex="-1" role="dialog" aria-labelledby="editTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('admin.packageCategory.packageCategoryUpdate') }}">
            @csrf
            <input type="hidden" name="id" id="modal-title-id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Title</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal-title-input">Title</label>
                        <input type="text" class="form-control" name="title" id="modal-title-input" value="{{ old('title') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-title-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('modal-title-id').value = this.dataset.id;
                document.getElementById('modal-title-input').value = this.dataset.title;
            });
        });
    });

    function deletePackage(id) {
        Swal.fire({
            icon: 'warning',
            title: "Are you sure?",
            text: "This action cannot be undone!",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Delete"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('admin.packageCategory.packageCategorydelete') }}", {
                    id: id,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    if (data.status === 200) {
                        toastFire('success', data.message);
                        location.reload();
                    } else {
                        toastFire('error', data.message);
                    }
                });
            }
        });
    }

</script>

