@extends('admin.layout.app')
@section('page-title', 'Itenaries list')
@section('section')


<section class="content">
	<div class="container">
		<div class="calendar-section">
		  <div class="row">
			<div class="row mb-3">
			</div>
  
			<div class="col-sm-12">
				<div class="col-md-12 text-right">
					<a href="" class="btn btn-primary"><i class="fa fa-plus"></i>Bulk Upload</a>
				</div>

				{{-- start date and end date --}}
				<div class="row mb-3">
					<div class="col-md-3 text-right">
						<label>Start Date</label>
						<input type="date" id="start_date" class="form-control">
					</div>
					<div class="col-md-3">
						<label>End Date</label>
						<input type="date" id="end_date" class="form-control">
					</div>
					<div class="col-md-3 mt-4">
						<button class="btn btn-success" id="filterCalendar">Show Calendar</button>
					</div>
				</div>
				
  
			  {{-- <div class="calendar calendar-first" id="calendar_first">
				<div class="calendar_header">
				  
				  <h2></h2>
				  <button class="switch-month switch-left">
					<i class="glyphicon glyphicon-chevron-left"></i>
				  </button>
				  <button class="switch-month switch-right">
					<i class="glyphicon glyphicon-chevron-right"></i>
				  </button>
				</div>
				<div class="calendar_weekdays"></div>
				<div class="calendar_content"></div>
			  </div> --}}

			  	<div class="calendar" id="calendar_first">
					<div class="calendar_header">
					<span class="switch-month left">&#9664;</span>
					<h2></h2>
					<span class="switch-month right">&#9654;</span>
					</div>
					<div class="calendar_weekdays"></div>
					<div class="calendar_content"></div>
				</div>  
  
			</div>
  
		  </div> 
			  
		</div> 
	  </div> 
</section>
@endsection

