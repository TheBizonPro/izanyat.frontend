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
        <!-- Page title -->
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
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <span class="d-none d-sm-inline">
                            <button id="create_new_project" class="btn btn-white" hidden>
                                Создать новый проект
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

        setTimeout(function () {
            var ProjectsManager = new ProjectsManagerClass;
        }, 1000);

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
            const token = window.localStorage.getItem('token');
            var settings = {
                ajax: {
                    url: '{{ config('app.api') }}/api/v2/company/projects/datatable',
                    dataSrc: 'data',
                    type: 'GET',
                    xhrFields: {
                        withCredentials: true
                    },
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                    error: (error) => {
                        if (error.status === 403) {
                            window.callPermissionModal();
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
                    {
                        name: 'name', data: 'name', title: 'Проект', class: '', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return '<b class="fad fa-folder me-2"></b><b>' + row.name + '</b>';
                        }
                    },
                    { name: 'tasks_count', data: 'tasks_count', title: 'Кол-во задач', class: 'nowrap text-center', sortable: false, searchable: false, visible: true },
                    { name: 'created_date', data: 'created_date', title: 'Дата создания', class: 'nowrap text-center', sortable: true, searchable: true, visible: true },
                    {
                        name: 'id', data: 'id', title: 'Открыть', class: 'text-end', sortable: true, searchable: true, visible: true,
                        render: function (data, type, row, meta) {
                            return '<a href="/project/' + row.id + '/tasks" class="btn btn-md btn-white btn-show btn-task-show" hidden><b class="fad fa-arrow-right"></b></a>';
                        }
                    },
                ],
                rowCallback: function (row, data, index) {

                },
                drawCallback: function (settings) {
                    $('.dataTables_paginate').find('span').addClass('btn-group');
                    window.permissionHandler.handle();
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


        bindCreateNewProjectBtn() {

            $('#create_new_project').bind('click', function () {
                var message = '<div class="form-group">'
                message += '	<label class="form-label">Название проекта</label>'
                message += '	<input id="new_project_name" type="text" class="form-control">'
                message += '</div>'

                bootbox.dialog({
                    title: 'Создание нового проекта',
                    message: message,
                    closeButton: false,
                    buttons: {
                        cancel: {
                            label: 'Отмена',
                            className: 'btn-light'
                        },
                        main: {
                            label: '<b class="fa fa-check me-2"></b>Создать проект',
                            className: 'btn-primary',
                            callback: function () {

                                let data = {};
                                data.name = $('#new_project_name').val();

                                console.log(data);

                                var ax = axios.post('{{ config('app.api') }}/api/v2/company/projects', data);
                                ax.then(function (response) {
                                    if (response.data.message) {
                                        boottoast.success({
                                            message: response.data.message,
                                            title: response.data.title ?? 'Успешно',
                                            imageSrc: "/images/logo-sm.svg"
                                        });
                                    }
                                    if (response.data.project_id) {
                                        setTimeout(function () {
                                            window.location = '/project/' + response.data.project_id + '/tasks';
                                        }, 1000);
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
                        }
                    }
                });
            });
        }

    }


</script>

@stop
