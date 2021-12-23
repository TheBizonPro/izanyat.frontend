@extends('layouts.master')

@section('title')
Сотрудники
@stop

@section('styles')
<style>
    .list-group-item-action {
        cursor: pointer;
    }
</style>
@stop


@section('scripts')
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
@stop

@section('content')
<div class="page-wrapper">
    <div class="container-xl mt-3">
        <div class="row">
            <div class="col-2 me-4">
                <div class="list-group list-group-flush">
                    <a href="/my-company-profile" style="font-size:13px"
                        class="list-group-item list-group-item-action">Личные
                        данные</a>
                    <a href="/my-company" style="font-size:13px" class="list-group-item list-group-item-action">Данные
                        организации</a>
                    <a href="/employees" style="font-size:13px"
                        class="list-group-item list-group-item-action list-group-item-dark">Сотрудники</a>
                    <a href="/my-company-permissions" id="employees-list" style="font-size:13px"
                        class="list-group-item list-group-item-action">Настройка прав</a>
                    <a style="font-size:13px" id="permissions-list" class="list-group-item list-group-item-action mt-5">Вопрос-ответ</a>
                </div>
            </div>
            <div class="col">
                <companyemployee></companyemployee>
                <addemployeemodal></addemployeemodal>
                <editemployeemodal></editemployeemodal>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" type="application/javascript">
    $(function () {
        window.InterfaceManager = new InterfaceManagerClass;
        window.InterfaceManager.menuShow('main_menu');
        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe(function (me) {
            if (me.is_client == false) {
                window.location = '/';
            }

            $('#user_name').text('')
        });
        window.InterfaceManager.notificationsCount();
    });
</script>

@stop
