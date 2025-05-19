@extends('admin.layout.app')
@section('page-title', 'Create Itineraries')

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
                        <form action="{{ route('admin.itenaries.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter itinerary title.." value="{{ old('title') }}">
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="actual_price">Actual Price <span style="color: red;">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="actual_price" id="actual_price" placeholder="Enter actual price" value="{{ old('actual_price') }}">
                                    @error('actual_price') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="selling_price">Selling Price <span style="color: red;">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="selling_price" id="selling_price" placeholder="Enter selling price" value="{{ old('selling_price') }}">
                                    @error('selling_price') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label for="short_description">Short Description</label>
                                    <textarea class="form-control" name="short_description" id="short_description" rows="3" placeholder="Enter short description...">{{ old('short_description') }}</textarea>
                                    @error('short_description') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="main_image">Main Image <span style="color: red;">*</span></label>
                                    <input type="file" class="form-control-file" name="main_image" id="main_image" accept="image/*">
                                    @error('main_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Create</button>
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
