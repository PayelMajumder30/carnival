@extends('admin.layout.app')
@section('page-title', 'Article Details')

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
                        <!-- Display Image -->
                        @if($article->image)
                            <div class="form-group">
                                <label for="image">Image</label>
                                <img src="{{ asset($article->image) }}" alt="Article Image" class="img-fluid" style="max-width: 20%; height: 20%;">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="title">Title</label>
                            <p>{{ $article->title }}</p>
                        </div>

                        <div class="form-group">
                            <label for="sub_title">Sub Title</label>
                            <p>{{ $article->sub_title }}</p>
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <div>{!! $article->content !!}</div>
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <div>{!! $article->meta_description !!}</div>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <p>{{ $article->meta_keywords }}</p>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <p>{{ $article->status == 1 ? 'Active' : 'Inactive' }}</p>
                        </div>

                        
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.article.edit', $article->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
