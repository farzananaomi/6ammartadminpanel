@extends('layouts.vendor.app')
@section('title',translate('messages.Employee Edit'))

@section('content')
<div class="content container-fluid">
    <!-- Page Heading -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/edit.png')}}" class="w--26" alt="">
            </span>
            <span>
                {{translate('messages.Employee_update')}}
            </span>
        </h1>
    </div>
    <!-- Content Row -->
    <form action="{{route('vendor.employee.update',[$e['id']])}}" method="post" enctype="multipart/form-data" class="js-validate">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="card-header-icon"><i class="tio-user"></i></span>
                    <span>{{translate('messages.general_information')}}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label " for="f_name">{{translate('messages.first_name')}}</label>
                            <input type="text" name="f_name" value="{{$e['f_name']}}" class="form-control" id="f_name"
                                    placeholder="{{translate('messages.first_name')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="input-label " for="l_name">{{translate('messages.last_name')}}</label>
                            <input type="text" name="l_name" value="{{$e['l_name']}}" class="form-control" id="l_name"
                                    placeholder="{{translate('messages.last_name')}}">
                        </div>
                        <div class="form-group">
                            <label class="input-label " for="phone">{{translate('messages.phone')}}</label>
                            <input type="tel"
                                   value="{{$e['phone']}}"
                                   required
                                   name="phone"
                                   class="form-control"
                                   id="phone"
                                   pattern="^\+?[1-9]\d{1,14}$"
                                   title="{{ translate('messages.please_enter_valid_phone_number_with_country_code') }}"
                                   placeholder="{{ translate('messages.Ex:') }} +88017********">
                        </div>
                        <div class="form-group mb-0">
                            <label class="input-label " for="role_id">{{translate('messages.Role')}}</label>
                            <select class="form-control w-100" id="role_id" name="role_id">
                                    <option value="" selected disabled>{{translate('messages.select_Role')}}</option>
                                    @foreach($rls as $r)
                                        <option
                                            value="{{$r->id}}" {{$r['id']==$e['employee_role_id']?'selected':''}}>{{$r->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="form-label text-center mb-3">
                                    {{translate('messages.employee_image')}}
                                    <span class="text-danger">{{translate('messages.Ratio (1:1)')}}</span>
                                </h5>

                                <div class="text-center mb-auto">
                                    <img class="store-banner onerror-image" id="viewer"
                                         data-onerror-image="{{asset('public/assets/admin/img/160x160/img1.jpg')}}"
                                         src="{{ $e->image_full_url }}"
                                         alt="Employee thumbnail"/>
                                </div>

                                <div class="form-group mt-3 mb-0">
                                    <label class="form-label">{{translate('messages.Employee image size max 2 MB')}} <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input read-url"
                                            accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">{{translate('messages.choose_file')}}</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <span class="card-header-icon"><i class="tio-user"></i></span>
                                    <span>{{translate('messages.account_information')}}</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="input-label " for="email">{{translate('messages.email')}}</label>
                                        <input type="email" value="{{$e['email']}}" name="email" class="form-control" id="email"
                                                placeholder="{{ translate('messages.Ex:') }} ex@gmail.com">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="js-form-message form-group mb-0">
                                            <label class="input-label" for="signupSrPassword">{{translate('messages.password')}}<span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
        data-original-title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span></label>

                                            <div class="input-group input-group-merge">
                                                <input type="password" class="js-toggle-password form-control" name="password" id="signupSrPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                aria-label="8+ characters required"
                                                data-msg="Your password is invalid. Please try again."
                                                data-hs-toggle-password-options='{
                                                "target": [".js-toggle-password-target-1"],
                                                "defaultClass": "tio-hidden-outlined",
                                                "showClass": "tio-visible-outlined",
                                                "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                                }'>
                                                <div class="js-toggle-password-target-1 input-group-append">
                                                    <a class="input-group-text" href="javascript:">
                                                        <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="js-form-message form-group mb-0">
                                            <label class="input-label" for="signupSrConfirmPassword">{{translate('messages.confirm_password')}}</label>
                                            <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control" name="confirmPassword" id="signupSrConfirmPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                            placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                            aria-label="8+ characters required"
                                                    data-msg="Password does not match the confirm password."
                                                    data-hs-toggle-password-options='{
                                                    "target": [".js-toggle-password-target-2"],
                                                    "defaultClass": "tio-hidden-outlined",
                                                    "showClass": "tio-visible-outlined",
                                                    "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                    }'>
                                                <div class="js-toggle-password-target-2 input-group-append">
                                                    <a class="input-group-text" href="javascript:">
                                                    <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Copy Of Password -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="btn--container justify-content-end">
                            <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script_2')
<script>
    "use strict";

    // Reset button functionality
    $('#reset_btn').click(function(){
        $('#viewer').attr('src','{{asset('storage/app/public/vendor')}}/{{$e['image']}}');
    });

    // Image preview functionality
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#viewer').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileUpload").change(function() {
        readURL(this);
    });
</script>
@endpush
