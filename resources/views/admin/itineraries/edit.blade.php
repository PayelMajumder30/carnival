@extends('admin.layout.app')
@section('page-title', 'Update Itineraries')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.itineraries.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <form action="{{ route('admin.itineraries.update', $itenary->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="title">Title </span></label>
                                <input type="text" class="form-control" name="title" id="title"
                                    value="{{ old('title', $itenary->title) }}" placeholder="Enter itinerary title..">
                                @error('title') 
                                    p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="actual_price">Actual Price</span></label>
                                <input type="number" step="0.01" class="form-control" name="actual_price" id="actual_price"
                                    value="{{ old('actual_price', $itenary->actual_price) }}" placeholder="Enter actual price">
                                @error('actual_price') 
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="destination_id">Destination</label>
                                <select name="destination_id" id="destination_id" class="form-control" required>
                                    <option value="" disabled {{ !$itenary->destination_id ? 'selected' : '' }}>--Select Destination--</option>
                                    @foreach($destinations as $destination) 
                                        <option value="{{ $destination->id }}" {{ $itenary->destination_id == $destination->id ? 'selected' : '' }}>
                                            {{ $destination->destination_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="form-group">
                                    <label for="discount_type">Discount Type</label>
                                    <select name="discount_type" id="discount_type" class="form-control">
                                        <option value="flat" {{old('discount_type', $itenary->discount_type) == 'flat' ? 'selected' : ''}}>Flat</option>
                                        <option value="percentage" {{old('discount_type', $itenary->discount_type) == 'percentage' ? 'selected' : ''}}>Percentage</option>
                                    </select>
                                    @error('discount_type') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label id="discount_value_label" for="discount_value">Discount Value</label>
                                    <input type="text" class="form-control" name="discount_value" id="discount_value" value="{{ old('discount_value', $itenary->discount_value) }}"  placeholder="Enter discount amount" value="{{ old('discount_value') }}">
                                    @error('discount_value') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="selling_price">Selling Price </span></label>
                                <input type="number" step="0.01" class="form-control" name="selling_price" id="selling_price"
                                    value="{{ old('selling_price', $itenary->selling_price) }}" placeholder="Enter selling price">
                                @error('selling_price') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>


                            <div class="form-group col-md-6">
                                <label for="short_description">Short Description</label>
                            <textarea class="form-control" name="short_description" id="short_description" rows="3" placeholder="Enter short description..">{{ old('short_description', $itenary->short_description) }}</textarea>
                                @error('short_description') 
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="duration">Duration </span></label>
                                <input type="duration" class="form-control" name="duration" id="duration"
                                    value="{{ old('duration', $itenary->duration) }}" placeholder="Enter itinerary duration..">
                                @error('duration') 
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="main_image">Main Image</label>
                                <input type="file" class="form-control-file" name="main_image" id="main_image" accept="image/*">
                                @error('main_image')
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror

                                @if(!empty($itenary->main_image) && file_exists(public_path($itenary->main_image)))
                                    <div class="mt-2">
                                        <p>Current Image:</p>
                                        <img src="{{ asset($itenary->main_image) }}" alt="Main Image" width="120">
                                    </div>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="discount_start_date">Discount Start Date</label>
                                <input type="datetime-local" class="form-control" name="discount_start_date" id="discount_start_date" 
                                value="{{ old('discount_start_date', $itenary->discount_start_date) }}">
                                @error('discount_start_date') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="discount_end_date">Discount End Date</label>
                                <input type="datetime-local" class="form-control" name="discount_end_date" id="discount_end_date"
                                value="{{ old('discount_end_date', $itenary->discount_end_date) }}">
                                @error('discount_end_date') 
                                    <p class="small text-danger">{{ $message }}</p> 
                                @enderror
                            </div>

                           
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
                discountLabel.html("Percentage Discount Value (%)");
                
            } else {
                discountLabel.html("Flat Discount Value");
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
