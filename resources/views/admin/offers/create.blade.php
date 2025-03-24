@extends('admin.layout.app')
@section('page-title', 'Create Offer')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{route('admin.offers.list.all')}}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.offers.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="coupon">Coupon Code <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="coupon_code" id="coupon_code" placeholder="Enter Coupon" value="{{ old('coupon_code') }}">
                                        @error('coupon_code') 
                                        <p class="small text-danger">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
                                    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date">Start Date <span style="color: red;">*</span></label>
                                        <input type="datetime-local" class="form-control" name="start_date" id="start_date" placeholder="Enter Start Date" value="{{ old('start_date') }}">
                                        @error('start_date') 
                                        <p class="small text-danger">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>                                    
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_date">End Date <span style="color: red;">*</span></label>
                                        <input type="datetime-local" class="form-control" name="end_date" id="end_date" placeholder="Enter Start Date" value="{{ old('end_date') }}">
                                        @error('end_date') 
                                            <p class="small text-danger">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="discount_type">Discount Type <span style="color: red;">*</label>
                                        <select name="discount_type" id="discount_type" class="form-control">
                                            <option value="flat">Flat</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                        @error('discount_type') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label id="discount_value_label" for="discount_value">Discount Value<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="discount_value" id="discount_value" placeholder="Enter discount amount" value="{{ old('discount_value') }}">
                                        @error('discount_value') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="minimum_order_amount">Minimum Order Amount <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="minimum_order_amount" id="minimum_order_amount" placeholder="Enter Minimum Order amount" value="{{ old('minimum_order_amount') }}">
                                        @error('minimum_order_amount') 
                                            <p class="small text-danger">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="maximum_discount">Maximum Discount Amount <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="maximum_discount" id="maximum_discount" placeholder="Enter Maximum Discount amount" value="{{ old('maximum_discount') }}">
                                        @error('maximum_discount') 
                                            <p class="small text-danger">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="usage_limit">Global Usage Limit(Optional) </span></label>
                                        <input type="text" class="form-control" name="usage_limit" id="usage_limit" placeholder="Enter global usage" value="{{ old('usage_limit') }}">
                                        @error('usage_limit') 
                                            <p class="small text-danger">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="usage_per_user">Usage Per User(Optional) </span></label>
                                        <input type="text" class="form-control" name="usage_per_user" id="usage_per_user" placeholder="Enter usage per user" value="{{ old('usage_per_user') }}">
                                        @error('usage_per_user') 
                                            <p class="small text-danger">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
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

@section('script')

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        function updateDiscountField() {
            let discountType = $("#discount_type").val();
            let discountLabel = $("#discount_value_label");

            if(discountType === 'percentage') {
                discountLabel.text("Percentage Discount Value (%)");
            } else {
                discountLabel.text("Flat Discount Value");
            }
        }

        $("#discount_type").change(updateDiscountField);
        updateDiscountField(); // Run on page load
    });
</script>


