@extends('admin.layout.app')
@section('page-title', 'Update Itinerary Gallery') 

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.itineraries.galleries.list', $itineraryGallery->itinerary_id)}}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.itineraries.galleryUpdate')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('POST') 
                            <div class="row form-group">
                                <div class="col-md-6" style="margin-top: 60px;">
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $itineraryGallery->title) }}">
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">                                  
                                    @if (!empty($itineraryGallery->image) && file_exists(public_path($itineraryGallery->image)))
                                        <img src="{{ asset($itineraryGallery->image) }}" alt="image-gallery" class="img-thumbnail mb-2" style="height: 50px">
                                    @else
                                        <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="image-gallery" style="height: 50px" class="mb-2">
                                    @endif
                                    <br>
                                    <label for="image">Image <span style="color: red;">*</span></label><br>
                                    <input type="file" class="form-control" name="image" id="image">
                                    @error('image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{ $itineraryGallery->id }}">
                            <input type="hidden" name="itinerary_id" value="{{ $itineraryGallery->itinerary_id }}">
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