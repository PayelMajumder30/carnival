@extends('admin.layout.app')
@section('page-title', 'Popular Packages')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
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
                        <table class="table table-sm table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Destination Name</th>
                                    <th>Popular Packages</th>
                                    <th>Assign Packages</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($destinations as $destination)
                                    <tr>
                                        <td>
                                            {{ $destination->destination_name }}
                                            @if($destination->country)
                                                ({{ $destination->country->country_name }})
                                            @endif
                                        </td>

                                        <td>
                                            @foreach($destination->popularItineraries as $pckg)
                                                @if($pckg->popularitinerary)
                                                    <div class="form-check d-flex align-items-center justify-content-between mb-2">

                                                        <div class="d-flex align-items-center">
                                                            <input
                                                                id="status-checkbox-{{ $pckg->id }}"
                                                                class="form-check-input me-2 status-checkbox"
                                                                type="checkbox"
                                                                data-id="{{ $pckg->id }}"
                                                                {{ $pckg->status ? 'checked' : '' }}
                                                            >
                                                            <label class="form-check-label mb-0 cursor-pointer" for="status-checkbox-{{ $pckg->id }}">
                                                                {{ $pckg->popularitinerary->title }}
                                                            </label>

                                                           <div class="d-flex flex-wrap gap-2">
                                                                @foreach($pckg->tags as $tag)
                                                                    <span class="badge rounded-pill bg-success text-white px-3 py-2 fs-6">
                                                                        {{ $tag->title }}
                                                                    </span>
                                                                @endforeach
                                                            </div>

                                                        </div>


                                                        <div class="d-flex">
                                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-dark me-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#tagAssignModal-{{ $pckg->id }}">
                                                                Assign Tags
                                                            </a>

                                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger"
                                                                onclick="deleteItenaryPackage({{ $pckg->id }})"
                                                                title="Delete"
                                                                style="padding: 0.2rem 0.4rem; font-size: 0.7rem;">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="tagAssignModal-{{ $pckg->id }}" tabindex="-1" aria-labelledby="tagModalLabel-{{ $pckg->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form action="{{ route('admin.popularpackages.assign.tags') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="popular_package_id" value="{{ $pckg->id }}">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="tagModalLabel-{{ $pckg->id }}">Assign Tags to Package</h5>
                                                                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; line-height: 1; border: none; background: none;">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Package Name</label>
                                                                            <input type="text" class="form-control" value="{{ $pckg->popularitinerary->title }}" readonly>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Select Tags</label>
                                                                            @php
                                                                                $assignedTagIds = $pckg->tags->pluck('id')->toArray();
                                                                            @endphp

                                                                            <select name="tag_ids[]" class="form-control select-tags" multiple required>
                                                                                @foreach($tags as $tag)
                                                                                    @if(!in_array($tag->id, $assignedTagIds))
                                                                                        <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-success">Assign Tags</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                        
                                        <td>
                                           <button 
                                                class="btn btn-dark btn-sm assign-btn" 
                                                data-id="{{ $destination->id }}"
                                                data-name="{{ $destination->destination_name }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#assignModal">
                                                Assign Packages
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Modal -->
                        <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.popularpackages.assign') }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="assignModalLabel">Assign Packages</h5>
                                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; line-height: 1; border: none; background: none;">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="destination_id" id="modalDestinationId">

                                        <div class="mb-3">
                                            <label for="destinationName" class="form-label">Destination</label>
                                            <input type="text" class="form-control" id="destinationName" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label for="itinerary_id" class="form-label">Select Packages</label>
                                            <select name="itinerary_ids[]" id="itineraryDropdown" class="form-control" multiple required>
                                                <option value="">-- Select Itinerary --</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Assign</button>
                                    </div>
                                </div>
                                </form>
                            </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        const assignButtons = document.querySelectorAll('.assign-btn');
        const itineraryDropdown = document.getElementById('itineraryDropdown');
        const modalDestinationId = document.getElementById('modalDestinationId');
        const destinationName = document.getElementById('destinationName');

        assignButtons.forEach(button => {
            button.addEventListener('click', function () {
                const destinationId = this.dataset.id;
                const destinationText = this.dataset.name;

                modalDestinationId.value = destinationId;
                destinationName.value = destinationText;

                itineraryDropdown.innerHTML = '';

                fetch(`{{ route('admin.popularpackages.fetch', ['id' => '__ID__']) }}`.replace('__ID__', destinationId))
                    .then(res => res.json())
                    .then(data => {
    
                        data.itineraries.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.text = item.title;
                            itineraryDropdown.appendChild(option);
                        });

                        $('#itineraryDropdown').select2({
                            dropdownParent: $('#assignModal') 
                        });
                    });
            });
        });
    });

    $(document).ready(function () {
        $('.status-checkbox').on('change', function () {
            var checkbox = $(this);
            var pckgId = checkbox.data('id');
            var status = checkbox.is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("admin.popularpackages.updateStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: pckgId,
                    status: status
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: status ? 'Package activated' : 'Package deactivated',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        checkbox.prop('checked', !status); // revert
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Failed to update status',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function () {
                    checkbox.prop('checked', !status); // revert
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
    });

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
                    url: "{{ route('admin.popularpackages.delete') }}",
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
                            
                        }
                    },
                    error: function () {
                        toastFire('error', 'Something went wrong. Please try again.');
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        $('.select-tags').select2({
            width: '100%',
            placeholder: 'Select Tags'
        });
    });
</script>

@endsection