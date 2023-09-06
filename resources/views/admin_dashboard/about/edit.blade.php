
@extends("admin_dashboard.layouts.app")

@section("style")
    <script src="https://cdn.tiny.cloud/1/nhtc4hkvw9rxs4ivm25pg3brruxcsjsaknsuggv71arm406g/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endsection

@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">About</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">About Page</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        
        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">Edit About Page</h5>
                <hr/>

                <form action="{{ route('admin.setting.update') }}" method='post' enctype='multipart/form-data'>
                    @csrf

                    <div class="form-body mt-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="border border-3 p-4 rounded">

                                    <div class="mb-3">
                                        <label for="about_first_text" class="form-label">"Who are we" text</label>
                                        <textarea name='about_first_text' class="form-control" id="about_first_text">{{ old("about_first_text", $setting->about_first_text) }}</textarea>
                                    
                                        @error('about_first_text')
                                            <p class='text-danger'>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="about_second_text" class="form-label">Second text</label>
                                        <textarea name='about_second_text' class="form-control" id="about_second_text">{{ old("about_second_text", $setting->about_second_text) }}</textarea>
                                    
                                        @error('about_second_text')
                                            <p class='text-danger'>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <div class="mb-3">
                                                <label for="input_image1" class="form-label">First Image (Max 540 x 340)</label>
                                                <input name='about_first_image' type='file' class="form-control" id="input_image1">
                                            
                                                @error('about_first_image')
                                                    <p class='text-danger'>{{ $message }}</p>
                                                @enderror
                                            </div>
                                        
                                            <hr>
                                            <div class='user-image'>
                                                <img class='img-fluid img-thumbnail' src="{{ asset('storage/' . $setting->about_first_image) }}">
                                            </div>
                                        </div>

                                        <div class='col-md-6'>
                                            <div class="mb-3">
                                                <label for="input_image2" class="form-label">Second Image (Max 540 x 340)</label>
                                                <input name='about_second_image' type='file' class="form-control" id="input_image2">
                                            
                                                @error('about_second_image')
                                                    <p class='text-danger'>{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <hr>
                                            <div class='user-image'>
                                                <img class='img-fluid img-thumbnail' src="{{ asset('storage/' . $setting->about_second_image) }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="about_our_mission" class="form-label">Our Mission</label>
                                        <textarea id="about_our_mission" name="about_our_mission" class="form-control" rows="3">{{ old("about_our_mission", $setting->about_our_mission) }}</textarea>   
                                    
                                        @error('about_our_mission')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="about_our_vision" class="form-label">Our Vision</label>
                                        <textarea id="about_our_vision" name="about_our_vision" class="form-control" rows="3">{{ old("about_our_vision", $setting->about_our_vision) }}</textarea>   
                                    
                                        @error('about_our_vision')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="about_services" class="form-label">Services</label>
                                        <textarea id="about_services" name="about_services" class="form-control" rows="3">{{ old("about_services", $setting->about_services) }}</textarea>   
                                    
                                        @error('about_services')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <button class='btn btn-primary' type='submit'>Update</button>
                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>

            </div>
        </div>


    </div>
</div>
<!--end page wrapper -->
@endsection

@section("script")

<script>
$(document).ready(function () {

    // Tiny MCE
    let initTinyMCE = (id) => {
        tinymce.init({
            selector: 'textarea#' + id,
            plugins: 'advlist autolink lists link charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            height: '300',

            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code | rtl ltr',
            toolbar_mode: 'floating',
        });       
    }
    
    initTinyMCE('about_our_mission');
    initTinyMCE('about_our_vision');
    initTinyMCE('about_services');

    setTimeout(() => {
        $(".general-message").fadeOut();
    }, 5000);

});
</script>
@endsection