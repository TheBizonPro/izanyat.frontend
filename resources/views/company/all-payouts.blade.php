@extends('layouts.master')

@section('title')
Платежи
@stop

@section('styles')
<style type="text/css">
    .modal.show .modal-dialog {
        transform: none;
        border: 1px solid #ccc;
    }

    .project-button-selected {
        background-color: #b8b8b8 !important;
    }

    .project-select-button {
        margin-top: 2px;
        outline: none !important;
        border-radius: 5px;
        border: none;
        text-align: left;
        padding: 10px 15px;
        background: none;
    }

    .project-select-button:hover {
        transition: .2s;
        filter: brightness(0.85);
    }

    #view_task_offers {
        max-height: 400px;
        overflow-y: scroll;
        scroll-behavior: smooth;
    }
</style>
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
                    <div id="project_name" class="page-pretitle"></div>
                    <h1 class="page-title">
                        Платежи
                    </h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2" id="projects_list_payouts" hidden>
                <h4>Проекты</h4>

                <div class="mb-3">
                    <input class="form-control" id="projects_search_input" placeholder="Поиск">
                </div>
                <div class="projects-select-list"></div>
            </div>

            <div class="col">
                <div class="card px-4 pb-2">
                    <div class="row mb-2 mt-3">
                        <div class="col-3 form-group">
                            <label class="form-label">Исполнитель</label>
                            <input id="contractor_search" type="text" class="form-control"
                                placeholder="Фамилия или ИНН">
                        </div>
                        <div class="col-3 form-group">
                            <label class="form-label">Статус</label>
                            <select class="form-select" aria-label="Default select example" id="payout_status_select">
                                <option value="not_selected" selected>Все</option>
                                <option value="error">Ошибка</option>
                                <option value="complete">Успешно выполнен</option>
                                <option value="process">В процессе</option>
                                <option value="canceled">Аннулирован</option>
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
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="form mt-3">
                                <div class="btn-list justify-content-between">
                                    <button class="btn btn-white" id="clear_payout_table_filters">Сбросить
                                        фильтры</button>

                                    <button id="add_payouts_excel_btn" class="btn btn-load-payouts btn-white me-2" hidden>
                                        <b class="fa fa-plus-circle text-success me-2"></b>Загрузить ведомость выплат
                                    </button>

                                    <div class="dropdown">
                                        <button class="btn btn-white dropdown-toggle" type="button" hidden
                                            id="reciepts_dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <b class="fa fa-download text-success me-2"></b> Выгрузить чеки
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="reciepts_dropdown">
                                            <li>
                                                <button class="dropdown-item" hidden
                                                    id="download_selected_receipt">Выбранные</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" hidden id="download_all_filtered_receipt">Все, с
                                                    применением фильтров</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" hidden id="download_all_receipt">Все</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body p-0 pb-3">
                        <table id="payouts_table" class="table" style="width:100%"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="add_payouts_modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавление ведомости выплат</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="load_tasks_table_wrapper" class="modal-body text-center">
                <div class="alert alert-info">
                    Загрузите файл с ведомостью выплат подготовленный в Excel или другом табличном редакторе. Файл
                    необходимо сохранить в формате xls/xlsx. Колонки файла должны соответствовать <a
                        href="/files/tasks_template.xlsx">шаблону</a>
                </div>
                <input type="file" id="load_payouts_file_input" hidden>
                <button id="load_payouts_file_btn" class="btn btn-lg btn-success btn-pill">
                    <div class="text"><b class="fad fa-file-excel me-2"></b>Загрузить файл Excel</div>
                    <div class="wait" hidden><b class="fad fa-spinner fa-pulse me-2"></b>Загрузка</div>
                </button>

                <div id="preview_payouts_table_wrapper" style="display:none;">
                    <div class="payment_matched mt-2">
                        <h2 id="payments-success-amount-text" class="text-green"></h2>
                    </div>

                    <div class="payment_errors d-flex flex-column align-items-center justify-content-center">
                        <h3 class="text-red">Ошибки</h3>

                        <ul class="list-group col-10 text-center excel-payments-errors"></ul>
                    </div>

                    <div class="payment_conflicts">
                        <h3 class="text-yellow">Конфликты</h3>
                        <small>Эти поля содержат 2 или более подходящих задач, выберите нужные</small>

                        <div class="payment-conflicts-resolver" hidden></div>
                    </div>

                    <button class="btn btn-danger mt-5" id="cancelExcelPayments">Отмена</button>
                    <button class="btn btn-success mt-5" id="submitExcelPayments">Продолжить</button>
                </div>
            </div>

            <div class="modal-footer text-end" hidden>
                <button id="add_tasks_back" class="btn btn-white">Назад</button>
                <button id="tasks_import_btn" class="btn btn-primary">
                    <div class="text">Добавить</div>
                    <div class="wait" hidden><b class="fad fa-spinner fa-pulse"></b>Импорт данных...</div>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        window.InterfaceManager = new InterfaceManagerClass;
        window.InterfaceManager.menuShow('main_menu');
        window.InterfaceManager.menuActive('payouts');
        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe();
        window.InterfaceManager.notificationsCount();
        //window.InterfaceManager.signUnrequestedCount();

        let PayoutsManager = new PayoutsManagerClass;
        PayoutsManager.project_id = $('#project_id').val()
        PayoutsManager.loadProjects()
        PayoutsManager.initPayoutsDatatable()
        PayoutsManager.initProjectsSearchPanel()
        PayoutsManager.initDownloadButton()
        PayoutsManager.bindAddPayoutsModalActions()

        var ProjectManager = new ProjectManagerClass;
        ProjectManager.project_id = $('#project_id').val();
    });



    class PayoutsManagerClass {
        constructor() {
            this.projects = {}
            this.import_payouts_data = {}
        }

        loadProjects() {
            axios.get('{{ config('app.api') }}/api/v2/company/projects')
                .then(r => r.data)
                .then(function (r) {
                    r.projects.forEach(project => {
                        this.projects[project.id] = project
                    })

                }.bind(this))
                .then(() => this.renderProjectsList())
        }

        renderProjectsList() {
            let $buttons = []

            for (const [key, project] of Object.entries(this.projects)) {
                $buttons.push('<button type="button" class="list-group-item-action project-select-button" data-id="' + project.id + '">' + project.name + '</button>')
            }

            $('.projects-select-list').html($buttons)

            this.initSearchPanel()
        }

        bindAddPayoutsModalActions() {
            let $payoutsModal = $('#add_payouts_modal')
            let $showPayoutsModalButton = $('#add_payouts_excel_btn')

            $('#load_payouts_file_btn').bind('click', function () {
                $('#load_payouts_file_input').val("");
                $('#load_payouts_file_input').trigger('click');
            });

            $('#load_payouts_file_input').change((e) => {
                this.uploadPayoutsFile(e.target.files[0])
            })

            $showPayoutsModalButton.click((evt) => {
                $payoutsModal.modal('show')
            })

            this.bindPayoutsExcelButtons()
        }

        uploadPayoutsFile(file) {
            let ths = this;

            let $loadFilesButton = $('#load_payouts_file_btn')

            $loadFilesButton.find(".btn-upload-payout-file").prop('disabled', true);
            $loadFilesButton.find(".btn-upload-payout-file .text").prop('hidden', true);
            $loadFilesButton.find(".btn-upload-payout-file .wait").prop('hidden', false);

            var formData = new FormData();
            formData.append('file', file);

            var ax = axios.post('{{ config('app.api') }}/api/v2/company/payouts/simulate', formData)
                .then(r => r.data)
                .then(resp => {
                    ths.import_payouts_data.matched = resp.matched

                    ths.displayPayoutsExcelActions(resp)
                })
        }

        displayPayoutsExcelActions(resp) {
            $('#load_payouts_file_btn').hide()
            $('#preview_payouts_table_wrapper').show()

            $('#payments-success-amount-text').html(`Успешно найдено задач: ${resp.matched.length}`)

            if (resp.errors?.length > 0) {
                this.displayExcelPaymentErrors(resp.errors)
            }

            this.displayExcelPaymentConflicts(resp.conflict)
        }

        displayExcelPaymentErrors(errors) {
            let $errorsList = $('.excel-payments-errors')

            errors.forEach(err => {
                $errorsList.append(`<li class="list-group-item">${err}</li>`)
            })
        }

        displayExcelPaymentConflicts(conflicts) {
            let $conflictsList = $('.payment-conflicts-resolver')
            let conflictsHtml = ''


            for (const [excelRow, conflictTasks] of Object.entries(conflicts)) {

                let conflictHtml = ''

                for (let task of conflictTasks) {
                    conflictHtml +=
                        `<div class="col-12 mt-2">
                            <label class="conflict-checkbox-row">
                                <input class="form-check-input payout-conflict-checkbox" type="checkbox" data-task-id='${task.id}'>
                                <span>#${task.id} "${task.name}", ${task.address ?? 'адрес не указан'}, ${task.sum}р.</span>
                            </label>
                       </div>`
                }

                conflictsHtml +=
                    `<div class="conflict-row">
                                <h4>Поле ${excelRow} содержит конфликты, выберите необходимые варианты</h4>
                                <div class="conflict-tasks-list">${conflictHtml}</div>
                            </div>`
            }

            $conflictsList.append(conflictsHtml)
            $conflictsList.attr('hidden', false)
        }

        /**
         * Initialization of datatable
         */
        initPayoutsDatatable() {
            let ths = this;
            let csrf_token = $('meta[name="csrf-token"]').attr('content');

            $.fn.dataTable.ext.classes.sPageButton = "btn btn-outline-primary ";
            $.fn.dataTable.ext.classes.sPageButtonActive = "bg-primary text-light ";
            $.fn.dataTable.ext.classes.sProcessing = "text-center mb-3 mx-auto py-3 bg-dark text-light fixed-bottom col-4 rounded";
            $.fn.dataTable.ext.classes.sInfo = "text-center my-2 mx-auto p-2";
            $.fn.dataTable.ext.classes.sRowEmpty = "d-none";
            $.fn.dataTable.ext.classes.sWrapper = "";

            var settings = {
                ajax: {
                    url: '{{ config('app.api') }}/api/v2/company/payouts/datatable',
                    dataSrc: 'data',
                    type: 'GET',
                    xhrFields: {
                        withCredentials: true
                    },
                    data: d => {
                        d.filter = this.getPayoutsFilter()
                    },
                    headers: {
                        Authorization: `Bearer ${window.localStorage.getItem('token')}`
                    },
                    error:(error)=>{
                        if(error.status === 403){
                            window.callPermissionModal();
                        }
                    }
                },
                processing: true,
                pageLength: 10,

                dom: '<"p-0 overflow-auto"rt><"text-center"<"mt-2"i><"mt-2 mb-2"p>>',
                sPageButton: "btn btn-dark",
                pagingType: "numbers",
                serverSide: true,
                stateSave: false,
                responsive: false,
                deferRender: false,
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
                        name: 'checkbox', data: 'checkbox', title: '', sortable: false, searchable: false, visible: true,
                        render: (data, type, row, meta) => {
                            return `
                                <div class="d-flex justify-content-center align-items-center"><input type="checkbox" class="form-check-input payout-row-checkbox" data-id="${row.id}"></div>`
                        }
                    },
                    { name: 'id', data: 'id', title: 'ID', sortable: true, searchable: true, visible: true },
                    { name: 'project_id', data: 'project_id', sortable: true, searchable: true, visible: false },
                    {
                        name: 'user_name', data: 'user_name', title: 'Получатель', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            var b = '<div class="font-weight-bold">' + row.user_name + '</div>'
                            b += '<small class="d-block text-muted">ИНН: ' + row.user_inn + '</small>';
                            return b;
                        }
                    },
                    { name: 'project_name', data: 'project_name', title: 'Проект', sortable: true, searchable: true, visible: true },
                    {
                        name: 'task_name', data: 'task_name', title: 'Назначение', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return `<a href="/project/${row.project_id}/tasks/${row.task_id}" target="_blank">${row.task_name}</a>`;
                        }
                    },
                    { name: 'task_id', data: 'task_id', title: 'Задача ID', sortable: true, searchable: true, visible: false },
                    { name: 'user_inn', data: 'user_inn', title: 'ИНН', sortable: true, searchable: true, visible: false },
                    { name: 'job_category_name', data: 'job_category_name', title: 'Категория работ', sortable: true, searchable: true, visible: false },


                    {
                        name: 'created_datetime', data: 'created_datetime', title: 'Дата', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return '<small>' + row.created_datetime + '</small>';
                        }
                    },

                    {
                        name: 'receipt_url', data: 'receipt_url', title: 'Чек ФНС', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            if (row.receipt_url != null) {
                                return '<a class="btn btn-sm btn-white btn-pill" target="_blank" href="' + row.receipt_url + '"><b class="fad fa-receipt me-2"></b>Чек ФНС</a>';
                            }
                            return '–';
                        }
                    },

                    {
                        name: 'status', data: 'status', title: 'Статус', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            let statuses = {
                                'draft': '<small class="badge bg-dark text-white">Черновик</small>',
                                'process': '<small class="badge bg-info text-white">В процессе</small>',
                                'complete': '<small class="badge bg-success text-white">Успешно</small>',
                                'error': '<small class="badge bg-danger text-white">Ошибка</small>',
                                'canceled': '<small class="badge bg-danger text-white">Аннулирован</small>',
                            }
                            return statuses[row.status];
                        }
                    },
                    { name: 'sum', data: 'sum', title: 'Сумма', sortable: true, searchable: true, visible: true },
                    {
                        name: 'description', data: 'description', title: 'Инфо', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            if (row.description != null) {
                                var b = '<div class="payout_description p-1 rounded border" hidden>';
                                b += '<small>' + row.description + '</small>';
                                b += '</div>';
                                b += '<button class="btn btn-sm btn-pill btn-show-description"><b class="fad fa-info-circle"></b></button>';
                                return b;
                            }
                            return '';
                        }
                    },
                    {
                        name: 'actions', data: 'actions', sortable: false, searchable: false, visible: true,
                        render: function (data, type, row, meta) {
                            if (row.status === 'canceled' || row.status === 'error')
                                return '<div class="d-flex justify-content-start"><button class="btn btn-sm btn-secondary repay-button" data-id="' + row.id + '" hidden>Повторить</button></div>'

                            return '-'
                        }
                    },
                ],
                rowCallback: function (row, data, index) {
                    $(row).find('button.btn-show-description').bind('click', function () {
                        $(this).prop('hidden', true)
                        $(row).find('.payout_description').prop('hidden', false);
                    });
                },
                drawCallback: function (settings) {
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                    $('.repay-button').click(e => ths.repayButtonClicked(e))
                }
            }
            ths.payouts_table = $('#payouts_table').DataTable(settings);
        }

        bindPayoutsExcelButtons() {
            $('#cancelExcelPayments').click((evt) => {
                this.clearPayoutsExcelForm()
            })

            $('#submitExcelPayments').click((evt) => {
                this.submitExcelPayments()
            })
        }

        clearPayoutsExcelForm() {
            $('#payments-success-amount-text').html('')
            $('.payment-conflicts-resolver').html('')
            $('.excel-payments-errors').html('')
            $('#preview_payouts_table_wrapper').hide()
            $('#load_payouts_file_btn').show()
        }

        repayButtonClicked(evt) {
            let ths = this;
            let $button = $(evt.target)
            let payoutId = $button.data('id')


            bootbox.dialog({
                title: 'Повторить платёж?',
                message: `Вы действительно повторить платёж №${payoutId}?`,
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: '<b class="fa fa-check me-2"></b>Да, повторить оплату',
                        className: 'btn-success',
                        callback: function () {
                            axios.post(`{{ config('app.api') }}/api/v2/company/payouts/${payoutId}/repay`)
                                .then(function (response) {
                                    if (response.data.message) {
                                        boottoast.success({
                                            message: response.data.message,
                                            title: response.data.title ?? 'Успешно',
                                            imageSrc: "/images/logo-sm.svg"
                                        });
                                    }

                                    ths.tasks_datatable.ajax.reload();
                                })
                                .catch(function (error) {
                                    console.error(error);
                                    bootbox.dialog({
                                        title: error.response.data.title ?? 'Ошибка',
                                        message: error.response.data.error ?? 'Что то пошло не так',
                                        closeButton: false,
                                        buttons: {
                                            cancel: {
                                                label: 'Закрыть',
                                                className: 'btn-dark'
                                            }
                                        }
                                    });
                                })

                        }
                    }
                }
            });
        }


        submitExcelPayments() {
            // спарсим конфликты, добавим их к совпавшим и шлем запрос на оплату
            let tasksToSubmit = Object.values(this.import_payouts_data.matched)
                .map(e => e.id)

            $('.payout-conflict-checkbox:checked').each((i, e) => {
                let taskId = $(e).data('task-id')
                tasksToSubmit.push(taskId)
            })

            axios.post('{{ config('app.api') }}/api/v2/company/payouts/mass_pay ', {
                tasks: tasksToSubmit
            })
                .then(resp => {
                    boottoast.success({
                        message: 'Задачи отправлены на оплату',
                        title: 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                    this.clearPayoutsExcelForm()
                })
        }

        initDownloadButton() {
            $('#download_all_receipt, #download_selected_receipt, #download_all_filtered_receipt').click(e => {
                let url = '{{ config('app.api') }}/api/v2/company/receipts/download'
                let button = e.target.id

                if (button === 'download_all_receipt') {
                    window.open(url, '_blank')
                    console.log(url)
                }

                if (button === 'download_all_filtered_receipt') {
                    let filter = this.getPayoutsFilter()

                    url = url + '?' + this.serializeToUrl({ filter })
                    console.log(url)

                }

                if (button === 'download_selected_receipt') {
                    let selected = $('.payout-row-checkbox:checked').get().map((e) => $(e).data('id'))

                    if (selected.length === 0)
                        return

                    let requestData = {
                        ids: selected
                    }

                    console.log(requestData)

                    url = url + '?' + this.serializeToUrl(requestData)
                }

                window.open(url, '_blank')
            })
        }

        serializeToUrl(obj, prefix) {
            let str = [],
                p;
            for (p in obj) {
                if (obj.hasOwnProperty(p)) {
                    let k = prefix ? prefix + "[" + p + "]" : p,
                        v = obj[p];
                    str.push((v !== null && typeof v === "object") ?
                        this.serializeToUrl(v, k) :
                        encodeURIComponent(k) + "=" + encodeURIComponent(v));
                }
            }
            return str.join("&");
        }

        getSelectedProjects() {
            let projectsIds = []

            $('.project-select-button').each((index, element) => {
                let $element = $(element)

                if ($element.hasClass('project-button-selected'))
                    projectsIds.push($element.data('id'))
            })

            return projectsIds
        }

        initSearchPanel() {
            let $select = $('#payout_status_select')

            $select.change(() => {
                this.reloadTable()
            })

            let $dateFrom = $('#date_from')
            $dateFrom.mask('99.99.9999', {
                placeholder: 'дд.мм.гггг'
            });

            let $dateTill = $('#date_till')
            $dateTill.mask('99.99.9999', {
                placeholder: 'дд.мм.гггг'
            });


            let $contractorSearch = $('#contractor_search')

            $contractorSearch.on('input', () => {
                this.reloadTable()
            })

            $('.project-select-button').click((evt) => {
                $(evt.target).toggleClass('project-button-selected')
                this.reloadTable()
            })

            $dateFrom.on('keyup', _ => this.onDatesUpdate(_))
            $dateTill.on('keyup', _ => this.onDatesUpdate(_))

            $('#clear_payout_table_filters').click(_ => this.clearFilters())
        }

        initProjectsSearchPanel() {
            let $search = $('#projects_search_input')

            $search.on('input', () => {
                let searchString = $('#projects_search_input').val().toLowerCase()

                $('.project-select-button').each((index, element) => {
                    let $elem = $(element)
                    let projectName = $elem.text().toLowerCase()

                    if (projectName.search(searchString) === -1) {
                        $elem.hide()
                        $elem.removeClass('project-button-selected')
                    } else {
                        $elem.show()
                        console.log('показываем')
                    }
                })
            })
        }

        clearFilters() {
            console.log('clearing')
            let $contractorSearch = $('#contractor_search')
            let $dateFrom = $('#date_from')
            let $dateTill = $('#date_till')
            let $statusSelect = $('#payout_status_select')

            $contractorSearch.val('')
            $dateFrom.val('')
            $dateTill.val('')
            $statusSelect.val('not_selected')

            $('.project-select-button').each((i, e) => $(e).removeClass('project-button-selected'))


            this.reloadTable()
        }

        onDatesUpdate(evt) {
            let $target = $(evt.target)
            let date = this.getDatesFromMask($target.val())

            if (date.length === 10 || date.length === 2)
                this.reloadTable()
        }

        getDatesFromMask(mask) {
            return mask.replace(/(?!-)[^0-9.]/g, "")
        }

        getPayoutsFilter() {
            let filter = {}
            let $contractorName = $('#contractor_search')
            let $dateFrom = $('#date_from')
            let $dateTill = $('#date_till')

            filter.contractor_search = $contractorName.val()
            filter.date_from = this.getDatesFromMask($dateFrom.val())
            filter.date_till = this.getDatesFromMask($dateTill.val())

            if (filter.date_from.length !== 10)
                filter.date_from = undefined

            if (filter.date_till.length !== 10)
                filter.date_till = undefined

            let selectedProjects = this.getSelectedProjects()

            if (selectedProjects.length !== 0)
                filter.projects = this.getSelectedProjects()


            let selectedStatuses = this.getSelectedStatuses()

            if (selectedStatuses.length !== 0)
                filter.statuses = selectedStatuses

            return filter
        }

        reloadTable() {
            this.payouts_table.ajax.reload()
        }
        /**
         * Склонение числительных
         */
        declOfNum(number, titles) {
            let cases = [2, 0, 1, 1, 1, 2];
            return titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];
        }

        getSelectedStatuses() {
            let status = $('#payout_status_select').val()

            if (status === 'not_selected')
                status = null

            console.log(status)

            return status ? [status] : [];
        }
    }
</script>





@stop
