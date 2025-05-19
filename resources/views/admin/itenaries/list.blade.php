@extends('admin.layout.app')
@section('page-title', 'Itinary List')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.itenaries.create')}}" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Create</a>
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
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th width="15%">Main Image</th>
                                    <th width="15%">Title</th>
                                    <th width="15%">Short Description</th>
                                    <th width="15%">Selling Price</th>
                                    <th width="15%">Actual Price</th>
                                    <th>Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr class="text-left align-middle">
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            @if (!empty($item->main_image) && file_exists(public_path($item->main_image)))
                                                <img src="{{ asset($item->main_image) }}" style="height: 40px; width: 40px;background-color: #524242 !important; object-position: center;" class="img-thumbnail" alt="main-image">
                                            @else
                                                <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" style="height: 40px; width: 40px;background-color: #524242 !important;" class="img-thumbnail" alt="main-image">
                                            @endif
                                        </td>

                                        <td>{{ ucwords($item->title) }}</td>

        
                                        <td>{{ ucwords($item->short_description ?? '-') }}</td>

                      
                                        <td>{{ $item->selling_price ?? '-' }}</td>

                                        <td>{{ $item->actual_price ?? '-' }}</td>

                                        <td>
                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $item->id }}"
                                                    {{ $item->status == 1 ? 'checked' : '' }}
                                                    onchange="statusToggle('{{ route('admin.itenaries.status', $item->id) }}')">
                                                <label class="custom-control-label" for="statusSwitch{{ $item->id }}"></label>
                                            </div>
                                        </td>

                                        <td class="d-flex">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.itenaries.edit', $item->id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-dark ml-1" onclick="deleteItenary({{ $item->id }})" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No itineraries found</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                       {{-- Pagination Links --}}
                        <div class="pagination-container">
                            {{$data->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
{{-- <script>  
    function deleteItenary(itenaryId) {
        Swal.fire({
            icon: 'warning',
            title: "Are you sure you want to delete this?",
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Delete",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/itinaries/itinary-list/delete/" + itenaryId, 
                    type: 'POST',  
                    data: {
                        _token: '{{ csrf_token() }}', 
                    },
                    success: function (data) {
                        if (data.status != 'success') {
                            toastFire('error', data.message);
                        } else {
                            toastFire('success', data.message);
                            location.reload();
                        }
                    },
                    error: function () {
                        toastFire('error', 'Something went wrong. Please try again.');
                    }
                });
            }
        });
    }
</script> --}}

<script>
    function deleteItenary(itenaryId) {
        const deleteUrl = "{{ route('admin.itenaries.delete', ['id' => '__id__']) }}".replace('__id__', itenaryId);

        Swal.fire({
            icon: 'warning',
            title: "Are you sure you want to delete this?",
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Delete",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',  
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (data) {
                        if (data.status !== 'success') {
                            toastFire('error', data.message);
                        } else {
                            toastFire('success', data.message);
                            location.reload();
                        }
                    },
                    error: function () {
                        toastFire('error', 'Something went wrong. Please try again.');
                    }
                });
            }
        });
    }
</script>

