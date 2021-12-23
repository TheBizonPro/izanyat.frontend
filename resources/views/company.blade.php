@extends('layouts.master')

@section('title')
	Профиль компании
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
							Профиль компании
						</h2>

						<div class="row mt-2">
							<div class="col-6">

								<div class="form-group mt-2">
									<label class="text-muted">Название</label>
									<div id="profile_name" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">Полное название</label>
									<div id="profile_full_name" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">ИНН</label>
									<div id="profile_inn" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">ОГРН</label>
									<div id="profile_ogrn" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">ОКПО</label>
									<div id="profile_okpo" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">О компании</label>
									<div id="profile_about" class="font-weight-bold"></div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group mt-2">
									<label class="text-muted">Email</label>
									<div id="profile_email" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">Телефон</label>
									<div>+<span id="profile_phone" class="font-weight-bold"></span></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">Регион</label>
									<div id="profile_address_region" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">Город</label>
									<div id="profile_address_city" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">Юридический адрес</label>
									<div id="profile_legal_address" class="font-weight-bold"></div>
								</div>
								<div class="form-group mt-2">
									<label class="text-muted">Фактический адрес</label>
									<div id="profile_fact_address" class="font-weight-bold"></div>
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
		window.InterfaceManager.signUnrequestedCount();

		var CompanyManager = new CompanyManagerClass({{ $company_id }});

	});


	class CompanyManagerClass {

		constructor(company_id){
			let ths = this;
			ths.company_id = company_id;

			ths.openProfile(ths.company_id);
		}



		openProfile(company_id){
			let ths = this;

			var ax = axios.get('{{ config('app.api') }}/api/company/' + company_id);
			ax.then(function (response) {
				if (response.data.company) {

					var company = response.data.company;

					$('#company_profile_modal').modal('show');
					$('#profile_name').html(company.name);
					$('#profile_full_name').html(company.full_name);
					$('#profile_address_region').html(company.address_region);
					$('#profile_address_city').html(company.address_city);
					$('#profile_legal_address').html(company.legal_address);
					$('#profile_fact_address').html(company.fact_address);
					$('#profile_inn').html(company.inn);
					$('#profile_ogrn').html(company.ogrn);
					$('#profile_okpo').html(company.okpo);
					$('#profile_email').html(company.email);
					$('#profile_about').html(company.about);
					$('#profile_phone').html(company.phone);
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
