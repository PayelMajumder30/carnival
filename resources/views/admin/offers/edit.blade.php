@extends('admin.layout.app')
@section('page-title', 'Update Offer')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{route('admin.offers.list.all')}}" class="btn btn-sm btn-primary"><i class="fa fa-chevron-left"></i>Back</a>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.offers.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="coupon_code">Coupon Code <span style="color: red;">*</label>
                                    <input type="coupon_code" class="form-control" name="coupon_code" id="coupon_code" placeholder="Enter coupon code.." 
                                        value="{{ old('coupon_code',$data->coupon_code) }}">
                                    @error('coupon_code') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span style="color: red;">*</span></label>
                                    <input type="datetime-local" class="form-control" name="start_date" id="start_date" 
                                        value="{{ old('start_date', \Carbon\Carbon::parse($data->start_date)->format('Y-m-d\TH:i'))}}">
                                    @error('start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date <span style="color: red;">*</span></label>
                                    <input type="datetime-local" class="form-control" name="end_date" id="end_date" 
                                        value="{{ old('end_date', \Carbon\Carbon::parse($data->end_date)->format('Y-m-d\TH:i'))}}">
                                    @error('end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount_type">Discount Type <span style="color: red;">*</span></label>
                                    <select name="discount_type" id="discount_type" class="form-control">
                                        <option value="flat" {{ old('discount_type', $data->discount_type) == 'flat' ? 'selected' : '' }}>Flat</option>
                                        <option value="percentage" {{ old('discount_type', $data->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    </select>
                                    @error('discount_type') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount_value" id="discount_value_label">Discount Value <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="discount_value" id="discount_value" 
                                        value="{{ old('discount_value', $data->discount_type == 'percentage' ? intval($data->discount_value) : $data->discount_value)}}">
                                    @error('discount_value') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="minimum_order_amount">Minimum Order Amount <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="minimum_order_amount" id="minimum_order_amount" 
                                        value="{{ old('minimum_order_amount', $data->minimum_order_amount)}}">
                                    @error('minimum_order_amount') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="maximum_discount">Maximum Discount <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="maximum_discount" id="maximum_discount" 
                                        value="{{ old('maximum_discount', $data->maximum_discount)}}">
                                    @error('maximum_discount') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="usage_limit">Global Usage Limit(optional)</label>
                                    <input type="text" class="form-control" name="usage_limit" id="usage_limit" 
                                        value="{{ old('usage_limit', $data->usage_limit)}}">
                                    @error('usage_limit') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="usage_per_user">Usage Per User(Optional) </label>
                                    <input type="text" class="form-control" name="usage_per_user" id="usage_per_user" 
                                        value="{{ old('usage_per_user', $data->usage_per_user)}}">
                                    @error('usage_per_user') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <button type="submit" class="btn btn-primary ">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function(){
    function updateDiscountField(){
        let discountType    = $('#discount_type').val();
        let discountLabel   = $('#discount_value_label');
        let discountValue   = $('#discount_value');

        if(discountType === 'percentage') {
            discountLabel.html("Percentage Discount Value % <span style='color: red;'>*</span>");
            discountValue.attr("placeholder", "Enter percentage discount value");
            discountValue.val(""); // Clear the input field when switching
        } else{
            discountLabel.html("Flat Discount Value <span style= 'color: red;'>*</span>");
            discountValue.attr("placeholder", "Enter flat discount value");
            discountValue.val(""); // Clear the input field when switching
        }
    }
        //Delete changes in the discount type dropdown
        $('#discount_type').change(updateDiscountField);

        //Run function on page load but retain the existing value
        let initialDiscountType = $('#discount_type').val();
        if(initialDiscountType === "percentage") {
            $('#discount_value_label').html("Percentage Discount Value % <span style='color: red;'>*</span>");  
        } else {
            $('#discount_value_label').html("Flat Discount value <span style= 'color:red'>*</span>")
        }  
        
  })
</script>