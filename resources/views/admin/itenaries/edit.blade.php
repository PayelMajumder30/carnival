@extends('admin.layout.app')
@section('page-title', 'Update Itineraries')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.itenaries.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <form action="{{ route('admin.itenaries.update', $itenary->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="title">Title </span></label>
                                <input type="text" class="form-control" name="title" id="title"
                                    value="{{ old('title', $itenary->title) }}" placeholder="Enter itinerary title..">
                                @error('title') 
                                    p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                             <div class="form-group col-md-3">
                                <label for="actual_price">Actual Price</span></label>
                                <input type="number" step="0.01" class="form-control" name="actual_price" id="actual_price"
                                    value="{{ old('actual_price', $itenary->actual_price) }}" placeholder="Enter actual price">
                                @error('actual_price') 
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="selling_price">Selling Price </span></label>
                                <input type="number" step="0.01" class="form-control" name="selling_price" id="selling_price"
                                    value="{{ old('selling_price', $itenary->selling_price) }}" placeholder="Enter selling price">
                                @error('selling_price') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>


                            <div class="form-group col-md-6">
                                <label for="short_description">Short Description</label>
                            <textarea class="form-control" name="short_description" id="short_description" rows="3" placeholder="Enter short description..">{{ old('short_description', $itenary->short_description) }}</textarea>
                                @error('short_description') 
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="main_image">Main Image</label>
                                <input type="file" class="form-control-file" name="main_image" id="main_image" accept="image/*">
                                @error('main_image')
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror

                                @if(!empty($itenary->main_image) && file_exists(public_path($itenary->main_image)))
                                    <div class="mt-2">
                                        <p>Current Image:</p>
                                        <img src="{{ asset($itenary->main_image) }}" alt="Main Image" width="120">
                                    </div>
                                @endif
                            </div>

                           
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
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
