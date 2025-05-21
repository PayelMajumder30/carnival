@extends('admin.layout.app')
@section('page-title', 'Itineraries')

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
                                    <th width="15%">Itinerary</th>
                                    <th>Destination Wise Package View</th>
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
                                                                    onchange="statusToggle('{{ route('admin.itenaries.status', $item->id) }}')">
                                                                <label class="custom-control-label" for="statusSwitch{{ $item->id }}"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="card-text text-muted mb-2">{{ \Str::limit(ucwords($item->short_description ?? '-'), 10, '...') }}</p>
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-success fw-bold">{{ENV('CURRENCY')}}{{number_format($item->selling_price,2)}}</span>
                                                        <span class="text-decoration-line-through text-muted">{{ENV('CURRENCY')}}{{number_format($item->actual_price,2)}}</span>
                                                    </div>
                                                    <a href="javascript:void(0)" class="btn btn-primary w-100">{{ENV('CURRENCY')}}{{number_format($item->selling_price,2)}}</a>
                                                </div>
                                            </div>
                                        </td>
                                       <td>
                                        <div class="container mt-5">
                                            
                                            {{-- <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 20%;">Destination</th>
                                                        <th>Packages</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Assam</strong></td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="packages[]" value="Wellness Tour" id="wellness_assam">
                                                                <label class="form-check-label" for="wellness_assam">Wellness Tour</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="packages[]" value="Holiday Tours" id="holiday_assam">
                                                                <label class="form-check-label" for="holiday_assam">Holiday Tours</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="packages[]" value="Family Tours" id="family_assam">
                                                                <label class="form-check-label" for="family_assam">Family Tours</label>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td><strong>Goa</strong></td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="packages[]" value="Wellness Tour" id="wellness_assam">
                                                                <label class="form-check-label" for="wellness_assam">Wellness Tour</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="packages[]" value="Holiday Tours" id="holiday_assam">
                                                                <label class="form-check-label" for="holiday_assam">Holiday Tours</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="packages[]" value="Family Tours" id="family_assam">
                                                                <label class="form-check-label" for="family_assam">Family Tours</label>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                    <!-- Add more destinations as needed -->
                                                </tbody>
                                            </table> --}}

                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 20%;">Destination</th>
                                                        <th>Packages</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item->itineraryItineraries->groupBy('destination_id') as $destinationId => $items)
                                                        <tr>
                                                            <td><strong>{{ $items->first()->destination->destination_name }}</strong></td>
                                                            <td>
                                                                @foreach($items as $pckg)
                                                                    @if($pckg->packageCategory)
                                                                        <div class="form-check">
                                                                            <input class="form-check-input package-checkbox"
                                                                                data-itinerary-id="{{ $item->id }}"
                                                                                data-destination-id="{{ $pckg->destination_id }}"
                                                                                data-package-id="{{ $pckg->package_id }}"
                                                                                type="checkbox" {{ $pckg->status == 1 ? 'checked' : ''}}>
                                                                                <label class="form-check-label">
                                                                                    {{ $pckg->packageCategory->title }}
                                                                                </label>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

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

                                                <!-- Modal Trigger Button -->
                                                <a href="javascript:void(0)" class="btn btn-sm btn-dark ml-1" data-toggle="modal" data-target="#assignModal{{ $item->id }}" title="Assign Itinerary">
                                                    <i class="fa fa-link"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Assign Destination and Package Modal -->
                                    <div class="modal fade" id="assignModal{{ $item->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <form action="{{ route('admin.itenaries.assignedItinerary') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="itinerary_id" value="{{ $item->id }}">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="assignModalLabel{{ $item->id }}">Assign Destination Wise Package</h5>
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
                                                            <label for="package_id">Select Packages</label>
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


    //for select2 in dropdown
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
        });
    });
    
    // for check uncheck the destinationwise package categories
    $(document).on('change', '.package-checkbox', function () {
        const checkbox = $(this);
        const isChecked = checkbox.is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{ route('admin.itenaries.togglePackageStatus') }}",
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

   
</script>
@endsection

