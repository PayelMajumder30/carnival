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
                                            <!-- Input group will be appended here -->
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
                                            <span>8 Activities</span>
                                        </span>
                                        <span class="summary-item">
                                            <img src="{{ asset('images/taxi.png') }}" alt="Transfers">
                                            <span>12 Transfers</span>
                                        </span>
                                        <span class="summary-item">
                                            <img src="{{ asset('images/hotel.png') }}" alt="Hotels">
                                            <span>12 Hotels</span>
                                        </span>
                                    </div>

                                    @foreach ($DayDivisons as $key => $divison_item)
                                    <div class="itinerary-day px-3 py-2 rounded mb-3 d-flex justify-content-between align-items-center toggle-header"
                                        data-key="{{$key}}">
                                        <strong>Day {{ $key + 1 }} – {{ ucwords($divison_item['name']) }}</strong>
                                        <div class="d-flex gap-3 text-muted small cursor-pointer toggle-icon">
                                            <span class="summary-day-item">
                                                <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                                <span> 8</span>
                                            </span>
                                            <span class="summary-day-item">
                                                <img class="day_logo" src="{{ asset('images/taxi.png') }}" alt="">
                                                <span> 12</span>
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

                                    <div class="day-details ps-4 mb-3 mx-4" id="day_details_{{$key}}"
                                        style="display: none;">
                                        <div class="mb-2">
                                            <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                            <strong>Activity:</strong> Transfer from Rome State Airport to Deluxe Hotel in
                                            Rome</div>
                                        <div class="mb-2"><img class="day_logo" src="{{ asset('images/taxi.png') }}" alt="">
                                            <strong>Transfer:</strong> Transfer from Rome State Airport to Deluxe Hotel in
                                            Rome</div>
                                        <div class="mb-2"><img class="day_logo" src="{{ asset('images/hotel.png') }}"
                                                alt=""> <strong>Hotel:</strong> Check-in at Deluxe Hotel in Rome</div>
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
                                            <span>8 Activities</span>
                                        </span>
                                    </div>

                                    @foreach ($DayDivisons as $key => $divison_item)
                                        <div class="itinerary-day px-3 py-2 rounded mb-3 d-flex justify-content-between align-items-center toggle-header"
                                            data-key="{{$key}}">
                                            <strong>Day {{ $key + 1 }} – {{ ucwords($divison_item['name']) }}</strong>
                                            <div class="d-flex gap-3 text-muted small cursor-pointer toggle-icon">
                                                <span class="summary-day-item">
                                                    <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                                    <span> 8</span>
                                                </span>
                                                <span class="summary-day-item">
                                                    <i class="fa fa-chevron-down mt-1"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="day-details ps-4 mb-3 mx-4" id="day_details_{{$key}}"
                                            style="display: none;">
                                            <div class="row mb-1">
                                                <div class="col-12">
                                                    <div class="text-right">
                                                        <button type="button" id="addHighlightBtn"
                                                            class="btn btn-outline-success mt-2 btn-sm"><i class="fa fa-plus me-1"></i> ADD ACTIVITY</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                                <strong>Activity:</strong> Transfer from Rome State Airport to Deluxe Hotel in
                                                Rome</div>
                                            <div class="mb-2">
                                                <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                                <strong>Activity:</strong> Transfer from Rome State Airport to Deluxe Hotel in
                                                Rome</div>
                                            <div class="mb-2">
                                                <img class="day_logo" src="{{ asset('images/travel.png') }}" alt="">
                                                <strong>Activity:</strong> Transfer from Rome State Airport to Deluxe Hotel in
                                                Rome</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
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
                    <input type="text" name="trip_highlights[]" class="form-control text-sm" rows="2" placeholder="Enter highlight">
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
</script>
@endsection
