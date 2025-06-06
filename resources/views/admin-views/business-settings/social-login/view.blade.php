@extends('layouts.admin.app')

@section('title', translate('Social Login Setup'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/captcha.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('Social Login Setup')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
        </div>
        <!-- End Page Header -->

        <div class="row g-3">
            @if (isset($socialLoginServices))
            @foreach ($socialLoginServices as $socialLoginService)
                    <div class="col-md-6">
                        <form
                        action="{{route('admin.social-login.update',[$socialLoginService['login_medium']])}}"
                        method="post">
                        @csrf
                        <div class="card">
                            <div class="card-header card-header-shadow">
                                <h5 class="card-title align-items-center">
                                    <img src="{{asset('/public/assets/admin/img')}}/{{$socialLoginService['login_medium']}}.png" class="mr-1 w-20" alt="">
                                    {{translate('messages.'.$socialLoginService['login_medium'])}}
                                </h5>
                                <label class="toggle-switch toggle-switch-sm p-0">
                                    <span class="d-flex align-items-center switch--label">
                                        <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip" data-placement="right" data-original-title="Lorem ipsum dolor set amet"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="Veg/non-veg toggle"> * </span>
                                    </span>
                                    <input id="{{$socialLoginService['login_medium']}}_status"

                                           data-id="{{$socialLoginService['login_medium']}}_status"
                                           data-type="toggle"
                                           data-image-on="{{asset('/public/assets/admin/img/modal')}}/{{$socialLoginService['login_medium']}}-on.png"
                                           data-image-off="{{asset('/public/assets/admin/img/modal')}}/{{$socialLoginService['login_medium']}}-off.png"
                                           data-title-on="{{translate('messages.'.$socialLoginService['login_medium'])}} {{translate('Login Turned ON ')}}"
                                           data-title-off="{{translate('messages.'.$socialLoginService['login_medium'])}} {{translate('Login Turned OFF ')}}"
                                           data-text-on="<p>{{translate('messages.'.$socialLoginService['login_medium'])}} {{translate('Login is now enabled. Customers will be able to sign up or log in using their social media accounts.')}}</p>"
                                           data-text-off="<p>{{translate('messages.'.$socialLoginService['login_medium'])}} {{translate('Login is now disabled. Customers will not be able to sign up or log in using their social media accounts. Please note that this may affect user experience and registration/login process.')}}</p>"
                                           class="status toggle-switch-input dynamic-checkbox-toggle"


                                           type="checkbox" name="status" value="1" {{$socialLoginService['status']==1?'checked' :''}}>
                                    <span class="toggle-switch-label text p-0">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    <div class="text--primary-2 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#{{$socialLoginService['login_medium']}}-modal">
                                        <strong class="mr-2 text--underline">{{translate('Credential Setup')}}</strong>
                                        <div class="blinkings">
                                            <i class="tio-info-outined"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{translate('messages.callback_uri')}}</label>
                                    <div class="position-relative">
                                        <span class="btn-right-fixed copy-to-clipboard" data-id="#id_{{$socialLoginService['login_medium']}}"><i class="tio-copy"></i></span>
                                        <span class="form-control h-unset" id="id_{{$socialLoginService['login_medium']}}">{{ url('/') }}/customer/auth/login/{{$socialLoginService['login_medium']}}/callback</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="client_id" class="form-label">{{translate('messages.client_id')}}</label>
                                    <input id="client_id" type="text" class="form-control" name="client_id" value="{{ $socialLoginService['client_id'] }}">
                                </div>
                                <div class="form-group">
                                    <label for="client_secret"
                                        class="form-label">{{translate('messages.client_secret')}}</label>
                                    <input id="client_secret" type="text" class="form-control" name="client_secret"
                                            value="{{ $socialLoginService['client_secret'] }}">
                                </div>
                                <div class="btn--container justify-content-end">
                                    <button type="reset" class="btn btn--reset mb-2">{{translate('Reset')}}</button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary mb-2">{{translate('messages.save')}}</button>
                                </div>
                                </div>
                            </div>
                        </form>
                    </div>
            @endforeach
            @endif
            @if (isset($appleLoginServices))
            @foreach ($appleLoginServices as $appleLoginService)
                    <div class="col-md-6">
                        <div class="card">
                            <form
                            action="{{route('admin.apple-login.update',[$appleLoginService['login_medium']])}}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                                <div class="card-header card-header-shadow">
                                    <h5 class="card-title align-items-center">
                                        <img src="{{asset('/public/assets/admin/img/apple.png')}}" class="mr-1 w--20" alt="">
                                        {{translate('messages.'.$appleLoginService['login_medium'])}}
                                    </h5>
                                    <label class="toggle-switch toggle-switch-sm p-0">
                                        <span class="d-flex align-items-center switch--label">
                                            <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip" data-placement="right" data-original-title="Lorem ipsum dolor set amet"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="Veg/non-veg toggle"> * </span>
                                        </span>
                                        <input  id="{{$appleLoginService['login_medium']}}_status"
                                               data-id="{{$appleLoginService['login_medium']}}_status"
                                               data-type="toggle"
                                               data-image-on="{{asset('/public/assets/admin/img/modal')}}/{{$appleLoginService['login_medium']}}-on.png"
                                               data-image-off="{{asset('/public/assets/admin/img/modal')}}/{{$appleLoginService['login_medium']}}-off.png"
                                               data-title-on="{{translate('messages.'.$appleLoginService['login_medium'])}} {{translate('Login Turned ON ')}}"
                                               data-title-off="{{translate('messages.'.$appleLoginService['login_medium'])}} {{translate('Login Turned OFF ')}}"
                                               data-text-on="<p>{{translate('messages.'.$appleLoginService['login_medium'])}} {{translate('Login is now enabled. Customers will be able to sign up or log in using their social media accounts.')}}</p>"
                                               data-text-off="<p>{{translate('messages.'.$appleLoginService['login_medium'])}} {{translate('Login is now disabled. Customers will not be able to sign up or log in using their social media accounts. Please note that this may affect user experience and registration/login process.')}}</p>"
                                               class="status toggle-switch-input dynamic-checkbox-toggle"


                                               type="checkbox" name="status" value="1" {{$appleLoginService['status']==1?'checked' :''}}>
                                        <span class="toggle-switch-label text p-0">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                                <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                                    <div class="d-flex justify-content-end">
                                        <div class="text--primary-2 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#{{$appleLoginService['login_medium']}}-modal">
                                            <strong class="mr-2 text--underline">{{translate('Credential Setup')}}</strong>
                                            <div class="blinkings">
                                                <i class="tio-info-outined"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="client_id"
                                            class="form-label">{{translate('messages.client_id_for_web')}}</label>
                                        <input id="client_id" type="text" class="form-control" name="client_id"
                                            value="{{ $appleLoginService['client_id'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="client_id_app"
                                            class="form-label">{{translate('messages.client_id_for_app')}}</label>
                                        <input id="client_id_app" type="text" class="form-control" name="client_id_app"
                                            value="{{ $appleLoginService['client_id_app']??'' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="team_id"
                                            class="form-label">{{translate('messages.team_id')}}</label>
                                        <input id="team_id" type="text" class="form-control" name="team_id"
                                            value="{{ $appleLoginService['team_id'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="key_id"
                                            class="form-label">{{translate('messages.key_id')}}</label>
                                        <input id="key_id" type="text" class="form-control" name="key_id"
                                            value="{{ $appleLoginService['key_id'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="key_id"
                                            class="form-label">{{translate('messages.redirect_url_for_flutter_web')}}</label>
                                        <input id="redirect_url_flutter" type="url" class="form-control" name="redirect_url_flutter"
                                            value="{{ $appleLoginService['redirect_url_flutter']??'' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="key_id"
                                            class="form-label">{{translate('messages.redirect_url_for_react_web')}}</label>
                                        <input id="redirect_url_react" type="url" class="form-control" name="redirect_url_react"
                                            value="{{ $appleLoginService['redirect_url_react']??'' }}">
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="form-label">{{translate('messages.service_file')}} {{ $appleLoginService['service_file']?translate('(Already Exists)'):'' }}</label>
                                        <input type="file" accept=".p8" class="form-control" name="service_file"
                                            value="{{ 'storage/app/public/apple-login/'.$appleLoginService['service_file'] }}">
                                    </div>
                                    <div class="btn--container justify-content-end">
                                        <button type="reset" class="btn btn--reset mb-2">{{translate('Reset')}}</button>
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary mb-2">{{translate('messages.save')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            @endforeach
            @endif
        </div>
    </div>

        <!-- Google -->
        <div class="modal fade" id="google-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog status-warning-modal">
                <div class="modal-content {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                    <div class="modal-header pb-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pb-0">
                        <div class="text-center mb-20">
                            <img src="{{asset('/public/assets/admin/img/modal/google.png')}}" alt="" class="mb-20">
                            <h5 class="modal-title">{{translate('messages.google_api_setup_instructions')}}</h5>
                        </div>
                        <ol>
                            <li>{{translate('messages.go_to_the_credentials_page')}} ({{translate('messages.click')}} <a href="https://console.cloud.google.com/apis/credentials" target="_blank">{{translate('here')}}</a>)</li>
                            <li>{{translate('messages.click')}} <b>{{translate('messages.create_credentials')}}</b> > <b>{{translate('messages.auth_client_id')}}</b>.</li>
                            <li>{{translate('messages.select_the')}} <b>{{translate('messages.web_application')}}</b> {{translate('messages.type')}}.</li>
                            <li>{{translate('messages.name_your_auth_client')}}</li>
                            <li>{{translate('messages.click')}} <b>{{translate('messages.add_uri')}}</b> {{translate('messages.from')}} <b>{{translate('messages.authorized_redirect_uris')}}</b> , {{translate('messages.provide_the')}} <code>{{translate('messages.callback_uri')}}</code> {{translate('messages.from_below_and_click')}} <b>{{translate('messages.created')}}</b></li>
                            <li>{{translate('messages.copy')}} <b>{{translate('messages.client_id')}}</b> {{translate('messages.and')}} <b>{{translate('messages.client_secret')}}</b>, {{translate('messages.past_in_the_input_field_below_and')}} <b>Save</b>.</li>
                        </ol>
                    </div>
                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn--primary w-100 mw-300px" data-dismiss="modal">{{translate('Got It')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Facebook -->
        <div class="modal fade" id="facebook-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog status-warning-modal">
                <div class="modal-content {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                    <div class="modal-header pb-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pb-0"><b></b>
                        <div class="text-center mb-20">
                            <img src="{{asset('/public/assets/admin/img/modal/facebook.png')}}" alt="" class="mb-20">
                            <h5 class="modal-title">{{translate('messages.facebook_api_set_instruction')}}</h5>
                        </div>
                        <ol>
                            <li>{{translate('messages.goto_the_facebook_developer_page')}} (<a href="https://developers.facebook.com/apps/" target="_blank">{{translate('messages.click_here')}}</a>)</li>
                            <li>{{translate('messages.goto')}} <b>{{translate('messages.get_started')}}</b> {{translate('messages.from_navbar')}}</li>
                            <li>{{translate('messages.from_register_tab_press')}} <b>{{translate('messages.continue')}}</b> <small>({{translate('messages.if_needed')}})</small></li>
                            <li>{{translate('messages.provide_primary_email_and_press')}} <b>{{translate('messages.confirm_email')}}</b> <small>({{translate('messages.if_needed')}})</small></li>
                            <li>{{translate('messages.in_about_section_select')}} <b>{{translate('messages.other')}}</b> {{translate('messages.and_press')}} <b>{{translate('messages.complete_registration')}}</b></li>

                            <li><b>{{translate('messages.create_app')}}</b> > {{translate('messages.select_an_app_type_and_press')}} <b>{{translate('messages.next')}}</b></li>
                            <li>{{translate('messages.complete_the_details_form_and_press')}} <b>{{translate('messages.create_app')}}</b></li><br/>

                            <li>{{translate('messages.form')}} <b>{{translate('messages.facebook_login')}}</b> {{translate('messages.press')}} <b>{{translate('messages.set_up')}}</b></li>
                            <li>{{translate('messages.select')}} <b>{{translate('messages.web')}}</b></li>
                            <li>{{translate('messages.provide')}} <b>{{translate('messages.site_url')}}</b> <small>({{translate('messages.base_url_of_the_site')}}: https://example.com)</small> > <b>{{translate('messages.save')}}</b></li><br/>
                            <li>{{translate('messages.now_go_to')}} <b>{{translate('messages.setting')}}</b> {{translate('messages.form')}} <b>{{translate('messages.facebook_login')}}</b> ({{translate('messages.left_sidebar')}})</li>
                            <li>{{translate('messages.make_sure_to_check')}} <b>{{translate('messages.client_auth_login')}}</b> <small>({{translate('messages.must_on')}})</small></li>
                            <li>{{translate('messages.provide')}} <code>{{translate('messages.valid_auth_redirect_uris')}}</code> {{translate('messages.from_below_and_click')}} <b>{{translate('messages.save_changes')}}</b></li>

                            <li>{{translate('messages.now_go_to')}} <b>{{translate('messages.setting')}}</b> ({{translate('messages.from_left_sidebar')}}) > <b>{{translate('messages.basic')}}</b></li>
                            <li>{{translate('messages.fill_the_form_and_press')}} <b>{{translate('messages.save_changes')}}</b></li>
                            <li>{{translate('messages.now_copy')}} <b>{{translate('messages.client_id')}}</b> & <b>{{translate('messages.client_secret')}}</b>, {{translate('messages.past_in_the_input_field_below_and')}} <b>{{translate('messages.save')}}</b>.</li>
                        </ol>
                    </div>
                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn--primary w-100 mw-300px" data-dismiss="modal">{{translate('Got It')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Apple -->
        <div class="modal fade" id="apple-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog status-warning-modal">
                <div class="modal-content {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pb-0"><b></b>
                        <div class="text-center mb-20">
                            <img src="{{asset('/public/assets/admin/img/modal/apple.png')}}" alt="" class="mb-20">
                            <h5 class="modal-title">{{translate('messages.apple_api_set_instruction')}}</h5>
                        </div>
                        <ol>
                            <li>{{translate('Go to Apple Developer page')}} (<a href="https://developer.apple.com/account/resources/identifiers/list" target="_blank">{{translate('messages.click_here')}}</a>)</li>
                            <li>{{translate('Here in top left corner you can see the')}} <b>{{ translate('Team ID') }}</b> {{ translate('[Apple_Developer_Account_Name - Team_ID]')}}</li>
                            <li>{{translate('Click Plus icon -> select App IDs -> click on Continue')}}</li>
                            <li>{{translate('Put a description and also identifier (identifier that used for app) and this is the')}} <b>{{ translate('Client ID') }}</b> </li>
                            <li>{{translate('Click Continue and Download the file in device named AuthKey_ID.p8 (Store it safely and it is used for push notification)')}} </li>
                            <li>{{translate('Again click Plus icon -> select Service IDs -> click on Continue')}} </li>
                            <li>{{translate('Push a description and also identifier and Continue')}} </li>
                            <li>{{translate('Download the file in device named')}} <b>{{ translate('AuthKey_KeyID.p8') }}</b> {{translate('[This is the Service Key ID file and also after AuthKey_ that is the Key ID]')}}</li>
                        </ol>
                    </div>
                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn--primary w-100 mw-300px" data-dismiss="modal">{{translate('Got It')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Twitter -->
        <div class="modal fade" id="twitter-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{translate('messages.twitter_api_set_up_instructions')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"><b></b>
                        {{translate('messages.instruction_will_be_available_very_soon')}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--primary" data-dismiss="modal">{{translate('messages.close')}}</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal Ends--}}



@endsection
@push('script_2')
    <script>
        "use strict";
        $(document).on('click', '.copy-to-clipboard', function () {
            let id=  $(this).data('id');
            let $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(id).text()).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.success("{{translate('Copied to the clipboard')}}");

        });

    </script>

@endpush
