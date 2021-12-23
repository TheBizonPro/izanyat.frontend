<div id="add_contractor_modal" class="modal" tabindex="-1">
	<div class="modal-dialog  modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавление исполнителя в проект</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-3 form-group">
						<label class="form-label text-center">Категория</label>
						<select id="job_category_filter" class="form-select text-center">
							<option value="">Любая</option>
						</select>
					</div>
					<div class="col-3 form-group">
						<label class="form-label text-center">ИНН</label>
						<input id="inn_filter" class="form-control input-letter-spacing text-center" placeholder="____________" maxlength="12">
					</div>
					<div class="col-3 form-group">
						<label class="form-label text-center">Фамилия</label>
						<input id="lastname_filter" class="form-control text-center">
					</div>
					<div class="col-3 form-group">
						<label class="form-label">&nbsp;</label>
						<button id="filter_btn" class="btn btn-white w-100"><b class="fad fa-search me-2"></b> Искать</button>
					</div>
				</div>


			</div>

			<div class="modal-body p-0">
				<table id="all_contractors_datatable" class="table mb-0" style="width: 100%;"></table>
			</div>

			<div class="modal-body border-0">
				<div class="row">
					<div class="col-6 form-group">
						<label class="form-label">Назначаемая категория</label>
						<select id="job_category_id" class="form-select"></select>
					</div>
					<div class="col-6 form-group">
						<label class="form-label">&nbsp;</label>
						<button id="add_contractors_btn" class="btn btn-primary w-100">
							<div class="text">Добавить исполнителей в проект (<span id="contractors_count"></span>)</div>
							<div class="wait" hidden><b class="fad fa-spinner fa-pulse"></b> Пожалуйста, подождите</div>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
