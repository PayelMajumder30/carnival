@extends('admin.layout.app')
@section('page-title', 'Update trip category banner')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.tripcategorybanner.list.all', $tripCategoryBanner->id)}}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.tripcategory.bannerupdate')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('POST') 
                            <div class="row form-group">
                                <div class="col-md-6">
                                    @if (!empty($tripCategoryBanner->image))
                                        @if (!empty($tripCategoryBanner->image) && file_exists(public_path($tripCategoryBanner->image)))
                                            <img src="{{ asset($tripCategoryBanner->image) }}" alt="tripCategoryBanner-img" class="img-thumbnail mr-3" style="height: 50px">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="tripCategoryBanner-image" style="height: 50px" class="mr-2">
                                        @endif
                                        <br>
                                    @endif
                                    <label for="image">Image <span style="color: red;">*</span></label>
                                    <input type="file" class="form-control" name="image" id="image">
                                    <p class="small text-muted">Size: less than 1 mb </p>
                                    @error('image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{ $tripCategoryBanner->id }}">
                            <input type="hidden" name="trip_cat_id" value="{{ $tripCategoryBanner->trip_cat_id }}">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- test git commit-->
@endsection