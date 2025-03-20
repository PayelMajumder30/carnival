@extends('admin.layout.app')
@section('page-title', 'Edit Class')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.class.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.class.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Name *</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{{ $data->name }}">
                                @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            {{-- <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="title">Page Title *</label>
                                        <input type="text" class="form-control" name="page_title" id="page_title" placeholder="Enter Page Title" value="{{ $data->page_title }}">
                                        @error('page_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="title">Meta Title *</label>
                                        <input type="text" class="form-control" name="meta_title" id="meta_title" placeholder="Enter Meta Title" value="{{ $data->meta_title }}">
                                        @error('meta_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="title">Meta Description *</label>
                                        <textarea class="form-control" name="meta_description" id="meta_description" placeholder="Enter Meta Description">{{ $data->meta_desc }}</textarea>
                                        @error('meta_description') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="title">Meta Keyword *</label>
                                        <input type="text" class="form-control" name="meta_keyword" id="meta_keyword" placeholder="Enter Meta Keyword" value="{{ $data->meta_keyword }}">
                                        @error('meta_keyword') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div> --}}
                            
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <button type="submit" class="btn btn-primary">Upload</button>
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
        checkCatParentLevel();
    </script>
@endsection