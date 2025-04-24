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
                              
                            </tbody>
                            
                        </table>

                        {{-- <div class="pagination-container">
                            {{$data->appends($_GET)->links()}}
                        </div> --}}
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
                                <select name="country_id" id="country_id" class="form-control">
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
                        
                            <button type="submit" class="btn btn-primary">Add Destination</button>
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
    function deleteDestination(destId) {
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
   
   
</script>

@endsection