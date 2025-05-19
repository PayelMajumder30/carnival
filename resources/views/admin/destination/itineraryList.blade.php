@extends('admin.layout.app')
@section('page-title', $destination->destination_name . '/' . 'Itinerary list')

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
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Package Category</th>
                                    <th>Itinerary</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($destinationItineraries as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + $destinationItineraries->firstItem() }}</td>
                                        <td class="text-center">
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->packageCategory->title }}</p>
                                            </div>
                                        </td>
                                          <td class="text-center">
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->itinerary->title }}</p>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == 1)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                           <td class="text-center">
                                            <div class="btn-group">
                                               {{-- <a href="javascript:void(0)" class="btn btn-sm btn-info mr-1 edit-title-btn"
                                                    data-toggle="modal" 
                                                    data-target="#editTitleModal"
                                                    data-id="{{ $item->id }}" 
                                                    data-title="{{ $item->title }}" 
                                                    title="Edit"> <i class="fa fa-edit"></i> </a>                        
                                                <a href="javascript: void(0)" class="btn btn-sm btn-dark mr-1" onclick="deletePackage({{$item->id}})" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="pagination-container">
                            {{$destinationItineraries->appends($_GET)->links()}}
                        </div>
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
                                        <option value="{{ $package->id }}">{{ $package->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="form-group" id="destination-group">
                                <label for="itinerary_id">Itinerary</label>
                                <select name="itinerary_id" id="itinerary_id" class="form-control">
                                    <option value="">-- Select Itinerary --</option>
                                    @foreach($itineraries as $itinerary)
                                        <option value="{{ $itinerary->id }}">{{ $itinerary->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="button" class="btn btn-primary" value="Assign" onclick="assignItinerary()" />

                        </form>                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
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

</script>
