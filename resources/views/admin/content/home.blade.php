@extends('admin.layout.app')
@section('page-title', 'Home')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('front.home') }}" class="btn btn-sm btn-primary" target="_blank"> View public page <i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.content.home.update') }}" method="post" enctype="multipart/form-data">@csrf
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="title1"> Title 1 *</label>
                                    <input type="text" class="form-control" name="title1" id="title1" placeholder="Enter page title 1" value="{{ old('title1') ? old('title1') : $data->title1 }}">
                                    @error('title1') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="title1_author">Auther Name *</label>
                                    <input type="text" class="form-control" name="title1_author" id="title1_author" placeholder="Enter auther name" value="{{ old('title1_author') ? old('title1_author') : $data->title1_author }}">
                                    @error('title1_author') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="title1_author_designation">Auther Designation *</label>
                                    <input type="text" class="form-control" name="title1_author_designation" id="title1_author_designation" placeholder="Enter auther designation" value="{{ old('title1_author_designation') ? old('title1_author_designation') : $data->title1_author_designation }}">
                                    @error('title1_author_designation') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    @if (!empty($data->title1_image))
                                        @if (!empty($data->title1_image) && file_exists(public_path($data->title1_image)))
                                            <img src="{{ asset($data->title1_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                        @endif
                                        <br>
                                    @endif

                                    <label for="title1_image">Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="title1_image" id="title1_image">
                                            <label class="custom-file-label" for="title1_image">Choose file</label>
                                        </div>
                                    </div>
                                    <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                    @error('title1_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6" style="margin-top: 50px;">
                                    <label for="title1_video">Youtube video link</label>
                                    <input type="text" class="form-control" name="title1_video" id="title1_video" placeholder="Enter youtube link" value="{{ old('title1_video') ? old('title1_video') : $data->title1_video }}">
                                    @error('title1_video') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title1_desc">title 1 description *</label>
                                <textarea name="title1_desc" id="title1_desc" class="form-control" placeholder="Enter title 1 description">{{ old('title1_desc') ? old('title1_desc') : $data->title1_desc }}</textarea>
                                @error('title1_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6" style="margin-top: 50px;">
                                <label for="why_choose_us_title">Why Choose Us Title*</label>
                                <input type="text" class="form-control" name="why_choose_us_title" id="why_choose_us_title" placeholder="Enter title" value="{{ old('why_choose_us_title') ? old('why_choose_us_title') : $data->why_choose_us_title }}">
                                @error('why_choose_us_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    @if (!empty($data->why_choose_us_image))
                                        @if (!empty($data->why_choose_us_image) && file_exists(public_path($data->why_choose_us_image)))
                                            <img src="{{ asset($data->why_choose_us_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                        @endif
                                        <br>
                                    @endif

                                    <label for="why_choose_us_image">Why Choose Us image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="why_choose_us_image" id="why_choose_us_image">
                                            <label class="custom-file-label" for="why_choose_us_image">Choose file</label>
                                        </div>
                                    </div>
                                    <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                    @error('why_choose_us_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6" style="margin-top: 50px;">
                                    <label for="why_choose_us_section1_title">Why Choose Us Section 1 Title *</label>
                                    <input type="text" class="form-control" name="why_choose_us_section1_title" id="why_choose_us_section1_title" placeholder="Enter page title" value="{{ old('why_choose_us_section1_title') ? old('why_choose_us_section1_title') : $data->why_choose_us_section1_title }}">
                                    @error('why_choose_us_section1_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    @if (!empty($data->why_choose_us_section1_image))
                                        @if (!empty($data->why_choose_us_section1_image) && file_exists(public_path($data->why_choose_us_section1_image)))
                                            <img src="{{ asset($data->why_choose_us_section1_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                        @endif
                                        <br>
                                    @endif

                                    <label for="why_choose_us_section1_image">Why Choose Us Section 1 image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="why_choose_us_section1_image" id="why_choose_us_section1_image">
                                            <label class="custom-file-label" for="why_choose_us_section1_image">Choose file</label>
                                        </div>
                                    </div>
                                    <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                    @error('why_choose_us_section1_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="why_choose_us_section1_desc">Why Choose Us Section 1 Description *</label>
                                <textarea name="why_choose_us_section1_desc" id="why_choose_us_section1_desc" class="form-control" placeholder="Enter title 1 description">{{ old('why_choose_us_section1_desc') ? old('why_choose_us_section1_desc') : $data->why_choose_us_section1_desc }}</textarea>
                                @error('why_choose_us_section1_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-md-6" style="margin-top: 50px;">
                                    <label for="why_choose_us_section2_title">Why Choose Us Section 2 Title *</label>
                                    <input type="text" class="form-control" name="why_choose_us_section2_title" id="why_choose_us_section2_title" placeholder="Enter section 2 title" value="{{ old('why_choose_us_section2_title') ? old('why_choose_us_section2_title') : $data->why_choose_us_section2_title }}">
                                    @error('why_choose_us_section2_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    @if (!empty($data->why_choose_us_section2_image))
                                        @if (!empty($data->why_choose_us_section2_image) && file_exists(public_path($data->why_choose_us_section2_image)))
                                            <img src="{{ asset($data->why_choose_us_section2_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                        @endif
                                        <br>
                                    @endif

                                    <label for="why_choose_us_section2_image">Why Choose Us Section 2 image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="why_choose_us_section2_image" id="why_choose_us_section2_image">
                                            <label class="custom-file-label" for="why_choose_us_section2_image">Choose file</label>
                                        </div>
                                    </div>
                                    <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                    @error('why_choose_us_section2_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="why_choose_us_section2_desc">Why Choose Us Section 2 Description *</label>
                                <textarea name="why_choose_us_section2_desc" id="why_choose_us_section2_desc" class="form-control" placeholder="Enter title 2 description">{{ old('why_choose_us_section2_desc') ? old('why_choose_us_section2_desc') : $data->why_choose_us_section2_desc }}</textarea>
                                @error('why_choose_us_section2_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-md-6" style="margin-top: 50px;">
                                    <label for="why_choose_us_section3_title">Why Choose Us Section 3 Title *</label>
                                    <input type="text" class="form-control" name="why_choose_us_section3_title" id="why_choose_us_section3_title" placeholder="Enter section 3 title" value="{{ old('why_choose_us_section3_title') ? old('why_choose_us_section3_title') : $data->why_choose_us_section3_title }}">
                                    @error('why_choose_us_section3_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    @if (!empty($data->why_choose_us_section3_image))
                                        @if (!empty($data->why_choose_us_section3_image) && file_exists(public_path($data->why_choose_us_section3_image)))
                                            <img src="{{ asset($data->why_choose_us_section3_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                        @endif
                                        <br>
                                    @endif

                                    <label for="why_choose_us_section3_image">Why Choose Us Section 3 image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="why_choose_us_section3_image" id="why_choose_us_section3_image">
                                            <label class="custom-file-label" for="why_choose_us_section3_image">Choose file</label>
                                        </div>
                                    </div>
                                    <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                    @error('why_choose_us_section3_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="why_choose_us_section3_desc">Why Choose Us Section 3 Description *</label>
                                <textarea name="why_choose_us_section3_desc" id="why_choose_us_section3_desc" class="form-control" placeholder="Enter title 3 description">{{ old('why_choose_us_section3_desc') ? old('why_choose_us_section3_desc') : $data->why_choose_us_section3_desc }}</textarea>
                                @error('why_choose_us_section3_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-md-6" style="margin-top: 50px;">
                                    <label for="why_choose_us_section4_title">Why Choose Us Section 4 Title *</label>
                                    <input type="text" class="form-control" name="why_choose_us_section4_title" id="why_choose_us_section4_title" placeholder="Enter section 4 title" value="{{ old('why_choose_us_section4_title') ? old('why_choose_us_section4_title') : $data->why_choose_us_section4_title }}">
                                    @error('why_choose_us_section4_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    @if (!empty($data->why_choose_us_section4_image))
                                        @if (!empty($data->why_choose_us_section4_image) && file_exists(public_path($data->why_choose_us_section4_image)))
                                            <img src="{{ asset($data->why_choose_us_section4_image) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                        @endif
                                        <br>
                                    @endif

                                    <label for="why_choose_us_section4_image">Why Choose Us Section 4 image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="why_choose_us_section4_image" id="why_choose_us_section4_image">
                                            <label class="custom-file-label" for="why_choose_us_section4_image">Choose file</label>
                                        </div>
                                    </div>
                                    <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                    @error('why_choose_us_section4_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="why_choose_us_section4_desc">Why Choose Us Section 3 Description *</label>
                                <textarea name="why_choose_us_section4_desc" id="why_choose_us_section4_desc" class="form-control" placeholder="Enter title 4 description">{{ old('why_choose_us_section4_desc') ? old('why_choose_us_section3_desc') : $data->why_choose_us_section4_desc }}</textarea>
                                @error('why_choose_us_section4_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="project_completed">Project Completed *</label>
                                    <input type="text" class="form-control" name="project_completed" id="project_completed" placeholder="Enter Completed Project" value="{{ old('project_completed') ? old('project_completed') : $data->project_completed }}">
                                    @error('project_completed') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="happy_customer">Happy Customers *</label>
                                    <input type="text" class="form-control" name="happy_customer" id="happy_customer" placeholder="Enter Happy Customer" value="{{ old('happy_customer') ? old('happy_customer') : $data->happy_customer }}">
                                    @error('happy_customer') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="solar_panels">Solar Panels *</label>
                                    <input type="text" class="form-control" name="solar_panels" id="solar_panels" placeholder="Enter Solar Panels" value="{{ old('solar_panels') ? old('solar_panels') : $data->solar_panels }}">
                                    @error('solar_panels') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="distributors">Distributors *</label>
                                    <input type="text" class="form-control" name="distributors" id="distributors" placeholder="Enter distributors" value="{{ old('distributors') ? old('distributors') : $data->distributors }}">
                                    @error('distributors') <p class="small text-danger">{{ $message }}</p> @enderror
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