@extends('admin.layout.app')
@section('page-title', 'Offer List')

@section('section')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.offers.create') }}" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Create</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"></div>
                            {{-- <div class="col-md-6">
                                <form action="" method="get">
                                    <div class="d-flex justify-content-end">
                                        <div class="form-group ml-2">
                                            <input type="search" class="form-control form-control-sm" name="keyword" id="keyword" value="{{ request()->input('keyword') }}" placeholder="Search something...">
                                        </div>

                                        <div class="form-group ml-2">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-filter"></i>
                                                </button>
                                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-toggle="tooltip" title="Clear filter">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th width="15%">Coupon Code</th>
                                    <th width="15%">Start Date</th> <!-- Add this column for image -->
                                    <th width="35%">End Date</th>
                                    <th width="35%">Discount Type</th>
                                    <th width="35%">Discount Value</th>
                                    <th width="35%">Minimum Order Amount</th>
                                    <th width="35%">Maximum Discount Amount</th>
                                    <th width="35%">Global Usage Limit</th>
                                    <th width="35%">Usage Per User</th>
                                    <th>Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($offer as $index => $item)
                                    <tr class="text-left align-middle">
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $item->coupon_code }}</td>
                                        <td>{{ $item->start_date }}</td>
                                        <td>{{ $item->end_date }}</td>
                                        <td>{{ $item->discount_type }}</td>
                                        <td>{{ $item->discount_value }}</td>
                                        <td>{{ $item->minimum_order_amount }}</td>
                                        <td>{{ $item->maximum_discount }}</td>
                                        <td>{{ $item->usage_limit }}</td>
                                        <td>{{ $item->usage_per_user }}</td>

                                        <td> 
                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{$item->id}}" {{ ($item->status == 1) ? 'checked' : '' }} onchange="statusToggle('{{ route('admin.offers.status', $item->id) }}')">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="d-flex">
                                            <div class="btn-group">
                                                <a href="" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="" class="btn btn-sm btn-dark" onclick="return confirm('Are you sure ?')" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')

@endsection
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
