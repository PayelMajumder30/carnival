@extends('admin.layout.app')
@section('page-title', 'Blog Details')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.blog.list.all') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Display Image -->
                        @if($blog->image)
                            <div class="form-group">
                                <label for="image">Image</label>
                                <img src="{{ asset($blog->image) }}" alt="Blog Image" class="img-fluid" style="max-width: 20%; height: 20%;">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="title">Title</label>
                            <p>{{ $blog->title }}</p>
                        </div>

                        <div class="form-group">
                            <label for="short_desc">Short Description</label>
                            <p>{{ $blog->short_desc }}</p>
                        </div>

                        <div class="form-group">
                            <label for="desc">Description</label>
                            <div>{!! $blog->desc !!}</div>
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <div>{!! $blog->meta_description !!}</div>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <p>{{ $blog->meta_keywords }}</p>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <p>{{ $blog->status == 1 ? 'Active' : 'Inactive' }}</p>
                        </div>

                        
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
