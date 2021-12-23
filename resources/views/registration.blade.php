@extends('layouts.master')

@section('title')
Регистрация
@stop

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/selectize.bootstrap4.min.css">
<style type="text/css">
    .input-letter-spacing {
        letter-spacing: 2pt;
        font-family: monospace;
    }

    .selectize-control {
        padding: 0;
    }

    .selectize-input {
        border: none;
    }
</style>
@stop

@section('scripts')
@stop

@section('content')
<div class="page-wrapper">
    <div class="container">

        <div class="row">
            <div class="col col-8 mx-auto">
                <!-- Page title -->
                <div class="page-header d-print-none mt-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Page pre-title -->
                            <div class="page-pretitle" hidden>
                            </div>
                            <h2 class="page-title">
                                <b class="fad fa-cabinet-filing me-2"></b>Регистрация
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info my-3">
                    <h3>Внимание!</h3>
                    <p>Данные будут использоваться для регистрации электронной подписи. Пожалуйста, заполняйте данную
                        форму максимально корректно, в соответствии с вашими документами. Все поля обязательны для
                        заполнения</p>
                </div>

                <div class="card">
                    <div class="card-body text-center">
                        <h2 class=" mb-3  text-center">
                            Я регистрируюсь как
                        </h2>
                        <div class="btn-group mt-3">
                            <button id="trigger_customer" type="button" class="btn">Заказчик (представитель
                                компании)</button>
                            <button id="trigger_contractor" type="button" class="btn">Исполнитель (самозанятый)</button>
                        </div>
                    </div>
                </div>


                <div id="organization_block" class="card mt-4" hidden>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h2 class="page-title">
                                Данные организации
                            </h2>
                            <div>
                                <button id="autofill_show_btn" class="btn btn-white btn-pill btn-sm">Подгрузить данные
                                    организации по ИНН</button>
                            </div>
                        </div>

                        <div class="mb-3" id="autofill_wrapper" hidden>
                            <div class="input-group mb-2">
                                <input id="inn_for_autofill" type="text" class="form-control" autocomplete="off"
                                    placeholder="ИНН">
                                <button id="autofill_btn" class="btn btn-white">
                                    <span class="text">Подгрузить данные</span>
                                    <span class="wait" hidden>Загружаю данные <b
                                            class="fad fa-spinner fa-pulse"></b></span>
                                </button>
                            </div>
                        </div>


                        <div class="form-floating mb-3">
                            <input id="company_full_name" type="text" class="form-control" autocomplete="off">
                            <label for="company_full_name">Полное наименование организации<sup
                                    class="text-danger">*</sup></label>
                        </div>


                        <div class="form-floating mt-2 mb-3">
                            <input id="company_name" type="text" class="form-control" autocomplete="off">
                            <label for="company_name">Краткое наименование организации<sup
                                    class="text-danger">*</sup></label>
                        </div>


                        <div class="form-floating mt-4 mb-3">
                            <select id="company_address_region" class="form-select"></select>
                            <label for="company_address_region">Регион<sup class="text-danger">*</sup></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="company_address_city" type="text" class="form-control">
                            <label for="company_address_city">Город<sup class="text-danger">*</sup></label>
                        </div>



                        <div class="form-floating mt-2 mb-3">
                            <input id="company_legal_address" type="text" class="form-control" autocomplete="off">
                            <label for="company_legal_address">Юридический адрес<sup class="text-danger">*</sup></label>
                        </div>


                        <div class="form-floating mt-2 mb-3">
                            <input id="company_fact_address" type="text" class="form-control" autocomplete="off">
                            <label for="company_fact_address">Фактический адрес<sup class="text-danger">*</sup></label>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-floating mt-2 mb-3">
                                    <input id="company_inn" type="text" class="form-control input-letter-spacing"
                                        autocomplete="off">
                                    <label for="company_inn">ИНН<sup class="text-danger">*</sup></label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-floating mt-2 mb-3">
                                    <input id="company_ogrn" type="text" class="form-control input-letter-spacing"
                                        autocomplete="off">
                                    <label for="company_ogrn">ОГРН / ОГРНИП <sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-floating mt-2 mb-3">
                                    <input id="company_okpo" type="text" class="form-control input-letter-spacing"
                                        autocomplete="off">
                                    <label for="company_okpo">ОКПО (найдите по ИНН)
                                        <sup class="text-danger">*</sup></label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-floating mt-2 mb-3">
                                    <input id="company_kpp" type="text" class="form-control input-letter-spacing"
                                        autocomplete="off">
                                    <label for="company_kpp">КПП</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-floating mt-2 mb-3">
                                    <input id="company_phone" type="text" class="form-control" autocomplete="off">
                                    <label for="company_phone">Телефон компании<sup class="text-danger">*</sup></label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-floating mt-2 mb-3">
                                    <input id="company_email" type="text" class="form-control" autocomplete="off">
                                    <label for="company_email">Email компании<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="contractor_block" hidden>
                    <div class="card">
                        <registerpage></registerpage>
                    </div>
                </div>

                <div id="personal_data_block" class="card mt-4" hidden>
                    <div class="card-body">
                        <h2 id="personal_data_block_contractor" class="page-title mb-3" hidden>
                            Персональные данные для выдачи электронной подписи
                        </h2>
                        <h2 id="personal_data_block_company" class="page-title mb-3" hidden>
                            Персональные данные уполномоченного сотрудника для выдачи электронной подписи
                        </h2>

                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_lastname" type="text" class="form-control" autocomplete="off">
                                    <label for="user_lastname">Фамилия<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_firstname" type="text" class="form-control" autocomplete="off">
                                    <label for="user_firstname">Имя<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_patronymic" type="text" class="form-control" autocomplete="off">
                                    <label for="user_patronymic">Отчество<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_birth_date" type="text" class="form-control" autocomplete="off">
                                    <label for="user_birth_date">Дата рождения<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <select id="user_sex" class="form-select">
                                        <option value="m">Мужской</option>
                                        <option value="f">Женский</option>
                                    </select>
                                    <label for="user_sex">Пол<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_birth_place" type="text" class="form-control">
                                    <label for="user_birth_place">Место рождения<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="user_email" type="text" class="form-control">
                            <label for="user_email">Электронная почта<sup class="text-danger">*</sup></label>
                        </div>


                        <div class="form-floating mt-4 mb-3">
                            <input id="user_passport" type="text" class="form-control input-letter-spacing"
                                autocomplete="off">
                            <label for="user_passport">Серия и номер паспорта<sup class="text-danger">*</sup></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="user_passport_issuer" type="text" class="form-control">
                            <label for="user_passport_issuer">Кем выдан паспорт<sup class="text-danger">*</sup></label>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_passport_issue_date" type="text" class="form-control">
                                    <label for="user_passport_issue_date">Дата выдачи паспорта<sup
                                            class="text-danger">*</sup></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_passport_code" type="text"
                                        class="form-control input-letter-spacing">
                                    <label for="user_passport_code">Код подразделения<sup
                                            class="text-danger">*</sup></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_inn" type="text" class="form-control input-letter-spacing">
                                    <label for="user_inn">ИНН<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_snils" type="text" class="form-control input-letter-spacing">
                                    <label for="user_snils">СНИЛС<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mt-4 mb-3">
                            <select id="user_address_region" class="form-select"></select>
                            <label for="user_address_region">Регион<sup class="text-danger">*</sup></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="user_address_city" type="text" class="form-control">
                            <label for="user_address_city">Город<sup class="text-danger">*</sup></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="user_address_street" type="text" class="form-control">
                            <label for="user_address_street">Улица<sup class="text-danger">*</sup></label>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_address_house" type="text" class="form-control">
                                    <label for="user_address_house">Дом<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_address_building" type="text" class="form-control">
                                    <label for="user_address_building">Корпус</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="user_address_flat" type="text" class="form-control">
                                    <label for="user_address_flat">Квартира<sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- <div id="bank_requisites_block" class="card mt-4" hidden>--}}
                    {{-- <div class="card-body">--}}
                        {{-- <h2 class="page-title">--}}
                            {{-- Банковские реквизиты --}}
                            {{-- </h2>--}}

                        {{-- <div class="form-group mt-4 mb-3">--}}
                            {{-- <label for="user_sbp_bank_id" class="form-label">Банк для получения выплат (СБП)<sup
                                    class="text-danger">*</sup></label>--}}
                            {{-- <select id="user_sbp_bank_id" class="form-select form-control"></select>--}}
                            {{-- </div>--}}

                        {{-- <div class="form-floating mb-3">--}}
                            {{-- <input id="user_sbp_phone" type="text" class="form-control">--}}
                            {{-- <label for="user_sbp_phone">Телефон для получения выплат (СБП)<sup
                                    class="text-danger">*</sup></label>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}

                <div id="addit_info_block" class="card mt-4" hidden>
                    <div class="card-body">
                        <h2 class="page-title">
                            Дополнительно
                        </h2>
                        <div class="form-group mt-4 mb-3">
                            <label for="user_job_category_id" class="form-label">Предпочтительная категория работ<sup
                                    class="text-danger">*</sup></label>
                            <select id="user_job_category_id" class="form-select form-control"></select>
                        </div>
                    </div>
                </div>

                <div id="confirmation_block" class="card mt-4" hidden>
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="confirm_18_yo">
                            <label class="form-check-label" for="confirm_18_yo">
                                Я подтверждаю, что мне исполнилось 18 лет
                            </label>
                        </div>
                    </div>
                    <div class="form-check">
                        <div class="card-body">
                            <input class="form-check-input" type="checkbox" value="" id="confirm_private_policy">
                            <label class="form-check-label" for="confirm_private_policy">
                                Я ознакомлен и согласен:<br>
                                - <a href="/files/rules.pdf" target="_blank">с правилами пользования электронной
                                    площадкой «ЯЗанят»</a><br>
                                - <a href="/files/agreement.pdf" target="_blank">с пользовательским соглашением
                                    электронной площадки «ЯЗанят»</a><br>
                                - <a href="/files/personal_data.pdf" target="_blank">с политикой обработки персональных
                                    данных электронной площадкой «ЯЗанят»</a>
                            </label>
                        </div>
                    </div>
                </div>


                <div id="error_bag" class="alert alert-danger mt-3 mb-2" hidden>
                    <h3>Ошибки</h3>
                    <ul id="validation_errors"></ul>
                </div>

                <div id="save_block" class="mt-4 text-center" hidden>
                    <button id="save_btn" class="btn btn-lg btn-primary">
                        <div class="text">Регистрация</div>
                        <div class="wait" hidden><b class="fad fa-spinner fa-pulse"></b> Подождите</div>
                    </button>
                </div>

                <div style="height:220px;"></div>

            </div>
        </div>


    </div>
</div>




<script type="text/javascript">
    $(function () {
        window.InterfaceManager = new InterfaceManagerClass;
        window.InterfaceManager.menuHide();
        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe();

        let RegistrationManager = new RegistrationManagerClass;
    });

    class RegistrationManagerClass {

        constructor() {
            let ths = this;
            ths.bindForm();
            ths.loadRegions();
            ths.loadBanks();
            ths.loadCategories();
            window.axios.defaults.withCredentials = true;
        }


        /**
         * Интерактивим форму
         */
        bindForm() {
            let ths = this;

            $('#inn_for_autofill').mask('9999999999?99', { placeholder: '____________' });

            $('#company_phone').mask('+7 (999) 999-99-99', { placeholder: '+7 (___) ___-__-__' });
            $('#company_inn').mask('9999999999?99', { placeholder: '____________' });
            $('#company_ogrn').mask('9999999999999?99', { placeholder: '_______________' });
            $('#company_okpo').mask('99999999?99', { placeholder: '__________' });
            $('#company_kpp').mask('999999999', { placeholder: '_________' });

            $('#user_birth_date').mask('99.99.9999', { placeholder: 'дд.мм.гггг' });
            $('#user_passport').mask('9999 999999', { placeholder: '____ ______' });
            $('#user_passport_issue_date').mask('99.99.9999', { placeholder: 'дд.мм.гггг' });
            $('#user_passport_code').mask('999-999', { placeholder: '___-___' });
            $('#user_inn').mask('999999999999', { placeholder: '____________' });
            $('#user_snils').mask('999-999-999 99', { placeholder: '___-___-___ __' });
            $('#user_sbp_phone').mask('+7 (999) 999-99-99', { placeholder: '+7 (___) ___-__-__' });

            $('#autofill_show_btn').bind('click', function () {
                $('#autofill_show_btn').prop('hidden', true);
                $('#autofill_wrapper').prop('hidden', false);
                $('#inn_for_autofill').focus();
                $('#autofill_btn').bind('click', function () {
                    ths.autofillCompanyByInn();
                });
            });

            $('#trigger_customer').bind('click', function () {
                $('#contractor_block').prop('hidden', true)

                $('#trigger_customer').addClass('btn-primary');
                $('#trigger_contractor').removeClass('btn-primary');

                $('#organization_block').prop('hidden', false)
                $('#personal_data_block').prop('hidden', false)
                $('#save_block').show()
                // $('#personal_data_block_company').hide()
                // $('#personal_data_block_contractor').hide()
                $('#confirmation_block').prop('hidden', false)
                $('#save_block').prop('hidden', false)

                ths.registration_type = 'client';
            });

            $('#trigger_contractor').bind('click', function () {
                $('#trigger_customer').removeClass('btn-primary');
                $('#trigger_contractor').addClass('btn-primary');

                $('#organization_block').prop('hidden', true)
                $('#personal_data_block').prop('hidden', true)
                $('#save_block').prop('hidden', true)
                $('#personal_data_block_company').prop('hidden', true)
                $('#personal_data_block_contractor').prop('hidden', true)
                $('#confirmation_block').prop('hidden', true)
                $('#error_bag').prop('hidden', true)

                $('#contractor_block').prop('hidden', false)

                ths.registration_type = 'contractor';
            });

            $('#save_btn').bind('click', function () {
                ths.saveForm();
            });
        }


        /**
         * Подгрузка данных по ИНН
         */
        autofillCompanyByInn() {
            let ths = this;
            let inn = $('#inn_for_autofill').val();
            if (inn == '') {
                return false;
            }

            $('#autofill_btn').prop('disabled', true);
            $('#autofill_btn .text').prop('hidden', true);
            $('#autofill_btn .wait').prop('hidden', false);

            var ax = axios.get(`{{ config('app.api') }}/api/v2/company/by_inn/${inn}`);
            ax.then(function (response) {
                if (response.status == 400) {
                    bootbox.dialog({
                        title: response.data.title ?? 'Ошибка',
                        message: response.data.message ?? response.statusText,
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: 'Закрыть',
                                className: 'btn-dark'
                            }
                        }
                    });
                    return;
                }
                if (response.data.company) {
                    let company = response.data.company;
                    $('#company_full_name').val(company.name ?? '');
                    $('#company_name').val(company.name ?? '');
                    $('#company_legal_address').val(company.address ?? '');
                    $('#company_fact_address').val(company.address ?? '');
                    $('#company_inn').val(company.inn ?? '');
                    $('#company_ogrn').val(company.ogrn ?? '');
                    $('#company_okpo').val(company.okpo ?? '');
                    $('#company_kpp').val(company.kpp ?? '');
                    $('#company_phone').val(company.phone ?? '');

                    boottoast.success({
                        message: response.data.message,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                }
            })
                .catch(function (error) {
                    console.log(error.response);
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
                    $('#autofill_btn').prop('disabled', false);
                    $('#autofill_btn .text').prop('hidden', false);
                    $('#autofill_btn .wait').prop('hidden', true);
                });
        }


        /**
         * Загрузка списка регионов
         */
        loadRegions() {
            var ax = axios.get('{{ config('app.api') }}/api/regions');
            ax.then(function (response) {
                if (response.data.regions) {
                    $.each(response.data.regions, function (i, region) {
                        $('#user_address_region').append('<option value="' + region.id + '">(' + region.id + ') – ' + region.name + '</option>')
                        $('#company_address_region').append('<option value="' + region.id + '">(' + region.id + ') – ' + region.name + '</option>')
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
                });
        }


        /**
         * Загрузка списка банков
         */
        loadBanks() {
            var ax = axios.post('{{ config('app.api') }}/api/banks');
            ax.then(function (response) {
                if (response.data.banks) {
                    $('#user_sbp_bank_id').append('<option value=""></option>')
                    $.each(response.data.banks, function (i, bank) {
                        $('#user_sbp_bank_id').append('<option value="' + bank.bik + '">' + bank.name + ' (БИК ' + bank.bik + ')</option>');
                    });
                    $('#user_sbp_bank_id').selectize({
                        create: false,
                        sortField: 'text'
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
                });
        }


        /**
         * Загрузка списка банков
         */
        loadCategories() {
            var ax = axios.get('{{ config('app.api') }}/api/job_categories');
            ax.then(function (response) {
                if (response.data.job_categories) {
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id == null) {
                            $('#user_job_category_id').append('<optgroup id="job_category_' + job_category.id + '" label="' + job_category.name + '"></optgroup>');
                        }
                    });
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id != null) {
                            $('#job_category_' + job_category.parent_id).append('<option value="' + job_category.id + '">' + job_category.name + '</option>');
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
                });
        }


        saveForm() {
            let ths = this;


            let confirm_18_yo = $('#confirm_18_yo').prop('checked');
            if (confirm_18_yo == false) {
                bootbox.dialog({
                    message: 'Подтвердите, что вам исполнилось 18 лет',
                    title: 'Ошибка',
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

            let confirm_private_policy = $('#confirm_private_policy').prop('checked');
            if (confirm_private_policy == false) {
                bootbox.dialog({
                    message: 'Необходимо согласиться на обработку персональных данных',
                    title: 'Ошибка',
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


            $('#error_bag').prop('hidden', true);

            $('#save_btn').prop('disabled', true);
            $('#save_btn .text').prop('hidden', true);
            $('#save_btn .wait').prop('hidden', false);

            let data = {}
            data.registration_type = ths.registration_type;

            data.user = {};
            data.user.lastname = $('#user_lastname').val();
            data.user.firstname = $('#user_firstname').val();
            data.user.patronymic = $('#user_patronymic').val();
            data.user.birth_date = $('#user_birth_date').val();
            data.user.sex = $('#user_sex').val();
            data.user.birth_place = $('#user_birth_place').val();
            data.user.email = $('#user_email').val();
            let passport = $('#user_passport').val().split(' ');
            data.user.passport_series = passport[0] ?? null;
            data.user.passport_number = passport[1] ?? null;
            data.user.passport_issuer = $('#user_passport_issuer').val();
            data.user.passport_issue_date = $('#user_passport_issue_date').val();
            data.user.passport_code = $('#user_passport_code').val();
            data.user.inn = $('#user_inn').val();
            data.user.snils = $('#user_snils').val();
            data.user.address_region = $('#user_address_region').val();
            data.user.address_city = $('#user_address_city').val();
            data.user.address_street = $('#user_address_street').val();
            data.user.address_house = $('#user_address_house').val();
            data.user.address_building = $('#user_address_building').val();
            data.user.address_flat = $('#user_address_flat').val();

            if (ths.registration_type == 'contractor') {
                data.user.sbp_bank_id = $('#user_sbp_bank_id').val();
                data.user.sbp_phone = $('#user_sbp_phone').val();
                data.user.job_category_id = $('#user_job_category_id').val();
            }

            if (ths.registration_type == 'client') {
                data.company = {};
                data.company.full_name = $('#company_full_name').val();
                data.company.name = $('#company_name').val();
                data.company.legal_address = $('#company_legal_address').val();
                data.company.fact_address = $('#company_fact_address').val();
                data.company.inn = $('#company_inn').val();
                data.company.ogrn = $('#company_ogrn').val();
                data.company.okpo = $('#company_okpo').val();
                data.company.kpp = $('#company_kpp').val();
                data.company.phone = $('#company_phone').val();
                data.company.email = $('#company_email').val();
                data.company.address_region = $('#company_address_region').val();
                data.company.address_city = $('#company_address_city').val();
            }

            var ax = axios.post('{{ config('app.api') }}/api/registration', data);
            ax.then(function (response) {

                if (response.status == 400) {
                    bootbox.dialog({
                        title: response.data.title ?? 'Ошибка',
                        message: response.data.message ?? response.statusText,
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: 'Закрыть',
                                className: 'btn-dark'
                            }
                        }
                    });
                    if (response.data.errors) {
                        $('#error_bag').prop('hidden', false)
                        $('#validation_errors').html('')
                        $.each(response.data.errors, function (i, error) {
                            $('#validation_errors').append('<li>' + error + '</li>');
                        });
                    }
                    return;
                }

                boottoast.success({
                    message: response.data.message ?? 'Данные успешно сохранены',
                    title: response.data.title ?? 'Успешно',
                    imageSrc: "/images/logo-sm.svg"
                });
                if (response.data.errors) {
                    $('#error_bag').prop('hidden', false)
                    $('#validation_errors').html('')
                    $.each(response.data.errors, function (i, error) {
                        $('#validation_errors').append('<li>' + error + '</li>');
                    });
                }
                else {
                    window.location = '/projects'
                }
            })
                .catch(function (error) {
                    console.log(error);

                    if (error.response.data.errors) {
                        $('#error_bag').prop('hidden', false)
                        $('#validation_errors').html('')
                        $.each(error.response.data.errors, function (i, error) {
                            $('#validation_errors').append('<li>' + error + '</li>');
                        });
                    }

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
                    $('#save_btn').prop('disabled', false);
                    $('#save_btn .text').prop('hidden', false);
                    $('#save_btn .wait').prop('hidden', true);
                });

        }


    }



</script>
@stop
