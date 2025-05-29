@extends('admin.layout.app')
@section('page-title', 'Itineraries')

@section('section')
<style>
    .tag-button {
      border-radius: 20px;
      font-size: 0.85rem;
      padding: 6px 16px;
      margin: 4px;
      font-weight: 500;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.itineraries.create')}}" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Create</a>
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
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th width="15%">Itinerary</th>
                                    <th>Destination Wise Package Category</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr class="text-left align-middle">
                                        <td>
                                            <div class="card shadow-sm" style="width: 18rem;">
                                                @if (!empty($item->main_image) && file_exists(public_path($item->main_image)))
                                                    <img src="{{ asset($item->main_image) }}" class="card-img-top" alt="main-image">
                                                @else
                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" class="card-img-top" alt="main-image">
                                                @endif
                                                <div class="card-body">
                                                    <div class="row justify-content-between">
                                                        <div>
                                                            <h5 class="card-title font-weight-bold">{{ ucwords($item->title) }}</h5>
                                                        </div>
                                                        <div>
                                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                                <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $item->id }}"
                                                                    {{ $item->status == 1 ? 'checked' : '' }}
                                                                    onchange="statusToggle('{{ route('admin.itineraries.status', $item->id) }}')">
                                                                <label class="custom-control-label" for="statusSwitch{{ $item->id }}"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="card-text text-muted mb-2">{{ \Str::limit(ucwords($item->short_description ?? '-'), 30, '...') }}</p>
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-success fw-bold">{{ENV('CURRENCY')}}{{number_format($item->selling_price)}}(
                                                            @if($item->discount_type === 'percentage')
                                                                {{ $item->discount_value }}{{ENV('PERCENTAGE')}}
                                                            @elseif($item->discount_type === 'flat')
                                                                {{ENV('CURRENCY')}}{{ number_format($item->discount_value) }} Flat
                                                            @else
                                                                _
                                                            @endif)
                                                        </span>
                                                        <span class="text-decoration-line-through text-muted">{{ENV('CURRENCY')}}{{number_format($item->actual_price)}}</span>
                                                    </div>
                                                    <a href="javascript:void(0)" class="btn btn-primary w-100">{{ENV('CURRENCY')}}{{number_format($item->selling_price)}}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="container">
                                                <div class="d-flex flex-wrap">
                                                    @php
                                                        $assignedTagIds = DB::table('itineraries_tags')
                                                            ->where('itenary_id', $item->id)
                                                            ->pluck('tag_id')
                                                            ->toArray();
                                                    @endphp

                                                    @foreach($tags as $tag)
                                                        <span class="btn tag-button {{ in_array($tag->id, $assignedTagIds) ? 'btn-success' : 'btn-outline-success' }}" 
                                                            data-tag-id="{{ $tag->id }}" 
                                                            data-itenary-id="{{ $item->id }}">
                                                            {{ $tag->title }}
                                                        </span>
                                                    @endforeach
                                                </div>


                                                <table class="table table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width: 20%;">Destination</th>
                                                            <th>Package Category</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($item->itineraryItineraries->groupBy('destination_id') as $destinationId => $items)
                                                            <tr id="destination-row-{{ $destinationId }}">
                                                                <td><strong>{{ $items->first()->destination->destination_name }}</strong></td>
                                                                <td>
                                                                    @foreach($items as $pckg)
                                                                        @if($pckg->packageCategory)
                                                                            <div class="form-check d-flex align-items-center justify-content-between mb-2">
                                                                                <div class="d-flex align-items-center">
                                                                                    <input class="form-check-input package-checkbox me-2"
                                                                                        data-itinerary-id="{{ $item->id }}"
                                                                                        data-destination-id="{{ $pckg->destination_id }}"
                                                                                        data-package-id="{{ $pckg->package_id }}"
                                                                                        type="checkbox"
                                                                                        {{ $pckg->status == 1 ? 'checked' : '' }}>
                                                                                    
                                                                                    <label class="form-check-label me-2 mb-0">
                                                                                        {{ ucwords($pckg->packageCategory->title) }}
                                                                                    </label>
                                                                                </div>

                                                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger"
                                                                                    onclick="deleteItenaryPackage({{ $pckg->id }})"
                                                                                    title="Delete"
                                                                                    style="padding: 0.2rem 0.4rem; font-size: 0.7rem;">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                                {{-- pagination link --}}
                                                <div class="pagination-container">
                                                    {{$data->links()}}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-flex">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.itineraries.edit', $item->id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-dark ml-1" onclick="deleteItenary({{ $item->id }})" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>

                                                <!-- Modal Trigger Button -->
                                                <a href="javascript:void(0)" class="btn btn-sm btn-warning ml-1" data-toggle="modal" data-target="#assignModal{{ $item->id }}" title="Assign Itinerary">
                                                    <i class="fa fa-link"></i>
                                                </a>
                                                <a href="{{ route('admin.itineraries.galleries.list', ['itinerary_id' => $item->id])}}" class="btn btn-sm btn-success ml-1" data-toggle="tooltip" title="Manage Gallery">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Assign Destination and Package Modal -->
                                    <div class="modal fade" id="assignModal{{ $item->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <form action="{{ route('admin.itineraries.assignedItinerary') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="itinerary_id" value="{{ $item->id }}">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="assignModalLabel{{ $item->id }}">Assign Package Category</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="destination_id">Destination</label>
                                                            <select name="destination_id" class="form-control" required>
                                                                <option value="">-- Select --</option>
                                                                @foreach ($destinations as $destination)
                                                                    <option value="{{ $destination->id }}">{{ $destination->destination_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>


                                                        <div class="form-group">
                                                            <label for="package_id">Package Category</label>
                                                            <select name="package_id[]" class="form-control select2" multiple required>
                                                                @foreach ($packageCategories as $category)
                                                                    <option value="{{ $category->id }}">{{ ucwords($category->title) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success btn-sm">Assign</button>
                                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

@section('script')

<link rel="stylesheet" href="{{ asset('backend-assets/css/select2.min.css') }}">
<script src="{{ asset('backend-assets/js/select2.min.js') }}"></script> 
<script>
    function deleteItenary(itenaryId) {
        const deleteUrl = "{{ route('admin.itineraries.delete', ['id' => '__id__']) }}".replace('__id__', itenaryId);

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


    //for select2 in dropdown
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
        });
    });
    
    // for check, uncheck the destinationwise package categories
    $(document).on('change', '.package-checkbox', function () {
        const checkbox = $(this);
        const isChecked = checkbox.is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{ route('admin.itineraries.togglePackageStatus') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                itinerary_id: checkbox.data('itinerary-id'),
                destination_id: checkbox.data('destination-id'),
                package_id: checkbox.data('package-id'),
                status: isChecked
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: isChecked ? 'Package activated' : 'Package deactivated',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    checkbox.prop('checked', !isChecked); // revert
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Failed to update package status',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
            error: function () {
                checkbox.prop('checked', !isChecked); // revert
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Server error. Try again.',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });
    

    //for delete Itinerary packages
    function deleteItenaryPackage(itinPckgId) {
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
                    url: "{{ route('admin.itineraries.packageItineraryDelete') }}",
                    type: 'POST',
                    data: {
                        id: itinPckgId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (data) {
                        if (data.status != 200) {
                            toastFire('error', data.message);
                        } else {
                            toastFire('success', data.message);

                            // Remove the individual package row
                            let packageBtn      = $(`[onclick="deleteItenaryPackage(${itinPckgId})"]`);
                            let formCheck       = packageBtn.closest('.form-check');
                            let destinationRow  = packageBtn.closest('tr');

                            formCheck.remove();

                            if (destinationRow.find('.form-check').length === 0) {
                                destinationRow.remove();
                            }
                        }
                    },
                    error: function () {
                        toastFire('error', 'Something went wrong. Please try again.');
                    }
                });
            }
        });
    }

    $(document).on('click', '.tag-button', function () {
        let button = $(this);
        let tagId = button.data('tag-id');
        let itenaryId = button.data('itenary-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to assign/unassign this tag to the itinerary?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.itineraries.assignTagToItenary") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        tag_id: tagId,
                        itenary_id: itenaryId
                    },
                    success: function (response) {
                        if (response.status === 'attached') {
                            button.removeClass('btn-outline-success').addClass('btn-success');
                            Swal.fire('Assigned!', 'Tag has been assigned.', 'success');
                        } else if (response.status === 'detached') {
                            button.removeClass('btn-success').addClass('btn-outline-success');
                            Swal.fire('Removed!', 'Tag has been unassigned.', 'info');
                        } else {
                            Swal.fire('Oops!', 'Unexpected response from server.', 'warning');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Server error occurred.', 'error');
                    }
                });
            }
        });
    });

</script>
@endsection

