@extends('admin.layout.app')
@section('page-title', 'Contact')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('front.contact.index') }}" class="btn btn-sm btn-primary" target="_blank"> View public page <i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.content.contact.update') }}" method="post" enctype="multipart/form-data">@csrf
                            <div class="form-group row">
                                <div class="col-md-6" style="margin-top: 50px">
                                    <label for="page_title">Page Title *</label>
                                    <input type="text" class="form-control" name="page_title" id="page_title" placeholder="Enter page title" value="{{ old('page_title') ? old('page_title') : $data->page_title }}">
                                    @error('page_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>

                                <div class="col-md-6">
                                    @if (!empty($data->page_banner))
                                        @if (!empty($data->page_banner) && file_exists(public_path($data->page_banner)))
                                            <img src="{{ asset($data->page_banner) }}" alt="category-img" class="img-thumbnail mr-3" style="height: 50px">
                                        @else
                                            <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="category-image" style="height: 50px" class="mr-2">
                                        @endif
                                        <br>
                                    @endif

                                    <label for="page_banner">Banner</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="page_banner" id="page_banner">
                                            <label class="custom-file-label" for="page_banner">Choose file</label>
                                        </div>
                                    </div>
                                    <p class="small text-muted">Size: less than 1 mb | Extension: .webp for better SEO</p>
                                    @error('page_banner') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <hr>
                            <div class="card">
                                <div class="card-header">
                                    <p class="text-primary mb-0 font-weight-bold">REGISTERED OFFICE :</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="registerd_office_title">Registered office title*</label>
                                        <input type="text" class="form-control" name="registerd_office_title" id="registerd_office_title" placeholder="Enter office title" value="{{ old('registerd_office_title') ? old('registerd_office_title') : $data->registerd_office_title }}">
                                        @error('registerd_office_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
        
                                    <div class="form-group">
                                        <label for="registerd_office_address">Registered office address*</label>
                                        <textarea name="registerd_office_address" cols="30" rows="2" class="form-control">{{ old('registerd_office_address') ? old('registerd_office_address') : $data->registerd_office_address }}
                                        </textarea>
                                        @error('registerd_office_address') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
        
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="registerd_office_tell">Registered Office Telephone *</label>
                                                <input type="text" class="form-control" name="registerd_office_tell" id="registerd_office_tell" placeholder="Enter office telephone" value="{{ old('registerd_office_tell') ? old('registerd_office_tell') : $data->registerd_office_tell }}">
                                                @error('registerd_office_tell') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="registerd_office_fax">Registered Office Fax *</label>
                                                <input type="text" class="form-control" name="registerd_office_fax" id="registerd_office_fax" placeholder="Enter office fax" value="{{ old('registerd_office_fax') ? old('registerd_office_fax') : $data->registerd_office_fax }}">
                                                @error('registerd_office_fax') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="registerd_office_email">Registered Office Email *</label>
                                                <input type="text" class="form-control" name="registerd_office_email" id="registerd_office_email" placeholder="Enter office email" value="{{ old('registerd_office_email') ? old('registerd_office_email') : $data->registerd_office_email }}">
                                                @error('registerd_office_email') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registerd_office_map">Registered office map link*</label>
                                        <input type="text" class="form-control" name="registerd_office_map" id="registerd_office_map" placeholder="Enter office map" value="{{ old('registerd_office_map') ? old('registerd_office_map') : $data->registerd_office_map }}">
                                        @error('registerd_office_map') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <p class="text-primary mb-0 font-weight-bold">CORPORATE OFFICE :</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="corporate_office_title">Corporate office title*</label>
                                        <input type="text" class="form-control" name="corporate_office_title" id="corporate_office_title" placeholder="Enter office title" value="{{ old('corporate_office_title') ? old('corporate_office_title') : $data->corporate_office_title }}">
                                        @error('corporate_office_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
        
                                    <div class="form-group">
                                        <label for="corporate_office_address">Corporate office address*</label>
                                        <textarea name="corporate_office_address" cols="30" rows="2" class="form-control">{{ old('corporate_office_address') ? old('corporate_office_address') : $data->corporate_office_address }}
                                        </textarea>
                                        @error('corporate_office_address') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="corporate_office_tell">Corporate Office Telephone *</label>
                                                <input type="text" class="form-control" name="corporate_office_tell" id="corporate_office_tell" placeholder="Enter office telephone" value="{{ old('corporate_office_tell') ? old('corporate_office_tell') : $data->corporate_office_tell }}">
                                                @error('corporate_office_tell') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="corporate_office_fax">Corporate Office Fax *</label>
                                                <input type="text" class="form-control" name="corporate_office_fax" id="corporate_office_fax" placeholder="Enter office fax" value="{{ old('corporate_office_fax') ? old('corporate_office_fax') : $data->corporate_office_fax }}">
                                                @error('corporate_office_fax') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="corporate_office_email">Corporate Office Email *</label>
                                                <input type="text" class="form-control" name="corporate_office_email" id="corporate_office_email" placeholder="Enter office email" value="{{ old('corporate_office_email') ? old('corporate_office_email') : $data->corporate_office_email }}">
                                                @error('corporate_office_email') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="corporate_office_map">Corporate office map link*</label>
                                        <input type="text" class="form-control" name="corporate_office_map" id="corporate_office_map" placeholder="Enter office map" value="{{ old('corporate_office_map') ? old('corporate_office_map') : $data->corporate_office_map }}">
                                        @error('corporate_office_map') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <p class="text-primary mb-0 font-weight-bold">PLANT 1:</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="plant_title">Plant title*</label>
                                        <input type="text" class="form-control" name="plant_title" id="plant_title" placeholder="Enter plant title" value="{{ old('plant_title') ? old('plant_title') : $data->plant_title }}">
                                        @error('plant_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="plant_address">Plant address*</label>
                                        <textarea name="plant_address" cols="30" rows="2" class="form-control">{{ old('plant_address') ? old('plant_address') : $data->plant_address }}
                                        </textarea>
                                        @error('plant_address') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="plant_tell">Plant Telephone *</label>
                                                <input type="text" class="form-control" name="plant_tell" id="plant_tell" placeholder="Enter office telephone" value="{{ old('plant_tell') ? old('plant_tell') : $data->plant_tell }}">
                                                @error('plant_tell') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="plant_fax">Plant Fax *</label>
                                                <input type="text" class="form-control" name="plant_fax" id="plant_fax" placeholder="Enter fax" value="{{ old('plant_fax') ? old('plant_fax') : $data->plant_fax }}">
                                                @error('plant_fax') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="plant_email">Plant Email *</label>
                                                <input type="text" class="form-control" name="plant_email" id="plant_email" placeholder="Enter office email" value="{{ old('plant_email') ? old('plant_email') : $data->plant_email }}">
                                                @error('plant_email') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="plant_map">Plant map link*</label>
                                        <input type="text" class="form-control" name="plant_map" id="plant_map" placeholder="Enter office map" value="{{ old('plant_map') ? old('plant_map') : $data->plant_map }}">
                                        @error('plant_map') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <p class="text-primary mb-0 font-weight-bold">PLANT 2:</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="plant_title1">Plant title*</label>
                                        <input type="text" class="form-control" name="plant_title1" id="plant_title1" placeholder="Enter plant title" value="{{ old('plant_title1') ? old('plant_title1') : $data->plant_title1 }}">
                                        @error('plant_title1') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="plant_address1">Plant address*</label>
                                        <textarea name="plant_address1" cols="30" rows="2" class="form-control">{{ old('plant_address1') ? old('plant_address1') : $data->plant_address1 }}
                                        </textarea>
                                        @error('plant_address1') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="plant_tell1">Plant Telephone *</label>
                                                <input type="text" class="form-control" name="plant_tell1" id="plant_tell1" placeholder="Enter office telephone" value="{{ old('plant_tell1') ? old('plant_tell1') : $data->plant_tell1 }}">
                                                @error('plant_tell1') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="plant_fax1">Plant Fax *</label>
                                                <input type="text" class="form-control" name="plant_fax1" id="plant_fax1" placeholder="Enter fax" value="{{ old('plant_fax1') ? old('plant_fax1') : $data->plant_fax1 }}">
                                                @error('plant_fax1') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="plant_email1">Plant Email *</label>
                                                <input type="text" class="form-control" name="plant_email1" id="plant_email1" placeholder="Enter office email" value="{{ old('plant_email1') ? old('plant_email1') : $data->plant_email1 }}">
                                                @error('plant_email1') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="plant_map1">Plant map link*</label>
                                        <input type="text" class="form-control" name="plant_map1" id="plant_map1" placeholder="Enter office map" value="{{ old('plant_map1') ? old('plant_map1') : $data->plant_map1 }}">
                                        @error('plant_map1') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="form_title">Enquiry form title*</label>
                                <input type="text" class="form-control" name="form_title" id="form_title" placeholder="Enter form title" value="{{ old('form_title') ? old('form_title') : $data->form_title }}">
                                @error('form_title') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="form_submit_btn_text">Enquiry form button text*</label>
                                <input type="text" class="form-control" name="form_submit_btn_text" id="form_submit_btn_text" placeholder="Enter button text" value="{{ old('form_submit_btn_text') ? old('form_submit_btn_text') : $data->form_submit_btn_text }}">
                                @error('form_submit_btn_text') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="form_desc">Enquiry form description*</label>
                                <textarea name="form_desc" cols="30" rows="2" class="form-control">{{ old('form_desc') ? old('form_desc') : $data->form_desc }}
                                </textarea>
                                @error('form_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
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