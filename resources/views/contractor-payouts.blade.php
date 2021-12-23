@extends('layouts.master')

@section('title')
    Мои платежи
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
<input type="hidden" id="payout_id" value="{{ $payout_id ?? '' }}">
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- Page title -->
        <div class="page-header d-print-none mt-4">
            <div class="row align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div id="project_name" class="page-pretitle"></div>
                    <h2 class="page-title">
                        Мои платежи
                    </h2>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body p-0 pb-3">
                <table id="payouts_table" class="table table-hover" style="width:100%; cursor:pointer;"></table>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <div>Если у вас возникли вопросы по платежам, обратитесь в техническую поддержку, написав на info@izanyat.ru или позвоните по номеру +7 (499) 705-80-70. В письме укажите id платежа из таблицы Мои платежи.</div>
        </div>

    </div>
</div>

<div id="payout_view_modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" id="payout_view_modal_close" class="btn-small btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div  class="modal-body bg-white" id="payout_view_modal_content">

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(function(){
        window.InterfaceManager = new InterfaceManagerClass;
        window.InterfaceManager.menuShow('main_menu');
        window.InterfaceManager.menuActive('payouts');
        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe();
        window.InterfaceManager.notificationsCount();
        //window.InterfaceManager.signUnrequestedCount();
        $('#payout_view_modal_close').bind('click',function(){
            $('#payout_view_modal_content').html('');
        })
        $(document).find('.button.btn-annulate').on('click', function(e){
            console.log(this)
        });
        let PayoutsManager = new PayoutsManagerClass;
            PayoutsManager.initPayoutsDatatable();
    });



    class PayoutsManagerClass {

        constructor(game_id){
            let ths = this;

			var payout_id = $('#payout_id').val();
			if (payout_id != '') {
                var ax = axios.get(`{{ config('app.api') }}/api/v2/contractor/payouts/${payout_id}/info/`);
                        ax.then(function (response) {
                            const view = response.data.view;
                            $('#payout_view_modal_content').html('');
                            $('#payout_view_modal_content').append(view);
							$('#payout_view_modal').modal('show');
                            $(document).find('button.btn-annulate').bind('click',function(){
                                ths.anullateReceipt(payout_id,this)
                            })
                        })
                        .catch(function (error) {
                        });
			}
        }


        /**
         * Initialization of datatable
         */
        initPayoutsDatatable(){
            let ths = this;
            let csrf_token = $('meta[name="csrf-token"]').attr('content');

            $.fn.dataTable.ext.classes.sPageButton = "btn btn-outline-primary ";
            $.fn.dataTable.ext.classes.sPageButtonActive = "bg-primary text-light ";
            $.fn.dataTable.ext.classes.sProcessing = "text-center mb-3 mx-auto py-3 bg-dark text-light fixed-bottom col-4 rounded";
            $.fn.dataTable.ext.classes.sInfo = "text-center my-2 mx-auto p-2";
            $.fn.dataTable.ext.classes.sRowEmpty = "d-none";
            $.fn.dataTable.ext.classes.sWrapper = "";

            const token = window.localStorage.getItem('token');

            var settings = {
                ajax : {
                    url: '{{ config('app.api') }}/api/v2/contractor/payouts/datatable',
                    dataSrc: 'data',
                    type: 'GET',
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

                dom : '<"p-0 overflow-auto"rt><"text-center"<"mt-2"i><"mt-2 mb-2"p>>',
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
                    {name: 'id', data: 'id', title: 'ID платежа', sortable: true, searchable: true, visible: true},
                    {name: 'task_name', data: 'task_name', title: 'Задача', sortable: true, searchable: true, visible: true,
                    render: function (data, type, row, meta ) {
                        var b = '<div class="font-weight-regular">' + row.task_name + ' <small>(ID#' + row.task_id + ')</small></div>'
                            b+= '<small class="d-block text-muted">' + row.job_category_name + '</small>';
                        return b;
                    }},
                    {name: 'created_datetime', data: 'created_datetime', title: 'Дата', sortable: true, searchable: true, visible: true,
                    render: function (data, type, row, meta ) {
                        return '<small>' + row.created_datetime + '</small>';
                    }},
                    {name: 'company_name', data: 'company_name', title: 'Отправитель', sortable: true, searchable: true, visible: true,
                    render: function (data, type, row, meta ) {
                        var b = '<div class="font-weight-regular">' + row.company_name + ' <a href="/company/' + row.company_id + '" target="_blank"><b class="fa fa-external-link"></b></a></div>'
                            b+= '<small>ИНН: ' + row.company_inn + '</small>';
                        return b;
                    }},
                    {name: 'sum', data: 'sum', title: 'Сумма', sortable: true, searchable: true, visible: true,
                    render: function (data,type,row,meta){
                        var b = `<div class="font-weight-bold">${row.sum}</div>`;
                        return b;
                    }},
                    {name: 'status', data: 'status', title: 'Статус платежа', sortable: true, searchable: true, visible: true,
                    render: function (data, type, row, meta ) {
                        let statuses = {
                            'draft' : '<small class="badge bg-dark text-white">Черновик</small>',
                            'process' : '<small class="badge bg-info text-white">В процессе</small>',
                            'complete' : '<small class="badge bg-success text-white">Успешно</small>',
                            'error' : '<small class="badge bg-danger text-white">Ошибка</small>',
                            //'canceled' : '<small class="badge bg-danger text-white">Аннулирован</small>',
                            'canceled' : '<small class="badge bg-success text-white">Успешно</small>',
                        }
                        return statuses[row.status];
                    }},
                    //{name: 'task_id', data: 'task_id', title: 'Задача ID', sortable: true, searchable: true, visible: false},

                    /*{name: 'user_name', data: 'user_name', title: 'Получатель', sortable: true, searchable: true, visible: true,
                    render: function (data, type, row, meta ) {
                        var b = '<div class="font-weight-bold">' + row.user_name + '</div>'
                            b+= '<small class="d-block text-muted">ИНН: ' + row.user_inn + '</small>';
                        return b;
                    }},*/
                    //{name: 'user_inn', data: 'user_inn', title: 'ИНН', sortable: true, searchable: true, visible: false},
                    //{name: 'job_category_name', data: 'job_category_name', title: 'Категория работ', sortable: true, searchable: true, visible: false},


                    {name: 'receipt_url', data: 'receipt_url', title: 'Чек ФНС', sortable: true, searchable: true, visible: true,
                    render: function (data, type, row, meta ) {
                        if (row.receipt_url != null && row.status =='canceled'){
                            return '<div class="font-weight-regular">Аннулирован</div>';
                        }
                        else if(row.receipt_url != null){
                            return '<div class="font-weight-regular">Сформирован</div>';
                        }
                        return '–';
                    }},
                    /*{title: 'Аннулировать', sortable: true, searchable: true, visible: true,
                    render: function (data, type, row, meta ) {
                        if (row.receipt_url != null && row.status == 'complete'){
                            var b = '<button class="btn-annulate btn btn-sm btn-danger btn-pill">';
                                b+= '   <div class="text"><b class="fad fa-times me-2"></b>Аннулировать</div>';
                                b+= '   <div class="wait" hidden><b class="fad fa-spinner fa-pulse me-2"></b>Ожидайте</div>';
                                b+= '</button>';
                            return b;
                        }
                        return '–';
                    }},*/

                    /*{name: 'description', data: 'description', title: 'Инфо', sortable: true, searchable: true, visible: true,
                    render: function (data, type, row, meta ) {
                            if (row.description != null) {
                                var b = '<div class="payout_description p-1 rounded border" hidden>';
                                    b+= '<small>'+ row.description + '</small>';
                                    b+= '</div>';
                                    b+= '<button class="btn btn-sm btn-pill btn-show-description"><b class="fad fa-info-circle"></b></button>';
                                return b;
                            }
                            return '';
                    }},*/
                ],
                rowCallback: function(row, data, index){
                    $(row).bind('click',function(){
                        const taskID = data.id;
                        var ax = axios.get(`{{ config('app.api') }}/api/v2/contractor/payouts/${data.id}/info/`);
                        ax.then(function (response) {
                            const view = response.data.view;
                            $('#payout_view_modal_content').html('');
                            $('#payout_view_modal_content').append(view);
							$('#payout_view_modal').modal('show');
                            $(document).find('button.btn-annulate').bind('click',function(){
                                ths.anullateReceipt(data.id,this)
                            })
                        })
                        .catch(function (error) {
                        });
                })},
                drawCallback: function(settings){
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                }
            }
            ths.payouts_table = $('#payouts_table').DataTable(settings);
        }


        anullateReceipt(payout_id, button){
            let ths = this;


            var message = '<div>Вы собираетесь аннулировать чек по данному платежу.</div>';
                message+= '<div class="form-group mt-2">'
                message+= ' <label class="form-label">Укажите причину:</label>'
                message+= ' <select id="annulate_reason" class="form-select">'
                message+= '     <option value="REFUND">Возврат средств</option>'
                message+= '     <option value="REGISTRATION_MISTAKE">Чек сформирован ошибочно</option>'
                message+= ' </select>'
                message+= '</div>'


            bootbox.dialog({
                title: 'Аннулировать чек?',
                message: message,
                closeButton: false,
                buttons:{
                    cancel:{
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main:{
                        label:'Аннулировать чек',
                        className: 'btn-danger',
                        callback: function(){

                            $(button).prop('disabled', true);
                            $(button).find('.text').prop('hidden', false);
                            $(button).find('.wait').prop('hidden', true);

                            var data = { annulate_reason : $('#annulate_reason').val() };

                            var ax = axios.post(`{{ config('app.api') }}/api/v2/contractor/payouts/${payout_id}/annulate`, data);
                            ax.then(function (response) {
                                console.log(response.data)
                                if (response.data.message) {
                                    boottoast.success({
                                        message: response.data.message,
                                        title: response.data.title ?? 'Успешно',
                                        imageSrc: "/images/logo-sm.svg"
                                    });
                                }
                                ths.payouts_table.ajax.reload();
                                $('#payout_view_modal').modal('hide');
                                window.history.pushState({}, 'Мои платежи', '/contractor/payouts/');
                            })
                            .catch(function (error) {
                                console.log(error.response);
                                if (error.response) {
                                    bootbox.dialog({
                                        title: error.response.data.title ?? 'Ошибка',
                                        message: ' '+ error.response.data.message ?? error.response.statusText,
                                        closeButton: false,
                                        buttons:{
                                            cancel:{
                                                label: 'Закрыть',
                                                className: 'btn-dark'
                                            }
                                        }
                                    });
                                }
                            })
                            .finally(function(){
                                $(button).prop('disabled', false);
                                $(button).find('.text').prop('hidden', true);
                                $(button).find('.wait').prop('hidden', false);
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
            return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
        }
    }
</script>





@stop
