@extends('admin.layout.app')
@section('page-title', 'Destination')
@section('section')
<style>
    .destination_div{
        border: 1px solid #ddd;
        padding: 0px !important;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                        </div>
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <form action="{{ route('admin.destination.list.all') }}" method="get">
                                    <div class="d-flex justify-content-end">
                                        <div class="form-group ml-2">
                                            <select name="country_filter" class="form-control form-control-sm filter" id="countrySelect">
                                                <option value="">Select Country</option>
                                                @foreach($showCountry as $name)
                                                    <option value="{{ $name }}" {{ request()->input('country_filter') == $name ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group ml-2">
                                            <input type="search" class="form-control form-control-sm" name="keyword" id="keyword"
                                                value="{{ request()->input('keyword') }}" placeholder="Search destination...">
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
                        <div class="row justify-content-end">
                            <div class="form-group">
                                <select name="" class="form-control" onchange="UpdateNewCountry(event)">
                                    <option value="" selected hidden>Add New Country</option>
                                    @forelse ($new_country as $country_item)
                                        @if(!in_array($country_item['id'], $existing_country))
                                            <option value="{{ucwords($country_item['country_name'])}}" data-id="{{$country_item['id']}}">{{ucwords($country_item['country_name'])}}</option>
                                        @endif
                                    @empty
                                        <option value="">No new data found</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr class="text-center">
                                    <th>SL</th>
                                    <th>Country Name</th>
                                    <th>Status</th>
                                    <th>Destinations</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $k=> $item)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>{{ucwords($item->country_name)}}</td>
                                        <td>
                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="countrySwitch{{ $item->id }}"
                                                    {{ $item->status == 1 ? 'checked' : '' }}
                                                    onchange="statusToggle('{{ route('admin.country.status', $item->id) }}')">
                                                <label class="custom-control-label" for="countrySwitch{{ $item->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="destination_div" width="60%">
                                            <div class="row justify-content-end mr-1">
                                                <div class="form-group">
                                                    @php
                                                        $Country_destination = GetDestinationBycountryId($item->crm_country_id);

                                                        $country_wise_destinations= App\Models\Destination::orderBy('destination_name', 'ASC')->where('country_id', $item->id)->get();
                                                        $existing_destination = $country_wise_destinations->pluck('crm_destination_id')->toArray();
                                                    @endphp
                                                    <select name="" class="form-control" onchange="addNewDestination(event)">
                                                        <option value="" selected hidden>Add New Destination</option>
                                                        @forelse ($Country_destination as $destination_item)
                                                            @if(!in_array($destination_item['id'], $existing_destination))
                                                                <option value="{{ucwords($destination_item['name'])}}" data-id="{{$destination_item['id']}}" data-country="{{$item->id}}">{{ucwords($destination_item['name'])}}</option>
                                                            @endif
                                                        @empty
                                                            <option value="">No new data found</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>
                                            <table class="table table-sm table-hover">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Logo</th>
                                                        <th>Name</th>
                                                        <th>Image</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($country_wise_destinations as $desti_item)
                                                    <tr class="text-center">
                                                        <td>
                                                            <div class="text-center">
                                                                @if (!empty($desti_item->logo) && file_exists(public_path($desti_item->logo)))
                                                                    <img src="{{ asset($desti_item->logo) }}" alt="destination-logo" style="height: 40px; width: 40px;background-color: #524242 !important;" class="img-thumbnail">
                                                                @else
                                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="placeholder-logo" style="height: 40px; width: 40px;" class="rounded-circle">
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{$desti_item->destination_name}}</td>
                                                        <td id="image-col-{{ $desti_item->id }}">
                                                            <div class="text-center">
                                                                @if (!empty($desti_item->image) && file_exists(public_path($desti_item->image)))
                                                                    <img src="{{ asset($desti_item->image) }}" alt="destination-image" style="height: 50px" class="img-thumbnail">
                                                                @else
                                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="placeholder-image" style="height: 50px" class="img-thumbnail">
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td> 
                                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{ $desti_item->id }}"
                                                                    {{ $desti_item->status == 1 ? 'checked' : '' }}
                                                                    onchange="statusToggle('{{ route('admin.destination.status', $desti_item->id) }}')">
                                                                <label class="custom-control-label" for="customSwitch{{ $desti_item->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="javascript: void(0)" class="btn btn-sm btn-dark mr-1" onclick="deleteDesti({{$desti_item->id}})" data-toggle="tooltip" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                            {{-- modal for upload image and logo  --}}
                                                            <a href="javascript:void(0)" class="btn btn-sm btn-info edit-media-btn" data-toggle="modal" data-target="#editMediaModal" data-id="{{ $desti_item->id }}"
                                                                data-name="{{ $desti_item->name }}" title="Edit Logo & Image"><i class="fa fa-image"></i>
                                                            </a> 
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <!-- Upload Modal -->

                                            <div class="modal fade" id="editMediaModal" tabindex="-1" role="dialog" aria-labelledby="editMediaModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="POST" enctype="multipart/form-data" id="editMediaForm">
                                                        @csrf
                                                        <input type="hidden" name="id" id="modal-destination-id">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Update Logo & Image</h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="logo">Upload Logo</label>
                                                                    <input type="file" class="form-control" name="logo" id="logo">
                                                                    <div class="error text-danger" id="logo-error"></div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="image">Upload Image</label>
                                                                    <input type="file" class="form-control" name="image" id="image">
                                                                    <div class="error text-danger" id="image-error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="button" id="editMediaSubmit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <li class="list-group-item text-center">No records found</li>
                                        </td>
                                    </tr>
                                @endforelse
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')

<script>
    function UpdateNewCountry(event) {
        var selectedOption = $(event.target).find('option:selected');
        var countryName = selectedOption.val();
        var crmCountryId = selectedOption.data('id');
        
        if (!countryName || !crmCountryId) {
            alert('Please select a valid country.');
            return;
        }

        $.ajax({
            url: "{{ route('admin.country.add') }}", // This should be your route name
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                country_name: countryName,
                crm_country_id: crmCountryId
            },
            success: function(response) {
                if (response.success) {
                    // alert('Country added successfully!');
                    location.reload(); // Refresh to see the updated table
                } else {
                    alert(response.message || 'Something went wrong');
                }
            },
            error: function(xhr) {
                alert('Error occurred: ' + xhr.responseText);
            }
        });
    }

    function addNewDestination(event){
        var selectdestination = $(event.target).find('option:selected');
        var destinationName = selectdestination.val();
        var crmDestinationId = selectdestination.data('id');
        var country_id = selectdestination.data('country');

        // console.log(destinationName, crmDestinationId);
        // console.log(crmDestinationId);
        if(!destinationName || !crmDestinationId){
            alert('please select a valid destination');
            return;
        }

        $.ajax({
            url: "{{( route('admin.destination.add') )}}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                country_id: country_id,
                destination_name: destinationName,
                crm_destination_id: crmDestinationId
            },
            success: function(response) {
                if(response.success) {
                  location.reload();
                }else{
                    console.log(response.message);
                    alert(response.message || 'Something Went Wrong');
                }
            },
            error: function(xhr){
                alert('Error occured: ' + xhr.responseText);
            }
        });
    }
     //for delete destination
    function deleteDesti(destiId) {
        //alert(destiId);
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
                  url: "{{ route('admin.destination.delete')}}",
                  type: 'POST',
                  data: {
                      "id": destiId,
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

    //for show image in destination
    function uploadImage(id) {
        let formData = new FormData($('#uploadForm-' + id)[0]);

        $.ajax({
            url: "{{ route('admin.destination.createImage') }}", // Update with your actual route
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.status === 200) {
                    toastFire('success', data.message);

                    // Show updated image
                    $('#image-col-' + id).html(`
                        <div class="text-center">
                            <img src="${data.image_url}" alt="destination-image" style="height: 50px" class="img-thumbnail">
                        </div>
                    `);
                } else {
                    toastFire('error', data.message);
                }
            },
            error: function () {
                toastFire('error', 'Image upload failed');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('click', '.edit-media-btn', function () {
            const destinationId = $(this).data('id');
            $('#modal-destination-id').val(destinationId);
        });

        $('#editMediaSubmit').on('click', function () {
            const form = $('#editMediaForm')[0];
            const formData = new FormData(form);

            $('#image-error').text('');
            $('#logo-error').text('');

            let hasError = false;

            const imageFile = $('#image').val();
            const logoFile = $('#logo').val();

            if (hasError) return;

            $.ajax({
                url: "{{ route('admin.destination.createImage') }}", 
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#editMediaModal').modal('hide');
                    Swal.fire('Success', 'Image and Logo updated successfully!', 'success').then(() => {
                        location.reload(); 
                    });
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    if (res && res.errors) {
                        if (res.errors.image) {
                            $('#image-error').text(res.errors.image[0]);
                        }
                        if (res.errors.logo) {
                            $('#logo-error').text(res.errors.logo[0]);
                        }
                        if (res.errors.id) {
                            alert('Destination ID is missing.');
                        }
                    } else {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                }
            });
        });
    });



    document.addEventListener('DOMContentLoaded', function () {
        var selects = document.getElementsByClassName('filter');

        Array.from(selects).forEach(function(select) {
            select.addEventListener('change', function () {
                var form = select.closest('form');
                if (form) {
                    form.submit();
                }
            });
        });
    });


</script>
@endsection