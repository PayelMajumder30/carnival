@extends('admin.layout.app')
@section('page-title', 'Create Itineraries')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.itenaries.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.itenaries.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter itinerary title.." value="{{ old('title') }}">
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="actual_price">Actual Price <span style="color: red;">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="actual_price" id="actual_price" placeholder="Enter actual price" value="{{ old('actual_price') }}">
                                    @error('actual_price') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label for="discount_type">Discount Type <span style="color: red;">*</span></label>
                                        <select name="discount_type" id="discount_type" class="form-control">
                                            <option value="flat">Flat</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                        @error('discount_type') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label id="discount_value_label" for="discount_value">Discount Value<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="discount_value" id="discount_value" placeholder="Enter discount amount" value="{{ old('discount_value') }}">
                                        @error('discount_value') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="selling_price">Selling Price</span></label>
                                    <input type="number" step="0.01" class="form-control" name="selling_price" id="selling_price" placeholder="Enter selling price" value="{{ old('selling_price') }}">
                                    @error('selling_price') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label for="short_description">Short Description</label>
                                    <textarea class="form-control" name="short_description" id="short_description" rows="3" placeholder="Enter short description...">{{ old('short_description') }}</textarea>
                                    @error('short_description') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-3">
                                   <label for="duration">Duration <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="duration" id="duration" placeholder="Enter itinerary duration.." value="{{ old('duration') }}">
                                    @error('duration') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="main_image">Main Image <span style="color: red;">*</span></label>
                                    <input type="file" class="form-control-file" name="main_image" id="main_image" accept="image/*">
                                    @error('main_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="discount_start_date">Discount Start Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" name="discount_start_date" id="discount_start_date" required>
                                    @error('discount_start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="discount_end_date">Discount End Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" name="discount_end_date" id="discount_end_date" required>
                                    @error('discount_end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Create</button>
                                    </div>
                                </div>
                            </div>
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
    $(document).ready(function(){
        function updateDiscountField() {
            let discountType    = $("#discount_type").val();
            let discountLabel   = $("#discount_value_label");
            let discountValue   = $("#discount_value");

            if(discountType === 'percentage') {
                discountLabel.html("Percentage Discount Value (%) <span style='color: red;'>*</span>");
                
            } else {
                discountLabel.html("Flat Discount Value <span style='color: red;'>*</span>");
            }
        }

        $("#discount_type").change(updateDiscountField);
        updateDiscountField();
    });


    document.addEventListener('DOMContentLoaded', function () {
        const actualPriceInput = document.getElementById('actual_price');
        const discountTypeInput = document.getElementById('discount_type');
        const discountValueInput = document.getElementById('discount_value');
        const sellingPriceInput = document.getElementById('selling_price');

        function calculateSellingPrice() {
            const actualPrice = parseFloat(actualPriceInput.value) || 0;
            const discountType = discountTypeInput.value;
            const discountValue = parseFloat(discountValueInput.value) || 0;

            let sellingPrice = actualPrice;

            if (discountType === 'flat') {
                sellingPrice = actualPrice - discountValue;
            } else if (discountType === 'percentage') {
                sellingPrice = actualPrice - ((discountValue / 100) * actualPrice);
            }

            if (sellingPrice < 0) sellingPrice = 0;

            sellingPriceInput.value = sellingPrice.toFixed(2);
        }

        actualPriceInput.addEventListener('input', calculateSellingPrice);
        discountTypeInput.addEventListener('change', calculateSellingPrice);
        discountValueInput.addEventListener('input', calculateSellingPrice);
    });
</script>
@endsection
