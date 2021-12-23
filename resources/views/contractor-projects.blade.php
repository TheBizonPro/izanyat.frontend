@extends('layouts.master')

@section('title')
Мои проекты
@stop

@section('styles')
@stop


@section('scripts')
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
@stop

@section('content')
<div class="page-wrapper">
    <div class="container-xl">

        <div class="page-header d-print-none mt-4">
            <div class="row align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle" hidden>
                    </div>
                    <h2 class="page-title">
                        <b class="fad fa-folder me-2"></b>Мои проекты
                    </h2>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            На этой странице выводятся проекты, в которых вам предложили участвовать. Вы можете согласиться или
            отказаться от участия в проекте.
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table id="projects_table" class="mt-0 table table-hover table-striped align-middle"></table>
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

        var ProjectsManager = new ProjectsManagerClass;
    });



    class ProjectsManagerClass {

        constructor() {
            let ths = this;
            ths.initProjectsDatatable();
            ths.bindSearchDatatable();
            ths.bindCreateNewProjectBtn();
        }

        /**
         * Initialization of datatable
         */
        initProjectsDatatable() {
            let ths = this;

            $.fn.dataTable.ext.classes.sPageButton = "btn btn-outline-primary ";
            $.fn.dataTable.ext.classes.sPageButtonActive = "bg-primary text-light ";
            $.fn.dataTable.ext.classes.sProcessing = "text-center mb-3 mx-auto py-3 bg-dark text-light fixed-bottom col-4 rounded";
            $.fn.dataTable.ext.classes.sInfo = "text-center my-2 mx-auto p-2";
            $.fn.dataTable.ext.classes.sRowEmpty = "d-none";
            $.fn.dataTable.ext.classes.sWrapper = "";

            var settings = {
                ajax: {
                    url: '{{ config('app.api') }}/api/contractor/projects/datatable',
                    dataSrc: 'data',
                    type: 'GET',
                    xhrFields: {
                        withCredentials: true
                    },
                    error: (error) => {
                        if (error.status === 403) {
                            window.callPermissionModal();
                        }
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
                        name: 'name', data: 'name', title: 'Проект', class: '', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return '<b class="fad fa-folder me-2"></b><b>' + row.name + '</b>';
                        }
                    },
                    { name: 'job_category_name', data: 'job_category_name', title: 'Категория работ', class: 'nowrap text-center', sortable: true, searchable: false, visible: true },
                    { name: 'created_date', data: 'created_date', title: 'Дата создания', class: 'nowrap text-center', sortable: true, searchable: true, visible: true },
                    { name: 'project_id', data: 'project_id', title: 'Id проекта', class: 'text-end', sortable: true, searchable: true, visible: false },
                    {
                        name: 'accepted_by_user', data: 'accepted_by_user', title: 'Статус', class: 'text-end', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            if (row.accepted_by_user == 1) {
                                return '<b>Проект принят</b>';
                            } else {
                                var b = '<div>'
                                b += '<button class="btn-accept btn btn-sm btn-success btn-pill">Принять проект</button>'
                                b += '</div>'
                                b += '<div>'
                                b += '<button class="btn-decline btn btn-sm btn-danger mt-1 btn-pill">Отклонить проект</button>'
                                b += '</div>'
                                return b;
                            }
                        }
                    }
                ],
                rowCallback: function (row, data, index) {
                    $(row).find('.btn-accept').bind('click', function () {
                        ths.acceptProject(data.project_id);
                    });
                    $(row).find('.btn-decline').bind('click', function () {
                        ths.declineProject(data.project_id);
                    });
                },
                drawCallback: function (settings) {
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                }
            }
            ths.projectsDatatable = $('#projects_table').DataTable(settings);
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
                    ths.projectsDatatable.search(this.value).draw();
                }
            });

            $('#search_input').bind('keyup', function () {
                ths.projectsDatatable.search(this.value).draw();
            });
        }



        acceptProject(project_id) {
            let ths = this;
            var ax = axios.post('{{ config('app.api') }}/api/project/' + project_id + '/accept');
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
                    ths.projectsDatatable.ajax.reload();
                });
        }



        declineProject(project_id) {
            let ths = this;
            bootbox.dialog({
                title: 'Отклонить проект?',
                closeButton: false,
                message: 'В случае отклонения проекта вы не сможете получать задания и оплату в рамках этого проекта. Вы уверены?',
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: 'btn-light'
                    },
                    main: {
                        label: 'Отклонить проект',
                        className: 'btn-danger',
                        callback: function () {

                            var ax = axios.post('{{ config('app.api') }}/api/project/' + project_id + '/decline');
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
                                    ths.projectsDatatable.ajax.reload();
                                });


                        }
                    }
                }
            });
        }





    }


</script>

@stop
