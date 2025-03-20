@extends('admin.layout.app')
@section('page-title', 'EPC')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('front.epc.index') }}" class="btn btn-sm btn-primary" target="_blank"> View public page <i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.content.epc.update') }}" method="post" enctype="multipart/form-data">@csrf
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-danger font-weight-bold">Section 1</p>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="point1_title"> Point 1 title *</label>
                                            <input type="text" class="form-control" name="point1_title" id="point1_title" placeholder="Enter point 1 title" value="{{ old('point1_title') ? old('point1_title') : $data->point1_title }}">
                                            @error('point1_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            @if (!empty($data->section1_image))
                                                @if (!empty($data->section1_image) && file_exists(public_path($data->section1_image)))
                                                    <img src="{{ asset($data->section1_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                                @else
                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                                @endif
                                                <br>
                                            @endif
                                            <label for="section1_image">Section 1 Image</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="section1_image" id="section1_image">
                                                    <label class="custom-file-label" for="section1_image">Choose file</label>
                                                </div>
                                            </div>
                                            <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                            @error('section1_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="section1_title"> Section 1 title *</label>
                                            <input type="text" class="form-control" name="section1_title" id="section1_title" placeholder="Enter section 1 title" value="{{ old('section1_title') ? old('section1_title') : $data->section1_title }}">
                                            @error('section1_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="section1_desc">Section 1 description *</label>
                                        <textarea name="section1_desc" id="section1_desc" class="form-control editor" placeholder="Enter description">{{ old('section1_desc') ? old('section1_desc') : $data->section1_desc }}</textarea>
                                        @error('section1_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-danger font-weight-bold">Section 2</p>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="point2_title"> Point 2 title *</label>
                                            <input type="text" class="form-control" name="point2_title" id="point2_title" placeholder="Enter point 2 title" value="{{ old('point2_title') ? old('point2_title') : $data->point2_title }}">
                                            @error('point2_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            @if (!empty($data->section2_image))
                                                @if (!empty($data->section2_image) && file_exists(public_path($data->section2_image)))
                                                    <img src="{{ asset($data->section2_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                                @else
                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                                @endif
                                                <br>
                                            @endif
                                            <label for="section2_image">Section 2 Image</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="section2_image" id="section2_image">
                                                    <label class="custom-file-label" for="section2_image">Choose file</label>
                                                </div>
                                            </div>
                                            <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                            @error('section2_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="section2_title"> Section 2 title *</label>
                                            <input type="text" class="form-control" name="section2_title" id="section2_title" placeholder="Enter section 2 title" value="{{ old('section2_title') ? old('section2_title') : $data->section2_title }}">
                                            @error('section2_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="section2_desc">Section 2 description *</label>
                                        <textarea name="section2_desc" id="section2_desc" class="form-control editor" placeholder="Enter description">{{ old('section2_desc') ? old('section2_desc') : $data->section2_desc }}</textarea>
                                        @error('section2_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-danger font-weight-bold">Section 3</p>
                                    <div class="form-group row">
                                        {{-- <div class="col-md-6">
                                            <label for="point_title"> Point 3 title *</label>
                                            <input type="text" class="form-control" name="point2_title" id="point2_title" placeholder="Enter point 2 title" value="{{ old('point2_title') ? old('point2_title') : $data->point2_title }}">
                                            @error('point2_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div> --}}
                                        <div class="col-md-12">
                                            @if (!empty($data->section3_image))
                                                @if (!empty($data->section3_image) && file_exists(public_path($data->section3_image)))
                                                    <img src="{{ asset($data->section3_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                                @else
                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                                @endif
                                                <br>
                                            @endif
                                            <label for="section3_image">Section 3 Image</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="section3_image" id="section3_image">
                                                    <label class="custom-file-label" for="section3_image">Choose file</label>
                                                </div>
                                            </div>
                                            <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                            @error('section3_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="section3_title"> Section 3 title *</label>
                                            <input type="text" class="form-control" name="section3_title" id="section3_title" placeholder="Enter section 3 title" value="{{ old('section3_title') ? old('section3_title') : $data->section3_title }}">
                                            @error('section3_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="section3_desc">Section 3 description *</label>
                                        <textarea name="section3_desc" id="section3_desc" class="form-control editor" placeholder="Enter description">{{ old('section3_desc') ? old('section3_desc') : $data->section3_desc }}</textarea>
                                        @error('section3_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-danger font-weight-bold">Section 4</p>
                                    <div class="form-group row">
                                        {{-- <div class="col-md-6">
                                            <label for="point_title"> Point 3 title *</label>
                                            <input type="text" class="form-control" name="point2_title" id="point2_title" placeholder="Enter point 2 title" value="{{ old('point2_title') ? old('point2_title') : $data->point2_title }}">
                                            @error('point2_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div> --}}
                                        <div class="col-md-12">
                                            @if (!empty($data->section4_image))
                                                @if (!empty($data->section4_image) && file_exists(public_path($data->section4_image)))
                                                    <img src="{{ asset($data->section4_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                                @else
                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                                @endif
                                                <br>
                                            @endif
                                            <label for="section4_image">Section 4 Image</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="section4_image" id="section4_image">
                                                    <label class="custom-file-label" for="section4_image">Choose file</label>
                                                </div>
                                            </div>
                                            <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                            @error('section4_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="section4_title"> Section 4 title *</label>
                                            <input type="text" class="form-control" name="section4_title" id="section4_title" placeholder="Enter section 4 title" value="{{ old('section4_title') ? old('section4_title') : $data->section4_title }}">
                                            @error('section4_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="section4_desc">Section 4 description *</label>
                                        <textarea name="section4_desc" id="section4_desc" class="form-control editor" placeholder="Enter description">{{ old('section4_desc') ? old('section4_desc') : $data->section4_desc }}</textarea>
                                        @error('section4_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-danger font-weight-bold">Section 5</p>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            @if (!empty($data->section5_image1))
                                                @if (!empty($data->section5_image1) && file_exists(public_path($data->section5_image1)))
                                                    <img src="{{ asset($data->section5_image1) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                                @else
                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                                @endif
                                                <br>
                                            @endif
                                            <label for="section5_image1">Section 5 Image 1</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="section5_image1" id="section5_image1">
                                                    <label class="custom-file-label" for="section5_image1">Choose file</label>
                                                </div>
                                            </div>
                                            <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                            @error('section5_image1') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            @if (!empty($data->section5_image2))
                                                @if (!empty($data->section5_image2) && file_exists(public_path($data->section5_image2)))
                                                    <img src="{{ asset($data->section5_image2) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                                @else
                                                    <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                                @endif
                                                <br>
                                            @endif
                                            <label for="section5_image2">Section 5 Image 2</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="section5_image2" id="section5_image2">
                                                    <label class="custom-file-label" for="section5_image2">Choose file</label>
                                                </div>
                                            </div>
                                            <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                            @error('section5_image2') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="section5_title"> Section 5 title *</label>
                                            <input type="text" class="form-control" name="section5_title" id="section5_title" placeholder="Enter section 5 title" value="{{ old('section5_title') ? old('section5_title') : $data->section5_title }}">
                                            @error('section5_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="section5_desc">Section 5 description *</label>
                                        <textarea name="section5_desc" id="section5_desc" class="form-control editor" placeholder="Enter description">{{ old('section5_desc') ? old('section5_desc') : $data->section5_desc }}</textarea>
                                        @error('section5_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <button type="submit" class="btn btn-primary">Update</button>
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
    const editorElements = document.querySelectorAll('.editor');

    // Loop through each editor element and initialize ClassicEditor
    editorElements.forEach((element) => {
        ClassicEditor
            .create(element)
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endsection