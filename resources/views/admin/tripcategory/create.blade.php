@extends('admin.layout.app')
@section('page-title', 'Create Trip Category')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.tripcategory.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.tripcategory.store') }}" method="post" enctype="multipart/form-data">@csrf
                                         
                                <div class="form-group">
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter trip category title.." value="{{ old('title') }}">
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="short_desc">Short Description </label>
                                    <input type="text" class="form-control" name="short_desc" id="short_desc" placeholder="Enter Short description.." value="{{ old('short_desc') }}">
                                    @error('short_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            
                            <!-- Button in a separate row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Upload</button>
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
