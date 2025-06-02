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
                                <label for="destination_id">Destination</label>
                                <select name="destination_id" id="destination_id" class="form-control" required>
                                    <option value="" disabled {{ !$itenary->destination_id ? 'selected' : '' }}>--Select Destination--</option>
                                    @foreach($destinations as $destination) 
                                        <option 
                                            value="{{ $destination->id }}" 
                                            data-crm_id="{{ $destination->crm_destination_id }}" 
                                            {{ $itenary->destination_id == $destination->id ? 'selected' : '' }}>
                                            {{ $destination->destination_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-md-6">
                                <label for="itinerary_journey">Itinerary Journey</label>
                                <select name="itinerary_journey" id="itinerary_journey" class="form-control" required>
                                    <option selected disabled hidden>Please choose a destination first</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
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
                                <div class="form-group">
                                    <label for="discount_type">Discount Type</label>
                                    <select name="discount_type" id="discount_type" class="form-control">
                                        <option value="flat" {{old('discount_type', $itenary->discount_type) == 'flat' ? 'selected' : ''}}>Flat</option>
                                        <option value="percentage" {{old('discount_type', $itenary->discount_type) == 'percentage' ? 'selected' : ''}}>Percentage</option>
                                    </select>
                                    @error('discount_type') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-3">
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
                                <label for="trip_durations">Trip Duration </span></label>
                                <input type="trip_durations" class="form-control" name="trip_durations" id="trip_durations"
                                    value="{{ old('trip_durations', $itenary->trip_durations) }}" placeholder="Enter itinerary duration..">
                                @error('trip_durations') 
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
                                <input type="hidden" id="crm_itinerary_id" name="crm_itinerary_id" value="{{ $itenary->crm_itinerary_id }}">
                                <input type="hidden" id="stay_by_division_journey" name="stay_by_division_journey" value="{{ $itenary->stay_by_division_journey }}">
                                <input type="hidden" id="total_nights" name="total_nights" value="{{ $itenary->total_nights }}">
                                <input type="hidden" id="total_days" name="total_days" value="{{ $itenary->total_days }}">
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
    $(document).ready(function () {
        const oldCrmItineraryId = '{{ $itenary->crm_itinerary_id }}';
        const oldTripDuration = '{{ $itenary->trip_durations }}';
        const selectedDestination = $('#destination_id').val();
        const selectedDestinationCrmId = $('#destination_id').find('option:selected').data('crm_id');

        function loadItineraries(destinationCrmId, preselect = true) {
            const $dropdown = $('#itinerary_journey');
            $dropdown.attr('disabled', 'disabled').empty().append('<option selected disabled hidden>Please wait..</option>');

            $.ajax({
                url: "{{ route('admin.itineraries.get_itineraries_from_crm') }}",
                type: 'GET',
                data: { destination_id: destinationCrmId },
                success: function (response) {
                    $dropdown.removeAttr('disabled').empty().append('<option selected disabled hidden>Select your itinerary journey</option>');

                    if (response.success && response.data.length > 0) {
                        $.each(response.data, function (index, itinerary) {
                            const text = `${itinerary.total_nights} Nights / ${itinerary.total_days} Days - ${itinerary.itinerary_journey}`;
                            const text_value = `${itinerary.total_nights} Nights / ${itinerary.total_days} Days`;
                            const selected = itinerary.id == oldCrmItineraryId ? 'selected' : '';

                            $dropdown.append(`<option value="${text_value}" data-id="${itinerary.id}" data-stay_by_journey="${itinerary.stay_by_journey}" data-total_nights="${itinerary.total_nights}" data-total_days="${itinerary.total_days}" ${selected}>${text}</option>`);
                        });

                        
                        if (preselect && oldCrmItineraryId) {
                            const $preSelected = $dropdown.find('option:selected');
                            $('#trip_durations').val($dropdown.val());
                            $('#crm_itinerary_id').val($preSelected.data('id'));
                            $('#stay_by_division_journey').val($preSelected.data('stay_by_journey'));
                            $('#total_nights').val($preSelected.data('total_nights'));
                            $('#total_days').val($preSelected.data('total_days'));
                        }

                    } else {
                        $dropdown.attr('disabled', 'disabled').empty().append('<option selected disabled hidden>No itineraries available for this destination</option>');
                        toastFire('error', response.message);
                    }
                },
                error: function () {
                    toastFire('error', 'No itineraries available for this destination');
                    $dropdown.attr('disabled', 'disabled').empty().append('<option selected disabled hidden>No itineraries available for this destination</option>');
                }
            });
        }



        if (selectedDestinationCrmId) {
            loadItineraries(selectedDestinationCrmId, true);
        }

        $('#destination_id').on('change', function () {
            const destinationCrmId = $(this).find('option:selected').data('crm_id');

            $('#trip_durations, #crm_itinerary_id, #stay_by_division_journey, #total_nights, #total_days').val('');

            if (destinationCrmId) {
                loadItineraries(destinationCrmId, false);
            } else {
                $('#itinerary_journey')
                    .attr('disabled', 'disabled')
                    .val('')
                    .empty()
                    .append('<option selected disabled hidden>Please choose a destination first');
            }
        });

        $('#itinerary_journey').on('change', function () {
            const $selected = $(this).find('option:selected');
            $('#trip_durations').val($(this).val());
            $('#crm_itinerary_id').val($selected.data('id'));
            $('#stay_by_division_journey').val($selected.data('stay_by_journey'));
            $('#total_nights').val($selected.data('total_nights'));
            $('#total_days').val($selected.data('total_days'));
        });
    });


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
