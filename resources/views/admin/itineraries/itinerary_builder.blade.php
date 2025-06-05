@extends('admin.layout.app')
@section('page-title', 'Itinerary builder')
@section('page-subtitle', ucwords($itinerary->title).'('.$itinerary->trip_durations.')')
<style>
    .itinerary-summary-icons {
        font-weight: 500;
        color: #333;
        font-size: 15px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #fff;
        padding: 8px 12px;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .summary-day-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
    }

    .summary-item img {
        max-width: 28px;
        height: auto;
    }
    .day_logo {
        max-width: 28px;
        height: auto;
    }

    .summary-item:hover {
        background-color: #f9f9f9;
        transform: translateY(-2px);
    }

    .itinerary-day {
        background-color: #f7f7f7;
        border-left: 5px solid #e84e4e;
        transition: all 0.3s ease;
    }

    .itinerary-day:hover {
        background-color: #f0f0f0;
    }

    .itinerary-day i {
        font-size: 16px;
        color: #888;
    }

    .itinerary-day strong {
        font-size: 16px;
        color: #333;
    }

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
                        <ul class="nav nav-pills nav-fill">
                            <li class="nav-item">
                                <a class="nav-link {{$active_tab=="summarised"?"active":""}}" href="{{route('admin.itinerary.builder',[$itinerary->id,'summarised'])}}">Summarised View</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$active_tab=="activities"?"active":""}}" href="{{route('admin.itinerary.builder',[$itinerary->id,'activities'])}}">Activities</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$active_tab=="hotels"?"active":""}}" href="{{route('admin.itinerary.builder',[$itinerary->id,'hotels'])}}">Hotels</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$active_tab=="transfer"?"active":""}}" href="{{route('admin.itinerary.builder',[$itinerary->id,'transfer'])}}">Transfers(Cab)</a>
                            </li>
                        </ul>
                        @if($active_tab=="summarised")
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-primary">Trip Highlights</label>
                                        <div id="tripHighlightsWrapper">
                                            @foreach ($trip_highlight as $trip_item)
                                                <div class="input-group mb-2 itinerary-highlight-item" id="highlight_exist_trip_{{$trip_item->id}}">
                                                    <input type="text" name="trip_highlights[]" class="form-control text-sm" rows="2" placeholder="Enter highlight" onkeyup="saveHighlight(this)" value="{{$trip_item->value}}">
                                                    <button type="button" class="btn btn-outline-danger ms-2 h-auto align-self-start" data-id="exist_trip_{{$trip_item->id}}" onclick="RemoveTripHighlight({{$trip_item->id}})"><i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="text-right">
                                            <button type="button" id="addHighlightBtn"
                                                class="btn btn-outline-success mt-2 btn-sm">ADD TRIP HIGHLIGHTS</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="itinerary-summary p-4">
                                    <h4 class="fw-bold mb-3">Trip Summary</h4>

                                    <div class="d-flex flex-wrap gap-4 mb-4 itinerary-summary-icons">
                                        <span class="summary-item">
                                            <img src="{{ asset('images/travel.png') }}" alt="Activities">
                                            <span>{{count($activities)}} Activities</span>
                                        </span>
                                        <span class="summary-item">
                                            <img src="{{ asset('images/taxi.png') }}" alt="Transfers">
                                            <span>{{count($total_cabs)}} Transfers</span>
                                        </span>
                                        <span class="summary-item">
                                            <img src="{{ asset('images/hotel.png') }}" alt="Hotels">
                                            <span>12 Hotels</span>
                                        </span>
                                    </div>

                                    @foreach ($DayDivisons as $key => $divison_item)
                                    @php
                                        $day_activities = App\Models\ItineraryDetail::where('itinerary_id',$itinerary->id)->where('header','day_'.$key + 1)->where('field','day_activity')->get();
                                        $day_cabs = App\Models\ItineraryDetail::where('itinerary_id',$itinerary->id)->where('header','day_'.$key + 1)->where('field','day_cab')->get();
                                    @endphp
                                    <div class="itinerary-day px-3 py-2 rounded mb-3 d-flex justify-content-between align-items-center toggle-header"
                                        data-key="{{$key+1}}">
                                        <strong>Day {{ $key + 1 }} – {{ ucwords($divison_item['name']) }}</strong>
                                        <div class="d-flex gap-3 text-muted small cursor-pointer toggle-icon">
                                            <span class="summary-day-item">
                                                <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                                <span> {{count($day_activities)}}</span>
                                            </span>
                                            <span class="summary-day-item">
                                                <img class="day_logo" src="{{ asset('images/taxi.png') }}" alt="">
                                                <span> {{count($day_cabs)}}</span>
                                            </span>
                                            <span class="summary-day-item">
                                                <img class="day_logo" src="{{ asset('images/hotel.png') }}" alt="">
                                                <span>12</span>
                                            </span>
                                            <span class="summary-day-item">
                                                <i class="fa fa-chevron-down mt-1"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="day-details ps-4 mb-3 mx-4" id="day_details_{{$key+1}}"
                                        style="display: none;">
                                        @foreach ($day_activities as $activity_item)
                                            <div class="mb-2">
                                                <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                                <strong>Activity:</strong>{{$activity_item->value}}
                                            </div>
                                        @endforeach
                                        @foreach ($day_cabs as $day_cab)
                                            <div class="mb-2">
                                                <img class="day_logo" src="{{ asset('images/taxi.png') }}" alt="">
                                                <strong>Transfer:</strong>{{$day_cab->location_from}} To {{$day_cab->location_to}}({{$day_cab->value}})
                                            </div>
                                        @endforeach
                                        <div class="mb-2"><img class="day_logo" src="{{ asset('images/hotel.png') }}"
                                                alt=""> <strong>Hotel:</strong> Check-in at Deluxe Hotel in Rome
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if($active_tab=="activities")
                            <div class="mt-2">
                                <div class="itinerary-summary p-4">
                                    <h4 class="fw-bold mb-3">Trip Activities</h4>

                                    <div class="d-flex flex-wrap gap-4 mb-4 itinerary-summary-icons">
                                        <span class="summary-item">
                                            <img src="{{ asset('images/travel.png') }}" alt="Activities">
                                            <span>{{count($activities)}} Activities</span>
                                        </span>
                                    </div>

                                    @foreach ($DayDivisons as $key => $divison_item)
                                        @php
                                            $day_activities = App\Models\ItineraryDetail::where('itinerary_id',$itinerary->id)->where('header','day_'.$key + 1)->where('field','day_activity')->get();
                                        @endphp
                                        <div class="itinerary-day px-3 py-2 rounded mb-3 d-flex justify-content-between align-items-center toggle-header"
                                            data-key="{{$key+1}}">
                                            <strong>Day {{ $key + 1 }} – {{ ucwords($divison_item['name']) }}</strong>
                                            <div class="d-flex gap-3 text-muted small cursor-pointer toggle-icon">
                                                <span class="summary-day-item">
                                                    <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                                    <span id="activityCountWrapper_{{$key+1}}">{{count($day_activities)}}</span>
                                                </span>
                                                <span class="summary-day-item">
                                                    <i class="fa fa-chevron-down mt-1"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="day-details ps-4 mb-3 mx-4" id="day_details_{{$key+1}}"
                                            style="display: none;">
                                            <div class="row mb-1">
                                                <div class="col-12">
                                                    <div class="text-right">
                                                        <button type="button"
                                                            class="btn btn-outline-success mt-2 btn-sm" onclick="AddActivity({{ $key + 1 }})"><i class="fa fa-plus me-1"></i> ADD ACTIVITY</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div id="activityListWrapper_{{$key+1}}">
                                                @foreach ($day_activities as $activity_item)
                                                    <div class="mb-2 d-flex justify-content-between align-items-center" id="activity-item-{{$activity_item->id}}">
                                                        <div>
                                                            <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                                            <strong>Activity:</strong>{{$activity_item->value}}
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="deleteActivity({{$activity_item->id}})">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="activityModalLabel">Add New Activity - <span id="day_number"></span></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="new_itinerary_id" name="itinerary_id" value="{{$itinerary->id}}">
                                        <input type="hidden" id="new_itinerary_day" name="itinerary_day" value="">
                                        
                                        <!-- Add more form fields as needed -->
                                        <div class="mb-3">
                                        <label for="activity_name" class="form-label">Activity Name</label>
                                            <div id="activityNameWrapper">
                                                <!-- Input fields will be added here -->
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="btn btn-outline-success btn-sm mt-2" id="addActivityNameBtn">
                                                    <i class="fa fa-plus me-1"></i> Add More
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Submit button -->
                                        <div class="text-center">
                                            <button class="btn btn-primary" onclick="submitActivity()">Save Activity</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($active_tab=="transfer")
                            <div class="mt-2">
                                <div class="itinerary-summary p-4">
                                    <h4 class="fw-bold mb-3">Transfer(Cab)</h4>

                                    <div class="d-flex flex-wrap gap-4 mb-4 itinerary-summary-icons">
                                        <span class="summary-item">
                                            <img src="{{ asset('images/taxi.png') }}" alt="Cabs">
                                            <span>{{count($total_cabs)}} Cabs</span>
                                        </span>
                                    </div>

                                    @foreach ($DayDivisons as $key => $divison_item)
                                        @php
                                            $day_cabs = App\Models\ItineraryDetail::where('itinerary_id',$itinerary->id)->where('header','day_'.$key + 1)->where('field','day_cab')->get();
                                        @endphp
                                        <div class="itinerary-day px-3 py-2 rounded mb-3 d-flex justify-content-between align-items-center toggle-header"
                                            data-key="{{$key+1}}">
                                            <strong>Day {{ $key + 1 }} – {{ ucwords($divison_item['name']) }}</strong>
                                            <div class="d-flex gap-3 text-muted small cursor-pointer toggle-icon">
                                                <span class="summary-day-item">
                                                    <img class="day_logo" src="{{ asset('images/taxi.png') }}" alt="">
                                                    <span id="cabCountWrapper_{{$key+1}}">{{ count($day_cabs) }}</span>
                                                </span>
                                                <span class="summary-day-item">
                                                    <i class="fa fa-chevron-down mt-1"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="day-details ps-4 mb-3 mx-4" id="day_details_{{$key+1}}"
                                            style="display: none;">
                                            <div class="row mb-1">
                                                <div class="col-12">
                                                    <div class="text-right">
                                                        <button type="button"
                                                            class="btn btn-outline-success mt-2 btn-sm" onclick="AddCab({{ $key + 1 }},{{$divison_item['id']}})"><i class="fa fa-plus me-1"></i> ADD CAB</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div id="cabWrapper_{{$key+1}}">
                                                @foreach ($day_cabs as $cab_item)
                                                    <div class="mb-2 d-flex justify-content-between align-items-center" id="cab-item-{{$cab_item->id}}">
                                                        {{-- <div>
                                                            <img class="day_logo" src="{{ asset('images/taxi.png') }}" alt="">
                                                            <strong>Cab:</strong>{{$cab_item->value}} {{$cab_item->location_from}} To {{$cab_item->location_to}}
                                                        </div> --}}
                                                        <div class="cab-item d-flex align-items-center p-3 mb-2 rounded shadow-sm border bg-light">
                                                            <img src="{{ asset('images/taxi.png') }}" alt="Cab" class="me-3" style="width: 24px; height: 24px;">
                                                            <div class="flex-grow-1">
                                                                <div class="fw-bold text-primary mb-1"> Cab: {{ $cab_item->value }}</div>
                                                                <div class="small text-muted">From <strong>{{ $cab_item->location_from }}</strong> to <strong>{{ $cab_item->location_to }}</strong></div>
                                                            </div>
                                                        </div>

                                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="deleteCab({{$cab_item->id}})">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- For Cab --}}
                            <div class="modal fade" id="cabModal" tabindex="-1" aria-labelledby="cabModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cabModalLabel">Add New Cab - <span id="day_number_label"></span></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="new_itinerary_id" name="itinerary_id" value="{{$itinerary->id}}">
                                        <input type="hidden" id="new_itinerary_day" name="itinerary_day" value="">
                                        <input type="hidden" id="day_number" name="day_number" value="">
                                
                                        <!-- Add more form fields as needed -->
                                        <div class="mb-3">
                                            <label for="cab_id" class="form-label">Select Your Cab</label>
                                            <select id="cab_id" name="cab_id" class="form-control" required>
                                                <option value="">-- Select Cab --</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                             <label for="location_from" class="form-label">Location From</label>
                                             <input type="text" id="location_from" name="location_from" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                             <label for="location_to" class="form-label">Location To</label>
                                             <input type="text" id="location_to" name="location_to" class="form-control" required>
                                        </div>

                                        <!-- Submit button -->
                                        <div class="text-center">
                                            <button class="btn btn-primary" onclick="submitCab()">Save Cab</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                const fetchCabsRoute = "{{ route('admin.itinerary.fetch.cabs', ['division_id' => 'divisionId']) }}";
                            </script>
                        @endif
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
        let count = 0;

        function createHighlightInput(id) {
            return `
                <div class="input-group mb-2 itinerary-highlight-item" id="highlight-${id}">
                    <input type="text" name="trip_highlights[]" class="form-control text-sm" rows="2" placeholder="Enter highlight" onkeyup="saveHighlight(this,${id})">
                    <button type="button" class="btn btn-outline-danger remove-highlight ms-2 h-auto align-self-start" data-id="${id}">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;
        }


        $('#addHighlightBtn').on('click', function() {
            count++;
            $('#tripHighlightsWrapper').append(createHighlightInput(count));
        });

        $('#tripHighlightsWrapper').on('click', '.remove-highlight', function() {
            const id = $(this).data('id');
            $('#highlight-' + id).remove();
        });

        // Add one by default
        $('#addHighlightBtn').click();
        
    });

    $(document).ready(function() {
        $('.toggle-header').click(function() {
            var detailsId = '#day_details_' + $(this).data('key');
            $(detailsId).slideToggle();

            var icon = $(this).find('.fa');
            icon.toggleClass('fa-chevron-down fa-chevron-up');
        });
    });
    // Trip Sammarised

    function saveHighlight(element,sl) {
        const value = $(element).val().trim();
        const itineraryId = "{{$itinerary->id}}"; // Ensure you have this ID available

        if (value.length < 3) return; // Prevent excessive calls on small input

        $.ajax({
            url: "{{route('admin.itinerary.save-highlight')}}", // Adjust this route as needed
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                itinerary_id: itineraryId,
                highlight: value,
            },
            success: function (response) {
                if (response.status && response.highlight_id) {
                    const wrapper = $('#highlight-' + sl);
                    // Remove any existing button inside the div
                    wrapper.find('button').remove();

                    // Append the new delete button after the input
                    wrapper.find('input').after(`
                        <button type="button" class="btn btn-outline-danger ms-2 h-auto align-self-start" 
                            data-id="highlight_exist_trip_${response.highlight_id}" 
                            onclick="RemoveTripHighlight(${response.highlight_id})">
                            <i class="fa fa-trash"></i>
                        </button>
                    `);
                    console.log('Highlight saved/updated.');
                }
            },
            error: function (xhr) {
                console.error('Failed to save highlight', xhr.responseText);
            }
        });
    }
    function RemoveTripHighlight(id) {
        $.ajax({
            url: "{{route('admin.itinerary.delete-highlight')}}", // Adjust this route as needed
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
            },
            success: function (response) {
                if (response.status) {
                    $('#highlight_exist_trip_' + id).remove();
                } else {
                    toastFire('success', 'Failed to delete trip highlight');
                }
            },
            error: function (xhr) {
                console.error('Failed to delete highlight', xhr.responseText);
            }
        });
    }
    // Day Activity
    function AddActivity(dayNumber) {
        // Set values inside modal
        document.getElementById('activityNameWrapper').innerHTML = '';
        document.getElementById('new_itinerary_day').value = dayNumber;
        document.getElementById('day_number').innerText = `Day ${dayNumber}`;
        
        $('#addActivityNameBtn').click();
        // Show the modal
        const myModal = new bootstrap.Modal(document.getElementById('activityModal'));
        myModal.show();
    }

    let activityCount = 0;

    function createActivityInput(id) {
        return `
            <div class="activity-name-item mb-3" id="activity-${id}">
                <div class="input-group">
                    <input type="text" class="form-control" name="activity_name[]" placeholder="Enter activity name">
                    <button type="button" class="btn btn-outline-danger ms-2 remove-activity" data-id="${id}">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <span class="text-danger error-msg mt-1 d-block"></span>
            </div>
        `;
    }

    $('#addActivityNameBtn').on('click', function () {
        activityCount++;
        $('#activityNameWrapper').append(createActivityInput(activityCount));
    });

    // Remove input
    $('#activityNameWrapper').on('click', '.remove-activity', function () {
        const id = $(this).data('id');
        $('#activity-' + id).remove();
    });

    // Add one by default
    $('#addActivityNameBtn').click();

    function submitActivity() {
        let isValid = true;
        $('.error-msg').text(''); // Clear previous messages

        $('input[name="activity_name[]"]').each(function () {
            if ($(this).val().trim() === '') {
                isValid = false;
                $(this).closest('.activity-name-item').find('.error-msg').text('Please enter activity name');
            }
        });

    if (isValid) {
            // Collect data
            const dayNumber = $('#new_itinerary_day').val(); // Assuming you have this hidden input set
            const itinerary_id = $('#new_itinerary_id').val(); // Assuming you have this hidden input set
            const activityNames = $('input[name="activity_name[]"]').map(function () {
                return $(this).val();
            }).get();

            // AJAX request
            $.ajax({
                url: "{{route('admin.itinerary.activity.create')}}", // 🔁 Replace with your actual URL
                method: 'POST',
                data: {
                    day_number: dayNumber,
                    itinerary_id: itinerary_id,
                    activity_name: activityNames,
                    _token: $('meta[name="csrf-token"]').attr('content') // Laravel CSRF
                },
                success: function (response) {
                    // Handle success response
                console.log('Appending activities for day:', dayNumber);

                    const container = $('#activityListWrapper_' + dayNumber);
                    console.log('Container found:', container.length);

                    container.html(''); // clear previous

                    response.data.forEach(function (item) {

                        container.append(`
                            <div class="mb-2 d-flex justify-content-between align-items-center" id="activity-item-${item.id}">
                                <div>
                                    <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                    <strong>Activity:</strong> ${item.value}
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="deleteCab(${item.id})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        `);
                    });
                $('#activityCountWrapper_' + dayNumber).text(response.data.length);
                    $('#activityModal').modal('hide');
                    toastFire('success', 'Activities saved successfully');
                },
                error: function (xhr) {
                    // Handle error
                    toastFire('error', 'Something went wrong while saving activities.');
                    // alert('Something went wrong while saving activities.');
                    console.error(xhr.responseText);
                }
            });
        }
    }

    // function deleteActivity(activityId) {
    //     // if (!confirm('Are you sure you want to delete this activity?')) return;

    //     $.ajax({
    //         url: "{{route('admin.itinerary.activity.delete')}}", // adjust to your actual route
    //         type: 'POST',
    //         data: {
    //             id: activityId,
    //             _token: $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function (response) {
    //             if (response.status) {
    //                 $('#activity-item-' + activityId).remove();
    //             } else {
    //                 toastFire('success', 'Failed to delete activity');
    //             }
    //         },
    //         error: function () {
    //             toastFire('error', 'An error occurred. Please try again');
    //         }
    //     });
    // }

    function deleteActivity(activityId) {
        $.ajax({
            url: "{{ route('admin.itinerary.activity.delete') }}",
            type: 'POST',
            data: {
                id: activityId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status) {
                    $('#activity-item-' + activityId).remove();
                    toastFire('success', response.message || 'Activity deleted successfully!');
                } else {
                   toastFire('error', response.message || 'Failed to delete activity.');
                }
            },
            error: function () {
                toastFire('error', 'An error occurred. Please try again.');
            }
        });
    }


    // Day Hotel
    // Day Cab


    function AddCab(dayNumber, division_id) {
        //console.log("division_id:", division_id);
        $('#cab_id').empty().append('<option value="">Loading...</option>');
        $('#location_from').val('');
        $('#location_to').val('');
        document.getElementById('new_itinerary_day').value = dayNumber;
        document.getElementById('day_number').value = dayNumber;
        document.getElementById('day_number_label').textContent = dayNumber;

        const url = fetchCabsRoute.replace('divisionId', division_id);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#cab_id').empty().append('<option value="">-- Select Cab --</option>');
                    $.each(response.data, function(index, cab) {
                        $('#cab_id').append(`<option value="${cab.id}">${cab.name}</option>`);
                    });
                } else {
                    $('#cab_id').empty().append('<option value="">No cabs found</option>');
                }
            },
            error: function() {
                $('#cab_id').empty().append('<option value="">Error loading cabs</option>');
            }
        });

        const myModal = new bootstrap.Modal(document.getElementById('cabModal'));
        myModal.show();
    }

    function submitCab() {
        const itineraryId = $('#new_itinerary_id').val();
        const itineraryDay = $('#new_itinerary_day').val();
        const dayNumber = $('#day_number').val();
        const cabId = $('#cab_id').val();
        const cabName = $('#cab_id option:selected').text(); 
        const locationFrom = $('#location_from').val();
        const locationTo = $('#location_to').val();

        if (!cabId || !locationFrom || !locationTo) {
            alert('Please fill in all fields.');
            return;
        }

        $.ajax({
            url: "{{ route('admin.itinerary.store.cab') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                itinerary_id: itineraryId,
                itinerary_day: itineraryDay,
                day_number: dayNumber,
                cab_id: cabId,
                cab_name: cabName,
                location_from: locationFrom,
                location_to: locationTo
            },
            success: function(response) {
                if (response.success) {
                    // $('#cabModal').modal('hide');
                    // toastFire('success', 'Cab Added successfully!');

                    const container = $('#cabWrapper_' + dayNumber);
                    container.html(''); 

                    response.data.forEach(function(cab_item) {
                        container.append(`
                            <div class="mb-2 d-flex justify-content-between align-items-center" id="cab-item-${cab_item.id}">
                                <div class="cab-item d-flex align-items-center p-3 mb-2 rounded shadow-sm border bg-light">
                                    <img src="{{ asset('images/taxi.png') }}" alt="Cab" class="me-3" style="width: 24px; height: 24px;">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-primary mb-1">Cab: ${cab_item.value}</div>
                                        <div class="small text-muted">From <strong>${cab_item.location_from}</strong> to <strong>${cab_item.location_to}</strong></div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="deleteCab(${cab_item.id})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        `);
                    });

                    $('#cabCountWrapper_' + dayNumber).text(response.data.length);

                    $('#cabModal').modal('hide');
                    toastFire('success', 'Cab Added successfully!');
                    
                } else {
                    toastFire('error', response.message || 'Failed to add cab.');
                }
            },
            error: function(xhr) {
                toastFire('error', 'Failed to save cab. Please try again.');
                console.error(xhr.responseText);
            }
        });
    }


    function deleteCab(cabId)
    {
        
        $.ajax({
            url: "{{ route('admin.itinerary.cab.delete') }}", 
            type: 'POST',
            data: {
                id: cabId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status) {
                    $('#cab-item-' + cabId).remove();
                    toastFire('success', 'Cab deleted successfully!');
                } else {
                    toastFire('error', response.message || 'Failed to delete cab.');
                }
            },
            error: function () {
                toastFire('error', 'An error occurred. Please try again.');
            }
        });
    }

</script>
@endsection
