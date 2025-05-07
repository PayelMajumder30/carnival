@extends('admin.layout.app')
@section('page-title', 'Update Trip Category')

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
                        <form action="{{ route('admin.tripcategory.update', $data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter trip category title.." value="{{ old('title') ? old('title') : $data->title }}">
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                    
                                    <label for="short_desc">Short Description </label>
                                    <input type="text" class="form-control" name="short_desc" id="short_desc" placeholder="Enter trip category short description.." value="{{ old('short_desc') ? old('short_desc') : $data->short_desc }}">
                                    @error('short_desc') <p class="small text-danger">{{ $message }}</p> @enderror
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
<!-- test git commit-->
@endsection