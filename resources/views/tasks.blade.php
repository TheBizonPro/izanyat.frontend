@extends('layouts.master')

@section('title')
Задачи
@stop

@section('styles')
<style type="text/css">
    .modal.show .modal-dialog {
        transform: none;
        border: 1px solid #ccc;
    }

    /*.conflict-row :not(:first-child){*/
    /*    margin-top: 10px;*/
    /*}*/

    /*.conflict-row :not(:first-child) :after {*/
    /*    content: '';*/
    /*    position: absolute;*/
    /*    width: 100%;*/
    /*    height: 1px;*/
    /*    background: black;*/
    /*    top: 100%;*/
    /*    left: 0;*/
    /*}*/


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
<input type="hidden" id="task_id" value="{{ $task_id ?? '' }}">
<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none mt-4">
            <div class="row align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div id="project_name" class="page-pretitle"></div>
                    <h2 class="page-title">
                        Задачи
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <span class="d-none d-sm-inline">
                            <button id="add_task_btn" hidden class="btn btn-white">
                                <b class="fa fa-plus-circle text-success me-2"></b>Создать задачу
                            </button>
                            <button id="add_tasks_excel_btn" hidden class="btn btn-white">
                                <b class="fa fa-plus-circle text-success me-2"></b>Загрузить Excel
                            </button>
                            <button id="pay_for_selected" hidden class="btn btn-white">Оплатить выбранные</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body p-0 pb-3">
                <table id="tasks_table" class="table" style="width:100%"></table>
            </div>
        </div>

    </div>
</div>



<div id="task_view_modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Задача «<span id="view_title_task_name"></span>» <span class="text-muted">(<b
                            id="view_title_task_id"></b>)</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-6">
                        <div class="p-3 rounded border">

                            <div class="d-flex justify-content-between">
                                <div class="form-group">
                                    <label class="form-label text-muted">Название задачи</label>
                                    <div id="view_task_name" class="font-weight-bold"></div>
                                </div>
                                <div id="view_task_actions" class="text-end">
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-light btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Действия
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <a id="edit_task_btn" hidden class="dropdown-item" href="#"><i
                                                    class="fad fa-pencil me-2"></i>Редактировать</a>
                                            <a id="copy_task_btn" hidden class="dropdown-item" href="#"><i
                                                    class="fad fa-copy me-2"></i>Копия задачи</a>
                                            <a id="delete_task_btn" hidden class="dropdown-item" href="#"><i
                                                    class="fad fa-trash me-2"></i>Удалить</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="form-label text-muted">Категория задачи</label>
                                <div id="view_task_job_category_name" class="font-weight-bold"></div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="form-label text-muted">Статус</label>
                                <div id="view_task_status" class="font-weight-bold"></div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="form-label text-muted">Описание работ/услуг</label>
                                <div id="view_task_description" class="font-weight-bold"></div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="form-label text-muted">Адрес выполнения работ/услуг</label>
                                <div id="view_task_address" class="font-weight-bold"></div>
                            </div>


                            <div class="form-group mt-3">
                                <label class="form-label text-muted">Период работ</label>
                                <div class="font-weight-bold">
                                    <span id="view_task_date_from"></span> → <span id="view_task_date_till"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mt-3 sum-payment-view">
                                        <label class="form-label text-muted">Сумма оплаты </label>
                                        <div class="font-weight-bold">
                                            <span id="view_task_sum"></span>
                                            <input class="form-control mt-2" id="view_task_sum_input"></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="view_task_offers_wrapper" class="col-6" hidden>
                        <div class="p-4 rounded border">
                            {{-- 2do кнопка обновить --}}
                            <h2 class="text-center">Отклики на задачу (<span id="view_task_offers_count"></span>)</h2>

                            <div id="view_task_offers_loading" class="mt-4 p-2 rounded border" hidden>
                                <h3 class="text-center"><b class="fa fa-spinner fa-pulse"></b></h3>
                                <div class="text-center">Загрузка откликов...</div>
                            </div>
                            <div id="view_task_no_offers" class="mt-4 p-2 rounded border" hidden>
                                <h3 class="text-center"><b class="fa fa-hourglass-half"></b></h3>
                                <div class="text-center">Пока нет ни одного отклика</div>
                            </div>
                            <div id="view_task_offers" class="mt-4" hidden>

                            </div>

                            <div id="invite_contractors_btn_wrapper" class="mt-4 text-center" hidden>
                                <button id="invite_contractors_btn" class="btn btn-dark">Предложить проект
                                    исполнителям</button>
                            </div>
                        </div>
                    </div>
                    <div id="view_task_contractor_wrapper" class="col-6" hidden>
                        <div class="p-4 rounded border">
                            <h2 class="text-center">Исполнитель</h2>
                            <div id="view_task_contractor"></div>
                        </div>

                        <div id="task_edit_contractor_errors_wrapper" class="modal-body" hidden>
                            <div id="task_edit_contractor_errors" class="alert alert-danger"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<div id="task_edit_modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Задача</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="task_edit" class="modal-body">
                <input type="text" id="task_id" hidden>

                <div class="form-group">
                    <label for="task_name" class="form-label">Название задачи</label>
                    <input id="task_name" type="text" class="form-control form-control-lg"
                        placeholder="Например: «Клининг»">
                </div>

                <div class="form-group mt-3">
                    <label for="task_job_category_id" class="form-label">Категория задачи</label>
                    <select id="task_job_category_id" class="form-select"></select>
                </div>

                <div class="form-group mt-3">
                    <label for="task_description" class="form-label">Описание работ/услуг</label>
                    <textarea id="task_description" class="form-control" rows="4"
                        placeholder="Например: уборка помещения 150 кв метров"></textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="task_address" class="form-label">Адрес выполнения работ/услуг</label>
                    <input id="task_address" type="text" class="form-control"
                        placeholder="Например: «г. Москва» или «Удаленно»">
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group mt-3">
                            <label for="task_date_from" class="form-label">Дата начала работ</label>
                            <input id="task_date_from" type="text" class="form-control" placeholder="дд.мм.гггг">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group mt-3">
                            <label for="task_date_till" class="form-label">Дата окончания работ</label>
                            <input id="task_date_till" type="text" class="form-control" placeholder="дд.мм.гггг">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group mt-3 sum-payment">
                            <label for="task_sum" class="form-label">Сумма оплаты (руб)</label>
                            <input id="task_sum" type="number" class="form-control">
                        </div>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="task_is_sum_confirmed">
                            <label class="form-check-label" for="task_is_sum_confirmed">Оплата договорная</label>
                        </div>
                    </div>
                </div>

            </div>
            <div id="task_edit_errors_wrapper" class="modal-body" hidden>
                <div id="task_edit_errors" class="alert alert-danger"></div>
            </div>
            <div class="modal-footer text-end border-top pt-3">
                <button class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">Отмена</button>
                <button id="task_save" class="btn btn-primary">
                    <div class="text">Создать задачу</div>
                    <div class="wait" hidden><b class="fad fa-spinner fa-pulse"></b>Сохранение</div>
                </button>
            </div>
        </div>
    </div>
</div>




<div id="contractors_invite_modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Предложить проект исполнителям</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table" id="contractors_datatable"></table>
            </div>
            <div class="modal-footer text-end border-top pt-3">
                <button class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">Закрыть</button>
            </div>
        </div>
    </div>
</div>


<div id="add_tasks_modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавление ведомости</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="load_tasks_table_wrapper" class="modal-body text-center">
                <div class="alert alert-info">
                    Загрузите файл с ведомостью подготовленный в Excel или другом табличном редакторе. Файл необходимо
                    сохранить в формате xls/xlsx. Колонки файла должны соответствовать <a
                        href="/files/tasks_template.xlsx">шаблону</a>
                </div>
                <input type="file" id="load_tasks_file_input" hidden>
                <button id="load_tasks_file_btn" class="btn btn-lg btn-success btn-pill">
                    <div class="text"><b class="fad fa-file-excel me-2"></b>Загрузить файл Excel</div>
                    <div class="wait" hidden><b class="fad fa-spinner fa-pulse me-2"></b>Загрузка</div>
                </button>
            </div>

            <div id="preview_tasks_table_wrapper" class="modal-body p-0" hidden>
                <div class="alert alert-info  m-4">
                    Проверьте данные, которые мы получили из вашего файла
                </div>

                <table id="tasks_table_preview" class="table mb-3" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Название задачи</th>
                            <th>ИНН</th>
                            <th>ФИО</th>
                            <th>Дата начала работ</th>
                            <th>Дата завершения работ</th>
                            <th>Сумма</th>
                            <th>Ошибка</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

<style>
    .conflict-row {
        margin-top: 50px;
    }

    .conflict-checkbox-row {
        cursor: pointer;
        padding: 3px;
    }

    .conflict-checkbox-row :hover {
        transition: .2s;
    }

    .conflict-checkbox-row :hover {}
</style>


<script type="text/javascript">
    $(function () {
        window.InterfaceManager = new InterfaceManagerClass;
        window.InterfaceManager.menuShow('project_menu');
        window.InterfaceManager.menuActive('tasks');
        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe();
        window.InterfaceManager.notificationsCount();
        ///window.InterfaceManager.signUnrequestedCount();

        let TasksManager = new TasksManagerClass;


        var ProjectManager = new ProjectManagerClass;
        ProjectManager.project_id = $('#project_id').val();
        ProjectManager.loadProjectData();
    });



    class TasksManagerClass {

        constructor(game_id) {
            let ths = this;
            ths.project_id = $('#project_id').val();
            ths.bindAddTaskBtn();
            ths.bindSaveTaskBtn();
            ths.initTasksDatatable();
            ths.loadCategories();
            ths.bindMassPayButton();

            ths.bindContractorsModal();
            ths.bindAddTasksModalActions();
            ths.bindFlexiblePayout();
            var task_id = $('#task_id').val();
            if (task_id != '') {
                console.log(task_id)
                ths.openTaskView(task_id);
            }
        }

        bindContractorsModal() {
            let ths = this;

            $('#invite_contractors_btn').unbind('click').bind('click', function () {
                $('#contractors_invite_modal').modal('show');
            });

            $('#contractors_invite_modal').bind('shown.bs.modal', function () {
                ths.initProjectContractorsDatatable();
            });

            $('#contractors_invite_modal').bind('hidden.bs.modal', function () {

            });
        }

        bindFlexiblePayout() {
            let ths = this;

            $('#task_is_sum_confirmed').on('change', function ($event) {
                const isChecked = $($event.currentTarget).is(':checked');

                $('#task_sum').parent().prop('hidden', isChecked);
            });
        }

        importTasksFile() {
            let ths = this;

            // document.getElementsByClassName('.button-open').item().addEventListener('click', evt => {
            //     evt.target.get().setProperty('hidden', true)
            // })

            $("#tasks_import_btn").prop('disabled', true);
            $("#tasks_import_btn .text").prop('hidden', true);
            $("#tasks_import_btn .wait").prop('hidden', false);

            let data = {};

            data.import_data = ths.import_tasks_data;
            var ax = axios.post(`{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/upload_tasks`, data);
            ax.then(function (response) {
                if (response.data) {
                    $('#add_tasks_modal').modal('hide');
                    boottoast.success({
                        message: response.data.message ?? error.response.statusText,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                }

                $('#load_tasks_table_wrapper').prop('hidden', false);
                $('#preview_tasks_table_wrapper').prop('hidden', true);
                $('#tasks_table_preview tbody').html('');
                $('#add_tasks_modal .modal-footer').prop('hidden', true);
                ths.tasks_datatable.ajax.reload();

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
                    $("#tasks_import_btn").prop('disabled', false);
                    $("#tasks_import_btn .text").prop('hidden', false);
                    $("#tasks_import_btn .wait").prop('hidden', true);
                });

        }

        uploadTasksFile(file) {
            let ths = this;
            ths.import_tasks_data = {};

            $("#load_tasks_file_btn").prop('disabled', true);
            $("#load_tasks_file_btn .text").prop('hidden', true);
            $("#load_tasks_file_btn .wait").prop('hidden', false);

            var formData = new FormData();
            formData.append('file', file);

            var ax = axios.post(`{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/order/simulate`, formData);
            ax.then(function (response) {
                if (response.data.table) {

                    $('#load_tasks_table_wrapper').prop('hidden', true);
                    $('#preview_tasks_table_wrapper').prop('hidden', false);
                    $('#tasks_table_preview tbody').html('', false);
                    $('#add_tasks_modal .modal-footer').prop('hidden', false);

                    $.each(response.data.table, function (i, row) {
                        var tr = '<tr>';
                        tr += '<td>' + row.job_name + '</td>';
                        tr += '<td>' + row.inn + '</td>';
                        tr += '<td>' + row.contractor + '</td>';
                        tr += '<td>' + row.job_start_date + '</td>';
                        tr += '<td>' + row.job_finish_date + '</td>';
                        tr += '<td>' + row.sum + '</td>';
                        tr += '<td>';
                        $.each(row.errors, function (n, error) {
                            tr += '<div class="text-danger">';
                            tr += error
                            tr += '</div>';
                        });
                        tr += '</td>';
                        tr += '</tr>';

                        ths.import_tasks_data[i] = row;
                        $('#tasks_table_preview tbody').append(tr);
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
                    $("#load_tasks_file_btn").prop('disabled', false);
                    $("#load_tasks_file_btn .text").prop('hidden', false);
                    $("#load_tasks_file_btn .wait").prop('hidden', true);
                    $("#load_tasks_file_input").val("");
                });

        }


        showPayoutsTable(order_id) {
            let ths = this;
            ths.hidePayoutsTable(order_id);
            $('#order_' + order_id).find('.tabs-wrapper button.btn').addClass('btn-white').removeClass('btn-dark');
            $('#order_' + order_id).find('button.btn-show-payouts').addClass('btn-dark').removeClass('btn-white');
            $('#order_' + order_id).find('.payouts-table-wrapper').prop('hidden', false);
            ths.initPayoutsDatatable(order_id);
        }

        bindAddTasksModalActions() {
            let ths = this;
            $('#add_tasks_excel_btn').bind('click', function () {
                $('#add_tasks_modal').modal('show');

                $('#load_tasks_file_input').val("");
            });

            $('#load_tasks_file_input').bind('change', function (e) {
                ths.uploadTasksFile(e.target.files[0])
            });

            $('#load_tasks_file_btn').bind('click', function () {
                $('#load_tasks_file_input').trigger('click');
            });

            $('#add_tasks_back').bind('click', function () {
                $('#load_tasks_table_wrapper').prop('hidden', false);
                $('#preview_tasks_table_wrapper').prop('hidden', true);
                $('#tasks_table_preview tbody').html('');
                $('#add_tasks_modal .modal-footer').prop('hidden', true);
            });

            $('#tasks_import_btn').bind('click', function () {
                ths.importTasksFile();
            });

            $('#load_payouts_file_btn').bind('click', function () {
                $('#load_payouts_file_input').val("");
                $('#load_payouts_file_input').trigger('click');
            });

            $('#load_payouts_file_input').change((e) => {
                ths.uploadPayoutsFile(e.target.files[0])
            })


        }

        bindMassPayButton() {
            $('#pay_for_selected').click(_ => this.massPayTask())
        }


        bindAddTaskBtn() {
            let ths = this;
            $('#add_task_btn').unbind('click').bind('click', function () {
                ths.prepareTaskEditModal(true);
                ths.showTaskEditModal();
            });
        }

        /**
         * Initialization of datatable
         */
        initTasksDatatable() {
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
                    url: `{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/tasks/datatable`,
                    dataSrc: 'data',
                    type: 'GET',
                    xhrFields: {
                        withCredentials: true
                    },
                    headers: {
                        Authorization: `Bearer ${window.localStorage.getItem('token')}`
                    },
                    error: (error) => {
                        if (error.status === 403) {
                            window.callPermissionModal();
                        }
                    },
                },
                processing: false,
                pageLength: 50,

                dom: '<"p-0 overflow-auto"rt><"text-center"<"mt-2"i><"mt-2 mb-2"p>>',
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
                columns: [
                    {
                        name: 'checkbox', data: 'checkbox', title: '', sortable: false, searchable: false, visible: true,
                        render: (data, type, row, meta) => {
                            return `
                                <div class="d-flex justify-content-center align-items-center"><input type="checkbox" class="form-check-input task-row-checkbox" data-id="${row.id}"></div>`
                        }
                    },
                    { name: 'id', data: 'id', title: 'ID', sortable: true, searchable: true, visible: true },
                    { name: 'job_category_name', data: 'job_category_name', title: 'Категория работ', sortable: true, searchable: true, visible: false },
                    {
                        name: 'name', data: 'name', title: 'Задача', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            var b = '<div class="font-weight-bold">' + row.name + '</div>'
                            b += '<small class="d-block text-muted">' + row.job_category_name + '</small>';
                            return b;
                        }
                    },
                    {
                        name: 'status', data: 'status', title: 'Статус', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            var statuses = {};
                            statuses['new'] = '<span class="badge bg-info text-white">Новая</span>';
                            statuses['work'] = '<span class="badge bg-secondary text-white">В работе</span>';
                            statuses['done'] = '<span class="badge bg-info text-white">На проверке</span>';
                            statuses['paid'] = '<span class="badge bg-dark text-white">Оплачена</span>';
                            statuses['await_payment_request'] = '<span class="badge bg-success text-white">Ожидает оплаты</span>';
                            statuses['await_payment'] = '<span class="badge bg-success text-white">Ожидает зачисления платежа</span>';

                            return statuses[row.status];
                        }
                    },
                    { name: 'date_from', data: 'date_from', title: 'Дата начала работ', sortable: true, searchable: true, visible: false },
                    { name: 'date_till', data: 'date_till', title: 'Дата окончания работ', sortable: true, searchable: true, visible: false },

                    {
                        name: 'date', title: 'Период работ', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return row.date_from + ' → ' + row.date_till;
                        }
                    },

                    { name: 'offers_count', data: 'offers_count', title: 'Количество оферов', sortable: true, searchable: true, visible: false },

                    {
                        name: 'user_name', data: 'user_name', title: 'Исполнитель', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            if (row.user_name == null) {
                                if (row.offers_count == 0) {
                                    return 'Предложений пока нет'
                                }
                                return row.offers_count + ' ' + ths.declOfNum(row.offers_count, ['предложение', 'предложения', 'предложений']);
                            }
                            return '<a href="/contractor/' + row.user_id + '" target="_blank"><b class="fa fa-user me-2"></b>' + row.user_name + '</a>';
                        }
                    },
                    {
                        name: 'sum', data: 'sum', title: 'Сумма', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            console.log(row.is_sum_confirmed);
                            return (!Boolean(row.is_sum_confirmed) ? 'Договорная' : row.sum);
                        }
                    },

                    {
                        title: 'Открыть', sortable: false, searchable: false, visible: true,
                        render: function (data, type, row, meta) {
                            return '<button class="btn btn-sm btn-open-task" hidden>Открыть</button>'
                        }
                    }
                ],
                rowCallback: function (row, data, index) {
                    $(row).find('button.btn-open-task').unbind('click').bind('click', function () {
                        ths.openTaskView(data.id);
                    });
                },
                drawCallback: function (settings) {
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                    window.permissionHandler.handle();
                }
            }
            ths.tasks_datatable = $('#tasks_table').DataTable(settings);
        }




        bindSaveTaskBtn() {
            let ths = this;
            $('#task_save').unbind('click').bind('click', function () {

                let data = {};

                const isSumConfirmed = !$('#task_is_sum_confirmed').is(":checked");

                data.id = $('#task_id').val();
                console.log(isSumConfirmed);
                data.name = $('#task_name').val();
                data.description = $('#task_description').val();
                data.address = $('#task_address').val();
                data.job_category_id = $('#task_job_category_id').val();
                data.date_from = $('#task_date_from').val();
                data.date_till = $('#task_date_till').val();
                data.sum = !isSumConfirmed ? 0 : $('#task_sum').val();
                data.is_sum_confirmed = Number(isSumConfirmed);

                $('#task_edit_errors_wrapper').prop('hidden', true);
                $('#task_edit_errors').html("");

                $('#task_save').prop('disabled', true);
                $('#task_save .text').prop('hidden', true);
                $('#task_save .wait').prop('hidden', false);

                let task_id = $('#task_id').val();
                if (task_id == '') {
                    var save_task_url = `{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/tasks/new`;
                } else {
                    var save_task_url = `{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/tasks/${task_id}`;
                }

                var ax = axios.post(save_task_url, data);
                ax.then(function (response) {
                    if (response.data.message) {
                        boottoast.success({
                            message: response.data.message,
                            title: response.data.title ?? 'Успешно',
                            imageSrc: "/images/logo-sm.svg"
                        });
                    }
                    $('#task_is_sum_confirmed').prop("checked", false);
                    ths.tasks_datatable.ajax.reload();
                    ths.hideTaskEditModal();
                    ths.openTaskView(response.data.task.id);
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


                        $('#task_edit_errors_wrapper').prop('hidden', false);
                        $.each(error.response.data.errors, function (i, error_text) {
                            $('#task_edit_errors').append('<p>' + error_text + '</p>');
                        })
                    })
                    .finally(function () {

                        $('#task_save').prop('disabled', false);
                        $('#task_save .text').prop('hidden', false);
                        $('#task_save .wait').prop('hidden', true);
                    });

                console.log(data);
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
                    $('#task_job_category_id').html('<option value="">Выберите категорию</option>')
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id == null) {
                            $('#task_job_category_id').append('<optgroup class="job_category_' + job_category.id + '" label="' + job_category.name + '"></optgroup>');
                        }
                    });
                    $.each(response.data.job_categories, function (i, job_category) {
                        if (job_category.parent_id != null) {
                            $('#task_job_category_id .job_category_' + job_category.parent_id).append('<option value="' + job_category.id + '">' + job_category.name + '</option>');
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



        /**
         * Загрузка задачи
         */
        loadTask(task_id, success_callback) {
            let ths = this;

            var ax = axios.get(`{{ config('app.api') }}/api/v2/company/tasks/${task_id}`);
            ax.then(function (response) {
                console.log(response.data)
                if (response.data.task) {
                    if (success_callback) {
                        success_callback(response.data.task);
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



        /**
         * Открытие отображения задачи
         */
        openTaskView(task_id) {
            let ths = this;
            ths.openedTask = task_id;
            ths.loadTask(task_id, function (task) {
                ths.prepareTaskViewModal();
                ths.fillTaskViewModal(task);
                ths.showTaskViewModal();
            });
        }

        /**
         * Подготовка окна отображения задачи
         */
        prepareTaskViewModal() {
            let ths = this;

            $('#view_title_task_id').text('');
            $('#view_title_task_name').text('');
            $('#view_task_name').text('');
            $('#view_task_job_category_name').text('');
            $('#view_task_status').text('');
            $('#view_task_description').text('');
            $('#view_task_address').text('');
            $('#view_task_date_from').text('');
            $('#view_task_date_till').text('');
            $('#view_task_sum').text('');
            $('#view_task_offers_count').text('');

            $('#view_task_actions').prop('hidden', true);
            $('#edit_task_btn').unbind('click');
            $('#edit_task_btn').prop('hidden', true);
            $('#copy_task_btn').unbind('click');
            $('#copy_task_btn').prop('hidden', true);
            $('#delete_task_btn').unbind('click');
            $('#delete_task_btn').prop('hidden', true);

            $('#view_task_offers_wrapper').prop('hidden', true);
            $('#view_task_contractor_wrapper').prop('hidden', true);
            $('#invite_contractors_btn_wrapper').prop('hidden', true);
        }


        /**
         * Заполнение данными окна отображения задачи
         */
        fillTaskViewModal(task) {
            let ths = this;
            $('#view_title_task_id').text(task.id);
            $('#view_title_task_name').text(task.name);
            $('#view_task_name').text(task.name);
            $('#view_task_job_category_name').text(task.job_category_name);

            var statuses = {};
            statuses['new'] = '<span class="badge bg-info text-white">Новая</span>';
            statuses['work'] = '<span class="badge bg-secondary text-white">В работе</span>';
            statuses['done'] = '<span class="badge bg-info text-white">На проверке</span>';
            statuses['paid'] = '<span class="badge bg-dark text-white">Оплачена</span>';
            statuses['await_payment_request'] = '<span class="badge bg-success text-white">Ожидает оплаты</span>';
            statuses['await_payment'] = '<span class="badge bg-success text-white">Ожидает зачисления платежа</span>';

            $('#view_task_status').html(statuses[task.status]);

            $('#view_task_description').text(task.description);
            $('#view_task_address').text(task.address);
            $('#view_task_date_from').text(task.date_from);
            $('#view_task_date_till').text(task.date_till);
            $('#view_task_sum').prop('hidden', false);
            $('#task_edit_contractor_errors_wrapper').prop('hidden', true);
            $('#view_task_sum_input').prop('hidden', true);
            if (!Boolean(task.is_sum_confirmed) && !Boolean(task.status == 'done')) {
                $('#view_task_sum').text(`Договорная`);
            }
            else if (!Boolean(task.is_sum_confirmed) && Boolean(task.status == 'done')) {
                $('#view_task_sum').prop('hidden', true);
                $('#view_task_sum_input').prop('hidden', false);
                $('#task_edit_contractor_errors_wrapper').prop('hidden', false);
                $('#task_edit_contractor_errors').html('<p>Не указанна стоимость задания, поэтому мы не можем сформировать акт выполненных работ. Пожалуйста, перед тем, как принять задачу, введите сумму, на которую будет сформирован акт</p>')
            }
            else {
                $('#view_task_sum').text(`${task.sum} ₽`);
            }
            $('#view_task_offers_count').text(task.offers_count);

            $('#edit_task_btn').prop('hidden', true);
            $('#delete_task_btn').prop('hidden', true);

            if (task.status == 'new' && task.offers_count == 0) {
                $('#view_task_actions').prop('hidden', false);
                $('#edit_task_btn').prop('hidden', false);
                $('#edit_task_btn').unbind('click').bind('click', function () {
                    ths.hideTaskViewModal();
                    ths.openTaskEdit(task.id);
                });
            }
            if (task.status == 'new') {
                $('#view_task_actions').prop('hidden', false);
                $('#invite_contractors_btn_wrapper').prop('hidden', false);
                $('#delete_task_btn').prop('hidden', false);
                $('#delete_task_btn').unbind('click').bind('click', function () {
                    ths.removeTask(task.id);
                });
            }

            $('#view_task_actions').prop('hidden', false);
            $('#copy_task_btn').prop('hidden', false);
            $('#copy_task_btn').unbind('click').bind('click', function () {
                ths.copyTask(task.id);
            });

            window.permissionHandler.handle();

            if (task.status == 'new') {
                $('#view_task_offers_wrapper').prop('hidden', false);
                ths.loadOffers(task.id);
            } else {
                $('#view_task_contractor_wrapper').prop('hidden', false);
                ths.renderContractor(task);
            }
        }


        /**
         * Показ окна отображения задачи
         */
        showTaskViewModal() {
            let ths = this;
            $('#task_view_modal').modal('show');
        }

        /**
         * Спрятать окно отображения задачи
         */
        hideTaskViewModal() {
            let ths = this;
            $('#task_view_modal').modal('hide');
        }


        /**
         * Открытие редактирования задачи
         */
        openTaskEdit(task_id) {
            let ths = this;
            ths.loadTask(task_id, function (task) {
                ths.prepareTaskEditModal(false);
                ths.fillTaskEditModal(task);
                ths.showTaskEditModal();

            });
        }


        /**
         * Очистка модального окна с задачей (подготовка)
         */
        prepareTaskEditModal(new_task = false) {
            let ths = this;
            $('#task_id').val('');
            $('#task_name').val('');
            $('#task_job_category_id').val('');
            $('#task_description').val('');
            $('#task_address').val('');
            $('#task_date_from').val('');
            $('#task_date_till').val('');
            $('#task_sum').val('');

            $('#task_date_from').mask('99.99.9999', { placeholder: 'дд.мм.гггг' });
            $('#task_date_till').mask('99.99.9999', { placeholder: 'дд.мм.гггг' });

            if (new_task == true) {
                $('#task_save .text').text('Создать задачу');
            } else {
                $('#task_save .text').text('Сохранить задачу');
            }

            $('#task_edit_errors_wrapper').prop('hidden', true);
            $('#task_edit_errors').html("");
        }


        /**
         * Заполнение модального окна редактирования
         */
        fillTaskEditModal(task) {
            $('#task_id').val(task.id);
            $('#task_name').val(task.name);
            $('#task_job_category_id').val(task.job_category_id);
            $('#task_description').val(task.description);
            $('#task_address').val(task.address);
            $('#task_date_from').val(task.date_from);
            $('#task_date_till').val(task.date_till);
            $('#task_sum').val(task.sum);
        }

        /**
         * Показ окна редактирования задачи
         */
        showTaskEditModal() {
            let ths = this;
            $('#task_edit_modal').modal('show');
        }

        /**
         * Спрятать окно редактирования задачи
         */
        hideTaskEditModal() {
            let ths = this;
            $('#task_edit_modal').modal('hide');
        }


        /**
         * Создание копии задачи
         */
        copyTask(task_id) {
            let ths = this;

            bootbox.dialog({
                title: 'Создать копию задачи?',
                message: 'Вы действительно хотите создать копию этой задачи?',
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: '<b class="fa fa-copy me-2"></b>Да, создать копию',
                        className: 'btn-primary',
                        callback: function () {

                            var ax = axios.post(`{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/tasks/${task_id}/copy`);
                            ax.then(function (response) {
                                if (response.data.message) {
                                    boottoast.success({
                                        message: response.data.message,
                                        title: response.data.title ?? 'Успешно',
                                        imageSrc: "/images/logo-sm.svg"
                                    });
                                }
                                ths.tasks_datatable.ajax.reload();
                                ths.hideTaskViewModal();
                                ths.openTaskEdit(response.data.task.id);
                            })
                                .catch(function (error) {
                                    console.error(error);
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
                }
            });

        }


        /**
         * Удаление задачи
         */
        removeTask(task_id) {
            let ths = this;

            bootbox.dialog({
                title: 'Удалить задачу?',
                message: 'Вы действительно хотите удалить эту задачу? Будут также удалены все отклики на задачу!',
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: '<b class="fa fa-trash me-2"></b>Да, удалить',
                        className: 'btn-danger',
                        callback: function () {

                            var ax = axios.delete(`{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/tasks/${task_id}`);
                            ax.then(function (response) {
                                if (response.data.message) {
                                    boottoast.success({
                                        message: response.data.message,
                                        title: response.data.title ?? 'Успешно',
                                        imageSrc: "/images/logo-sm.svg"
                                    });
                                }
                                ths.tasks_datatable.ajax.reload();
                                ths.hideTaskViewModal();
                            })
                                .catch(function (error) {
                                    console.error(error);
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
                }
            });
        }



        loadOffers(task_id) {
            let ths = this;

            $('#view_task_offers_loading').prop('hidden', false)
            $('#view_task_no_offers').prop('hidden', true)
            $('#view_task_offers').prop('hidden', true)

            var ax = axios.get(`{{ config('app.api') }}/api/v2/company/projects/tasks/${task_id}/offers`);
            ax.then(function (response) {
                if (response.data.offers) {
                    if (response.data.offers.length == 0) {
                        $('#view_task_no_offers').prop('hidden', false);
                    } else {
                        $('#view_task_offers').prop('hidden', false);
                        ths.renderOffers(response.data.offers);
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
                    $('#view_task_offers_loading').prop('hidden', true)
                });
        }



        renderOffers(offers) {
            let ths = this;
            $('#view_task_offers').html("");
            $.each(offers, function (i, offer) {

                var b = '<div class="offer p-2 mb-3 rounded border">'

                b += '<div class="d-flex justify-content-between">';
                if (offer.user) {
                    b += '<div>';
                    b += '	<h3>' + offer.user.name + ' <small>(<a href="/contractor/' + offer.user.id + '" target="_blank">профиль</a>)</small></h3>';
                    b += '	<small class="d-block">ИНН: ' + offer.user.inn + '</small>';
                    b += '	<small class="d-block">Подключен к ЯЗанят как плательщик НПД: ' + (offer.user.taxpayer_registred_as_npd == 1 ? 'Да' : '<span class="text-danger">Нет!</span>') + ' </small>';
                    b += '	<small class="d-block">Телефон: +' + offer.user.phone + '</small>';
                    b += '	<small class="d-block">Email: ' + offer.user.email + '</small>';
                    b += '	<small class="d-block">Рейтинг: <b class="fal fa-star me-1"></b>' + (offer.user.rating ?? '') + '</small>';
                    b += '	<small class="d-block">О себе: ' + (offer.user.about ?? '') + '</small>';
                    b += '</div>';
                }

                if (window.permissionHandler.isCan('company.tasks.contractor_assign')) {
                    b += '	<div class="text-end">';
                    b += '		<div class="mb-1"><button class="btn-accept-offer btn btn-success btn-sm">Выбрать этого исполнителя</button></div>';
                    //b+= '		<div class="mb-1"><button class="btn btn-danger btn-sm">Отклонить</button></div>';
                    b += '	</div>';
                }


                b += '</div>';
                b += '<div class="text-muted text-end"><small>Отклик создан ' + offer.created_datetime + '</small> (<small>ID#' + offer.id + '</small>)</div>';
                b += '</div>';


                var block = $(b);

                $(block).find('.btn-accept-offer').unbind('click').bind('click', function () {
                    ths.acceptOffer(offer);
                });

                $('#view_task_offers').append(block);

            });

        }


        acceptOffer(offer) {
            let ths = this;
            bootbox.dialog({
                title: 'Выбрать исполнителя?',
                message: 'Вы действительно хотите выбрать этого исполнителя для выполнения вашей задачи?',
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: '<b class="fa fa-check me-2"></b>Да, выбрать',
                        className: 'btn-success',
                        callback: function () {

                            var ax = axios.post(`{{ config('app.api') }}/api/v2/company/projects/offers/${offer.id}/accept`);
                            ax.then(function (response) {
                                if (response.data.message) {
                                    boottoast.success({
                                        message: response.data.message,
                                        title: response.data.title ?? 'Успешно',
                                        imageSrc: "/images/logo-sm.svg"
                                    });
                                }
                                ths.tasks_datatable.ajax.reload();
                                ths.openTaskView(offer.task_id);
                            })
                                .catch(function (error) {
                                    console.error(error);
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
                }
            });
        }


        renderContractor(task) {
            let ths = this;

            var user = task.user;

            var b = '<div class="offer p-2 mb-3 rounded border">'

            b += '<div class="d-flex justify-content-between">';
            if (user) {
                b += '<div>';
                b += '	<h3>' + user.name + '</h3>';
                b += '	<small class="d-block">ИНН: ' + user.inn + '</small>';
                b += '	<small class="d-block">Подключен к ЯЗанят как плательщик НПД: ' + (user.taxpayer_registred_as_npd == 1 ? 'Да' : '<span class="text-danger">Нет!</span>') + ' </small>';
                b += '	<small class="d-block">Телефон: +' + user.phone + '</small>';
                b += '	<small class="d-block">Email: ' + user.email + '</small>';
                b += '	<small class="d-block">Рейтинг: <b class="fal fa-star me-1"></b>' + (user.rating ?? '') + '</small>';
                b += '	<small class="d-block">О себе: ' + (user.about ?? '') + '</small>';
                b += '</div>';
            }
            b += '	<div class="text-end">';
            if (task.status == 'done' && Boolean(task.is_sum_confirmed) && window.permissionHandler.isCan('company.tasks.accept_job')) {
                b += '		<div class="mb-1">';
                b += '			<button class="btn-complete-task btn btn-success btn-sm">';
                b += '				<span class="text">Принять работу</span>';
                b += '				<span class="wait" hidden><b class="fa fa-spinner fa-pulse"></b> Пожалуйста, ждите</span>';
                b += '			</button>';
                b += '		</div>';

                b += '		<div class="mb-1">';
                b += '			<button class="btn-return-task btn btn-white btn-sm">';
                b += '				<span class="text">Вернуть в работу</span>';
                b += '				<span class="wait" hidden><b class="fa fa-spinner fa-pulse"></b> Пожалуйста, ждите</span>';
                b += '			</button>';
                b += '		</div>';
            }
            else if (task.status == 'done' && !Boolean(task.is_sum_confirmed) && (window.permissionHandler.isCan('company.tasks.accept_job') || window.permissionHandler.isCan('company.admin'))) {
                b += `
                    <div class="mb-1">
                        <button class="btn-confirm-sum btn btn-success btn-sm">
                            <span class="text">Согласовать оплату</span>
                            <span class="wait" hidden><b class="fa fa-spinner fa-pulse"></b> Пожалуйста, ждите</span>
                        </button>
                    </div>
                    <div class="mb-1">
                        <button class="btn-return-task btn btn-white btn-sm">
                            <span class="text">Вернуть в работу</span>
                            <span class="wait" hidden><b class="fa fa-spinner fa-pulse"></b> Пожалуйста, ждите</span>
                        </button>
                    </div>`
            }
            else if (task.status == 'await_payment_request' && (window.permissionHandler.isCan('company.tasks.pay') || window.permissionHandler.isCan('company.admin'))) {
                b += '<div class="mb-1">';
                b += '	<button class="btn-pay-task btn btn-success btn-sm">';
                b += '		<span class="text">Оплатить работу</span>';
                b += '		<span class="wait" hidden><b class="fa fa-spinner fa-pulse"></b> Пожалуйста, ждите</span>';
                b += '	</button>';
                b += '</div>';
            }
            else if (task.status == 'await_money') {
                b += '<div class="mb-1 badge bg-info">Ожидает зачисления</div>';
            }
            else if (task.status == 'work') {
                b += '		<div class="mb-1 badge bg-info">В работе</div>';
            } else if (task.status == 'paid') {
                b += '		<div class="mb-1 badge bg-success">Оплачено!</div>';
            }
            b += '	</div>';

            b += '</div>';
            b += '</div>';

            var block = $(b);

            $(block).find('.btn-complete-task').unbind('click').bind('click', function () {
                ths.contractorCompleteTask(task, this);
            });

            $(block).find('.btn-pay-task').unbind('click').bind('click', function () {
                ths.payTask(task, this);
            });

            $(block).find('.btn-return-task').unbind('click').bind('click', function () {
                ths.returnTask(task, this);
            });
            $(block).find('.btn-confirm-sum').unbind('click').bind('click', function () {
                ths.confirmSum(task, this);
            });

            $('#view_task_contractor').html(block);
            window.permissionHandler.handle();
        }


        massPayTask() {
            let ths = this
            let selected = $('.task-row-checkbox:checked').get().map(e => $(e).data('id'))

            if (selected.length === 0) {
                bootbox.dialog({
                    title: 'Упс!',
                    message: 'Вы не выбрали ни одной задачи для оплаты',
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


            bootbox.dialog({
                title: 'Оплатить выбранные задачи?',
                message: 'Вы действительно хотите оплатить выбранные задачи?',
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: '<b class="fa fa-check me-2"></b>Да, принять и оплатить',
                        className: 'btn-success',
                        callback: function () {
                            axios.post('{{ config('app.api') }}/api/v2/company/payouts/mass_pay ', {
                                tasks: selected
                            })
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

        contractorCompleteTask(task, button) {
            let ths = this;

            bootbox.dialog({
                title: 'Принять работу?',
                message: 'Вы действительно хотите принять работу у исполнителя?',
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: '<b class="fa fa-check me-2"></b>Да, принять',
                        className: 'btn-success',
                        callback: function () {
                            $(button).prop('disabled', true);
                            $(button).find('.text').prop('hidden', true);
                            $(button).find('.wait').prop('hidden', false);

                            axios.post(`{{ config('app.api') }}/api/v2/company/tasks/${task.id}/complete`).then(function (response) {
                                if (response.data.message) {
                                    boottoast.success({
                                        message: response.data.message,
                                        title: response.data.title ?? 'Успешно',
                                        imageSrc: "/images/logo-sm.svg"
                                    });
                                }
                                ths.tasks_datatable.ajax.reload();
                                ths.openTaskView(task.id);
                            })
                                .catch(function (error) {
                                    console.error(error);
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
                                    $(button).prop('disabled', false);
                                    $(button).find('.text').prop('hidden', false);
                                    $(button).find('.wait').prop('hidden', true);
                                });
                        }
                    }
                }
            });
        }

        payTask(task, button) {
            let ths = this;

            bootbox.dialog({
                title: 'Оплатить?',
                message: 'Вы действительно хотите оплатить работу исполнителя?',
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: '<b class="fa fa-check me-2"></b>Да, оплатить',
                        className: 'btn-success',
                        callback: function () {

                            $(button).prop('disabled', true);
                            $(button).find('.text').prop('hidden', true);
                            $(button).find('.wait').prop('hidden', false);

                            var ax = axios.post(`{{ config('app.api') }}/api/v2/company/projects/tasks/${task.id}/pay`);
                            ax.then(function (response) {
                                if (response.data.message) {
                                    boottoast.success({
                                        message: response.data.message,
                                        title: response.data.title ?? 'Успешно',
                                        imageSrc: "/images/logo-sm.svg"
                                    });
                                }
                                ths.tasks_datatable.ajax.reload();
                                ths.openTaskView(task.id);
                            })
                                .catch(function (error) {
                                    console.error(error);
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
                                    $(button).prop('disabled', false);
                                    $(button).find('.text').prop('hidden', false);
                                    $(button).find('.wait').prop('hidden', true);
                                });

                        }
                    }
                }
            });

        }

        confirmSum(task, button) {
            let ths = this;

            const sum = $('#view_task_sum_input').val();
            if (sum > 0) {
                bootbox.dialog({
                    title: 'Согласовать оплату?',
                    message: 'Вы действительно хотите отправить сумму на согласование исполнителю?',
                    closeButton: false,
                    buttons: {
                        cancel: {
                            label: 'Отмена',
                            className: 'btn-light'
                        },
                        main: {
                            label: 'Да, отправить',
                            className: 'btn-dark',
                            callback: function () {

                                $(button).prop('disabled', true);
                                $(button).find('.text').prop('hidden', true);
                                $(button).find('.wait').prop('hidden', false);
                                const data = {
                                    sum: sum
                                }
                                var ax = axios.post(`{{ config('app.api') }}/api/v2/company/projects/tasks/${task.id}/confirm`, data);
                                ax.then(function (response) {
                                    if (response.data.message) {
                                        boottoast.success({
                                            message: response.data.message,
                                            title: response.data.title ?? 'Успешно',
                                            imageSrc: "/images/logo-sm.svg"
                                        });
                                    }
                                    ths.tasks_datatable.ajax.reload();
                                    ths.openTaskView(task.id);
                                })
                                    .catch(function (error) {
                                        console.error(error);
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
                                        $(button).prop('disabled', false);
                                        $(button).find('.text').prop('hidden', false);
                                        $(button).find('.wait').prop('hidden', true);
                                    });

                            }
                        }
                    }
                });
            }
            else {
                bootbox.dialog({
                    title: 'Ошибка',
                    message: 'Введите сумму оплаты',
                    closeButton: false,
                    buttons: {
                        main: {
                            label: 'Ок',
                            className: 'btn-dark',
                        }
                    }
                });
            }

        }


        returnTask(task, button) {
            let ths = this;

            bootbox.dialog({
                title: 'Вернуть задачу на доработку?',
                message: 'Вы действительно хотите вернуть задачу на доработку исполнителю? Необходимо будет связаться с исполнителем и объяснить суть доработок.',
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: 'Да, вернуть на доработки',
                        className: 'btn-dark',
                        callback: function () {

                            $(button).prop('disabled', true);
                            $(button).find('.text').prop('hidden', true);
                            $(button).find('.wait').prop('hidden', false);
                            const taskID = task.id;
                            var ax = axios.post(`{{ config('app.api') }}/api/v2/company/tasks/${taskID}/return`);
                            ax.then(function (response) {
                                if (response.data.message) {
                                    boottoast.success({
                                        message: response.data.message,
                                        title: response.data.title ?? 'Успешно',
                                        imageSrc: "/images/logo-sm.svg"
                                    });
                                }
                                ths.tasks_datatable.ajax.reload();
                                ths.openTaskView(task.id);
                            })
                                .catch(function (error) {
                                    console.error(error);
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
                                    $(button).prop('disabled', false);
                                    $(button).find('.text').prop('hidden', false);
                                    $(button).find('.wait').prop('hidden', true);
                                });

                        }
                    }
                }
            });

        }




        /**
         * Initialization of datatable
         */
        initProjectContractorsDatatable() {
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
                    url: `{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/contractors/datatable`,
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
                columns: [
                    {
                        name: 'name', data: 'name', title: 'ФИО', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return '<a href="/contractor/' + row.id + '" target="_blank"><b class="fa fa-user me-2"></b>' + row.name + '</a>';
                        }
                    },
                    { name: 'inn', data: 'inn', title: 'ИНН', class: '', sortable: true, searchable: true, visible: true },
                    { name: 'job_category_name', data: 'job_category_name', title: 'Категория работ', class: '', sortable: true, searchable: true, visible: true },
                    { name: 'created_date', data: 'created_date', title: 'Дата регистрации', class: '', sortable: true, searchable: true, visible: true },
                    {
                        name: 'id', data: 'id', title: 'Отправить приглашение', class: '', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            var b = '<button class="btn btn-sm btn-primary btn-invite">';
                            b += '	<span class="text">Отправить приглашение</span>';
                            b += '	<span class="wait" hidden><b class="fa fa-spinner fa-pulse me-2"></b>Отправка...</span>';
                            b += '</button>';
                            return b;
                        }
                    },
                ],
                rowCallback: function (row, data, index) {
                    $(row).find('.btn-invite').bind('click', function () {
                        ths.inviteContractor(data.id, ths.openedTask, this);
                    });
                },
                drawCallback: function (settings) {
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                    window.permissionHandler.handle();
                }
            }
            ths.project_contractors_datatable = $('#contractors_datatable').DataTable(settings);
        }


        inviteContractor(user_id, task_id, button) {
            let ths = this;

            $(button).prop('disabled', true);
            $(button).find('.text').prop('hidden', true);
            $(button).find('.wait').prop('hidden', false);

            var ax = axios.post(`{{ config('app.api') }}/api/v2/company/tasks/${task_id}/invite_user/${user_id}`);
            ax.then(function (response) {
                if (response.data.message) {
                    boottoast.success({
                        message: response.data.message,
                        title: response.data.title ?? 'Успешно',
                        imageSrc: "/images/logo-sm.svg"
                    });
                }

                $(button).prop('disabled', true).html('<b class="fa fa-check me-2"></b>Отправлено');
            })
                .catch(function (error) {
                    console.error(error);
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

                    $(button).prop('disabled', false);
                    $(button).find('.text').prop('hidden', false);
                    $(button).find('.wait').prop('hidden', true);
                })
                .finally(function () {

                });
        }


        /**
         * Склонение числительных
         */
        declOfNum(number, titles) {
            let cases = [2, 0, 1, 1, 1, 2];
            return titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];
        }
    }
</script>





@stop
