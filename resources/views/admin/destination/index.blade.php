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
                        <div class="row justify-content-end">
                            <div class="form-group">
                                <a href="{{route('admin.destination.fetch-data-from-crm')}}" class="btn btn-sm btn-primary" data-toggle="tooltip">
                                    Pull Destination From CRM
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
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
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Banner Image</th>
                                    <th>Short Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($data as $desti_item)
                                <tr class="text-center">
                                    <td>
                                        <div class="text-center">
                                            @if (!empty($desti_item->logo) && file_exists(public_path($desti_item->logo)))
                                                <img src="{{ asset($desti_item->logo) }}" alt="destination-logo" style="height: 40px; width: 40px;background-color: #524242 !important;" class="img-thumbnail">
                                            @else
                                                <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="placeholder-logo" title="logo" style="height: 40px; width: 40px;" class="rounded-circle">
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            {{$desti_item->destination_name}}
                                        </div>
                                    </td>
                                    <td id="image-col-{{ $desti_item->id }}">
                                        <div class="text-center">
                                            @if (!empty($desti_item->image) && file_exists(public_path($desti_item->image)))
                                                <img src="{{ asset($desti_item->image) }}" alt="destination-image" style="height: 50px; width: 70px; object-position: center;" class="img-thumbnail">
                                            @else
                                                <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="placeholder-image" title="image" style="height: 50px; width: 70px;" class="img-thumbnail">
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            @if (!empty($desti_item->banner_image) && file_exists(public_path($desti_item->banner_image)))
                                                <img src="{{ asset($desti_item->banner_image) }}" alt="destination-banner-image" style="height: 50px; width: 70px; object-position: center;" class="img-thumbnail">
                                            @else
                                                <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="placeholder-banner_image" title="banner image" style="height: 50px; width: 70px;" class="img-thumbnail">
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                                {{ \Str::limit(($desti_item->short_desc), 10, '...') }}
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
                                        <div class="text-center">
                                            <a href="javascript: void(0)" class="btn btn-sm btn-dark" onclick="deleteDesti({{$desti_item->id}})" data-toggle="tooltip" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <a href="javascript:void(0)" 
                                                class="btn btn-sm btn-info edit-media-btn" 
                                                data-toggle="modal" 
                                                data-target="#editMediaModal" 
                                                data-id="{{ $desti_item->id }}" 
                                                data-name="{{ $desti_item->destination_name }}" 
                                                data-short_desc="{{ $desti_item->short_desc ?? '' }}" 
                                                title="Edit Destination">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="{{ route('admin.destination.aboutDestination.list', $desti_item->id)}}" class="btn btn-sm btn-primary" data-toggle="tooltip">
                                                About Destination
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="alert alert-light" role="alert">
                                            Destination not found!
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="pagination-container">
                            {{$data->appends($_GET)->links()}}
                        </div>
                        <!-- Upload Modal -->

                        <div class="modal fade" id="editMediaModal" tabindex="-1" role="dialog" aria-labelledby="editMediaModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST" enctype="multipart/form-data" id="editMediaForm">
                                    @csrf
                                    <input type="hidden" name="id" id="modal-destination-id">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editMediaModalLabel">Edit Destination</h5>
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
                                            <div class="form-group">
                                                <label for="banner_image">Upload Banner Image</label>
                                                <input type="file" class="form-control" name="banner_image" id="banner_image">
                                                <div class="error text-danger" id="banner-image-error"></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="short_desc">Upload Short Description</label>
                                                <textarea class="form-control" name="short_desc" id="short_desc" rows="4" 
                                                    placeholder="Enter short description...">{{ old('short_desc', $destination->short_desc ?? '') }}</textarea>
                                                <div class="error text-danger" id="short-desc-error"></div>
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

    //for open modal and image,logo, short description, banner image
    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('click', '.edit-media-btn', function () {
            const destinationId = $(this).data('id');
            const destinationName = $(this).data('name');
            const shortDesc = $(this).data('short_desc'); // Get the short description

            $('#modal-destination-id').val(destinationId);
            $('#short_desc').val(shortDesc); // Set it in the textarea

            $('#editMediaModalLabel').text('Edit ' + destinationName);
        });

        $('#editMediaSubmit').on('click', function () {
            const form = $('#editMediaForm')[0];
            const formData = new FormData(form);

            $('#image-error').text('');
            $('#logo-error').text('');
            $('#banner-image-error').text('');
            $('#short-desc-error').text('');

            let hasError = false;

            const imageFile = $('#image').val();
            const logoFile = $('#logo').val();
            const bannerImageFile = $('#banner_image').val();

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
                        if (res.errors.banner_image) {
                            $('#banner-image-error').text(res.errors.banner_image[0]);
                        }
                        if (res.errors.short_desc) {
                            $('#short-desc-error').text(res.errors.short_desc[0]);
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