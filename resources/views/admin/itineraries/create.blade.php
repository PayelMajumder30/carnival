@extends('admin.layout.app')
@section('page-title', 'Create Itineraries')
<style>
   
</style>
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
                        <form action="{{ route('admin.itineraries.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="destination_id">Destination<span style="color: red;">*</span></label>
                                    <select name="destination_id" id="destination_id" class="form-control" required>
                                        <option value="" selected hidden>--Select Destination--</option>
                                        @foreach($destinations as $destination) 
                                            <option value="{{ $destination->id }}" data-crm_id="{{$destination->crm_destination_id}}">{{ $destination->destination_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="itinerary">Itinerary journey:</label>
                                    <select id="itinerary_journey" class="form-control" disabled>
                                        <option selected disabled hidden>Please choose a destination first</option>
                                    </select>
                                    @error('itinerary_journey') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="title">Title <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter itinerary title.." value="{{ old('title') }}">
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="actual_price">Actual Price <span style="color: red;">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="actual_price" id="actual_price" placeholder="Enter actual price" value="{{ old('actual_price') }}">
                                    @error('actual_price') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="form-group">
                                        <label for="discount_type">Discount Type <span style="color: red;">*</span></label>
                                        <select name="discount_type" id="discount_type" class="form-control">
                                            <option value="flat">Flat</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                        @error('discount_type') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label id="discount_value_label" for="discount_value">Discount Value<span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="discount_value" id="discount_value" placeholder="Enter discount amount" value="{{ old('discount_value') }}">
                                    @error('discount_value') <p class="small text-danger">{{ $message }}</p> @enderror
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
                                   <label for="trip_durations">Trip Duration <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="trip_durations" id="trip_durations" placeholder="Enter itinerary trip duration.." value="{{ old('trip_durations') }}" readonly>
                                    @error('trip_durations') <p class="small text-danger">{{ $message }}</p> @enderror
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
                                    <input type="hidden" name="crm_itinerary_id" id="crm_itinerary_id" value="">
                                    <input type="hidden" name="stay_by_division_journey" id="stay_by_division_journey" value="">
                                    <input type="hidden" name="total_nights" id="total_nights" value="">
                                    <input type="hidden" name="total_days" id="total_days" value="">
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
    $(document).ready(function () {
        $('#destination_id').on('change', function () {
            const $dropdown = $('#itinerary_journey');
            const destinationId = $(this).val();
            const destination_crm_id = $(this).find('option:selected').data('crm_id');

            $('#trip_durations').val('');
            $('#crm_itinerary_id').val('');
            $('#stay_by_division_journey').val('');
            $('#total_nights').val(''); 
            $('#total_days').val('');

            if (destination_crm_id) {
                $dropdown.attr('disabled', 'disabled');
                $dropdown.empty().append('<option selected disabled hidden>Please wait..</option>');

                $.ajax({
                    url: "{{route('admin.itineraries.get_itineraries_from_crm')}}", // Laravel route to fetch itineraries
                    type: 'GET',
                    data: { destination_id: destination_crm_id },
                    success: function (response) {
                        // Clear and enable the itinerary dropdown
                        
                        $dropdown.removeAttr('disabled');
                        $dropdown.empty().append('<option selected disabled hidden>Select your itinerary journey</option>');
                        
                        if (response.success && response.data.length > 0) {
                            $.each(response.data, function (index, itinerary) {
                                const text = `${itinerary.total_nights} Nights / ${itinerary.total_days} Days - ${itinerary.itinerary_journey}`;
                                const text_value = `${itinerary.total_nights} Nights / ${itinerary.total_days} Days`;
                                $dropdown.append(`<option value="${text_value}" data-id="${itinerary.id}" data-stay_by_journey="${itinerary.stay_by_journey}" data-total_nights="${itinerary.total_nights}" data-total_days="${itinerary.total_days}">${text}</option>`);
                            });
                        } else {
                            $dropdown.attr('disabled', 'disabled');
                            $dropdown.empty().append('<option selected disabled hidden>No itineraries available for this destination</option>');
                            toastFire('error', response.message); 
                        }
                    },
                    error: function () {
                        toastFire('error', 'No itineraries available for this destination'); 
                        $dropdown.attr('disabled', 'disabled');
                        $dropdown.empty().append('<option selected disabled hidden>No itineraries available for this destination</option>');
                    }
                });
            } else {
                $('#itinerary_journey')
                    .attr('disabled', 'disabled')
                    .val('')
                    .find('option:first')
                    .text('Please choose a destination first');
            }
        });
        $('#itinerary_journey').on('change', function (){
            const itinerary_journey = $(this).val();
            $('#trip_durations').val(itinerary_journey);

            const crm_itinerary_id = $(this).find('option:selected').data('id'); 
            $('#crm_itinerary_id').val(crm_itinerary_id);

            const stay_by_journey = $(this).find('option:selected').data('stay_by_journey'); 
            $('#stay_by_division_journey').val(stay_by_journey);

            const total_nights = $(this).find('option:selected').data('total_nights'); 
            $('#total_nights').val(total_nights);

            const total_days = $(this).find('option:selected').data('total_days'); 
            $('#total_days').val(total_days);
        });
    });

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
