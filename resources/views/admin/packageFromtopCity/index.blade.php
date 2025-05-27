@extends('admin.layout.app')
@section('page-title', 'Packages from Top Cities')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
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
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Destination</th>
                                    <th>City</th>
                                    <th>status</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignedCities as $index => $item)
                                    <tr class="text-center">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->title ?: '-' }}</td>
                                        <td>{{ $item->destination->destination_name ?? '-' }}</td>
                                        <td>{{ ucfirst($item->city) }}</td>
                                         <td> 
                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{$item->id}}" {{ ($item->status == 1) ? 'checked' : '' }} onchange="statusToggle('{{ route('admin.assignCitytoPackage.status', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                {{-- <a href="#" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a> --}}
                                                <a href="javascript:void(0);" class="btn btn-sm btn-dark mr-1" onclick="deleteTopCity({{ $item->id }})" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No assigned cities found.</td>
                                    </tr>
                                @endforelse
                                </tbody>

                        </table>

                        {{-- Edit modal for title --}}

                        {{-- <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{ route('admin.packageCategory.update') }}">
                                @csrf
                                <input type="hidden" name="id" id="edit-id">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title">Edit Package Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="edit-title">Title</label>
                                            <input type="text" name="title" id="edit-title" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Package category</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div> --}}

                        <div class="pagination-container">
                            {{$assignedCities->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Create</h4>
                    </div>
                    <div class="card-body">
                        <form id="assign-top-city-form" method="POST" action="{{ route('admin.assignCitytoPackage.store') }}">
                            @csrf
                        
                            <div class="form-group">
                                <label for="destination_id">Package Destination</label>
                                <select name="destination_id" id="destination_id" class="form-control">
                                    <option value="">-- Package Destination --</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}">{{ ucwords($destination->destination_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            {{-- <div class="form-group" id="destination-group">
                                <label for="itinerary_id">From where(City)</label>
                                <select name="indian_cities" id="indian_cities">
                                    <option value="">--Select a City--</option>
                                    <option value="delhi">Delhi</option>
                                    <option value="mumbai">Mumbai</option>
                                    <option value="bangalore">Bangalore</option>
                                    <option value="hyderabad">Hyderabad</option>
                                    <option value="chennai">Chennai</option>
                                    <option value="kolkata">Kolkata</option>
                                    <option value="ahmedabad">Ahmedabad</option>
                                    <option value="pune">Pune</option>
                                    <option value="jaipur">Jaipur</option>
                                    <option value="surat">Surat</option>
                                    <option value="lucknow">Lucknow</option>
                                    <option value="kanpur">Kanpur</option>
                                    <option value="nagpur">Nagpur</option>
                                    <option value="bhopal">Bhopal</option>
                                    <option value="patna">Patna</option>
                                    <option value="indore">Indore</option>
                                    <option value="coimbatore">Coimbatore</option>
                                    <option value="thiruvananthapuram">Thiruvananthapuram</option>
                                    <option value="vadodara">Vadodara</option>
                                    <option value="visakhapatnam">Visakhapatnam</option>
                                </select>
                            </div> --}}
                            @php
                                $allCities = [
                                    'delhi', 'mumbai', 'bangalore', 'hyderabad', 'chennai', 'kolkata', 'ahmedabad',
                                    'pune', 'jaipur', 'surat', 'lucknow', 'kanpur', 'nagpur', 'bhopal', 'patna',
                                    'indore', 'coimbatore', 'thiruvananthapuram', 'vadodara', 'visakhapatnam'
                                ];
                            @endphp
                            <div class="form-group" id="destination-group">
                                <label for="itinerary_id">From where (City)</label>
                                <select name="indian_cities" id="indian_cities" class="form-control">
                                    <option value="">--Select a City--</option>
                                    @foreach($allCities as $city)
                                        @if(!in_array($city, $assignedCityNames))
                                            <option value="{{ $city }}">{{ ucfirst($city) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>

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
 
    function deleteTopCity(cityId) {
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
                    url: "{{ route('admin.assignCitytoPackage.delete')}}",
                    type: 'POST',
                    data: {
                        "id": cityId,
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


    //script for modal
    $(document).ready(function () {
        $('.edit-btn').on('click', function () {
            const id = $(this).data('id');
            const title = $(this).data('title');

            $('#edit-id').val(id);
            $('#edit-title').val(title);
        });
    });
</script>
@endsection