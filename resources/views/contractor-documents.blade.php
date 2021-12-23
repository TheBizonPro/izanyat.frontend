@extends('layouts.master')

@section('title')
Документы
@stop

@section('styles')
<style type="text/css">
    tr.active {
        background-color: #83b7f4 !important;
        color: white !important;
    }

    h2 {
        font-weight: 400 !important;
    }

    .dts_label {
        display: none;
    }

    #project_list {
        max-height: 65vh;
        overflow: auto;
    }

    .action.dropdown-toggle::after {
        content: none;
    }
</style>
@stop

@section('scripts')
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/dataTables.scroller.min.js"></script>
@stop

@section('content')
<input type="text" id="project_id" value="" hidden>
<input type="text" id="status" value="" hidden>
<div class="page-wrapper">
    <div class="container-xxl">
        <!-- Page title -->
        <div class="page-header d-print-none mt-4">
            <div class="row align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div id="project_name" class="page-pretitle"></div>
                    <h2 class="page-title">
                        <b class="fal fa-file me-2"></b> Документы
                    </h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card px-4 pb-2 border-bottom-0 rounded-bottom-0">
                    <div class="row">
                        <div class="col">
                            <div class="form-selectgroup form-selectgroup-tabs d-flex justify-content-start mt-3">
                                <label class="form-selectgroup-item me-4">
                                    <input type="radio" name="document_type" value="all" class="form-selectgroup-input"
                                        checked>
                                    <span class="form-selectgroup-label border-0 w-100">Все
                                        <span class="counter rounded-pill ms-2 text-black-50">0</span>
                                    </span>
                                </label>
                                <label class="form-selectgroup-item me-4  ">
                                    <input type="radio" name="document_type" value="contract"
                                        class="form-selectgroup-input">
                                    <span class="form-selectgroup-label border-0 w-100">Договоры
                                        <span class="counter rounded-pill ms-2 text-black-50">0</span>
                                    </span>
                                </label>
                                <label class="form-selectgroup-item me-4  ">
                                    <input type="radio" name="document_type" value="act" class="form-selectgroup-input">
                                    <span class="form-selectgroup-label border-0 w-100">Акты
                                        <span class="counter rounded-pill ms-2 text-black-50">0</span>
                                    </span>
                                </label>
                                <label class="form-selectgroup-item  ">
                                    <input type="radio" name="document_type" value="work_order"
                                        class="form-selectgroup-input">
                                    <span class="form-selectgroup-label border-0 w-100">Заказ наряды
                                        <span class="counter rounded-pill ms-2 text-black-50">0</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form mt-3">
                                <div class="btn-list justify-content-end">
                                    <div class="dropdown">
                                        <button class="btn btn-white dropdown-toggle" type="button"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <b class="fa fa-download text-success me-2"></b> Скачать
                                        </button>
                                        <ul class="dropdown-menu action" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <button
                                                    data-document-url="{{config('app.api')}}/api/v2/contractor/documents/get/all"
                                                    class="dropdown-item download-documents-all">Все</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" id="download_selected">Выбранные</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card px-4 pb-2 border-top-0">
                    <div class="row mb-4 mt-2">
                        <div class="col-3 form-group">
                            <label class="form-label">Статус</label>
                            <select class="form-select" id="status_change">
                                <option value='' selected>Выберите статус</option>
                                <option value="not_requested">Не запрошена подпись</option>
                                <option value="requested">Ожидает подписания</option>
                                <option value="signed">Подписан</option>
                            </select>
                        </div>
                        <div class="col-3 form-group">
                            <label class="form-label">Дата с</label>
                            <input id="date_from" type="text" class="form-control" placeholder="дд.мм.гггг">
                        </div>
                        <div class="col-3 form-group">
                            <label class="form-label">Дата до</label>
                            <input id="date_till" type="text" class="form-control" placeholder="дд.мм.гггг">
                        </div>
                        <div class="col-3 form-group">
                            <label class="form-label">Заказчик</label>
                            <input id="contractor" type="text" class="form-control" placeholder="Фамилия или ИНН">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div>Выбрано документов: <span class="me-3" id="document_count">0</span>
                                <button type="button" class="btn-select-all btn btn-link p-0">Выбрать все</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0 pb-3">
                        <table id="documents_table" class="table" style="width:100%"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $(function () {

        let project_id = $('#project_id').val();
        window.InterfaceManager = new InterfaceManagerClass();
        this.project_id = project_id;
        if (project_id) {
            window.InterfaceManager.menuShow('project_menu');
            window.InterfaceManager.menuActive('documents');
        } else {
            window.InterfaceManager.menuShow('main_menu');
        }

        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe();
        window.InterfaceManager.notificationsCount();
        // window.InterfaceManager.signUnrequestedCount();


        let DocumentsManager = new DocumentsManagerClass;
        DocumentsManager.createInterface();
    });


    class DocumentsManagerClass {

        constructor() {
            let ths = this;
        }

        createInterface() {
            let ths = this;
            ths.initDocumentsDatatable();
            ths.initSearchPanel();
            ths.loadTypes();
            // ths.loadProjectsList()
            ths.projectSearch();
            ths.changeStatusHandle();
            ths.selectAllDocuments();
            ths.initSignAll();
            ths.initSignSelected();
            ths.downloadSelectedDocuments();
            if (ths.project_id) {
                ths.bindAddNewDocumentBtn();
            }
            $('.download-document-button').bind('click',function(){
                const documentUrl = $(this)?.data('document-url');
                ths.downloadDocument(documentUrl);
            });
            $('.download-documents-all').bind('click',function(){
                const documentUrl = $(this)?.data('document-url');
                console.log(documentUrl);
                ths.downloadDocument(documentUrl);
            });

        }

        initSignAll() {
            let ths = this;
            const $requestAllButton = $('.btn-request-all');
            $requestAllButton.on('click', function (event) {
                ths.requestSignAllDocuments();
            });
        }


        downloadSelectedDocuments() {
            let ths = this;
            const $button = $('#download_selected');
            $button.on('click', function (event) {
                let values = [];
                $('.document_checker').filter((i, e) => {
                    const $element = $(e);
                    if ($element.is(':checked')) {
                        const value = $element.val();
                        values.push(Number(value));
                    }
                });
                if (values.length > 0) {
                    bootbox.dialog({
                        title: 'Скачать выбранные документы',
                        message: 'Сейчас начнется загрузка документов',
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: 'Отмена',
                                className: 'btn-light'
                            },
                            main: {
                                label: '<b class="fa fa-check me-2"></b>Да',
                                className: 'btn-primary',
                                callback: function () {
                                    let url = `{{ config('app.api') }}/api/v2/contractor/documents/get`
                                    for (var i = 0; i < values.length; ++i) {
                                        if (url.indexOf('?') === -1) {
                                            url = url + `?ids[]=` + values[i];
                                        } else {
                                            url = url + `&ids[]=` + values[i];
                                        }
                                    }
                                    ths.downloadDocument(url);
                                }
                            }
                        }
                    });
                }
                else {
                    bootbox.dialog({
                        title: 'Файлы не выбраны',
                        message: 'Выберите файлы',
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: 'Отмена',
                                className: 'btn-light'
                            }
                        }
                    });
                }
            })
        }

        initSignSelected() {
            let ths = this;
            let $button = $('.btn-request-scope');
            $button.on('click', function (event) {
                let values = [];
                $('.document_checker').filter((i, e) => {
                    const $element = $(e);
                    if ($element.is(':checked')) {
                        const value = $element.val();
                        values.push(Number(value));
                    }
                });
                bootbox.dialog({
                    title: 'Отправить на подпись все документы',
                    message: 'Сейчас все неподписанные документы будут отправлены на подпись!',
                    closeButton: false,
                    buttons: {
                        cancel: {
                            label: 'Отмена',
                            className: 'btn-light'
                        },
                        main: {
                            label: '<b class="fa fa-check me-2"></b>Да',
                            className: 'btn-primary',
                            callback: function () {
                                const body = {
                                    'documents': values
                                }
                                const ax = axios.post(`{{ config('app.api') }}/api/v2/company/documents/request_sign_documents_scope`, body);
                                ax.then(function (response) {
                                    boottoast.info({
                                        message: response.data.message,
                                        title: response.data.title ?? 'Информация',
                                        imageSrc: "/images/logo-sm.svg"
                                    });
                                    console.log(response.data);
                                    if (response.data.result) {
                                        var errors = "";


                                        if (Object.keys(response.data.result.errors).length > 0) {
                                            errors = "<b>Ошибки от SignMe:</b><br>"
                                        }
                                        else {
                                            errors = "<b>Документы отправлены на подпись</b><br>"
                                        }

                                        errors += "<ul>";
                                        if (response.data.result.errors) {
                                            $.each(response.data.result.errors, function (phone, error) {
                                                errors += "<li><b>" + phone + ":</b>" + error + "</li>"
                                            })
                                        }
                                        errors += "</ul>";

                                        bootbox.dialog({
                                            title: 'Результаты отправки на подпись в SignMe',
                                            message: errors,
                                            closeButton: false,
                                            buttons: {
                                                cancel: {
                                                    label: 'Закрыть',
                                                    className: 'btn-light'
                                                }
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
                                        // ths.signUnrequestedCount();
                                    });
                            }
                        }
                    }
                });
            })
        }

        selectAllDocuments() {
            const $button = $('.btn-select-all');
            $button.on('click', function (event) {
                if (!($('.document_checker').is(':checked'))) {
                    $('.document_checker').prop('checked', true);
                }
                else {
                    $('.document_checker').prop('checked', false);
                }
            });
        }

        changeStatusHandle() {
            let ths = this;
            const $selectStatusChange = $('#status_change');
            $selectStatusChange.change(function (event) {
                const value = $(event.currentTarget).val();
                const $statusInput = $('#status');
                $statusInput.val(value);
                ths.documents_datatable.ajax.reload()
            });
        }

        projectSearch() {
            let ths = this;
            const $projectSearchInput = $('#project_search');
            $projectSearchInput.on('input', function (element) {
                const value = $(element.currentTarget).val();
                const $list = $('#project_list');
                $list.children().not(":first").remove();
                ths.loadProjectsList(value);
            });
        }

        loadTypes() {
            let ax = axios.get('{{ config('app.api') }}/api/documents/types').then(res => {
                const types = res.data;
                types.forEach(item => {
                    const $element = $(`input[name="document_type"][value="${item.type}"]`);
                    $element.parent().find('.counter').html(item.count);
                })
            });
        }

        downloadDocument(url){
            axios.get(url, {responseType: 'blob', headers:{
                Accept: 'application/zip',
                mode: 'no-cors'
            }
        }).then((response) => {
                window.open(URL.createObjectURL(response.data));
            }).catch((response) => {
                console.error("Could not Download from the backend.", response);
            });
        }

        /**
         * Initialization of datatable
         */
        initDocumentsDatatable() {
            let ths = this;
            let csrf_token = $('meta[name="csrf-token"]').attr('content');

            $.fn.dataTable.ext.classes.sPageButton = "btn btn-outline-primary ";
            $.fn.dataTable.ext.classes.sPageButtonActive = "bg-primary text-light ";
            $.fn.dataTable.ext.classes.sProcessing =
                "text-center mb-3 mx-auto py-3 bg-dark text-light fixed-bottom col-4 rounded";
            $.fn.dataTable.ext.classes.sInfo = "text-center my-2 mx-auto p-2";
            $.fn.dataTable.ext.classes.sRowEmpty = "d-none";
            $.fn.dataTable.ext.classes.sWrapper = "";

            var datatable_url = "{{ config('app.api') }}/api/v2/contractor/documents/datatable";
            var show_project_name_column = true;

            const token = window.localStorage.getItem('token');
            var settings = {
                ajax: {
                    url: datatable_url,
                    dataSrc: 'data',
                    type: 'GET',
                    data: function (d) {
                        d.filter = {};

                        let contractor = $('#contractor').val();
                        if (contractor != '') {
                            d.filter.contractor = contractor;
                        }

                        let date_from = $('#date_from').val();
                        if (date_from != '') {
                            d.filter.date_from = date_from;
                        }

                        let date_till = $('#date_till').val();
                        if (date_till != '') {
                            d.filter.date_till = date_till;
                        }

                        let status = $('#status').val();
                        if (status != '') {
                            d.filter.status = status;
                        }

                        let project_id = $('#project_id').val();
                        if (project_id != '') {
                            d.filter.project_id = project_id;
                        }

                        let document_type = $('input[name="document_type"]:checked').val();
                        if (document_type != '') {
                            d.filter.document_type = document_type;
                        }

                    },
                    xhrFields: {
                        withCredentials: true
                    },
                    headers:{
                        Authorization: `Bearer ${token}`
                    },
                    error:(error)=>{
                        if(error.status===403){
                            window.callPermissionModal();
                        }
                    }
                },
                processing: true,
                pageLength: 10,
                dom: '<"p-0 overflow-auto"rt><"text-center"<"mt-2"i><"mt-2"p>>',
                sPageButton: "btn btn-dark",
                pagingType: "numbers",
                serverSide: true,
                stateSave: false,
                responsive: false,
                deferRender: true,
                oLanguage: {
                    sInfo: "<b>_START_</b> &rarr; <b>_END_</b>, из <b>_TOTAL_</b>",
                    sInfoEmpty: "Нет записей для отображения",
                    sInfoFiltered: "(отфильтровано из _MAX_)",
                    sLoadingRecords: "Загрузка...",
                    sProcessing: "<i class='fad fa-spinner fa-pulse'></i> Загрузка...",
                    sEmptyTable: "Нет данных в таблице",
                },
                'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta) {
                        return `<input type="checkbox" class="document_checker" value="${data}">`;
                    }
                }],
                'order': [
                    [1, 'asc']
                ],
                columns: [{
                    name: 'document_id',
                    data: 'document_id',
                    // title: 'ID',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: true
                },
                {
                    name: 'project_name',
                    data: 'project_name',
                    title: 'Проект',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: show_project_name_column,
                    render: function (data, type, row, meta) {
                        return '<div>' + row.project_name +
                            '</div>';
                    }
                },
                {
                    name: 'document_name',
                    data: 'document_name',
                    title: 'Документ',
                    class: '',
                    sortable: false,
                    searchable: true,
                    visible: true,
                    render: function (data, type, row, meta) {
                        return '<div class="font-weight-regular">' + row.document_name + '</div>';
                    }
                },
                {
                    name: 'contractor_inn',
                    data: 'contractor_inn',
                    title: 'ИНН',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: false
                },
                {
                    name: 'contractor_name',
                    data: 'contractor_name',
                    title: 'Исполнитель',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: true,
                    render: function (data, type, row, meta) {
                        return '<div>' + row.contractor_name + '</div>' +
                            '<small class="d-block">ИНН ' + row.contractor_inn + '</small>';
                    }
                },
                {
                    name: 'order_name',
                    data: 'order_name',
                    title: 'Ведомость',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: false
                },
                {
                    name: 'document_type',
                    data: 'document_type',
                    title: 'Документ',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: false,
                    render: function (data, type, row, meta) {
                        let document_types = {
                            contract: 'Договор',
                            work_order: 'Заказ-наряд',
                            act: 'Акт',
                            signme_anketa: 'Анкета SignMe',
                            reciept: 'Чек',
                            other: 'Другое'
                        };
                        return '<small>' + document_types[row.document_type] + '</small>';
                    }
                },
                {
                    name: 'document_date',
                    data: 'document_date',
                    title: 'Дата',
                    class: 'text-center',
                    sortable: false,
                    searchable: true,
                    visible: false
                },
                {
                    name: 'company_sign_requested',
                    data: 'company_sign_requested',
                    title: 'Отправлено клиенту на подпись',
                    class: '',
                    sortable: false,
                    searchable: false,
                    visible: false
                },
                {
                    name: 'user_sign_requested',
                    data: 'user_sign_requested',
                    title: 'тправлено исполнителю на подпись',
                    class: '',
                    sortable: false,
                    searchable: false,
                    visible: false
                },
                {
                    name: 'is_signed_by_company',
                    data: 'is_signed_by_company',
                    title: 'Подписано клиентом',
                    class: '',
                    sortable: false,
                    searchable: false,
                    visible: false
                },
                {
                    name: 'is_signed_by_user',
                    data: 'is_signed_by_user',
                    title: 'Подписано исполнителем',
                    class: '',
                    sortable: false,
                    searchable: false,
                    visible: false
                },
                {
                    name: 'sign_status',
                    title: 'Cтатус подписания',
                    class: 'text-left',
                    sortable: true,
                    searchable: true,
                    visible: true,
                    render: function (data, type, row, meta) {
                        if (row.document_type == 'reciept') {
                            return "-";
                        }

                        let b = '';

                        b += '<small class="d-block">';
                        if (row.is_signed_by_company == true) {
                            b += '<b class="fad fa-check-circle text-success me-2"></b>';
                            b += 'Клиент: подписано';
                        } else if (row.company_sign_requested == true) {
                            b += '<b class="fad fa-hourglass-half text-info me-2"></b>';
                            b += 'Клиент: запрошено, ожидание';
                        } else {
                            b += '<b class="fad fa-times-circle text-danger me-2"></b>';
                            b += 'Клиент: не запрошено';
                        }
                        b += '</small>';


                        b += '<small class="d-block">';
                        if (row.is_signed_by_user == true) {
                            b += '<b class="fad fa-check-circle text-success me-2"></b>';
                            b += 'Исполнитель: подписано';
                        } else if (row.user_sign_requested == true) {
                            b += '<b class="fad fa-hourglass-half text-info me-2"></b>';
                            b += 'Исполнитель: запрошено, ожидание';
                        } else {
                            b += '<b class="fad fa-times-circle text-danger me-2"></b>';
                            b += 'Исполнитель: не запрошено';
                        }
                        b += '</small>';

                        return b;
                    }
                },
                {
                    name: 'document_link',
                    data: 'document_link',
                    title: 'Действие',
                    class: 'text-center',
                    sortable: true,
                    searchable: true,
                    visible: true,
                    render: function (data, type, row, meta) {
                        return `
                            <div class="dropdown">
                                <button class="action btn dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                    ...
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                  <li><button data-document-url="${row.document_link}" class="dropdown-item download-document-button">Скачать</button></li>
                                  <li><a target="_blank" href="{{ config('app.api') }}/api/document/show/${row.document_id}" class="dropdown-item">Посмотреть</a></li>
                                </ul>
                        </div>`
                    }
                },
                {
                    data: 'document_id',
                    title: 'Выбрать',
                    class: 'text-center',
                    sortable: true,
                    searchable: true,
                    visible: false,
                    render: function (data, type, row, meta) {
                        return '<b class="check fal fa-square"></b>';
                    }
                }],
                rowCallback: function (row, data, index) {
                    $(row).find('b.check').bind('click', function () {
                        if ($(this).hasClass('fa-square')) {
                            $(this).removeClass('fa-square')
                            $(this).addClass('fa-check-square')
                            $('#download_selected_btn').prop('hidden', false);
                        } else {
                            $(this).addClass('fa-square')
                            $(this).removeClass('fa-check-square')
                        }
                    });
                },
                drawCallback: function (settings) {
                    $('.document_checker').change(function (event) {
                        const $checkBox = $(event.currentTarget);
                        const $spanDocumentCount = $('#document_count');
                        if ($checkBox.is(':checked')) {
                            let count = Number($spanDocumentCount.html());
                            count += 1;
                            $spanDocumentCount.html(count)
                        }
                        else {
                            let count = Number($spanDocumentCount.html());
                            count -= 1;
                            $spanDocumentCount.html(count)
                        }
                    });
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                }
            }
            ths.documents_datatable = $('#documents_table').DataTable(settings);
        }

        /**
         * Binding search_input keyup for searching
         */
        initSearchPanel() {
            let ths = this;

            $('#date_from').mask('99.99.9999', {
                placeholder: 'дд.мм.гггг'
            });
            $('#date_till').mask('99.99.9999', {
                placeholder: 'дд.мм.гггг'
            });

            $('input[name="document_type"]').bind('change', function () {
                ths.documents_datatable.ajax.reload();
            });

            $('#contractor, #date_from, #date_till, input[name="document_type"], #filter_project_id').bind('change',
                function () {
                    ths.documents_datatable.ajax.reload();
                });

            if (ths.project_id) {

            } else {
                // ths.loadProjects();
                $('#project_id_wrapper').prop('hidden', false);
            }
        }


        /**
         * Загрузка проектов
         */
        loadProjects() {
            let ths = this;
            $("#filter_project_id").prop('disabled', true);
            $("#filter_project_id").html("<option value=''>Все проекты</option>");

            var ax = axios.get('{{ config('app.api') }}/api/v2/company/projects');
            ax.then(function (response) {
                if (response.data.projects) {
                    $.each(response.data.projects, function (i, project) {
                        $("#filter_project_id").append("<option value='" + project.id + "'>" +
                            project.name + "</option>");
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
                    $("#filter_project_id").prop('disabled', false);
                });
        }

        requestSignAllDocuments() {
            let ths = this;
            bootbox.dialog({
                title: 'Отправить на подпись все документы',
                message: 'Сейчас все неподписанные документы будут отправлены на подпись!',
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: '<b class="fa fa-check me-2"></b>Да',
                        className: 'btn-primary',
                        callback: function () {
                            var ax = axios.get('{{ config('app.api') }}/api/v2/company/documents/request_sign_documents');
                            ax.then(function (response) {
                                boottoast.info({
                                    message: response.data.message,
                                    title: response.data.title ?? 'Информация',
                                    imageSrc: "/images/logo-sm.svg"
                                });
                                console.log(response.data);
                                if (response.data.result) {
                                    var errors = "";


                                    if (Object.keys(response.data.result.errors).length > 0) {
                                        errors = "<b>Ошибки от SignMe:</b><br>"
                                    }
                                    else {
                                        errors = "<b>Документы отправлены на подпись</b><br>"
                                    }

                                    errors += "<ul>";
                                    if (response.data.result.errors) {
                                        $.each(response.data.result.errors, function (phone, error) {
                                            errors += "<li><b>" + phone + ":</b>" + error + "</li>"
                                        })
                                    }
                                    errors += "</ul>";

                                    bootbox.dialog({
                                        title: 'Результаты отправки на подпись в SignMe',
                                        message: errors,
                                        closeButton: false,
                                        buttons: {
                                            cancel: {
                                                label: 'Закрыть',
                                                className: 'btn-light'
                                            }
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
                                    // ths.signUnrequestedCount();
                                });
                        }
                    }
                }
            });
        }

        bindFilterProjectContractorsDatatableBtn() {
            let ths = this;
            $('#filter_btn').unbind('click').bind('click', function () {
                ths.project_contractors_datatable.ajax.reload(null, false);
            });

            $('#job_category_filter, #inn_filter, #lastname_filter').unbind('change').bind('change', function () {
                ths.project_contractors_datatable.ajax.reload(null, false);
            });
        }

        bindAddNewDocumentBtn() {
            let ths = this;

            $('#add_document_btn').prop('hidden', false);


            ths.hideSelectContractorBlock();
            ths.hideUploadDocumentBlock();
            ths.hideDocumentPreviewBlock();

            $('#add_document_btn').bind('click', function () {
                $('#add_document_modal').modal('show');
                ths.showSelectContractorBlock()
            });

            $('#add_document_modal').bind('hidden.bs.modal', function () {
                ths.hideSelectContractorBlock();
                ths.hideUploadDocumentBlock();
                ths.hideDocumentPreviewBlock();
            });
        }


        showSelectContractorBlock() {
            let ths = this;
            ths.initProjectContractorsDatatable();
            ths.loadCategories();
            ths.bindFilterProjectContractorsDatatableBtn();
            ths.project_contractors_datatable.ajax.reload(null, false);
            $('#select_contractor_block').prop('hidden', false);
        }


        hideSelectContractorBlock() {
            let ths = this;
            ths.destroyProjectContractorsDatatable();
            $('#select_contractor_block').prop('hidden', true);
        }


        showUploadDocumentBlock(user_id) {
            let ths = this;
            ths.document_user_id = user_id;

            $('#upload_document_block').prop('hidden', false);

            $('#upload_document_file').unbind('change').bind('change', function (e) {
                ths.uploadDocument(e.target.files[0]);
            });

            $('#upload_document_btn').unbind('click').bind('click', function () {
                $('#upload_document_file').trigger('click');
            });

            $('#back_to_select_contractor_btn').unbind('click').bind('click', function () {
                ths.showSelectContractorBlock();
                ths.hideUploadDocumentBlock();
            })
        }

        hideUploadDocumentBlock() {
            let ths = this;
            $('#upload_document_block').prop('hidden', true);
        }


        showDocumentPreviewBlock() {
            let ths = this;
            $('#preview_document_block').prop('hidden', false);
            $('#document_preview_iframe').attr('src', ths.new_document_preview);

            $('#back_to_upload_document_btn').unbind('click').bind('click', function () {
                ths.hideDocumentPreviewBlock();
                ths.showUploadDocumentBlock();
            });

            $('#save_document_btn').unbind('click').bind('click', function () {
                ths.saveDocument();
            });

            $('#new_document_date').mask('99.99.9999', {
                placeholder: 'дд.мм.гггг'
            });
        }


        hideDocumentPreviewBlock() {
            let ths = this;
            $('#preview_document_block').prop('hidden', true);
            $('#document_preview_iframe').attr('src', '');
        }


        /**
         * Initialization of datatable
         */
        initProjectContractorsDatatable() {
            let ths = this;
            let csrf_token = $('meta[name="csrf-token"]').attr('content');

            $.fn.dataTable.ext.classes.sPageButton = "btn btn-outline-primary ";
            $.fn.dataTable.ext.classes.sPageButtonActive = "bg-primary text-light ";
            $.fn.dataTable.ext.classes.sProcessing =
                "text-center mb-3 mx-auto py-3 bg-dark text-light fixed-bottom  rounded";
            $.fn.dataTable.ext.classes.sInfo = "text-center my-2 mx-auto p-2";
            $.fn.dataTable.ext.classes.sRowEmpty = "d-none";
            $.fn.dataTable.ext.classes.sWrapper = "";

            const token = window.localStorage.getItem('token');
            console.log(ths.project_id);
            var settings = {
                ajax: {
                    url: `{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/contractors/datatable`,
                    dataSrc: 'data',
                    type: 'GET',
                    data: function (d) {
                        d.filter = {};
                        let job_category_filter = $('#job_category_filter').val();
                        if (job_category_filter) {
                            d.filter.job_category_id = job_category_filter;
                        }

                        let inn_filter = $('#inn_filter').val();
                        if (inn_filter) {
                            d.filter.inn = inn_filter;
                        }

                        let lastname_filter = $('#lastname_filter').val();
                        if (lastname_filter) {
                            d.filter.lastname = lastname_filter;
                        }
                    },
                    xhrFields: {
                        withCredentials: true
                    },

                    headers:{
                        Authorization: `Bearer ${token}`
                    },

                    error:(error)=>{
                        if(error.status===403){
                            window.callPermissionModal();
                        }
                    }
                },
                processing: true,
                pageLength: 50,
                dom: '<"p-0 overflow-auto"rt><"text-center"<"mt-2"i><"mt-2"p>>',
                sPageButton: "btn btn-dark",
                pagingType: "numbers",
                serverSide: true,
                stateSave: false,
                responsive: false,
                deferRender: true,
                destroy: true,
                paging: true,
                scrollY: 200,
                scrollCollapse: false,
                processing: false,
                scroller: {
                    rowHeight: 36,
                    serverWait: 100,
                    boundaryScale: 0.7
                },
                oLanguage: {
                    sInfo: "<b>_START_</b> &rarr; <b>_END_</b>, из <b>_TOTAL_</b>",
                    sInfoEmpty: "Нет записей для отображения",
                    sInfoFiltered: "(отфильтровано из _MAX_)",
                    sLoadingRecords: "Загрузка...",
                    sProcessing: "<i class='fad fa-spinner fa-pulse'></i> Загрузка...",
                    sEmptyTable: "Нет данных в таблице",
                },
                columns: [{
                    name: 'name',
                    data: 'name',
                    title: 'ФИО',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: true
                },
                {
                    name: 'inn',
                    data: 'inn',
                    title: 'ИНН',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: true
                },
                {
                    name: 'job_category_name',
                    data: 'job_category_name',
                    title: 'Категория работ',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: true
                },
                {
                    name: 'created_date',
                    data: 'created_date',
                    title: 'Дата регистрации',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: true
                },
                {
                    name: 'id',
                    data: 'id',
                    title: 'Выбрать',
                    class: '',
                    sortable: true,
                    searchable: true,
                    visible: true,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-sm btn-primary btn-select">Выбрать</button>';
                    }
                },
                ],
                rowCallback: function (row, data, index) {
                    $(row).find('button.btn-select').bind('click', function () {
                        ths.showUploadDocumentBlock(data.id);
                        ths.hideSelectContractorBlock();
                    });
                },
                drawCallback: function (settings) {
                    $('.document_checker').change(function (event) {
                        const $checkBox = $(event.currentTarget);
                        const $spanCount = $('#document');
                        if ($checkBox.is(':checked')) {

                        }
                    });
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                }
            }
            ths.project_contractors_datatable = $('#project_contractors_datatable').DataTable(settings);
        }


        destroyProjectContractorsDatatable() {
            let ths = this;
            if (ths.project_contractors_datatable) {
                try {
                    ths.project_contractors_datatable.destroy();
                    delete (ths.project_contractors_datatable);
                } catch (e) { }
            }
        }


        uploadDocument(file) {
            let ths = this;

            $('#upload_document_btn').prop('disabled', true);
            $('#upload_document_btn .text').prop('hidden', true);
            $('#upload_document_btn .wait').prop('hidden', false);

            var formData = new FormData();
            formData.append('file', file);

            var ax = axios.post('{{ config('app.api') }}/api/v2/company/documents/upload', formData);
            ax.then(function (response) {
                if (response.data.message) {
                    boottoast.success({
                        message: response.data.message,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                }
                if (response.data.preview) {
                    ths.new_document_path = response.data.path;
                    ths.new_document_preview = response.data.preview;
                    ths.showDocumentPreviewBlock();
                    ths.hideUploadDocumentBlock();
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

                    $('#upload_document_btn').prop('disabled', false);
                    $('#upload_document_btn .text').prop('hidden', false);
                    $('#upload_document_btn .wait').prop('hidden', true);

                    $('#upload_document_file').val("");
                });
        }


        saveDocument() {

            let ths = this;

            let new_document_type = $('#new_document_type').val();
            let new_document_number = $('#new_document_number').val();
            let new_document_date = $('#new_document_date').val();
            let new_document_path = ths.new_document_path;
            let new_document_user_id = ths.document_user_id;

            if (new_document_type == '' || new_document_number == '' || new_document_date == '' ||
                new_document_path == '' || new_document_user_id == '') {
                bootbox.dialog({
                    message: 'Необходимо заполнить все поля',
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

            $('#save_document_btn').prop('disabled', true);
            $('#save_document_btn .text').prop('hidden', true);
            $('#save_document_btn .wait').prop('hidden', false);

            let data = {}
            data.document_type = new_document_type;
            data.document_number = new_document_number;
            data.document_date = new_document_date;
            data.document_path = new_document_path;
            data.document_user_id = new_document_user_id;

            var ax = axios.post(`{{ config('app.api') }}/v2/company/projects/${ths.project_id}/document`, data);
            ax.then(function (response) {
                if (response.data.message) {
                    boottoast.success({
                        message: response.data.message,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });

                    $('#add_document_modal').modal('hide');
                    ths.documents_datatable.ajax.reload();
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
                    $('#save_document_btn').prop('disabled', false);
                    $('#save_document_btn .text').prop('hidden', false);
                    $('#save_document_btn .wait').prop('hidden', true);
                });


        }


        /**
         * Загрузка списка банков
         */
        loadCategories() {
            var ax = axios.get('{{ config('app.api') }}/api/job_categories');
            ax.then(function (response) {
                if (response.data.job_categories) {
                    $('#job_category_filter').html('<option value="">Любая</option>')
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id == null) {
                            $('#job_category_filter').append('<optgroup class="job_category_' +
                                job_category.id + '" label="' + job_category.name +
                                '"></optgroup>');
                        }
                    });
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id != null) {
                            $('#job_category_filter .job_category_' + job_category.parent_id)
                                .append('<option value="' + job_category.id + '">' + job_category
                                    .name + '</option>');
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
                .finally(function () { });
        }


    }
</script>


@include('add-document')

@stop
