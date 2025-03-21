@extends('admin.layout.app')
@section('page-title', 'Create Article')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.article.list.all') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.article.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Enter Title" value="{{ old('title') }}">
                                @error('title') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="sub_title">Sub Title <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="sub_title" id="sub_title" placeholder="Enter Sub Title" value="{{ old('sub_title') }}">
                                @error('sub_title') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="content">Content <span style="color: red;">*</span></label>
                                <textarea class="form-control ckeditor" name="content" id="content" placeholder="Enter Content">{{ old('content') }}</textarea>
                                @error('content') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" name="image" id="image">
                                @error('image')
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_type">Meta Type</label>
                                <input type="text" class="form-control" name="meta_type" id="meta_type" placeholder="Enter Meta Type" value="{{ old('meta_type') }}">
                                @error('meta_type') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>
                            
                                <div class="col">
                                    <div class="form-group">
                                        <label for="meta_description">Meta Description</label>
                                        <textarea class="form-control ckeditor" name="meta_description" id="meta_description" placeholder="Enter Meta Description">{{ old('meta_description') }}</textarea>
                                        @error('meta_description') 
                                            <p class="small text-danger">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
                            <div class="form-group">
                                <label for="meta_keywords">Meta Keywords</label>
                                <textarea class="form-control" name="meta_keywords" id="meta_keywords" placeholder="Enter Meta Keywords">{{ old('meta_keywords') }}</textarea>
                                @error('meta_keywords') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Create Article</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')

@endsection
