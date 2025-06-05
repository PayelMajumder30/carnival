@extends('admin.layout.app')
@section('page-title', 'Create Why choose us')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.whychooseus.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.whychooseus.store') }}" method="post" enctype="multipart/form-data">@csrf
                          
                                <div class="form-group">
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter title.." value="{{ old('title') }}">
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="desc">Description <span style="color: red;">*</span></label>
                                    <textarea class="form-control" name="desc" id="desc" placeholder="Enter Description Here">{{ old('desc') }}</textarea>
                                    @error('desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                          
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
