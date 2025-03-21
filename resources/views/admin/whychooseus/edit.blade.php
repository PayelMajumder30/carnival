@extends('admin.layout.app')
@section('page-title', 'Update why choose us')

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
                        <form action="{{ route('admin.whychooseus.update', $data->id) }}" method="post" enctype="multipart/form-data">@csrf
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                {{-- <div class="col-md-6" style="margin-top: 50px;"> --}}
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter social title.." value="{{ old('title') ? old('title') : $data->title }}">
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                {{-- </div> --}}
                            </div>
                            <div class="form-group">
                                <label for="title">Description <span style="color: red">*</span></label>
                                <textarea class="form-control" name="desc" id="desc" placeholder="Enter Description Here">{{ $data->desc }}</textarea>
                                @error('desc') <p class="small text-danger">{{ $message }}</p> @enderror
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