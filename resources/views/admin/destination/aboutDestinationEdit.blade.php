@extends('admin.layout.app')
@section('page-title', 'Update Content')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{  route('admin.destination.aboutDestination.list', ['destination_id' => $destination->id])}}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.destination.aboutDestiUpdate') }}" method="post">
                            @csrf
                            @method('POST')
        
                            <input type="hidden" name="destination_id" value="{{ $destination->id }}">
                            <div class="form-group">
                                <label for="content">Content <span style="color: red;">*</span></label>
                                <textarea class="form-control ckeditor" name="content" id="content" placeholder="Enter Content">{{ $data->content }}</textarea>
                                @error('content') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>
                            
                            <div class="col">
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

