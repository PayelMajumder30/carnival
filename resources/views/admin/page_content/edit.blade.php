@extends('admin.layout.app')
@section('page-title', 'Edit Page Content')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.page_content.list.all') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.page_content.update', $data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            
                            <div class="form-group">
                                <label for="page">Page Name <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="page" id="page" placeholder="Enter page name" value="{{ old('page', $data->page) }}">
                                @error('page')
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="title">Page Title <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Enter title" value="{{ old('title', $data->title) }}">
                                @error('title')
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="short_description">Short Description</label>
                                <textarea name="short_description" id="short_description" cols="4" rows="5" class="form-control" placeholder="Enter short description">{{ old('short_description', $data->short_description) }}</textarea>
                                @error('short_description')
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description <span style="color: red;">*</span></label>
                                <textarea name="description" id="description" cols="4" rows="5" class="form-control ckeditor" placeholder="Enter description">{{ old('description', $data->description) }}</textarea>
                                @error('description')
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control" name="meta_title" id="meta_title" placeholder="Enter Meta Title.." value="{{ old('meta_title', $data->meta_title) }}">
                                @error('meta_title') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" cols="4" rows="5" class="form-control ckeditor" placeholder="">{{ old('meta_description', $data->meta_description) }}</textarea>
                                @error('meta_description')
                                    <p class="small text-danger">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="meta_keyword">Meta Keyword</label>
                                <input type="text" class="form-control" name="meta_keyword" id="meta_keyword" placeholder="Enter Meta Keywords.." value="{{ old('meta_keyword', $data->meta_keyword) }}">
                                @error('meta_keyword') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image">Current Image</label>
                                @if($data->image)
                                    <div class="mb-3">
                                        <img src="{{ asset($data->image) }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                                <label for="image">Upload New Image</label>
                                <input type="file" class="form-control" name="image" id="image">
                                @error('image')
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 ml-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
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
   CKEDITOR.replace('description');
</script>
@endsection
