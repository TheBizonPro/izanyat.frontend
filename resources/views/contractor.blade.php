@extends('layouts.master')

@section('title')
	Исполнитель
@stop

@section('styles')
<style type="text/css">
div.info {
	font-size: 8pt;
}
div.info div{
	white-space: nowrap;
}

.dts_label {
	display: none;
}
.input-letter-spacing {
	letter-spacing: 2pt;
	font-family: monospace;
}
</style>
@stop

@section('scripts')
@stop

@section('content')
<div class="page-wrapper">
	<div class="container-xl">
		<!-- Page title -->

		<div class="page-header d-print-none mt-4">
			<div class="row align-items-center">
				<div class="col">

					<div class="p-3 border rounded bg-white">

						<!-- Page pre-title -->
						<h2 class="page-title">
							Профиль исполнителя
						</h2>

						<div class="row mt-2">
							<div class="col-6">
								<div class="form-group mt-2">
									<label class="text-muted">Имя</label>
									<div id="profile_name" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-3">
									<label class="text-muted">Основная категорий работ</label>
									<div id="profile_job_category_name" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-3">
									<label class="text-muted">Зарегистрирован как плательщик НПД</label>
									<div id="profile_taxpayer_registred_as_npd" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-3">
									<label class="text-muted">ИНН</label>
									<div id="profile_inn" class="font-weight-bold"></div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group mt-3">
									<label class="text-muted">Телефон</label>
									<div id="profile_phone" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-3">
									<label class="text-muted">Email</label>
									<div id="profile_email" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-3">
									<label class="text-muted">Рейтинг</label>
									<div id="profile_rating" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-3">
									<label class="text-muted">Обо мне</label>
									<div id="profile_about" class="font-weight-bold"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<script type="text/javascript">
	$(function(){
		window.InterfaceManager = new InterfaceManagerClass();
		window.InterfaceManager.menuShow('main_menu');
		window.InterfaceManager.checkAuth();
		window.InterfaceManager.loadMe();
		window.InterfaceManager.notificationsCount();
		//window.InterfaceManager.signUnrequestedCount();

		var ContractorManager = new ContractorManagerClass({{ $user_id }});

	});


	class ContractorManagerClass {

		constructor(user_id){
			let ths = this;
			ths.user_id = user_id;

			ths.openProfile(ths.user_id);
		}



		openProfile(user_id){
			let ths = this;

			var ax = axios.get('{{ config('app.api') }}/api/v2/contractor');
			ax.then(function (response) {
				if (response.data.user) {

					var user = response.data.user;

					$('#contractor_profile_modal').modal('show');
					$('#profile_name').html(user.name);
					$('#profile_inn').html(user.inn);
					$('#profile_email').html(user.email);
					$('#profile_phone').html(user.phone);
					$('#profile_birth_place').html(user.birth_place);
					$('#profile_birth_date').html(user.birth_date);

					$('#profile_taxpayer_registred_as_npd').html(user.taxpayer_registred_as_npd == 1 ? '<b class="fad fa-check-circle text-success" titile="Пользователь зарегистрирован как плательщик НПД"></b> Зарегистрирован' : '<b class="fad fa-question-circle text-muted" titile="Информации о статусе нет"></b> Не зарегистрирован');



					$('#profile_rating').html(user.rating);
					$('#profile_about').html(user.about);
					$('#profile_job_category_name').html(user.job_category_name);
				}
			})
			.catch(function (error) {
				console.log(error);
				bootbox.dialog({
					title: error.response.data.title ?? 'Ошибка',
					message: error.response.data.message ?? error.response.statusText,
					closeButton: false,
					buttons:{
						cancel:{
							label: 'Закрыть',
							className: 'btn-dark'
						}
					}
				});
			})
			.finally(function(){
			});

		}


	}


</script>

@stop
