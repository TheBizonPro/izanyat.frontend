@extends('layouts.master')

@section('title')
Мой профиль
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
                        <b class="fad fa-user me-2"></b>Профиль
                    </h2>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <span class="text-danger"><sup>*</sup> Для изменения ваших данных напишите в службу технической
                            поддержки - <a href="mailto:info@izanyat.ru" target="_blank">info@izanyat.ru</a></span>
                    </div>
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-muted">Имя</label>
                                    <div id="profile_name" class="font-weight-bold"></div>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="text-muted">Зарегистрирован как плательщик НПД</label>
                                    <div id="profile_taxpayer_registred_as_npd" class="font-weight-bold"></div>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="text-muted">ИНН</label>
                                    <div id="profile_inn" class="font-weight-bold"></div>
                                </div>

                                <div class="form-group mt-3">
                                    <label class="text-muted">СНИЛС</label>
                                    <div id="profile_snils" class="font-weight-bold"></div>
                                </div>

                                <div class="form-group mt-3">
                                    <label class="text-muted">Паспорт</label>
                                    <div>
                                        <span id="profile_passport_series" class="font-weight-bold"></span>&nbsp;<span
                                            id="profile_passport_number" class="font-weight-bold"></span>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label class="text-muted">Кем выдан</label>
                                    <div id="profile_passport_issuer" class="font-weight-bold"></div>
                                    <div>(код подразделения: <span id="profile_passport_code"
                                            class="font-weight-bold"></span>)</div>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="text-muted">Дата выдачи</label>
                                    <div id="profile_passport_issue_date" class="font-weight-bold"></div>
                                </div>

                                <div class="form-group mt-3">
                                    <label class="text-muted">Метсто рождения</label>
                                    <div id="profile_birth_place" class="font-weight-bold"></div>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="text-muted">Дата рождения</label>
                                    <div id="profile_birth_date" class="font-weight-bold"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mt-3">
                                    <label class="text-muted">Телефон</label>
                                    <div>+<span id="profile_phone" class="font-weight-bold"></span></div>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="text-muted">Email</label>
                                    <div id="profile_email" class="font-weight-bold"></div>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="text-muted">Рейтинг</label>
                                    <div id="profile_rating" class="font-weight-bold"></div>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="text-muted">Адрес</label>
                                    <div>Регион: <span id="profile_address_region" class="font-weight-bold"></span>
                                    </div>
                                    <div>Город: <span id="profile_address_city" class="font-weight-bold"></span></div>
                                    <div>Улица: <span id="profile_address_street" class="font-weight-bold"></span></div>
                                    <div>Дом: <span id="profile_address_house" class="font-weight-bold"></span></div>
                                    <div>Корпус: <span id="profile_address_building" class="font-weight-bold"></span>
                                    </div>
                                    <div>Квартира: <span id="profile_address_flat" class="font-weight-bold"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group mt-3">
                            <label class="text-muted">Основная категорий работ</label>
                            <select id="job_category_id" class="form-select"></select>
                        </div>
                        <div class="form-group mt-3">
                            <label class="text-muted">Обо мне</label>
                            <textarea id="about" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="card-body text-end">
                        <button id="save_profile_btn" class="btn btn-primary">
                            <span class="text">Сохранить данные профиля</span>
                            <span class="wait" hidden><b class="fa fa-spinner fa-pulse me-2"></b>Пожалуйста,
                                подождите</span>
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="form-group mt-3">
                            <label class="text-muted">Номер банковской карты</label>
                            <input id="user_card_account_number" type="text" class="form-control w-50">
                        </div>
                    </div>

                    <div class="card-body text-end">
                        <button id="save_bank_account_btn" class="btn btn-primary">
                            <span class="text">Сохранить данные банковского счёта</span>
                            <span class="wait" hidden><b class="fa fa-spinner fa-pulse me-2"></b></span>
                        </button>
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
            ths.loadMe()
                .then(() => {
                    ths.loadBankAccount()
                })

            $('#save_profile_btn').bind('click', function () {
                ths.saveProfile();
            });

            $('#save_bank_account_btn').click(evt => {
                ths.updateBankAccount()
            })

            ths.loadCategories();
        }

        updateBankAccount() {
            let bankCardNumber = $('#user_card_account_number').val()
            let payload = {
                card_number: bankCardNumber,
            }

            // провалидируем значения
            for (let value of Object.values(payload)) {
                if (!value) {
                    bootbox.dialog({
                        title: 'Ошибка',
                        message: 'Введите все поля',
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: 'Закрыть',
                                className: 'btn-dark'
                            }
                        }
                    });

                    return
                }
            }

            axios.post('{{ config('app.api') }}/api/v2/contractor/bank_account', payload)
                .then(r => {
                    boottoast.success({
                        message: 'Данные для выплат успешно сохранены',
                        title: 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                })
                .catch(err => {
                    console.log(err)
                })
        }

        loadBankAccount() {
            axios.get('{{ config('app.api') }}/api/v2/contractor/bank_account')
                .then(r => r.data)
                .then(r => {
                    $('#user_card_account_number').val(r.card_number)
                })
                .catch(err => {
                    if (err.response.status === 404) {
                        // если 404 - реквизитов нет
                        return
                    }
                })
        }

        loadMe() {
            let ths = this;

            var ax = axios.get('{{ config('app.api') }}/api?withPermissions=1');
            return ax.then(function (response) {
                if (response.data.company) {
                    window.localStorage.setItem('company', JSON.stringify(response.data.company));
                }
                if (response.data.me) {

                    window.localStorage.setItem('me', JSON.stringify(response.data.me))
                    const responsePermissions = response.data.permissions?.map(item => item.name) || [];

                    window.localStorage.setItem('permissions', JSON.stringify(responsePermissions));
                    window.permissionHandler = new PermissionsHandler(responsePermissions);
                    window.permissionHandler.handle();

                    var user = response.data.me;

                    $('#contractor_profile_modal').modal('show');
                    $('#profile_name').html(user.name);
                    $('#profile_inn').html(user.inn);
                    $('#profile_email').html(user.email);
                    $('#profile_phone').html(user.phone);
                    $('#profile_birth_place').html(user.birth_place);
                    $('#profile_birth_date').html(user.birth_date);

                    $('#profile_birth_place').html(user.birth_place);
                    $('#profile_birth_date').html(user.birth_date);
                    $('#profile_passport_series').html(user.passport_series);
                    $('#profile_passport_number').html(user.passport_number);
                    $('#profile_passport_code').html(user.passport_code);
                    $('#profile_passport_issuer').html(user.passport_issuer);
                    $('#profile_passport_issue_date').html(user.passport_issue_date);
                    $('#profile_snils').html(user.snils);
                    $('#profile_sbp_bank_id').html(user.sbp_bank_id);
                    $('#profile_sbp_phone').html(user.sbp_phone);
                    $('#profile_address_region').html(user.address_region);
                    $('#profile_address_city').html(user.address_city);
                    $('#profile_address_street').html(user.address_street);
                    $('#profile_address_house').html(user.address_house);
                    $('#profile_address_building').html(user.address_building);
                    $('#profile_address_flat').html(user.address_flat);

                    $('#profile_taxpayer_registred_as_npd').html(user.taxpayer_registred_as_npd == 1 ? '<b class="fad fa-check-circle text-success" titile="Пользователь зарегистрирован как плательщик НПД"></b> Зарегистрирован' : '<b class="fad fa-question-circle text-muted" titile="Информации о статусе нет"></b> Не зарегистрирован');



                    $('#profile_rating').html(user.rating);
                    $('#about').val(user.about);

                    ths.user_job_category_id = user.job_category_id;
                    $('#job_category_id').val(ths.user_job_category_id);

                    ths.user = user

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



        saveProfile() {

            let ths = this;

            $('#save_profile_btn').prop('disabled', true);
            $('#save_profile_btn .text').prop('hidden', true);
            $('#save_profile_btn .wait').prop('hidden', false);

            var data = {};
            data.job_category_id = $('#job_category_id').val();
            data.about = $('#about').val();

            var ax = axios.post('{{ config('app.api') }}/api/me', data);
            ax.then(function (response) {
                if (response.data.message) {
                    boottoast.success({
                        message: response.data.message,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
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
                    $('#save_profile_btn').prop('disabled', false);
                    $('#save_profile_btn .text').prop('hidden', false);
                    $('#save_profile_btn .wait').prop('hidden', true);
                });


        }


        /**
         * Загрузка списка банков
         */
        loadCategories() {
            let ths = this;
            var ax = axios.get('{{ config('app.api') }}/api/job_categories');
            ax.then(function (response) {
                if (response.data.job_categories) {
                    $('#job_category_id').html('<option value="">Выберите категорию</option>')
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id == null) {
                            $('#job_category_id').append('<optgroup class="job_category_' + job_category.id + '" label="' + job_category.name + '"></optgroup>');
                        }
                    });
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id != null) {
                            $('#job_category_id .job_category_' + job_category.parent_id).append('<option value="' + job_category.id + '">' + job_category.name + '</option>');
                        }
                    });
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

                    if (ths.user_job_category_id) {
                        $('#job_category_id').val(ths.user_job_category_id);
                    }

                });
        }



    }


</script>

@stop
