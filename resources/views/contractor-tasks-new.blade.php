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
    <div class="container-fluid">
        <!-- Page title -->
        <div class="page-header d-print-none mt-4">
            <div class="row align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        Все доступные задачи
                    </h2>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <h4>Внимание!</h4>
            <div>Пожалуйста, самостоятельно следите за лимитом своего дохода, при превышении лимита 2 400 000 рублей за
                год вы не сможете брать задачи в работу в сервисе «Я занят»</div>
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
                    <label class="form-label">&nbsp;</label>
                    <button id="filter_btn" class="btn btn-white w-100"><b class="fad fa-search me-2"></b>
                        Искать</button>
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
                <h5 class="modal-title">Задача «<span id="view_title_task_name"></span>» <span class="text-muted">(ID#<b
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
                            </div>
                            <div class="form-group mt-3">
                                <label class="form-label text-muted">Заказчик</label>
                                <span id="view_task_company_name" class="font-weight-bold"></span> <a href=""
                                    id="view_task_company_link" target="_blank"><small>(профиль)</small></a>
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
                                    <div class="form-group mt-3">
                                        <label class="form-label text-muted">Сумма оплаты </label>
                                        <div class="font-weight-bold">
                                            <span id="view_task_sum"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="make_offer_wrapper" class="col-6" hidden>
                        <div class="p-3 rounded border">
                            {{-- 2do кнопка обновить --}}
                            <h2 class="text-center">Откликнуться на задачу</h2>

                            <p>Вы можете предложить заказчику свою кандидатуру для исполнения этой задачи</p>

                            <div class="mt-4 text-center mb-4">
                                <button id="make_offer_btn" class="btn btn-lg btn-pill btn-success">
                                    <span class="text">Предложить себя на эту задачу</span>
                                    <span class="wait" hidden><b class="fa fa-spinner fa-pulse"></b> Подождите...</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="done_offer_wrapper" class="col-6" hidden>
                        <div class="p-3 rounded border">
                            {{-- 2do кнопка обновить --}}
                            <h2 class="text-center">Вы откликнулись на эту задачу</h2>
                            <p class="text-center">Необходимо дождаться решения заказчика</p>
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
        window.InterfaceManager.loadMe();
        window.InterfaceManager.notificationsCount();
        //window.InterfaceManager.signUnrequestedCount();

        let TasksManager = new TasksManagerClass;
    });



    class TasksManagerClass {

        constructor(game_id) {
            let ths = this;
            ths.initTasksDatatable();
            ths.bindFilterAllTasksDatatableBtn();

            ths.loadCategories();


            var task_id = $('#task_id').val();
            if (task_id != '') {
                ths.openTaskView(task_id);
            }
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
                    url: '{{ config('app.api') }}/api/v2/contractor/tasks/new/datatable',
                    dataSrc: 'data',
                    type: 'GET',
                    headers: {
                        Authorization: `Bearer ${window.localStorage.getItem('token')}`
                    },
                    data: function (d) {
                        console.log(d);
                        d.filter = {};
                        let job_category_filter = $('#job_category_filter').val();
                        if (job_category_filter != '') {
                            d.filter.job_category_id = job_category_filter;
                        }
                    },
                    xhrFields: {
                        withCredentials: true
                    },
                    error:(error)=>{
                        if(error.status === 403){
                            window.location = '/my';
                        }
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
                oLanguage: {
                    sInfo: "<b>_START_</b> &rarr; <b>_END_</b>, из <b>_TOTAL_</b>",
                    sInfoEmpty: "Нет записей для отображения",
                    sInfoFiltered: "(отфильтровано из _MAX_)",
                    sLoadingRecords: "Загрузка...",
                    sProcessing: "<i class='fad fa-spinner fa-pulse'></i> Загрузка...",
                    sEmptyTable: "Нет данных в таблице",
                },
                columns: [
                    { name: 'id', data: 'id', title: 'ID', sortable: true, searchable: true, visible: true },
                    {
                        name: 'company_name', data: 'company_name', title: 'Заказчик', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            var b = '<div class="font-weight-bold">' + row.company_name + ' <a href="/company/' + row.company_id + '" target="_blank"><b class="fa fa-external-link"></b></a></div>'
                            return b;
                        }
                    },
                    { name: 'job_category_name', data: 'job_category_name', title: 'Категория работ', sortable: true, searchable: true, visible: false },
                    {
                        name: 'name', data: 'name', title: 'Задача', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            var b = '<div class="font-weight-bold">' + row.name + '</div>'
                            b += '<small class="d-block text-muted">' + row.job_category_name + '</small>';
                            return b;
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

                    { name: 'offers_count', data: 'offers_count', title: 'Количество откликов', sortable: true, searchable: true, visible: true },

                    {
                        name: 'sum', data: 'sum', title: 'Сумма', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return '<span class="text-nowrap">' + (!Boolean(row.is_sum_confirmed) ? 'Договорная' : row.sum) + '</span>';
                        }
                    },
                    { name: 'created_datetime', data: 'created_datetime', title: 'Дата размещения', sortable: true, searchable: true, visible: true },
                    {
                        name: 'my_offer_id', data: 'my_offer_id', title: 'Статус', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            if (row.my_offer_id != null) {
                                return '<span class="badge bg-warning">Ожидание решения заказчика</span'
                            }
                            return '<span class="badge bg-info">Новая</span'
                        }
                    },

                    {
                        title: 'Открыть', sortable: false, searchable: false, visible: true,
                        render: function (data, type, row, meta) {
                            return '<button class="btn-open-task-contractor btn btn-sm">Открыть</button>'
                        }
                    }
                ],
                rowCallback: function (row, data, index) {
                    $(row).find('button.btn-open-task-contractor').unbind('click').bind('click', function () {
                        ths.openTaskView(data.id);
                    });
                },
                drawCallback: function (settings) {
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                }
            }
            ths.tasks_datatable = $('#tasks_table').DataTable(settings);
        }



        bindFilterAllTasksDatatableBtn() {
            let ths = this;
            $('#filter_btn').unbind('click').bind('click', function () {
                ths.tasks_datatable.ajax.reload(null, false);
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




        /**
         * Загрузка задачи
         */
        loadTask(task_id, success_callback) {
            let ths = this;

            var ax = axios.get(`{{ config('app.api') }}/api/v2/contractor/tasks/${task_id}`);
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
            $('#view_task_company_name').text('');
            $('#view_task_company_link').attr('href', '#');
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

            $('#make_offer_wrapper').prop('hidden', true);
            $('#done_offer_wrapper').prop('hidden', true);
        }


        /**
         * Заполнение данными окна отображения задачи
         */
        fillTaskViewModal(task) {
            let ths = this;
            $('#view_title_task_id').text(task.id);
            $('#view_title_task_name').text(task.name);
            $('#view_task_name').text(task.name);
            $('#view_task_company_name').text(task.company.name ?? '');
            $('#view_task_company_link').attr('href', ('/company/' + task.company.id) ?? '');
            $('#view_task_job_category_name').text(task.job_category_name);

            var statuses = {};
            statuses['new'] = '<span class="badge bg-info text-white">Новая</span>';
            statuses['work'] = '<span class="badge bg-secondary text-white">В работе</span>';
            statuses['done'] = '<span class="badge bg-info text-white">На проверке</span>';
            statuses['paid'] = '<span class="badge bg-dark text-white">Оплачена</span>';
            statuses['await_payment'] = '<span class="badge bg-success text-white">Ожидает зачисления платежа</span>';

            $('#view_task_status').html(statuses[task.status]);

            $('#view_task_description').text(task.description);
            $('#view_task_address').text(task.address);
            $('#view_task_date_from').text(task.date_from);
            $('#view_task_date_till').text(task.date_till);
            if (!Boolean(task.is_sum_confirmed)) {
                $('#view_task_sum').text(`Договорная`);
            } else {
                $('#view_task_sum').text(`${task.sum} руб`);
            }
            $('#view_task_offers_count').text(task.offers_count);

            $('#make_offer_btn').unbind('click').bind('click', function () {
                ths.makeOffer(task.id);
            });


            ths.checkForMyOffer(task.id)


        }


        checkForMyOffer(task_id) {
            let ths = this;

            var ax = axios.get(`{{ config('app.api') }}/api/v2/contractor/tasks/${task_id}/my_offer`);
            ax.then(function (response) {
                if (response.data.offer) {
                    $('#done_offer_wrapper').prop('hidden', false);
                } else {
                    $('#make_offer_wrapper').prop('hidden', false);
                }
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



        makeOffer(task_id) {
            let ths = this;
            bootbox.dialog({
                title: 'Предложить себя в качестве исполнителя?',
                message: 'Вы действительно хотите предложить себя в качестве исполнителя данной задачи?',
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: '<b class="fa fa-check me-2"></b>Да, предложить',
                        className: 'btn-success',
                        callback: function () {

                            $('#make_offer_btn').prop('disabled', true);
                            $('#make_offer_btn .text').prop('hidden', true);
                            $('#make_offer_btn .wait').prop('hidden', false);

                            var ax = axios.post(`{{ config('app.api') }}/api/v2/contractor/tasks/${task_id}/make_offer`);
                            ax.then(function (response) {
                                if (response.data.message) {
                                    boottoast.success({
                                        message: response.data.message,
                                        title: response.data.title ?? 'Успешно',
                                        imageSrc: "/images/logo-sm.svg"
                                    });
                                }
                                ths.tasks_datatable.ajax.reload();
                                ths.openTaskView(task_id);
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
                                    $('#make_offer_btn').prop('disabled', false);
                                    $('#make_offer_btn .text').prop('hidden', false);
                                    $('#make_offer_btn .wait').prop('hidden', true);
                                });

                        }
                    }
                }
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
