@extends('admin.layout.app')
@section('page-title', $destination->destination_name . '/' . 'Package category list')

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
                                    <th>Title</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($packageCategories as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + $packageCategories->firstItem() }}</td>
                                        <td class="text-center">
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ ucwords($item->title) }}</p>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{$item->id}}" {{ ($item->status == 1) ? 'checked' : '' }} onchange="statusToggle('{{ route('admin.offers.status', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                            </div>
                                        </td>
                                           <td class="text-center">
                                            <div class="btn-group">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-info mr-1 edit-title-btn" data-toggle="modal" 
                                                data-target="#editTitleModal" data-id="{{ $item->id }}" data-price="{{ $item->title }}" data-title="{{ $item->title }}" 
                                                title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>                          
                                                <a href="javascript: void(0)" class="btn btn-sm btn-dark mr-1" onclick="deleteOffer({{$item->id}})" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
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

                         <!-- Edit Title Modal -->
                        <div class="modal fade" id="editTitleModal" tabindex="-1" role="dialog" aria-labelledby="editTitleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{ route('admin.country/destinations.packageCategoryUpdate') }}">
                                    @csrf
                                    <input type="hidden" name="id" id="modal-title-id">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Title</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="modal-title-input">Title</label>
                                                <input type="text" class="form-control" name="title" id="modal-title-input" required>
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
                            {{$packageCategories->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                       <h4>Create Title</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.country/destinations.packageCategoryStore')}}" method="post">@csrf                                 
                            <div class="form-group">
                                <label for="title">Title <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Enter title.." value="{{ old('title') }}">
                                @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Button in a separate row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="destination_id" value="{{ $destination->id }}">
                                    <button type="submit" class="btn btn-primary">Create</button>
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
<script>  
    $(document).on('click', '.edit-title-btn', function () {
        var id = $(this).data('id');
        var title = $(this).data('title');
        $('#modal-title-id').val(id);
        $('#modal-title-input').val(title);
    });
</script>
