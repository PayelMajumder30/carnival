@extends('admin.layout.app')
@section('page-title', $trip->title . '/' .'Destination List')

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
                                    <th>Destination Name</th>
                                    <th>Image</th>
                                    <th>status</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody id="destination-table-body" class="text-center">
                              @foreach($tripCategoryDestination as $index => $item)
                                <tr>
                                    <td>{{ $index+1}}</td>
                                    <td>{{$item->tripdestination->destination_name}}</td>
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
                            {{$tripCategoryDestination->appends($_GET)->links()}}
                        </div> 
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4>New Destination</h4>
                    </div>
                    <div class="card-body">
                        <form id="add-destination-form">
                            @csrf
                            <input type="hidden" name="trip_cat_id" id="trip_cat_id" value="{{ $trip->id }}">
                        
                            <div class="form-group">
                                <label for="country_id">Add Country</label>
                                <select name="country_id" id="country_id" class="form-control" onchange="showDestiations()">
                                    <option value="">-- Select Country --</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="form-group" id="destination-group" style="display: none;">
                                <label for="destination_id">Assigned Destination</label>
                                <select name="destination_id" id="destination_id" class="form-control">
                                    <option value="">-- Select Destination --</option>
                                </select>
                            </div>                      
                            <input type="button" class="btn btn-primary" value="Add Destination" onclick="addDestination()" />
                        </form>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.tripcategory.destinationDelete')}}",
                    type: 'POST',
                    data: {
                        "id": destId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function (data){
                        if (data.status != 200) {
                            toastFire('error', data.message);
                        } else {
                            toastFire('success', data.message);
                            location.reload();
                            // $("#banner_section_" + bannerId).hide();
                        }
                    }
                });
            }
        });
    }


    function showDestiations() {
        const countryId = $('#country_id').val();
        const tripCatId = $('#trip_cat_id').val(); // Ensure this input exists in your Blade

        $.ajax({
            url: "{{ URL::to('/admin/master-module/tripcategory/destination/by-country') }}/" + countryId + "/" + tripCatId,
            type: 'GET',
            success: function (data) {
                $("#destination_id").html("<option value=''>-- Select Destination --</option>");

                if (data.status != 200 || data.destinations.length === 0) {
                   
                    $('#destination-group').hide();
                    toastr.error(data.message || 'No destinations found for selected country.');
                } else {
                  
                    data.destinations.forEach(destination => {
                        $('#destination_id').append(new Option(destination.destination_name, destination.id));
                    });
                    $('#destination-group').show();
                    toastr.success(data.message || 'Destinations fetched successfully.');
                }
            }
        });
    }


    function addDestination() {
        var formData = $("#add-destination-form").serializeArray();
        $.ajax({
            url: "{{ route('admin.tripcategorydestination.destinationAdd') }}",
            type: 'POST',
            data: formData,
            success: function (data){
                if (data.status != 201) {
                    toastFire('error', 'Unable to add destination');
                } else {
                    toastFire('success', data.message);
                    console.log('Added destination', data.destination);
                    const rowCount = $('destination-table-body tr').length +1;
                    const newDestination = data.destination;
                    const BASE_URL  = "{{ asset('') }}";
                    const imagePath = BASE_URL + newDestination.tripdestination.image;
                    // Appened added destination
                    $("#destination-table-body").append("<tr>\
                        <td>"+ rowCount +"</td>\
                        <td>" + newDestination.tripdestination.destination_name + " </td>\
                        <td><img src='" + imagePath + "' width='50' height='40'></td>\
                        <td><span class='badge badge-success'>Active</span></td>\
                        <td><a href='javascript:void(0);' class='btn btn-sm btn-dark mr-1' onclick='deletetripDesti(" + newDestination.id + ")'><i class='fa fa-trash'></i></a></td>\
                    </tr>");

                    setTimeout(function() {
                        window.location.href = "{{ route('admin.tripcategorydestination.list.all', $trip->id) }}";
                    }, 1000);
                }
            }
        });
    } 
</script>

@endsection