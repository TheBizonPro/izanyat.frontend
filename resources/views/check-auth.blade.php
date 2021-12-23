@extends('layouts.empty')

@section('title')
Проверка авторизации
@stop

@section('styles')
<style type="text/css">
    #animated_logo {
        font-size: 50pt;
        font-weight: 600;
        width: 400px;
    }

    #animated_logo span.i {
        color: #1759a8;
    }

    #animated_logo span.profession {
        color: #555;
        margin-left: 10px;
    }
</style>
@stop

@section('scripts')
@stop

@section('content')
<section class="container-fluid flex-grow-0" id="blocks_wrapper">
    <div class="mt-5 text-center">
        <div id="animated_logo" class="mx-auto">
            <span class="i">Я</span><span class="profession"></span>
        </div>
        <div class="mt-5 text-center text-muted">
            <b class="fad fa-spinner fa-pulse me-2"></b> Пожалуйста, подождите. Проверка аутентификации.
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        let CheckAuthManager = new CheckAuthManagerClass;
    });

    class CheckAuthManagerClass {

        constructor() {
            let ths = this;
            ths.animateLogo();
            setTimeout(function () {
                ths.requestAuth();
            }, 2000);
        }

        requestAuth() {
            let ths = this;
            var ax = axios.get('{{ config('app.api') }}/api/check-auth', {
                headers: {
                    'Authorization': `Bearer ${window.localStorage.getItem('token')}`
                }
            });
            ax.then(function (response) {
                if (response.data.me) {
                    if (response.data.me.is_selfemployed == 1) {
                        window.location = '/projects';
                    } else if (response.data.me.is_client == 1) {
                        window.location = '/my-company-profile';
                    } else {
                        window.location = '/projects';
                    }
                }
            })
                .catch(function (error, code) {
                    console.error(error.response);
                    if (error.response.status == 401) {
                        window.location = '/login';
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
                .finally(function () {

                });
        }


        animateLogo() {
            let professions = [
                'Репетитор',
                'Курьер',
                'Няня',
                'Дизайнер',
                'Разработчик',
                'Промоутер',
                'Водитель',
                'Визажист',
                'Стилист',
                'Тренер',
                'Маникюрщица',
                'Доула',
                'Инструктор',
                'Таксист',
                'Грузчик',
                'Уборщик',
                'Программист',
                'Слесарь',
                'Сантехник',
                'Занят'
            ];

            let timeout = 300

            $.each(professions, function (i, item) {
                setTimeout(function () {
                    $('#animated_logo').find('.profession').text(item);
                }, timeout);
                timeout = timeout + 120;
            });

        }


    }
</script>
@stop
