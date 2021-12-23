@extends('layouts.master')

@section('title')
Мои уведомления
@stop

@section('styles')
<style type="text/css">
    tr.unread {
        font-weight: bolder;
    }

    .unread-indicator {
        display: none;
    }

    tr.unread .unread-indicator {
        display: block;
    }

    #notification_text {
        white-space: pre-line;
        overflow-wrap: normal;
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
                    <div class="page-pretitle" hidden>
                    </div>
                    <h2 class="page-title">
                        <b class="fad fa-bell me-2"></b>Мои уведомления
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <span class="d-none d-sm-inline">
                            <button id="all_read_btn" class="btn btn-white">
                                Пометить все как прочитанные
                            </button>
                            <button id="toggle_search" class="btn btn-white">
                                <b class="fad fa-search"></b>&nbsp;
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <input id="search_input" hidden type="text" class="form-control mt-1 mb-4 w-100"
            placeholder="Введите текст для поиска">

        <div class="card">
            <div class="card-body p-0">
                <table id="notifications_table" class="mt-0 table table-hover table-striped align-middle"></table>
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

        var NotificationsManager = new NotificationsManagerClass;
    });



    class NotificationsManagerClass {

        constructor(game_id) {
            let ths = this;
            ths.initNotificationsDatatable();
            ths.bindSearchDatatable();
            ths.bindSetAllReadBtn();
        }

        bindAcceptPaymentBtn() {
            let ths = this;
            let $btn = $('.btn-confirm-price');
            const taskID = $btn.data('task-id');
            const sum = $btn.data('sum');
            $btn.on('click', function ($event) {
                let ax = axios.post(`{{ config('app.api') }}/api/v2/contractor/tasks/${taskID}/confirm/accept`, {
                    sum: sum
                })
                    .then(response => {
                        if (response.data.message) {
                            boottoast.success({
                                message: response.data.message,
                                title: response.data.title ?? 'Успешно',
                                imageSrc: "/images/logo-sm.svg"
                            });
                            $('#notification_modal').modal('hide');
                        }
                        window.InterfaceManager.notificationsCount();
                    })
            });
        }

        bindAcceptDenyBtn() {
            let ths = this;
            let $btn = $('.btn-deny-price');
            const taskID = $btn.data('task-id');
            $btn.on('click', function ($event) {
                $btn.on('click', function ($event) {
                    let ax = axios.post(`{{ config('app.api') }}/api/v2/contractor/tasks/${taskID}/confirm/deny`)
                        .then(response => {
                            if (response.data.message) {
                                boottoast.success({
                                    message: response.data.message,
                                    title: response.data.title ?? 'Успешно',
                                    imageSrc: "/images/logo-sm.svg"
                                });
                                $('#notification_modal').modal('hide');
                            }
                            window.InterfaceManager.notificationsCount();
                        })
                });
            });
        }
        /**
         * Initialization of datatable
         */
        initNotificationsDatatable() {
            let ths = this;

            $.fn.dataTable.ext.classes.sPageButton = "btn btn-outline-primary ";
            $.fn.dataTable.ext.classes.sPageButtonActive = "bg-primary text-light ";
            $.fn.dataTable.ext.classes.sProcessing = "text-center mb-3 mx-auto py-3 bg-dark text-light fixed-bottom col-4 rounded";
            $.fn.dataTable.ext.classes.sInfo = "text-center my-2 mx-auto p-2";
            $.fn.dataTable.ext.classes.sRowEmpty = "d-none";
            $.fn.dataTable.ext.classes.sWrapper = "";

            var settings = {
                ajax: {
                    url: '{{ config('app.api') }}/api/notifications/datatable',
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
                    {
                        name: 'is_readed', data: 'is_readed', title: '<b class="fa fa-circle"></b>', class: 'nowrap text-center', sortable: true, searchable: false, visible: true,
                        render: function (data, type, row, meta) {
                            return '<b class="unread-indicator fad fa-circle text-success"></b>';
                        }
                    },
                    { name: 'created_at', data: 'created_at', title: 'Дата', class: 'nowrap', sortable: true, searchable: true, visible: false },
                    { name: 'created_datetime', data: 'created_datetime', title: 'Дата', class: 'nowrap', sortable: true, searchable: true, visible: true },
                    { name: 'from', data: 'from', title: 'От кого', class: 'nowrap', sortable: true, searchable: true, visible: true },
                    { name: 'subject', data: 'subject', title: 'Тема', class: 'nowrap', sortable: true, searchable: true, visible: true },
                    { name: 'text', data: 'text', title: 'Сообщение', class: 'nowrap', sortable: true, searchable: true, visible: true },
                    {
                        name: 'id', data: 'id', title: 'Показать', class: 'text-end', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return '<button class="btn btn-sm btn-white btn-show">Показать</a>';
                        }
                    },
                ],
                rowCallback: function (row, data, index) {
                    $(row).addClass('cursor-pointer')

                    if (data.is_readed == false) {
                        $(row).addClass('unread')
                    }

                    $(row).find('button.btn-show').bind('click', function (e) {
                        e.stopPropagation();
                        ths.openNotification(data.id, row);
                    });

                    $(row).bind('click', function (e) {
                        e.stopPropagation();
                        ths.openNotification(data.id, row);
                    });
                },
                drawCallback: function (settings) {
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                }
            }
            ths.notificationsDatatable = $('#notifications_table').DataTable(settings);
        }


        /**
         * Binding search_input keyup for searching
         */
        bindSearchDatatable() {
            let ths = this;
            $('#toggle_search').bind('click', function () {
                if ($('#search_input').prop('hidden') == true) {
                    $('#search_input').prop('hidden', false);
                    $('#search_input').focus();
                } else {
                    $('#search_input').prop('hidden', true);
                    $('#search_input').val('');
                    ths.notificationsDatatable.search(this.value).draw();
                }
            });

            $('#search_input').bind('keyup', function () {
                ths.notificationsDatatable.search(this.value).draw();
            });
        }


        bindSetAllReadBtn() {
            let ths = this;
            $('#all_read_btn').bind('click', function () {

                bootbox.dialog({
                    title: 'Прочитать все',
                    message: 'Отметить все ваши уведомления как прочитанные?',
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

                                var ax = axios.post('{{ config('app.api') }}/api/notifications/read_all',);
                                ax.then(function (response) {
                                    if (response.data.message) {
                                        boottoast.success({
                                            message: response.data.message,
                                            title: response.data.title ?? 'Успешно',
                                            imageSrc: "/images/logo-sm.svg"
                                        });
                                    }
                                    window.InterfaceManager.notificationsCount();
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
                                        ths.notificationsDatatable.ajax.reload();
                                    });

                            }
                        }
                    }
                });
            });
        }


        openNotification(notification_id, row) {
            let ths = this;

            var ax = axios.get('{{ config('app.api') }}/api/notification/' + notification_id);
            ax.then(function (response) {
                if (response.data) {
                    $('#notification_from').text(response.data.notification.from);
                    $('#notification_subject').text(response.data.notification.subject);
                    $('#notification_created_datetime').text(response.data.notification.created_datetime);
                    $('#notification_text').html(response.data.notification.text);
                    $('#notification_modal').modal('show')
                    $(row).removeClass('unread');

                    ths.bindAcceptPaymentBtn();
                    ths.bindAcceptDenyBtn();
                }

                setTimeout(function () {
                    try {
                        window.InterfaceManager.notificationsCount();
                    } catch (e) { }
                }, 2000);
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


</script>


<div id="notification_modal" class="modal" tabindex="-1">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Уведомление</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="form-label text-muted">От кого</label>
                        <div class="font-weight-bold" id="notification_from"></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label text-muted">Тема</label>
                        <div class="font-weight-bold" id="notification_subject"></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label text-muted">Дата</label>
                        <div class="font-weight-bold" id="notification_created_datetime"></div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label text-muted">Сообщение</label>
                    <div class="" id="notification_text"></div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
