@extends('layouts.empty')

@section('title')
	Выход из системы
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
			<b class="fad fa-spinner fa-pulse me-2"></b> Пожалуйста, подождите. Выход из системы.
		</div>
	</div>
</section>

<script type="text/javascript">
	$(function(){
		let LogoutManager = new LogoutManagerClass;
	});

	class LogoutManagerClass {

		constructor(){
			let ths = this;
			ths.animateLogo();
			ths.logout();
		}

		logout(){
			let ths = this;

            window.localStorage.removeItem('token');
            window.location.href = '/login';
		}


		animateLogo(){
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

			$.each(professions, function(i, item){
				setTimeout(function(){
					$('#animated_logo').find('.profession').text(item);
				}, timeout);
				timeout = timeout + 120;
			});

		}


	}
</script>
@stop
