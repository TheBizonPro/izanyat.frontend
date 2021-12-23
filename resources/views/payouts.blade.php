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
                    <h2 class="page-title">
                        Платежи
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none" hidden>
                    <div class="btn-list">
                        <span class="d-none d-sm-inline">
                            <button id="add_payout_xls_btn" class="btn btn-white">
                                <b class="fa fa-plus-circle text-success me-2"></b>Загрузить реестр выплат
                            </button>
                        </span>
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



<script type="text/javascript">
    $(function () {
        window.InterfaceManager = new InterfaceManagerClass;
        window.InterfaceManager.menuShow('project_menu');
        window.InterfaceManager.menuActive('payouts');
        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe();
        window.InterfaceManager.notificationsCount();
        //window.InterfaceManager.signUnrequestedCount();

        let PayoutsManager = new PayoutsManagerClass;
        PayoutsManager.project_id = $('#project_id').val();
        PayoutsManager.initPayoutsDatatable();

        var ProjectManager = new ProjectManagerClass;
        ProjectManager.project_id = $('#project_id').val();
        ProjectManager.loadProjectData();
    });



    class PayoutsManagerClass {

        constructor(game_id) {
            let ths = this;

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
                    url: `{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}/payouts/datatable`,
                    dataSrc: 'data',
                    type: 'GET',
                    xhrFields: {
                        withCredentials: true
                    },
                    headers: {
                        Authorization: `Bearer ${window.localStorage.getItem('token')}`
                    },
                },
                processing: true,
                pageLength: 50,

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
                    { name: 'id', data: 'id', title: 'ID платежа', sortable: true, searchable: true, visible: true },
                    { name: 'sum', data: 'sum', title: 'Сумма', sortable: true, searchable: true, visible: true },
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
                    { name: 'task_id', data: 'task_id', title: 'Задача ID', sortable: true, searchable: true, visible: false },

                    {
                        name: 'user_name', data: 'user_name', title: 'Получатель', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            var b = '<div class="font-weight-bold">' + row.user_name + '</div>'
                            b += '<small class="d-block text-muted">ИНН: ' + row.user_inn + '</small>';
                            return b;
                        }
                    },
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
                                return '<a hidden class="btn btn-sm btn-white btn-pill receipt-url" target="_blank" href="' + row.receipt_url + '"><b class="fad fa-receipt me-2"></b>Чек ФНС</a>';
                            }
                            return '–';
                        }
                    },

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
                ],
                rowCallback: function (row, data, index) {
                    $(row).find('button.btn-show-description').bind('click', function () {
                        $(this).prop('hidden', true)
                        $(row).find('.payout_description').prop('hidden', false);
                    });
                },
                drawCallback: function (settings) {
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                }
            }
            ths.payouts_table = $('#payouts_table').DataTable(settings);
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
