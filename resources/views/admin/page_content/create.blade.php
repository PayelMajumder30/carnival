@extends('admin.layout.app')
@section('page-title', 'Create New Page')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.page_content.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.page_content.store') }}" method="post" enctype="multipart/form-data">@csrf
                            <div class="form-group">
                                <input type="hidden" name="custom_field"  id="custom_field" />
                                <label for="">New Page Name <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="page" id="page"  placeholder="Enter new page name" value="{{old('page')}}">
                                @error('page')
                                  <p class="small text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="title">New Page Title <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Enter title.." value="{{ old('title') }}">
                                @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Short Description</label>
                                <textarea name="short_description" id="short_description" cols="4" rows="5" class="form-control" placeholder="">{{old('short_description')}}</textarea>
                                @error('short_description')
                                    <p class="small text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Description <span style="color: red;">*</span></label>
                                <textarea name="description" id="description" cols="4" rows="5" class="form-control ckeditor" placeholder="">{{old('description')}}</textarea>
                                @error('description')
                                    <p class="small text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control" name="meta_title" id="meta_title" placeholder="Enter Meta Title.." value="{{ old('meta_title') }}">
                                @error('meta_title') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" cols="4" rows="5" class="form-control ckeditor" placeholder="">{{old('meta_description')}}</textarea>
                                @error('meta_description')
                                    <p class="small text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_keyword">Meta Keyword</label>
                                <input type="text" class="form-control" name="meta_keyword" id="meta_keyword" placeholder="Enter Meta Keyword.." value="{{ old('meta_keyword') }}">
                                @error('meta_keyword') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="custom_field"  id="custom_field" />
                                <label for="">New Image</label>
                                <input type="file" class="form-control" name="image" id="image"  placeholder="Enter new image name" value="{{old('image')}}">
                                @error('image')
                                  <p class="small text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            
                          
                            </div>
                            <div class="mb-4 ml-4">

                                <button type="submit" class="btn btn-primary">Upload</button>
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
   CKEDITOR.replace( 'desc' ); 
    // Loop through each editor element and initialize ClassicEditor
    
</script>
@endsection
