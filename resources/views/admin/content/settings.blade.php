@extends('admin.layout.app')
@section('page-title', 'Settings')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- {{dd($data)}} --}}
                    <div class="card-body">
                        <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">@csrf
                            <div class="form-group">
                                <label for="official_phone1">Official contact number 1 <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="official_phone1" id="official_phone1" value="{{ old('official_phone1') ? old('official_phone1') : $data[0]->content }}">
                                @error('official_phone1') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                            
                            <div class="form-group">
                                <label for="official_phone2">Official contact number 2</label>
                                <input type="text" class="form-control" name="official_phone2" id="official_phone2" value="{{ old('official_phone2') ? old('official_phone2') : $data[1]->content }}">
                                @error('official_phone2') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <label for="whatsapp_number">Whatsapp number</label>
                                <input type="text" class="form-control" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') ? old('whatsapp_number') : $data[9]->content }}">
                                @error('whatsapp_number') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="official_email">Official email <span style="color: red;">*</span></label>
                                <input type="email" class="form-control" name="official_email" id="official_email" value="{{ old('official_email') ? old('official_email') : $data[2]->content }}">
                                @error('official_email') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="official_email">Official email 2 </label>
                                <input type="email" class="form-control" name="official_email_2" id="official_email_2" value="{{ old('official_email_2') ? old('official_email_2') : $data[10]->content }}">
                                @error('official_email_2') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="website_link">Website <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="website_link" id="website_link" value="{{ old('website_link') ? old('website_link') : $data[8]->content }}">
                                @error('website_link') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="full_company_name">Full Company name <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="full_company_name" id="full_company_name" value="{{ old('full_company_name') ? old('full_company_name') : $data[3]->content }}">
                                @error('full_company_name') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="pretty_company_name">Pretty Company name <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="pretty_company_name" id="pretty_company_name" value="{{ old('pretty_company_name') ? old('pretty_company_name') : $data[4]->content }}">
                                @error('pretty_company_name') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="company_short_desc">Short Company description <span style="color: red;">*</span></label>
                                <textarea name="company_short_desc" id="company_short_desc" class="form-control" rows="4">{{ old('company_short_desc') ? old('company_short_desc') : $data[5]->content }}</textarea>
                                @error('company_short_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="company_full_address">Full Company address <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="company_full_address" id="company_full_address" value="{{ old('company_full_address') ? old('company_full_address') : $data[6]->content }}">
                                @error('company_full_address') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="google_map_address_link">Google map address link <span style="color: red;">*</span></label>
                                <textarea name="google_map_address_link" id="google_map_address_link" class="form-control" rows="4">{{ old('google_map_address_link') ? old('google_map_address_link') : $data[7]->content }}</textarea>
                                @error('google_map_address_link') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection