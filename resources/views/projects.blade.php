@extends('layouts.master')

@section('title')
	Мои проекты
@stop

@section('styles')
@stop


@section('scripts')
@stop

@section('content')
<div class="page-wrapper">
</div>

<script type="text/javascript">
	$(function(){
		window.InterfaceManager = new InterfaceManagerClass;
		window.InterfaceManager.menuShow('main_menu');
		window.InterfaceManager.checkAuth();
		window.InterfaceManager.loadMe(function(me){
			if (me.is_selfemployed == 1) {
				window.location = '/contractor/tasks/new';
			} else if (me.is_client == 1) {
				window.location = '/client/projects';
			} else {
				window.location = '/';
			}
		});
		window.InterfaceManager.notificationsCount();
		//window.InterfaceManager.signUnrequestedCount();

	});
</script>

@stop
