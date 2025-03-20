@extends('admin.layout.app')
@section('page-title', 'Edit Page Details')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.page_content.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.page_content.update') }}" method="post" enctype="multipart/form-data">@csrf
                            <div class="form-group">
                                <h5><strong class="text-danger">{{ strtoupper($data->page) }}</strong></h5>
                            </div>
                            @if($data->id!=1)

                            @if ($data->custom_field==1)
                            <div class="form-group">
                                <input type="hidden" name="custom_field"  id="custom_field" />
                                <label for="">New Page Name</label>
                                <input type="text" class="form-control" name="page" id="page"  placeholder="Enter new page name"   value="{{$data->page}}">
                            </div>
                            <div class="form-group">
                                <label for="">New Page Location*</label>
                                <select name="location" id="location" class="form-control">
                                    <option selected hidden>--Select Options--</option>
                                    <option value="header" {{$data->location == 'header'? "selected":""}} >Header</option>
                                    <option value="footer" {{$data->location == 'footer'? "selected":""}}>Footer</option> 
                                </select>
                            </div>
                            @endif
                               
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <input type="text" class="form-control" name="tilte" value="{{$data->tilte}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea name="desc" id="desc" class="form-control" placeholder="Enter description" >{{ old('desc') ? old('desc') : $data->desc }}</textarea>
                                </div>

                                @if ( $data->page=="About us")
                                <div class="form-group">
                                    <label for="">About Videos*</label>
                                   
                                    <input type="text" name="about_videos" id="about_videos" class="form-control"  value="{{$data->about_videos}}">
                                </div>

                                <div class="form-group">
                                    <label for="">Founder's Message</label>
                                    <textarea name="about_founder_msg" id="about_founder_msg" class="form-control" placeholder="Enter Founder's Message" >{{ old('about_founder_msg') ? old('about_founder_msg') : $data->about_founder_msg }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="">Principal's Message</label>
                                    <textarea name="about_principal_msg" id="about_principal_msg" class="form-control" placeholder="Enter Principal's Message" >{{ old('about_principal_msg') ? old('about_principal_msg') : $data->about_principal_msg }}</textarea>
                                </div>
                                <input type="hidden" name="old_vdo_path" value="{{$data->about_videos}}">
                                @endif
                              

                           @endif
                            {{-- HomePage --}}
                            @if($data->id==1)
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for=""><strong>CURRICULUM</strong></label>
                                        <p for="curriculum_image">Image</p>
                                        <div class="form-group">
                                            <input type="file" name="curriculum_image" value="{{$data->curriculum_image}}" accept="image/*" >
                                            <img src="{{$data->curriculum_image?asset($data->curriculum_image):asset('backend-assets/images/placeholder.jpg')}}" alt="" width="25%">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <textarea name="curriculum_desc" id="curriculum_desc" class="form-control" placeholder="Enter description" >{{ old('curriculum_desc') ? old('curriculum_desc') : $data->curriculum_desc }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for=""><strong>BEYOND ACADEMICS</strong></label>
                                        <p for="beyond_image">Image</p>
                                        <div class="form-group">
                                            <input type="file" name="beyond_image" value="{{$data->beyond_image}}" accept="image/*" >
                                            <img src="{{$data->beyond_image?asset($data->beyond_image):asset('backend-assets/images/placeholder.jpg')}}" alt="" width="25%">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <textarea name="beyond_desc" id="beyond_desc" class="form-control" placeholder="Enter description" >{{ old('beyond_desc') ? old('beyond_desc') : $data->beyond_desc }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for=""><strong>ADMISSIONS</strong></label>
                                        <p for="admission_image">Image</p>
                                        <div class="form-group">
                                            <input type="file" name="admission_image" value="{{$data->admission_image}}" accept="image/*" >
                                            <img src="{{$data->admission_image?asset($data->admission_image):asset('backend-assets/images/placeholder.jpg')}}" alt="" width="25%">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <textarea name="admission_desc" id="admission_desc" class="form-control" placeholder="Enter description" >{{ old('admission_desc') ? old('admission_desc') : $data->admission_desc }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <label for=""><strong>School Walkthrough</strong></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <textarea name="walkthrough_desc" id="walkthrough_desc" class="form-control" placeholder="Enter description" >{{ old('walkthrough_desc') ? old('walkthrough_desc') : $data->walkthrough_desc }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Video</label>
                                            <input type="text" class="form-control" name="walkthrough_video" value="{{$data->walkthrough_video}}" placeholder="Enter video path here" >
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <label for=""><strong>SCHOOL CORE VALUES</strong></label>
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea name="core_value_desc" id="core_value_desc" class="form-control" placeholder="Enter description" >{{ old('core_value_desc') ? old('core_value_desc') : $data->core_value_desc }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Image 1</label>
                                            <input type="file" class="form-control" name="core_value_image_1" accept="image/*" >
                                            <img src="{{$data->core_value_image_1?asset($data->core_value_image_1):asset('backend-assets/images/placeholder.jpg')}}" alt="" width="25%" style="background-color:#293b8c;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Title 1</label>
                                            <input type="text" class="form-control" name="core_value_title_1" value="{{$data->core_value_title_1}}" placeholder="Enter title here">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Image 2</label>
                                            <input type="file" class="form-control" name="core_value_image_2" accept="image/*" >
                                            <img src="{{$data->core_value_image_2?asset($data->core_value_image_2):asset('backend-assets/images/placeholder.jpg')}}" alt="" width="25%" style="background-color:#293b8c;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Title 2</label>
                                            <input type="text" class="form-control" name="core_value_title_2" value="{{$data->core_value_title_2}}" placeholder="Enter title here">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Image 3</label>
                                            <input type="file" class="form-control" name="core_value_image_3" accept="image/*" >
                                            <img src="{{$data->core_value_image_3?asset($data->core_value_image_3):asset('backend-assets/images/placeholder.jpg')}}" alt="" width="25%" style="background-color:#293b8c;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Title 3</label>
                                            <input type="text" class="form-control" name="core_value_title_3" value="{{$data->core_value_title_3}}" placeholder="Enter title here">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <label for=""><strong>Founder’s Perspective</strong></label>
                                <div class="form-group">
                                    <textarea name="founder_perspective" id="founder_perspective" class="form-control" placeholder="Enter description" >{{ old('founder_perspective') ? old('founder_perspective') : $data->founder_perspective }}</textarea>
                                </div>
                                <hr>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label for=""><strong>Teacher-Student mentorship</strong></label>
                                        <input type="text" name="teacher_student_mentorship" class="form-control"  value="{{$data->teacher_student_mentorship}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for=""><strong>Staff Only</strong></label>
                                        <input type="text" name="staff_only" class="form-control" value="{{$data->staff_only}}"  >
                                    </div>
                                    <div class="col-md-3">
                                        <label for=""><strong>Board curriculum</strong></label>
                                        <input type="text" name="board_curriculum" class="form-control" value="{{$data->board_curriculum}}" >
                                    </div>
                                    <div class="col-md-3">
                                        <label for=""><strong>A pioneer in Eastern India</strong></label>
                                        <input type="text" name="a_pioneer_in_eastern_india" value="{{$data->a_pioneer_in_eastern_india}}" class="form-control" >
                                    </div>
                                    <div class="col-md-3">
                                        <label for=""><strong>Area of School</strong></label>
                                        <input type="text" name="area_of_school" value="{{$data->area_of_school}}" class="form-control" >
                                    </div>
                                </div>
                            @endif
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <button type="submit" class="btn btn-primary">Update</button>
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
        checkCatParentLevel();
        
        CKEDITOR.replace('desc');
        CKEDITOR.replace('curriculum_desc');
        CKEDITOR.replace('beyond_desc');
        CKEDITOR.replace('admission_desc');
        CKEDITOR.replace('walkthrough_desc');
        CKEDITOR.replace('core_value_desc');
    
   



     
    </script>
@endsection