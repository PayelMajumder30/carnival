@extends('admin.layout.app')
@section('page-title', 'Create Trip Category Banner')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.tripcategorybanner.list.all', $trip->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
