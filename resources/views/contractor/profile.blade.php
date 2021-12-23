@extends('layouts.master')

@section('title')
Профиль
@stop


@section('content')
<div id='contractor-app'>

</div>


<script type="application/javascript">
    $(function () {
        window.InterfaceManager = new InterfaceManagerClass();
        window.InterfaceManager.menuShow('main_menu');
        window.InterfaceManager.checkAuth();
        window.InterfaceManager.loadMe();
        window.InterfaceManager.notificationsCount();
        //window.InterfaceManager.signUnrequestedCount();

	});
</script>
@stop
