@extends('admin.layout.app')
@section('page-title', $trip->title . '/' .'Activity List')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <!-- Back Button on the Left -->
                            <div class="col-md-6 text-left">
                                <a href="{{ route('admin.tripcategory.list.all')}}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                    
                            <!-- Search Form on the Right -->
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
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Logo</th>
                                    <th>Activity Name</th>
                                    <th>Image</th>
                                    <th>status</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody id="destination-table-body" class="text-center">
                              @foreach($activities as $index => $item)
                                <tr>
                                    <td>{{ $index+1}}</td>
                                    <td>
                                        @if (!empty($item->logo) && file_exists(public_path($item->logo)))
                                            <img src="{{ asset($item->logo) }}" style="height: 40px; width: 40px;background-color: #524242 !important; object-position: center;" class="img-thumbnail" alt="destination-logo">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" style="height: 40px; width: 40px;background-color: #524242 !important;" class="img-thumbnail" alt="placeholder-logo">
                                        @endif
                                    </td>
                                    <td>{{ ucwords($item->activity_name) }}</td>
                                    <td>
                                        @if (!empty($item->image) && file_exists(public_path($item->image)))
                                            <img src="{{ asset($item->image) }}" style="height: 50px; width: 70px; object-position: center;" class="img-thumbnail" alt="destination-image">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" style="height: 50px" class="img-thumbnail" alt="placeholder-image">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="customSwitch{{$item->id}}"
                                                    {{ ($item->status == 1) ? 'checked' : '' }}
                                                    onchange="statusToggle('{{ route('admin.tripcategory.activitiesStatus', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript: void(0)" class="btn btn-sm btn-dark mr-1" onclick="deletetripDesti({{$item->id}})" data-toggle="tooltip" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-info mr-1 edit-activity-btn" data-toggle="modal" data-target="#editActivityModal" data-id="{{ $item->id }}" data-image="{{ $item->image }}" data-activity_name="{{ $item->activity_name }}" data-logo="{{ $item->logo }}" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                         {{-- edit activities modal --}}
                        <div class="modal fade" id="editActivityModal" tabindex="-1" role="dialog" aria-labelledby="editActivityModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST" enctype="multipart/form-data" action="{{ route('admin.tripcategory.updateActivities') }}">
                                    @csrf
                                    <input type="hidden" name="id" id="modal-activities-id">
                            
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Activity</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="modal-logo">Logo</label>
                                                <input type="file" step="0.01" class="form-control" name="activity_logo" id="modal-logo">
                                            </div>
                                            <div class="form-group">
                                                <label for="modal-activity-name">Activity Name</label>
                                                <input type="name" step="0.01" class="form-control" name="activity_name" id="modal-activity-name">
                                            </div>
                                            <div class="form-group">
                                                <label for="modal-image">Image</label>
                                                <input type="file" step="0.01" class="form-control" name="activity_image" id="modal-image">
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
  
                        <div class="pagination-container">
                            {{$activities->appends($_GET)->links()}}
                        </div> 
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4>New Activity</h4>
                    </div>
                    <div class="card-body">
                        <form id="add-destination-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="trip_cat_id" id="trip_cat_id" value="{{ $trip->id }}">
                        
                            <div class="form-group">
                                <label for="country_id">Country</label>
                                <select name="country_id" id="country_id" class="form-control" onchange="showDestiations()">
                                    <option value="">-- Select Country --</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="form-group" id="destination-group" style="display: none;">
                                <label for="destination_id">Destination</label>
                                <select name="destination_id" id="destination_id" class="form-control">
                                    <option value="">-- Select Destination --</option>
                                </select>
                            </div> 
                        

                            <div class="form-group" id="image-logo-group" style="display: none;">

                                <label for="activity_name">Activity</label>
                                <select id="activity_name" name="activity_name" class="form-control" required>
                                    <option value="">-- Select Activity --</option>
                                </select>   

                                <label for="image">Image</label>
                                <input type="file" name="image" id="image" class="form-control mb-2" accept="image/*">
                                <img id="image-preview" src="" alt="Current Image" style="max-height: 100px; display:none; margin-bottom:10px;">
                                

                                <label for="logo">Logo</label>
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                                <img id="logo-preview" src="" alt="Current Logo" style="max-height: 100px; display:none; margin-bottom:10px;">
                            </div>
                            

                            <input type="button" class="btn btn-primary" value="Add Activity" onclick="addDestination()" />
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

    function deletetripDesti(destId) {
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
                    url: "{{ route('admin.tripcategory.activitiesDelete') }}",
                    type: 'POST',
                    data: {
                        "id": destId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function (data) {
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


    function showDestiations() {
        const countryId = $('#country_id').val();
        const tripCatId = $('#trip_cat_id').val();

        let baseUrl = "{{ route('admin.tripcategorydestination.getActivities', ['country_id' => '__COUNTRY__', 'trip_cat_id' => '__TRIPCAT__']) }}";
        baseUrl = baseUrl.replace('__COUNTRY__', countryId).replace('__TRIPCAT__', tripCatId);

        $.ajax({
            url: baseUrl,
            type: 'GET',
            success: function (data) {
                $("#destination_id").html("<option value=''>-- Select Destination --</option>");

                if (data.status != 200 || data.destinations.length === 0) {
                    $('#destination-group').hide();
                    $('#image-logo-group').hide();
                    toastr.error(data.message || 'No destinations found for selected country.');
                } else {
                    data.destinations.forEach(destination => {
                        $('#destination_id').append(`<option value="${destination.id}" data-crm-id="${destination.crm_destination_id}">${destination.destination_name}</option>`);
                    });

                    $('#destination-group').show();
                    $('#image-logo-group').show();
                    toastr.success(data.message || 'Destinations fetched successfully.');
                }
            }
        });
    }

    
    function addDestination() {
        const destinationId = $('#destination_id').val();
        const activityName = $('#activity_name').val();
        const image = $('#image').prop('files')[0];
        const logo = $('#logo').prop('files')[0];

        if (!activityName) {
            toastr.error("Please select an activity.");
            return;
        }

        if (!destinationId) {
            toastr.error("Please select a destination.");
            return;
        }

        if (!image || !logo) {
            toastr.error("Both Image and Logo are required.");
            return;
        }

        let form = $('#add-destination-form')[0];
        let formData = new FormData(form);

        $.ajax({
            url: "{{ route('admin.tripcategorydestination.activityAdd') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.status != 201) {
                    toastFire('error', 'Unable to add destination');
                } else {
                    toastFire('success', data.message);
                    const rowCount = $('#destination-table-body tr').length + 1;
                    const newDestination = data.destination;
                    const BASE_URL = "{{ asset('') }}";

                    const imagePath = BASE_URL + newDestination.image;
                    const logoPath = BASE_URL + newDestination.logo;
                    console.log("New Destination object:", newDestination);
                    $("#destination-table-body").append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td><img src="${logoPath}" width="60" height="45"></td>
                            <td>${newDestination.activity_name}</td>
                            <td><img src="${imagePath}" width="60" height="45"></td>
                            <td>
                              
                            </td>
                            <td>
                                <a href='javascript:void(0);' class='btn btn-sm btn-dark' onclick='deletetripDesti(${newDestination.id})'>
                                    <i class='fa fa-trash'></i>
                                </a>
                            </td>
                        </tr>
                    `);

                    setTimeout(function () {
                        window.location.href = "{{ route('admin.tripcategoryactivities.list.all', $trip->id) }}";
                    }, 1000);
                }
            }
        });
    }




    function fetchActivities(crmDestinationId) {
        const apiUrl = `https://christmastree.quickdemo.in/api/crm/active/destination-wise/activities/${crmDestinationId}`;

        $.get(apiUrl, function(response) {

            $('#activity_name').html("<option value=''>-- Select Activity --</option>");

            if (response.data.length > 0) {
                let existingActivityNames = [];
                $('#destination-table-body tr').each(function () {
                    let activityName = $(this).find('td:nth-child(3)').text().trim(); 
                    if (activityName) {
                        existingActivityNames.push(activityName.toLowerCase());
                    }
                });

                let filteredActivities = response.data.filter(activity => {
                    return !existingActivityNames.includes(activity.name.toLowerCase());
                });

                if (filteredActivities.length > 0) {
                    filteredActivities.forEach(activity => {
                        $('#activity_name').append(new Option(activity.name));
                    });
                } else {
                    $('#activity_name').html("<option value=''>No new activities available</option>");
                    toastr.warning('All activities for this destination are already added.');
                }

            } else {
                $('#activity_name').html("<option value=''>No activities found</option>");
                toastr.error('No activities found for selected destination');
            }
        }).fail(function() {
            $('#activity_name').html("<option value=''>-- Select Activity --</option>");
            toastr.error('Failed to fetch activities');
        });
    }



    $(document).ready(function () {
        $('#destination_id').on('change', function () {
            const selectedOption = $(this).find('option:selected');
            console.log("Selected option HTML:", selectedOption[0].outerHTML);  // DEBUG

            const crmDestinationId = selectedOption.data('crm-id');
            console.log("CRM Destination ID:", crmDestinationId);  // DEBUG

            if (crmDestinationId) {
                fetchActivities(crmDestinationId); // Pass CRM ID here
            } else {
                $('#activity_name').html("<option value=''>-- Select Activity --</option>");
            }
        });
    });

    $(document).ready(function() {
        $('.edit-activity-btn').on('click', function() {
            let id = $(this).data('id');
            let activityName = $(this).data('activity_name');
            let logo = $(this).data('logo');
            let image = $(this).data('image');

            $('#modal-activities-id').val(id);
            $('#modal-activity-name').val(activityName);

            // Optionally, show existing logo and image previews
            // For example:
            $('#logo-preview').attr('src', logo).show();
            $('#image-preview').attr('src', image).show();
        });
    });

</script>

@endsection