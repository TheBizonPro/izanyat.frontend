@extends('layouts.master')

@section('title')
Мой профиль
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
                    <a href="/my-company" style="font-size:13px" class="list-group-item list-group-item-action ">Данные
                        организации</a>
                    <a href="/employees" id="employees-list" hidden style="font-size:13px" class="list-group-item list-group-item-action">Сотрудники</a>
                    <a style="font-size:13px" id="permissions-list" hidden
                        class="list-group-item list-group-item-action list-group-item-dark">Настройка прав</a>
                    <a style="font-size:13px" class="list-group-item list-group-item-action mt-5">Вопрос-ответ</a>
                </div>
            </div>
            <div class="col">
                <companypermissiontable></companypermissiontable>
            </div>
        </div>

    </div>
</div>
<addrolemodal></addrolemodal>
<script type="text/javascript" type="application/javascript">
    $(function(){
		window.InterfaceManager = new InterfaceManagerClass;
		window.InterfaceManager.menuShow('main_menu');
		window.InterfaceManager.checkAuth();
		window.InterfaceManager.loadMe(function(me){
			if (me.is_client == false) {
				window.location = '/';
			}

			$('#user_name').text('')
		});
		window.InterfaceManager.notificationsCount();
		//window.InterfaceManager.signUnrequestedCount();

	});
</script>

@stop
