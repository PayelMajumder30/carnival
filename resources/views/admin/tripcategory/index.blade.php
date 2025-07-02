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
                                
                                 <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#highlightModal">
                                    <i class="fa fa-star"></i> Is Highlighted
                                </button>
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
                    <div class="card-body dotted-table-container">
                        <div class="d-flex font-weight-bold text-center border-bottom py-2 bg-light">
                            <div class="col-1">#</div>
                            <div class="col-3 text-left">Title</div>
                            <div class="col-2">Highlighted Status</div>
                            <div class="col-3">Status</div>
                            <div class="col-3">Action</div>
                        </div>
                        
                        <ul class="list-group sortable-list" id="sortable">
                            @forelse($data as $index => $item)
                                <li class="list-group-item sortable-item py-3" data-id="{{ $item->id }}">
                                    <div class="d-flex text-center align-items-center">
                                        <div class="col-1">{{ $index + $data->firstItem() }}</div>
                                        <div class="col-3 text-left">{{ $item->title }}</div>
                                        <div class="col-2">
                                            <div class="custom-control custom-switch"  title="Toggle Highlight"> 
                                                <input type="checkbox" class="custom-control-input" 
                                                    id="highlightSwitch{{ $item->id }}" 
                                                    {{ $item->is_highlighted == 1 ? 'checked' : '' }}>
                                                   
                                                <label class="custom-control-label" for="highlightSwitch{{ $item->id }}"></label> 
                                            </div>
                                        </div>                                       
                                        <div class="col-3">
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
                                            <a href="{{ route('admin.tripcategorydestination.list.all', ['trip_cat_id' => $item->id])}}" class="btn btn-sm btn-info mr-1" data-toggle="tooltip" title="Destination">
                                                Destination
                                            </a>
                                            <a href="{{ route('admin.tripcategoryactivities.list.all', ['trip_cat_id' => $item->id])}}" class="btn btn-sm btn-dark mr-1" data-toggle="tooltip" title="Activities">
                                                Activities
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

                {{-- add modal for highlighted status --}}

                <div class="modal fade" id="highlightModal" tabindex="-1" role="dialog" aria-labelledby="highlightModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <form id="highlightForm">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="highlightModalLabel">Manage Highlighted Trips</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <div class="row">
                                @foreach($allTrips as $trip)
                                <div class="col-md-4">
                                    <div class="form-check">
                                    <input class="form-check-input trip-checkbox" type="checkbox" name="trip_ids[]" value="{{ $trip->id }}" id="trip{{ $trip->id }}"
                                        {{ $trip->is_highlighted ? 'unchecked' : '' }}>
                                    <label class="form-check-label cursor-pointer" for="trip{{ $trip->id }}">
                                        {{ $trip->title }}
                                    </label>
                                    </div>
                                </div>                                                      
                                @endforeach
                                <div id="highlightError" class="text-danger mt-2" style="display: none;"></div>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
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
@section('script')

<script src="{{ asset('backend-assets/js/jquery-ui.min.js') }}"></script> 
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
                    url: "{{ route('admin.tripcategory.sort') }}",
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

                        setTimeout(function () {
                            location.reload();
                        }, 1000); // reload after 1 second (1000ms)
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

     //select checkboxes
    $(document).ready(function () {
        // Function to reset the Save button state
        function resetSaveButton() {
            const $btn = $('#highlightForm button[type="submit"]');
            $btn.prop('disabled', false).text('Save Changes');
        }

        // Function to show loading state
        function showLoadingButton() {
            const $btn = $('#highlightForm button[type="submit"]');
            $btn.prop('disabled', true).text('Saving...');
        }

        // On checkbox change
        $(document).on('change', '.trip-checkbox', function () {
            let checkedCount = $('.trip-checkbox:checked').length;

            if (checkedCount > 3) {
                this.checked = false;
                $('#highlightError').text('You can only select up to three checkboxes.').show();
                return;
            }

            if (checkedCount > 0 && checkedCount <= 3) {
                $('#highlightError').hide();
                resetSaveButton();
            }
        });

        // On form submit
        $('#highlightForm').on('submit', function (e) {
            e.preventDefault();
            let checkedCount = $('.trip-checkbox:checked').length;

            if (checkedCount === 0) {
                $('#highlightError').text('Please select at least one checkbox.').show();
                showLoadingButton(); // simulate loading
                setTimeout(() => {
                    resetSaveButton(); // revert after short delay
                    $('#highlightError').hide();
                }, 2000);
                return;
            }

            if (checkedCount > 3) {
                $('#highlightError').text('You can only select up to three checkboxes.').show();
                return;
            }

            // All good — show loading, then send request
            showLoadingButton();

            $.ajax({
                url: '{{ route("admin.tripcategory.updateHighlights") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    if (res.status) {
                        $('#highlightModal').modal('hide');
                        location.reload(); // Refresh to reflect changes
                    } else {
                        resetSaveButton(); // Reset button in case of failure
                    }
                },
                error: function () {
                    resetSaveButton(); // Reset button on error
                    $('#highlightError').text('Something went wrong. Please try again.').show();
                }
            });
        });
    });
</script>
@endsection