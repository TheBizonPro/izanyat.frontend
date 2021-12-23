<!DOCTYPE html>
<html lang="ru" dir="ltr">

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="Content-Language" content="ru" />
    <meta name="msapplication-TileColor" content="#596be7" />
    <meta name="theme-color" content="#596be7" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="MobileOptimized" content="320" />
    <meta name="format-detection" content="telephone=no">
    <meta name="robots" content="index, follow">
    <link rel="icon" href="/images/favicon.svg">
    @yield('meta')

    <link rel="stylesheet" href="/css/tabler.min.css">
    <link rel="stylesheet" href="/css/admin.min.css">
    <link rel="preload" href="/css/fontawesome.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

    @yield('styles')


    <script src="/js/app.js" defer></script>
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/axios.min.js"></script>
    <script type="text/javascript" type="application/javascript">
        window.axios.defaults.withCredentials = true;
        window.axios.interceptors.request.use(function (config) {
            const accessToken = window.localStorage.getItem('token');

            config.headers.Authorization = `Bearer ${accessToken}`;
            return config;
        });
        window.axios.interceptors.response.use((response) => response,
            function (responseError) {
                if (responseError.response.status == 401) {
                    window.location.href = '/login';
                    return responseError;
                }
                if (responseError.response.status == 403) {
                    window.callPermissionModal();
                    return responseError;
                }
                return responseError.response;
            });
    </script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/bootbox.min.js"></script>
    <script>
        window.callPermissionModal = function () {
            bootbox.dialog({
                title: "Недостаточно прав для просмотра страницы",
                message: "Свяжитесь с администратором компании для предоставления доступа",
                closeButton: false,
                buttons: {
                    cancel: {
                        label: 'Покинуть страницу',
                        className: 'btn-dark',
                        callback: function () {
                            const me = JSON.parse(window.localStorage.getItem('me'));
                            if (me.is_client) {
                                window.location.href = '/my-company-profile';
                            }
                            else {
                                window.location.href = '/my';
                            }
                        }

                    }
                }
            });
        }
    </script>
    <script type="text/javascript" src="/js/boottoast5.min.js"></script>
    <script type="text/javascript" src="/js/app.min.js"></script>
    <script type="text/javascript" src="/js/selectize.min.js"></script>
    <script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>


    @yield('scripts')
</head>

<body class="antialiased subpixel-antialiased">

    <input type="hidden" id="project_id" value="{{ $project_id ?? '' }}">

    <div class="page" id="app">
        <div class="flex-fill">
            @include('layouts.header')
            @include('layouts.menu')
            @yield('content')
        </div>
    </div>



    <div id="sign_me_complete_identify_modal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Завершение идентификации</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Необходимо пройти идентификацию личности, встретившись с курьером SignMe. После этого вам будет
                        доступен весь функционал платформы «ЯЗанят»</p>
                    <div class="mt-3">
                        <button id="check_sign_me_identification" class="btn btn-primary btn-lg">
                            <span class="text">Проверить</span>
                            <span class="wait" hidden><b class="fa fa-spinner fa-pulse me-2"></b>Идет проверка</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="balance_modal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Пополнение баланса</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Для осуществления платежей необходим действующий договор ИТО с НКО “МОБИ.Деньги”. В профиле на
                        площадке ЯЗанят укажите идентификатор партнера и секретный пароль, предоставленные НКО
                        “МОБИ.Деньги”
                    </p>
                    <p>
                        Для пополнения баланса внесите средства на счет обеспечения предприятия в НКО и ваш баланс
                        обновится автоматически.
                    </p>
                </div>
            </div>
        </div>
    </div>



    <script type="text/javascript" type="application/javascript">
        class InterfaceManagerClass {
            constructor() {
                let ths = this;

                //ths.signUnrequestedCount();


                $('#sign_me_complete_identify_btn').bind('click', function () {
                    $('#sign_me_complete_identify_modal').modal('show')
                });

                $('#check_sign_me_identification').bind('click', function () {
                    ths.checkSignMeIdentification();
                });

                $('#balance_btn').bind('click', function () {
                    $('#balance_modal').modal('show');
                });
            }


            menuHide() {
                $('#main_menu').prop('hidden', true);
                $('#project_menu').prop('hidden', true);
            }

            menuShow(menu) {
                $('#' + menu).prop('hidden', false);
            }

            menuActive(item) {
                $('#main-menu .nav-item').removeClass('active');
                $('#main-menu [data-menu="' + item + '"]').addClass('active');
            }

            checkAuth() {
                let ths = this;
                var ax = axios.get('{{ config('app.api') }}/api/check-auth');
                ax.then(function (response) {
                    console.log('Вы авторизованы!');
                })
                    .catch(function (error, code) {
                        console.log(errror);
                        console.error(error.response);
                        if (error.response.status == 403) {
                            window.location = '/check-auth';
                        } else if (error.response.status == 419) {
                            window.location = '/check-auth';
                        } else {
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
                        }
                    })
                    .finally(function () { });
            }


            loadMe(callback) {
                let ths = this;
                var ax = axios.get('{{ config('app.api') }}/api/me?withPermissions=1');
                ax.then(function (response) {
                    if (response.data.action) {
                        if (response.data.action == 'registration') {
                            if (window.location.pathname != '/registration') {
                                window.location = '/registration';
                            }
                        }
                    }

                    if (response.data.company) {
                        window.localStorage.setItem('company', JSON.stringify(response.data.company));
                    }
                    if (response.data.me) {
                        window.localStorage.setItem('me', JSON.stringify(response.data.me))
                        const responsePermissions = response.data.permissions?.map(item => item.name) || [];

                        window.localStorage.setItem('permissions', JSON.stringify(responsePermissions));
                        window.permissionHandler = new PermissionsHandler(responsePermissions);
                        window.permissionHandler.handle();

                        if (callback) {
                            callback(response.data.me)
                        }

                        let name = (response.data.me.firstname ?? '') + ' ' + (response.data.me.lastname ?? '');
                        if (name == ' ') {
                            name = response.data.me.phone
                        }

                        $('#user_name').text(name);

                        if (response.data.me.is_administrator == 1) {
                            $('#user_info').text('Администратор');
                            ths.role = 'administrator';
                        }

                        if (response.data.me.is_client == 1) {
                            if (response.data.company != null) {
                                $('#user_info').text(response.data.company.name);
                            } else {
                                $('#user_info').text('?');
                            }
                            ths.role = 'client';
                            $('#client_menu').prop('hidden', false);
                            $('#menu-profile-client').prop('hidden', false);
                            $('#balance_btn').prop('hidden', false);
                            $('#balance').text(response.data.company.balance);

                        }

                        if (response.data.me.is_selfemployed == 1) {
                            $('#user_info').text('Самозанятый');
                            ths.role = 'selfemployed'
                            $('#contractor_menu').prop('hidden', false);
                            $('#menu-profile-contractor').prop('hidden', false);
                        }

                        if (response.data.me.is_identified != true) {
                            $('#not_identified_alert').prop('hidden', false);
                        }

                        if (response.data.me.is_selfemployed == 1) {
                            if (response.data.me.taxpayer_binded_to_platform == 0 || response.data.me.taxpayer_binded_to_platform == null) {
                                $('#not_npd_alert').prop('hidden', false);
                                $('#task_not_npd_alert').prop('hidden', false);
                            }
                        }

                    }
                })
                    .catch(function (error) {
                        console.log(error);
                        window.localStorage.removeItem('me')
                        bootbox.dialog({
                            title: error?.response?.data?.title ?? 'Ошибка',
                            message: error?.response?.data?.message ?? error?.response?.statusText ?? "Ошибка",
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


            notificationsCount() {
                let ths = this;
                var ax = axios.get('{{ config('app.api') }}/api/notifications/unread_count');
                ax.then(function (response) {
                    if (response.data.notifications_count > 0) {
                        $('#new_notifications_badge').prop('hidden', false);
                        $('#new_notifications_badge').text(response.data.notifications_count);
                    } else {
                        $('#new_notifications_badge').prop('hidden', true);
                    }

                })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(function () {
                        setTimeout(function () {
                            ths.notificationsCount();
                        }, 5000);
                    });
            }

            signUnrequestedCount(id = '') {
                let ths = this;
                var ax = axios.get(`{{ config('app.api') }}/api/v2/company/documents/unrequested_signs_count?project_id=${id}`);
                ax.then(function (response) {
                    if (response.data) {
                        $('#request_signs_documents_btn').prop('hidden', false);
                        $('#unrequested_signs_count_badge').text(response.data?.unrequested_signs_count || 0);
                        $('#unrequested_signs_count_documents').html(response.data?.unrequested_signs_count || 0);
                        $('#request_signs_documents_btn').unbind('click').bind('click', function () {
                            ths.requestSignAllDocuments();
                        });
                    } else {
                        $('#request_signs_documents_btn').prop('hidden', true);
                    }
                })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(function () {
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
                                        ths.signUnrequestedCount();
                                    });
                            }
                        }
                    }
                });
            }


            checkSignMeIdentification() {
                let ths = this;

                $('#check_sign_me_identification').prop('disabled', true);
                $('#check_sign_me_identification .text').prop('hidden', true);
                $('#check_sign_me_identification .wait').prop('hidden', false);

                var ax = axios.get('{{ config('app.api') }}/api/signme/check_identification');
                ax.then(function (response) {

                    if (response.data.message) {
                        boottoast.info({
                            message: response.data.message,
                            title: response.data.title ?? 'Успешно',
                            imageSrc: "/images/logo-sm.svg"
                        });
                    }

                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
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
                        $('#check_sign_me_identification').prop('disabled', false);
                        $('#check_sign_me_identification .text').prop('hidden', false);
                        $('#check_sign_me_identification .wait').prop('hidden', true);
                    });
            }
        }


        class ProjectManagerClass {

            constructor() {
                let ths = this;
            }

            loadProjectData() {
                let ths = this;
                var ax = axios.get(`{{ config('app.api') }}/api/v2/company/projects/${ths.project_id}`);
                ax.then(function (response) {
                    $('.project_name, #project_name').text(response.data.project.name)
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

    <script>
        window.permissionsNameDefinition = {
            "company.admin": [
                '.btn-task-show',
                '#add_task_btn',
                '.btn-open-task',
                "#all-payouts-button",
                '#delete_task_btn',
                '#edit_task_btn',
                '#copy_task_btn',
                "#add_payouts_excel_btn",
                '#add_tasks_excel_btn',
                '#pay_for_selected',
                "#all-payouts-button",
                ".repay-button",
                '#projects_list_payouts',
                "#all-documents-button",
                "#documents_show_menu",
                '#add_document_btn',
                '.btn-request-all',
                '.btn-request-scope',
                "#company_bank_account",
                "#company-about-text",
                "#company-about-save",
                '#reciepts_dropdown',
                '#download_selected_receipt',
                '#download_all_filtered_receipt',
                '#download_all_receipt',
                '.receipt-url',
                '#projects_list_payouts',
                "#all-projects-button",
                '#employees-list',
                '#permissions-list',
                '#company_bank_account',
                '#project-tasks',
                '#project-payouts',
                '#project-documents',
                "#create_new_project"
            ],
            "company.tasks.show": [
                '.btn-task-show',
                '#add_task_btn',
                '.btn-open-task',
                '#project-tasks',
            ],
            "company.tasks.delete": [
                '#delete_task_btn'
            ],
            "company.tasks.update": [
                '#edit_task_btn',
                '#copy_task_btn',
                '#add_task_btn',
                '#add_tasks_excel_btn',
                '.sum-payment'
            ],
            "company.tasks.contractor_assign": [
                '.btn-accept-offer'
            ],
            "company.tasks.accept_job": [
                '.sum-payment-view',
                '.btn-confirm-sum'
            ],
            "company.tasks.pay": [
                "#add_payouts_excel_btn",
                '#pay_for_selected',
                '.btn-pay-task',
            ],
            "company.payouts.show": [
                "#all-payouts-button",
                '#project-payouts',
            ],
            "company.payouts.repay": [
                ".repay-button",
            ],
            "company.documents.show": [
                '#projects_list_payouts',
                "#all-documents-button",
                "#documents_show_menu",
                '#project-documents',
                '.documents-dropdown-show'
            ],
            "company.documents.create": [
                '#add_document_btn',
            ],
            "company.documents.request_sign": [
                '.btn-request-all',
                '.btn-request-scope',
                '.documents-sign-count'
            ],
            "company.bank_account.update": [
                "#company_bank_account",
            ],
            "company.company_data.update": [
                "#company-about-text",
                "#company-about-save",
            ],
            "company.receipts.show": [
                '#reciepts_dropdown',
                '#download_selected_receipt',
                '#download_all_filtered_receipt',
                '#download_all_receipt',
                '.receipt-url',
            ],
            "company.projects.show": [
                "#all-projects-button",
                '#projects-list-payouts',
            ],
            "company.projects.create": [
                "#create_new_project",
            ],
        }


        class PermissionsHandler {

            constructor(permissionList) {
                this.permissionList = (permissionList);
            }

            handle() {
                for (let permission of this.permissionList) {
                    let currentPermission = window?.permissionsNameDefinition[permission];
                    currentPermission?.forEach(element => {
                        this.showElement(element);
                    });
                }
                let allPermissions = new Set(Object.keys(window.permissionsNameDefinition));
                let userPermissions = new Set(this.permissionList);
                let difference = new Set([...allPermissions].filter(x => !userPermissions.has(x)));
                const isAdmin = this.permissionList.includes('company.admin')
                if (!isAdmin) {
                    for (let permission of difference) {
                        if (permission !== 'company.admin') {
                            let currentPermission = window.permissionsNameDefinition[permission];
                            currentPermission.forEach(element => {
                                this.deleteElement(element);
                            });
                        }
                    }
                }
                console.log('Permission is enable');

            }

            isCan(permission) {
                const isAdmin = this.permissionList.includes('company.admin')
                if (isAdmin) return true;
                return this.permissionList.includes(permission);
            }

            deleteElement(selector) {
                const $element = $(selector);
                $(selector).remove();
            }

            showElement(selector) {
                const $element = $(selector);
                $(selector).prop('hidden', false);
            }
        }

        window.permissionHandler = new PermissionsHandler(JSON.parse(window.localStorage.getItem('permissions')));

        window.permissionHandler.handle();
    </script>
</body>

</html>
