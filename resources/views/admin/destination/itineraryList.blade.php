@extends('admin.layout.app')
@section('page-title', $destination->destination_name . '/' . 'Itineraries')

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
                                <a href="{{ route('admin.destination.list.all')}}" class="btn btn-sm btn-primary">
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
                            <thead class="text-center">
                                <tr>
                                    <th style="width: 20%;">Package Category</th>
                                    <th>Itineraries</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div>{{ ucwords($index) }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-3">
                                                @foreach ($item['itineraries'] as $key => $item_itinerary)
                                                    <div class="card text-center" style="width: 150px;">
                                                        <div class="card-body p-2">
                                                            @if (!empty($item_itinerary->main_image) && file_exists(public_path($item_itinerary->main_image)))
                                                                <img src="{{ asset($item_itinerary->main_image) }}" alt="Itinerary Image"
                                                                    class="img-fluid mb-2" style="height: 80px; object-fit: cover;">
                                                            @else
                                                                <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="No Image"
                                                                    class="img-fluid mb-2" style="height: 80px; object-fit: cover;">
                                                            @endif
                                                            <p class="mb-1">{{ $item_itinerary->title }}</p>
                                                        </div>
                                                        <div class="card-footer p-1">
                                                            <div class="mb-1">
                                                                <a href="javascript:void(0)" onclick="deleteDestItinerary({{ $key }})"
                                                                class="btn btn-sm btn-dark" title="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </div>
                                                            @if ($item_itinerary->status == 1)
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-danger">Inactive</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <!-- Add Itinerary section -->
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Assign Itinerary</h4>
                    </div>
                    <div class="card-body">
                        <form id="adssign-itinerary-form" method="POST" action="{{ route('admin.destination.assignItinerary', $destination->id) }}">
                            @csrf
                            <input type="hidden" name="destination_id" id="destination_id" value="{{ $destination->id }}">
                        
                            <div class="form-group">
                                <label for="package_id">Package Category</label>
                                <select name="package_id" id="package_id" class="form-control">
                                    <option value="">-- Select Package Category --</option>
                                    @foreach($packageCategories as $package)
                                        <option value="{{ $package->id }}">{{ ucwords($package->title) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="form-group" id="destination-group">
                                <label for="itinerary_id">Itinerary</label>
                                <select name="itinerary_id[]" id="itinerary_id" class="form-control" multiple>
                                    {{-- <option value="">-- Select Itinerary --</option> --}}
                                    @foreach($itineraries as $itinerary)
                                        <option value="{{ $itinerary->id }}">{{ ucwords($itinerary->title) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="button" class="btn btn-primary" value="Submit" onclick="assignItinerary()" />

                        </form>                        
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
    function assignItinerary() {
        if ($('#package_id').val() == '') {
            toastFire('error', 'Please select package category');
        } else if ($('#itinerary_id').val() == '') {
            toastFire('error', 'Please select itinerary');
        } else {
            $('#adssign-itinerary-form').submit();
        }
    }

    function deleteDestItinerary(destItinId) {
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
                  url: "{{ route('admin.destination.deleteItinerary')}}",
                  type: 'POST',
                  data: {
                      "id": destItinId,
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

    //for select multiple data in itinerary
    $(document).ready(function() {
        $('#itinerary_id').select2({
            placeholder: "-- Select Itinerary --"
        });
    });

</script>
@endsection
