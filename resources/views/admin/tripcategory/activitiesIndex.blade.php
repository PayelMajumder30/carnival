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
                                        @if (!empty($item->tripdestination->logo) && file_exists(public_path($item->tripdestination->logo)))
                                            <img src="{{ asset($item->tripdestination->logo) }}" style="height: 40px; width: 40px;background-color: #524242 !important;" class="img-thumbnail" alt="destination-logo">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" style="height: 40px; width: 40px;background-color: #524242 !important;" class="img-thumbnail" alt="placeholder-logo">
                                        @endif
                                    </td>
                                    <td>{{ $item->tripdestination->activity_name }}</td>
                                    <td>
                                        @if (!empty($item->tripdestination->image) && file_exists(public_path($item->tripdestination->image)))
                                            <img src="{{ asset($item->tripdestination->image) }}" style="height: 50px" class="img-thumbnail" alt="destination-image">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" style="height: 50px" class="img-thumbnail" alt="placeholder-image">
                                        @endif
                                    </td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <a href="javascript: void(0)" class="btn btn-sm btn-dark mr-1" onclick="deletetripDesti({{$item->id}})" data-toggle="tooltip" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>
  
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
                                        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
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

                                <label for="logo">Logo</label>
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
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

        $.ajax({
            url: "{{ url('/admin/master-module/tripcategory/activities/by-destination') }}/" + countryId + "/" + tripCatId,
            type: 'GET',
            success: function (data) {
                $("#destination_id").html("<option value=''>-- Select Destination --</option>");

                if (data.status != 200 || data.destinations.length === 0) {
                    $('#destination-group').hide();
                    $('#image-logo-group').hide();
                    toastr.error(data.message || 'No destinations found for selected country.');
                } else {
                    data.destinations.forEach(destination => {
                        $('#destination_id').append(new Option(destination.destination_name, destination.id));
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

                    const imagePath = BASE_URL + newDestination.tripdestination.image;
                    const logoPath = BASE_URL + newDestination.tripdestination.logo;

                    $("#destination-table-body").append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td><img src="${logoPath}" width="60" height="45"></td>
                            <td>${newDestination.tripdestination.activity_name}</td>
                            <td><img src="${imagePath}" width="60" height="45"></td>
                            <td><span class='badge badge-success'>Active</span></td>
                            <td>
                                <a href='javascript:void(0);' class='btn btn-sm btn-dark' onclick='deletetripDesti(${newDestination.id})'>
                                    <i class='fa fa-trash'></i>
                                </a>
                            </td>
                        </tr>
                    `);

                    setTimeout(function () {
                        window.location.href = "{{ route('admin.tripcategoryactivities.list.all', $trip->id) }}";
                    }, 10000);
                }
            }
        });
    }

    function fetchActivities(destinationId) {
        const apiUrl = `https://christmastree.quickdemo.in/api/crm/active/destination-wise/activities/${destinationId}`;
        
        $.get(apiUrl, function(response) {
        console.log(response); 
        if (response.status === 200 && response.data.length > 0) {
            $('#activity_name').html("<option value=''>-- Select Activity --</option>");
            response.data.forEach(activity => {
                $('#activity_name').append(new Option(activity.activity_name, activity.activity_name));
            });
        } else {
            $('#activity_name').html("<option value=''>No activities found</option>");
            toastr.error('No activities found for selected destination');
        }
});

    }

    $(document).ready(function () {
        $('#destination_id').on('change', function () {
            const destinationId = $(this).val();

            if (destinationId) {
                fetchActivities(destinationId);
            } else {
                $('#activity_name').html("<option value=''>-- Select Activity --</option>");
            }
        });
    });


</script>

@endsection