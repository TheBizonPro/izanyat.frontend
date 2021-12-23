@extends('layouts.master')

@section('title')
Я Занят
@stop

@section('styles')
@stop


@section('scripts')
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
@stop

@section('content')
<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none mt-4">
            <div class="row align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle" hidden>
                    </div>
                    <h2 class="page-title">
                        <b class="fad fa-link me-2"></b>Привязка к «Я Занят» в «Мой Налог»
                    </h2>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body border-top">
                        <h3>Статус привязки самозанятого к партнеру</h3>
                        <div class="form-group mb-3">
                            <label class="form-label text-muted"></label>

                            <div id="my_npd_bind_false" class="alert alert-danger text-center" hidden>
                                <h1><b class="fad fa-times text-danger"></b></h1>
                                <h3>Привязка не выполнена.</h3>
                                <p>Чтобы работать через платформу «Я Занят» необходима привязка к партнёру. Без привязки
                                    вы не сможете брать задачи в работу, получить оплату и отчитаться в ФНС о своих
                                    доходах!</p>
                                <div id="manually_bind_wrapper" class="mb-3">
                                    <button id="manually_bind_btn" class="btn btn-md btn-green">
                                        <span class="text">Выполнить привязку к «Я Занят»</span>
                                        <span class="wait" hidden><b class="fad fa-spinner fa-pulse"></b> Пожалуйста,
                                            подождите</span>
                                    </button>
                                </div>
                            </div>

                            <div id="my_npd_bind_wait" class="alert alert-info text-center" hidden>
                                <h1><b class="fad fa-spinner fa-pulse text-dark"></b></h1>
                                <h3>Необходимо подтвердить привязку в «Мой Налог»</h3>
                                <p>Для связки вашего аккаунта самозанятого с платформой «Я Занят» необходимо открыть
                                    мобильное приложение или веб-сервис «Мой Налог» и предоставить доступ сервису «Я
                                    Занят». Настройки → Партнёры → Разрешить действия партнёру «Я Занят»</p>
                                <p>После этого подождите несколько минут, статус вашей привязки к партнеру будет
                                    обновлен на этой странице.</p>
                                <div id="bind_requested_wrapper" class="mb-3">
                                    <button id="bind_requested_btn" class="btn btn-md btn-grey">
                                        <span class="wait"><b class="fad fa-spinner fa-pulse"></b> Запрос на привязку
                                            отправлен</span>
                                    </button>
                                    <button id="cancel_bind_btn" class="btn btn-md btn-danger">
                                        <span class="text">Отменить</span>
                                        <span class="wait" hidden><b class="fad fa-spinner fa-pulse"></b> Пожалуйста,
                                            подождите</span>
                                    </button>
                                </div>
                            </div>


                            <div id="my_npd_bind_success" class="alert alert-success text-center" hidden>
                                <h1><b class="fad fa-check-circle text-success"></b></h1>
                                <h3>Привязка выполнена!</h3>
                                <p>Вы можете работать в платформе: брать и выполнять задачи, получать оплату.</p>
                                <div id="manually_unbind_wrapper" class="mb-3">
                                    <button id="manually_unbind_btn" class=" btn btn-md btn-danger">
                                        <span class="text">Выполнить отвязку от «Я Занят»</span>
                                        <span class="wait" hidden><b class="fad fa-spinner fa-pulse"></b> Пожалуйста,
                                            подождите</span>
                                    </button>
                                </div>
                            </div>

                        </div>





                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="form-group mt-3 mb-3 text-center">
                            <h1><b class="fad fa-receipt text-primary"></b></h1>
                            <h3>Автоматическая фискализация доходов</h3>
                            <p>Все полученные в рамках работы в платформе «Я Занят» доходы за выполненные задания будут
                                фискализированы в ПП НПД автоматически. Платформа автоматически сформирует чек и
                                полученный вами доход будет отображаться в приложении и личном веб - кабинете «Мой
                                Налог»</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(function () {
        window.InterfaceManager = new InterfaceManagerClass;
        window.InterfaceManager.menuShow('main_menu');
        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe(function (me) {
            if (me.is_selfemployed == false) {
                window.location = '/';
            }

            $('#user_name').text('')
        });
        window.InterfaceManager.notificationsCount();
        //window.InterfaceManager.signUnrequestedCount();

        var ProfileManager = new ProfileManagerClass;
    });



    class ProfileManagerClass {

        constructor() {
            let ths = this;
            ths.loadMe();

            $('#manually_bind_btn').bind('click', function () {
                ths.bindToPartner();
            });

            $('#manually_unbind_btn').bind('click', function () {
                ths.preUnbindFromPartner();
            });

            $('#cancel_bind_btn').bind('click', function () {
                ths.cancelBindToPartner();
            });

            $('#not_npd_alert .btn').prop('hidden', true);

            setInterval(function () {
                ths.loadMe()
            }, 20000);
        }



        loadMe() {
            let ths = this;

            var ax = axios.get('{{ config('app.api') }}/api/me?withPermissions=1');
            ax.then(function (response) {

                if (response.data.me) {

                    let name = (response.data.me.firstname ?? '') + ' ' + (response.data.me.lastname ?? '');
                    $('#my_name').text(name);
                    $('#my_inn').text(response.data.me.inn);

                    if (response.data.me.taxpayer_binded_to_platform == true) {

                        $('#my_npd_bind_false').prop('hidden', true);
                        $('#my_npd_bind_wait').prop('hidden', true);
                        $('#my_npd_bind_success').prop('hidden', false);
                        $('#not_npd_alert').prop('hidden', true);

                        //$('#manually_bind_wrapper').prop('hidden', true);
                        //$('#bind_requested_wrapper').prop('hidden', true);
                        //$('#manually_unbind_wrapper').prop('hidden', false);

                    } else if (response.data.me.taxpayer_binded_to_platform == false && response.data.me.taxpayer_bind_id != null) {
                        $('#my_npd_bind_false').prop('hidden', true);
                        $('#my_npd_bind_wait').prop('hidden', false);
                        $('#my_npd_bind_success').prop('hidden', true);
                        $('#not_npd_alert').prop('hidden', false);

                        //$('#manually_bind_wrapper').prop('hidden', true);
                        //$('#bind_requested_wrapper').prop('hidden', false);
                        //$('#manually_unbind_wrapper').prop('hidden', true);

                    } else {
                        $('#my_npd_bind_false').prop('hidden', false);
                        $('#my_npd_bind_wait').prop('hidden', true);
                        $('#my_npd_bind_success').prop('hidden', true);
                        $('#not_npd_alert').prop('hidden', false);

                        //$('#manually_bind_wrapper').prop('hidden', false);
                        //$('#bind_requested_wrapper').prop('hidden', true);
                        //$('#manually_unbind_wrapper').prop('hidden', true);

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

                });
        }



        preBindToPartner() {
            let ths = this;
            bootbox.dialog({
                title: 'Инициировать привязку к партнеру «Я Занят»?',
                closeButton: false,
                message: 'Инициировать привязку к партнеру «Я Занят»? Если вы уже подтвердили привязку в приложении «Мой налог», запускать повторую привязке не нужно, достаточно подождать несколько минут.',
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: 'Выполнить привязку',
                        className: 'btn-success',
                        callback: function () {
                            ths.bindToPartner();
                        }
                    }
                }
            });
        }



        bindToPartner() {
            let ths = this;
            $('#manually_bind_btn').prop('disabled', true);
            $('#manually_bind_btn .text').prop('hidden', true);
            $('#manually_bind_btn .wait').prop('hidden', false);

            var ax = axios.post('{{ config('app.api') }}/api/v2/contractor/npd/bind_to_partner');
            ax.then(function (response) {
                if (response.data.message) {
                    bootbox.dialog({
                        title: 'Результат привязки к партнеру',
                        closeButton: false,
                        message: response.data.message,
                        buttons: {
                            cancel: {
                                label: 'Закрыть',
                                className: 'btn-light'
                            }
                        }
                    });
                    ths.loadMe();
                }
            })
                .catch(function (error, code) {
                    console.error(error.response);
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
                    $('#manually_bind_btn').prop('disabled', false);
                    $('#manually_bind_btn .text').prop('hidden', false);
                    $('#manually_bind_btn .wait').prop('hidden', true);
                });
        }



        preUnbindFromPartner() {
            let ths = this;
            bootbox.dialog({
                title: 'Выполнить отвязку от партнера «Я Занят»?',
                closeButton: false,
                message: 'Вы уверены, что хотите выполнить отвязку от партнера в «Мой налог»?',
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: 'Выполнить отвязку',
                        className: 'btn-danger',
                        callback: function () {
                            ths.unbindFromPartner();
                        }
                    }
                }
            });
        }


        unbindFromPartner() {
            let ths = this;

            $('#manually_unbind_btn').prop('disabled', true);
            $('#manually_unbind_btn .text').prop('hidden', true);
            $('#manually_unbind_btn .wait').prop('hidden', false);
            var ax = axios.post('{{ config('app.api') }}/api/v2/contractor/npd/unbind_from_partner');
            ax.then(function (response) {

                if (response.data.message) {
                    bootbox.dialog({
                        title: 'Результат отвязки от партнера',
                        closeButton: false,
                        message: response.data.message,
                        buttons: {
                            cancel: {
                                label: 'Закрыть',
                                className: 'btn-light'
                            }
                        }
                    });

                    ths.loadMe();
                }

            })
                .catch(function (error, code) {
                    console.error(error.response);
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
                    $('#manually_unbind_btn').prop('disabled', false);
                    $('#manually_unbind_btn .text').prop('hidden', false);
                    $('#manually_unbind_btn .wait').prop('hidden', true);
                });
        }

        cancelBindToPartner() {
            let ths = this;

            $('#cancel_bind_btn').prop('disabled', true);
            $('#cancel_bind_btn .text').prop('hidden', true);
            $('#cancel_bind_btn .wait').prop('hidden', false);

            var ax = axios.post('{{ config('app.api') }}/api/v2/contractor/npd/cancel_bind_to_partner');
            ax.then(function (response) {
                ths.loadMe();
            })
                .catch(function (error, code) {
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

                    $('#cancel_bind_btn').prop('disabled', false);
                    $('#cancel_bind_btn .text').prop('hidden', false);
                    $('#cancel_bind_btn .wait').prop('hidden', true);
                });
        }


    }


</script>

@stop
