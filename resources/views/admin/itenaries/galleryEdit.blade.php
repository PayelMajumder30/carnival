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
                                <a href="{{ route('admin.itenaries.galleries.list', $itineraryGallery->itinerary_id)}}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.itenaries.galleryUpdate')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('POST') 
                            <div class="row form-group">
                                <div class="col-md-6">
                                    @if (!empty($itineraryGallery->image))
                                        @if (!empty($itineraryGallery->image) && file_exists(public_path($itineraryGallery->image)))
                                            <img src="{{ asset($itineraryGallery->image) }}" alt="image-gallery" class="img-thumbnail mr-3" style="height: 50px">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="image-gallery" style="height: 50px" class="mr-2">
                                        @endif
                                        <br>
                                    @endif
                                    <label for="image">Image <span style="color: red;">*</span></label>
                                    <input type="file" class="form-control" name="image" id="image">
                                    <p class="small text-muted"></p>
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