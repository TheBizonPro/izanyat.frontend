@extends('layouts.empty')

@section('title')
Авторизация
@stop

@section('styles')
<style type="text/css">
    input#code {
        letter-spacing: 4pt;
        font-size: 1.2rem;
    }
</style>
@stop

@section('scripts')
<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>
@stop

@section('content')
<section class="container-fluid flex-grow-0" id="blocks_wrapper">
    <div class="row">
        <div class="col col-5 mx-auto">

            <div class="text-center mb-2 mt-5">
                <h1 class="h2 mb-3 text-dark">
                    Вход в личный кабинет «Я Занят»
                </h1>
            </div>

            <div id="auth_card" class="card">

                <div id="phone_block" class="card-body p-3">
                    <div class="form-group">
                        <label class="form-label">Номер мобильного телефона</label>
                        <input id="phone" type="text" class="form-control form-control-lg text-center"
                            placeholder="+7 (___) ___-__-__">
                    </div>
                    <div class="form-footer text-center row">
                        <div class="col"></div>
                        <div class="col">
                            <button class="btn btn-dark w-100 btn-continue">
                                <span class="text">
                                    Продолжить<b class="fad fa-arrow-right ms-2"></b>
                                </span>
                                <span class="wait" hidden>
                                    <b class="fad fa-spinner fa-pulse me-2"></b>Пожалуйста, подождите
                                </span>
                            </button>
                        </div>
                    </div>
                </div>


                <div id="password_block" class="card-body p-3" hidden>
                    <div class="form-group">
                        <label class="form-label">Номер мобильного телефона</label>
                        <input type="text" class="phone_repeat form-control form-control-lg text-center" readonly>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Пароль</label>
                        <input id="password" type="password" class="form-control form-control-lg text-center focus">
                    </div>
                    <div class="form-footer text-center row">
                        <div class="col-6">
                            <button class="btn btn-white w-100 btn-back">
                                <b class="fad fa-arrow-left me-2"></b>Назад
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-dark w-100 btn-continue">
                                <span class="text">
                                    Войти
                                </span>
                                <span class="wait" hidden>
                                    <b class="fad fa-spinner fa-pulse me-2"></b>Пожалуйста, подождите
                                </span>
                            </button>
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-white w-100 btn-reset-password">
                                Забыл пароль
                            </button>
                        </div>
                    </div>
                </div>


                <div id="code_block" class="card-body p-3" hidden>
                    <div class="form-group">
                        <label class="form-label">Номер мобильного телефона</label>
                        <input type="text" class="phone_repeat form-control form-control-lg text-center" readonly>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Смс-код</label>
                        <input id="code" type="text" class="form-control form-control-lg text-center focus"
                            placeholder="___-___">
                    </div>
                    <div class="form-footer text-center row">
                        <div class="col-6">
                            <button class="btn btn-white w-100 btn-back">
                                <b class="fad fa-arrow-left me-2"></b>Назад
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-dark w-100 btn-continue">
                                <span class="text">
                                    Продолжить<b class="fad fa-arrow-right ms-2"></b>
                                </span>
                                <span class="wait" hidden>
                                    <b class="fad fa-spinner fa-pulse me-2"></b>Пожалуйста, подождите
                                </span>
                            </button>
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-white w-100 btn-new-code">Запросить новый код <span
                                    id="new_code_timeout" class="ms-1"></span></button>
                        </div>
                    </div>
                </div>


                <div id="new_password_block" class="card-body p-3" hidden>
                    <div class="form-group">
                        <label class="form-label">Пожалуйста, придумайте пароль для входа</label>
                        <input id="new_password" type="password" class="focus form-control form-control-lg text-center">
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Повторите пароль</label>
                        <input id="new_password_repeat" type="password"
                            class="form-control form-control-lg text-center">
                    </div>
                    <div class="form-footer text-center row">
                        <div class="col-6">
                        </div>
                        <div class="col-6">
                            <button class="btn btn-dark w-100 btn-continue">
                                <span class="text">
                                    Продолжить<b class="fad fa-arrow-right ms-2"></b>
                                </span>
                                <span class="wait" hidden>
                                    <b class="fad fa-spinner fa-pulse me-2"></b>Пожалуйста, подождите
                                </span>
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(function () {
        let AuthManager = new AuthManagerClass;
    });


    class AuthManagerClass {

        constructor() {
            let ths = this;

            ths.updateCsrfCookie();
            ths.bindPhoneBlock();
            ths.bindPasswordBlock();
            ths.bindCodeBlock();
            ths.bindNewPasswordBlock();
        }


		updateCsrfCookie(){
			// axios.get('{{ config('app.api') }}/sanctum/csrf-cookie')
		}


        bindPhoneBlock() {
            let ths = this;
            $('#phone_block #phone').mask('+7 (999) 999-99-99', { placeholder: '+7 (___) ___-__-__' });
            $('#phone_block #phone').focus();
            $('#phone_block button.btn-continue').bind('click', function () {
                ths.initAuth();
            });
            $('#phone_block #phone').bind('keypress', function (e) {
                if (e.keyCode == 13) {
                    $('#phone_block button.btn-continue').trigger('click');
                }
            });
        }


        bindPasswordBlock() {
            let ths = this;
            $('#password_block button.btn-back').bind('click', function () {
                ths.showBlock('phone_block');
            });
            $('#password_block button.btn-continue').bind('click', function () {
                ths.authWithPassword();
            });
            $('#password_block button.btn-reset-password').bind('click', function () {
                ths.resetPasswordModal();
            });
            $('#password_block #password').bind('keypress', function (e) {
                if (e.keyCode == 13) {
                    $('#password_block button.btn-continue').trigger('click');
                }
            });
        }


        bindCodeBlock() {
            let ths = this;
            $('#code_block #code').mask('999-999', { placeholder: '___-___' });
            $('#code_block button.btn-back').bind('click', function () {
                ths.showBlock('phone_block');
            });

            $('#code_block #code').bind('keypress', function (e) {
                var lastSymbol = $(this).val().charAt(6);
                if (lastSymbol != '_') {
                    $('#code_block button.btn-continue').trigger('click');
                }
            });

            $('#code_block button.btn-continue').bind('click', function () {
                ths.authWithCode();
            });

            $('#code_block button.btn-new-code').bind('click', function () {
                ths.initAuth();
            });
        }


        bindNewPasswordBlock() {
            let ths = this;
            $('#new_password_block button.btn-continue').bind('click', function () {
                ths.saveNewPassword();
            });

            $('#new_password_block #new_password').bind('keypress', function (e) {
                if (e.keyCode == 13) {
                    $('#new_password_block #new_password_repeat').focus();
                }
            });
            $('#new_password_block #new_password_repeat').bind('keypress', function (e) {
                if (e.keyCode == 13) {
                    $('#new_password_block button.btn-continue').trigger('click');
                }
            });


        }


        showBlock(block) {
            let ths = this;
            $('#auth_card .card-body').prop('hidden', true);
            $('#' + block).prop('hidden', false);
            $('#' + block + ' .focus').focus();
        }


        initAuth(skip_password = 0) {
            console.log('initAuth(' + skip_password + ')')
            let ths = this;
            ths.skip_password = skip_password;


            let phone = $('#phone').val();
            if (phone == '') { return false; }
            $('.phone_repeat').val(phone);

            $('#phone_block button.btn-continue').prop('disabled', true);
            $('#phone_block button.btn-continue .text').prop('hidden', true);
            $('#phone_block button.btn-continue .wait').prop('hidden', false);

            let data = { phone: phone, skip_password: skip_password };
            var ax = axios.post('{{ config('app.api') }}/api/login', data);
            ax.then(function (response) {
                if (response.data.message) {
                    boottoast.success({
                        message: response.data.message,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                }
                if (response.data.action) {
                    if (response.data.action == 'enter_password') {
                        ths.showBlock('password_block');
                    } else if (response.data.action == 'enter_code') {
                        ths.showBlock('code_block');
                        if (response.data.wait_before_repeat_code) {
                            ths.smsRepeatCountdown(response.data.wait_before_repeat_code);
                        }

                    } else if (response.data.action == 'redirect') {
                        window.location = '/projects';
                    }
                }
            })
                .catch(function (error) {
                    console.log(error);
                    bootbox.dialog({
                        title: error.response.data.title ?? 'Ошибка',
                        message: error.response.data.message ?? (error.response.error ?? error.response.statusText),
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: 'Закрыть',
                                className: 'btn-dark'
                            }
                        }
                    });
                })
                .finally(function () {
                    $('#phone_block button.btn-continue').prop('disabled', false);
                    $('#phone_block button.btn-continue .text').prop('hidden', false);
                    $('#phone_block button.btn-continue .wait').prop('hidden', true);
                });
        }


        /**
         * Аутентификация по паролю
         */
        authWithPassword() {
            let ths = this;
            let phone = $('#phone').val();
            let password = $('#password').val();

            $('#password_block button.btn-continue').prop('disabled', true);
            $('#password_block button.btn-continue .text').prop('hidden', true);
            $('#password_block button.btn-continue .wait').prop('hidden', false);

            let data = { phone: phone, password: password };
            var ax = axios.post('{{ config('app.api') }}/api/login', data);
            ax.then(function (response) {
                const token = response.data.token;
                window.localStorage.setItem('token',token);
                if (response.data.message) {
                    boottoast.success({
                        message: response.data.message,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                }
                if (response.data.action) {
                    if (response.data.action == 'redirect') {
                        window.location = '/projects';
                    }
                }
            })
                .catch(function (error) {
                    console.log(error);
                    bootbox.dialog({
                        title: error.response.data.title ?? 'Ошибка',
                        message: error.response.data.message ?? error.response.statusText,
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: 'Закрыть',
                                className: 'btn-dark'
                            }
                        }
                    });
                })
                .finally(function () {
                    $('#password_block button.btn-continue').prop('disabled', false);
                    $('#password_block button.btn-continue .text').prop('hidden', false);
                    $('#password_block button.btn-continue .wait').prop('hidden', true);
                });
        }


        /**
         * Аутентификация по смс-коду
         */
        authWithCode() {
            let ths = this;
            let phone = $('#phone').val();
            let code = $('#code').val();

            $('#code_block button.btn-continue').prop('disabled', true);
            $('#code_block button.btn-continue .text').prop('hidden', true);
            $('#code_block button.btn-continue .wait').prop('hidden', false);

            let data = { phone: phone, code: code, skip_password: ths.skip_password };
            var ax = axios.post('{{ config('app.api') }}/api/login', data);
            ax.then(function (response) {
                if (response.data.message) {
                    boottoast.success({
                        message: response.data.message,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                }
                if (response.data.action) {
                    if (response.data.action == 'set_new_password') {
                        console.log(response.data.token);
                        window.localStorage.setItem('token', response.data.token);
                        ths.showBlock('new_password_block');
                    } else if (response.data.action == 'redirect') {
                        window.location = '/client/projects';
                    }
                }
            })
                .catch(function (error) {
                    console.log(error);
                    bootbox.dialog({
                        title: error.response.data.title ?? 'Ошибка',
                        message: error.response.data.message ?? error.response.statusText,
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: 'Закрыть',
                                className: 'btn-dark'
                            }
                        }
                    });
                })
                .finally(function () {
                    $('#code_block button.btn-continue').prop('disabled', false);
                    $('#code_block button.btn-continue .text').prop('hidden', false);
                    $('#code_block button.btn-continue .wait').prop('hidden', true);
                });
        }


        smsRepeatCountdown(seconds) {
            console.log('fired smsRepeatCountdown ' + seconds)
            let ths = this;

            try {
                clearTimeout(ths.sms_repeat_countdown_timeout);
            } catch (e) { }

            ths.sms_repeat_countdown_timeout = setTimeout(function () {
                if (seconds > 0) {
                    $('#new_code_timeout').text('(через ' + seconds + ' сек)');
                    $('#code_block .btn-new-code').prop('disabled', true);
                    ths.smsRepeatCountdown(seconds - 1);
                } else {
                    $('#new_code_timeout').text('');
                    $('#code_block .btn-new-code').prop('disabled', false);
                }
            }, 1000);


        }



        /**
         * Аутентификация по смс-коду
         */
        saveNewPassword() {
            let ths = this;
            let phone = $('#phone').val();
            let code = $('#code').val();
            let new_password = $('#new_password').val();
            let new_password_repeat = $('#new_password_repeat').val();

            if (new_password_repeat != new_password) {
                bootbox.dialog({
                    title: 'Ошибка',
                    message: 'Пароли не совпадают',
                    closeButton: false,
                    buttons: {
                        cancel: {
                            label: 'Закрыть',
                            className: 'btn-dark'
                        }
                    }
                });
                return false;
            }

            $('#new_password_block button.btn-continue').prop('disabled', true);
            $('#new_password_block button.btn-continue .text').prop('hidden', true);
            $('#new_password_block button.btn-continue .wait').prop('hidden', false);

            let data = { phone: phone, password: new_password, code: code };
            var ax = axios.post('{{ config('app.api') }}/api/change_password', data, {
                headers:{
                    Authorization: `Bearer ${window.localStorage.getItem('token')}`
                }
            });
            ax.then(function (response) {
                if (response.data.message) {
                    boottoast.success({
                        message: response.data.message,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                }
                window.location = '/client/projects';
            })
                .catch(function (error) {
                    console.log(error);
                    bootbox.dialog({
                        title: error.response.data.title ?? 'Ошибка',
                        message: error.response.data.message ?? error.response.statusText,
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: 'Закрыть',
                                className: 'btn-dark'
                            }
                        }
                    });
                })
                .finally(function () {
                    $('#new_password_block button.btn-continue').prop('disabled', false);
                    $('#new_password_block button.btn-continue .text').prop('hidden', false);
                    $('#new_password_block button.btn-continue .wait').prop('hidden', true);
                });
        }


        resetPasswordModal() {
            let ths = this;
            let phone = $('#phone').val();

            bootbox.dialog({
                title: 'Сбросить пароль?',
                closeButton: false,
                message: 'Мы отправим смс-код на номер ' + phone + '. После подтверждения номера, вы сможете сбросить пароль.',
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: 'Отправить код',
                        className: 'btn-primary',
                        callback: function () {
                            ths.initAuth(1);
                        }
                    }
                }
            });
        }




    }



</script>
@stop
