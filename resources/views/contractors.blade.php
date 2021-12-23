@extends('layouts.master')

@section('title')
Исполнители
@stop

@section('styles')
<style type="text/css">
    div.info {
        font-size: 8pt;
    }

    div.info div {
        white-space: nowrap;
    }

    .dts_label {
        display: none;
    }

    .input-letter-spacing {
        letter-spacing: 2pt;
        font-family: monospace;
    }
</style>
@stop

@section('scripts')
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/dataTables.scroller.min.js"></script>
@stop

@section('content')
<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->

        <div class="page-header d-print-none mt-4">
            <div class="row align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        Все исполнители
                    </h2>
                </div>
            </div>
        </div>

        <div class="p-4 border rounded mb-4 bg-white">
            <div class="row">
                <div class="col-3 form-group">
                    <label class="form-label text-center">Категория</label>
                    <select id="job_category_filter" class="form-select text-center">
                        <option value="">Любая</option>
                    </select>
                </div>
                <div class="col-3 form-group">
                    <label class="form-label text-center">ИНН</label>
                    <input id="inn_filter" class="form-control input-letter-spacing text-center"
                        placeholder="____________" maxlength="12">
                </div>
                <div class="col-3 form-group">
                    <label class="form-label text-center">Фамилия</label>
                    <input id="lastname_filter" class="form-control text-center">
                </div>
                <div class="col-3 form-group">
                    <label class="form-label">&nbsp;</label>
                    <button id="filter_btn" class="btn btn-white w-100"><b class="fad fa-search me-2"></b>
                        Искать</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0 pb-3">
                <table id="contractors_table" class="table table-striped" style="width:100%"></table>
            </div>
        </div>
    </div>
</div>



<div id="contractor_profile_modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Профиль исполнителя</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="text-muted">Имя</label>
                    <div id="profile_name" class="font-weight-bold"></div>
                </div>

                <div class="form-group mt-3">
                    <label class="text-muted">Основная категорий работ</label>
                    <div id="profile_job_category_name" class="font-weight-bold"></div>
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
                    <label class="text-muted">Телефон</label>
                    <div id="profile_phone" class="font-weight-bold"></div>
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
                    <label class="text-muted">Обо мне</label>
                    <div id="profile_about" class="font-weight-bold"></div>
                </div>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(function () {
        window.InterfaceManager = new InterfaceManagerClass();
        window.InterfaceManager.menuShow('main_menu');
        window.InterfaceManager.menuActive('contractors');
        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe();
        window.InterfaceManager.notificationsCount();
        //window.InterfaceManager.signUnrequestedCount();

        var ContractorsManager = new ContractorsManagerClass;

    });


    class ContractorsManagerClass {

        constructor() {
            let ths = this;
            ths.initAllContractorsDatatable();
            ths.loadCategories();
            ths.bindFilterAllContractorsDatatableBtn();
        }

        /**
         * Binding search_input keyup for searching
         */
        bindSearchProjectContractorsDatatable() {
            let ths = this;

            $('#toggle_search').bind('click', function () {
                $('#search_input').prop('hidden', false);
                $('#search_input').focus();
            })

            $('#search_input').bind('keyup', function () {
                ths.project_contractors_datatable.search(this.value).draw();
            });
        }

        /**
         * Initialization of datatable
         */
        initAllContractorsDatatable() {
            let ths = this;
            let csrf_token = $('meta[name="csrf-token"]').attr('content');

            $.fn.dataTable.ext.classes.sPageButton = "btn btn-outline-primary ";
            $.fn.dataTable.ext.classes.sPageButtonActive = "bg-primary text-light ";
            $.fn.dataTable.ext.classes.sProcessing = "text-center mb-3 mx-auto py-3 bg-dark text-light fixed-bottom  rounded";
            $.fn.dataTable.ext.classes.sInfo = "text-center my-2 mx-auto p-2";
            $.fn.dataTable.ext.classes.sRowEmpty = "d-none";
            $.fn.dataTable.ext.classes.sWrapper = "";

            var settings = {
                ajax: {
                    url: '{{ config('app.api') }}/api/v2/company/contractors/datatable',
                    dataSrc: 'data',
                    type: 'GET',
                    headers: {
                        Authorization: `Bearer ${window.localStorage.getItem('token')}`
                    },
                    error: (error) => {
                        if (error.status === 403) {
                            window.callPermissionModal();
                        }
                    },
                    data: function (d) {
                        d.filter = {};
                        let job_category_filter = $('#job_category_filter').val();
                        if (job_category_filter != '') {
                            d.filter.job_category_id = job_category_filter;
                        }

                        let inn_filter = $('#inn_filter').val();
                        if (inn_filter != '') {
                            d.filter.inn = inn_filter;
                        }

                        let lastname_filter = $('#lastname_filter').val();
                        if (lastname_filter != '') {
                            d.filter.lastname = lastname_filter;
                        }
                    },
                    xhrFields: {
                        withCredentials: true
                    }
                },
                processing: true,
                pageLength: 50,
                dom: '<"p-0 overflow-auto"rt><"text-center"<"mt-2"i><"mt-2 mb-2"p>>',
                sPageButton: "btn btn-dark",
                pagingType: "numbers",
                serverSide: true,
                stateSave: false,
                responsive: false,
                deferRender: true,
                destroy: true,
                paging: true,
                processing: true,

                oLanguage: {
                    sInfo: "<b>_START_</b> &rarr; <b>_END_</b>, из <b>_TOTAL_</b>",
                    sInfoEmpty: "Нет записей для отображения",
                    sInfoFiltered: "(отфильтровано из _MAX_)",
                    sLoadingRecords: "Загрузка...",
                    sProcessing: "<i class='fad fa-spinner fa-pulse'></i> Загрузка...",
                    sEmptyTable: "Нет данных в таблице",
                },
                columns: [
                    {
                        name: 'id', data: 'id', title: 'ID', class: '', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return '<small class="d-block">' + row.id + '</small>';
                        }
                    },
                    {
                        name: 'taxpayer_registred_as_npd', data: 'taxpayer_registred_as_npd', title: 'Статус НПД', class: 'text-center', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            if (row.taxpayer_registred_as_npd == 1) {
                                return '<b class="fad fa-check-circle text-success" titile="Пользователь зарегистрирован как плательщик НПД"></b>'
                            } else {
                                return '<b class="fad fa-question-circle text-muted" titile="Информации о статусе нет"></b>'
                            }
                        }
                    },
                    { name: 'job_category_name', data: 'job_category_name', title: 'Категория', class: '', sortable: true, searchable: true, visible: false },
                    {
                        name: 'name', data: 'name', title: 'ФИО', class: '', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            var b = '<div class="font-weight-bold">' + row.name + '</div>'
                            b += '<small class="d-block text-muted">ИНН: ' + row.inn + '</small>';
                            b += '<small class="d-block text-muted">' + row.job_category_name + '</small>';
                            return b;
                        }
                    },
                    { name: 'inn', data: 'inn', title: 'ИНН', class: 'text-center', sortable: true, searchable: true, visible: false },
                    { name: 'rating', data: 'rating', title: 'Рейтинг', class: 'text-center', sortable: true, searchable: true, visible: true },

                    { name: 'phone', data: 'phone', title: 'Телефон', class: '', sortable: true, searchable: true, visible: false },
                    { name: 'email', data: 'email', title: 'Email', class: '', sortable: true, searchable: true, visible: false },
                    {
                        name: 'contacts', title: 'Контакты', class: '', sortable: false, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            var b = '<div>Телефон: <b>' + row.phone + '</b></div>';
                            b += '<div>Email: <b>' + row.email + '</b></div>';
                            return b;
                        }
                    },
                    { name: 'birth_date', data: 'birth_date', title: 'Дата рождения', class: 'text-center', sortable: true, searchable: true, visible: true },
                    {
                        name: 'id', title: 'Профиль', class: '', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return '<button class="btn btn-white btn-sm btn-profile">Профиль</button>';
                        }
                    },
                ],
                rowCallback: function (row, data, index) {
                    $(row).find('.btn-profile').bind('click', function () {
                        ths.openProfile(data.id);
                    });
                },
                drawCallback: function (settings) {
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                }
            }
            ths.contractors_table = $('#contractors_table').DataTable(settings);
        }


        loadCategories() {
            var ax = axios.get('{{ config('app.api') }}/api/job_categories');
            ax.then(function (response) {
                if (response.data.job_categories) {
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id == null) {
                            $('#job_category_filter').append('<optgroup class="job_category_' + job_category.id + '" label="' + job_category.name + '"></optgroup>');
                            $('#job_category_id').append('<optgroup class="job_category_' + job_category.id + '" label="' + job_category.name + '"></optgroup>');
                        }
                    });
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id != null) {
                            $('#job_category_filter .job_category_' + job_category.parent_id).append('<option value="' + job_category.id + '">' + job_category.name + '</option>');
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
                });
        }


        bindFilterAllContractorsDatatableBtn() {
            let ths = this;
            $('#filter_btn').bind('click', function () {
                ths.contractors_table.ajax.reload(null, false);
            });
        }


        openProfile(user_id) {
            let ths = this;

            var ax = axios.get('{{ config('app.api') }}/api/user/' + user_id);
            ax.then(function (response) {
                if (response.data.user) {

                    var user = response.data.user;

                    $('#contractor_profile_modal').modal('show');
                    $('#profile_name').html(user.name);
                    $('#profile_inn').html(user.inn);
                    $('#profile_email').html(user.email);
                    $('#profile_phone').html(user.phone);
                    $('#profile_birth_place').html(user.birth_place);
                    $('#profile_birth_date').html(user.birth_date);

                    $('#profile_taxpayer_registred_as_npd').html(user.taxpayer_registred_as_npd == 1 ? '<b class="fad fa-check-circle text-success" titile="Пользователь зарегистрирован как плательщик НПД"></b> Зарегистрирован' : '<b class="fad fa-question-circle text-muted" titile="Информации о статусе нет"></b> Не зарегистрирован');



                    $('#profile_rating').html(user.rating);
                    $('#profile_about').html(user.about);
                    $('#profile_job_category_name').html(user.job_category_name);
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


    }


</script>

@stop
