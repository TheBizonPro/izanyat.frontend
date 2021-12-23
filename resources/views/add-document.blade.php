<div id="add_document_modal" class="modal" tabindex="-1">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Добавление нового документа</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div id="select_contractor_block" hidden>
                <div class="modal-body">
                    <div class="row">
                        <div id="select_project_block"  class="col-3 me-4">
                            <div class="alert">Выберите проект</div>
                            <div class="row mt-2 mb-2">
                                <div class="col form-group">
                                    <label class="form-label">Название</label>
                                    <input id="project_name_filter" class="form-control">
                                </div>
                            </div>

                            <table id="projects_datatable" class="table table-striped table-hover mb-0"
                                style="width: 100%;">
                            </table>
                        </div>
                        <div class='col'>
                            <div class="alert">Выберите исполнителя, для которого предназначен документ</div>
                            <div class="row mt-2">
                                <div class="col-3 form-group">
                                    <label class="form-label">Фамилия</label>
                                    <input id="lastname_filter" class="form-control">
                                </div>
                                <div class="col-3 form-group">
                                    <label class="form-label">ИНН</label>
                                    <input id="inn_filter" class="form-control">
                                </div>
                                <div class="col-3 form-group">
                                    <label class="form-label">Категория</label>
                                    <select id="job_category_filter" class="form-select"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <button id="filter_btn" class="btn btn-white w-100"><b class="fad fa-search me-2"></b>
                                        Искать</button>
                                </div>
                            </div>
                            <div class="modal-body mt-2 p-0 pb-3">
                                <table id="project_contractors_datatable" class="table table-striped table-hover mb-0"
                                    style="width: 100%;"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="upload_document_block" class="modal-body text-center" hidden>
                <div class="alert">Выберите документ в формате PDF для загрузки.</div>
                <input id="upload_document_file" type="file" accept="application/pdf" hidden>
                <button id="upload_document_btn" class="btn btn-lg btn-success btn-pill mt-3">
                    <div class="text">Загрузить файл</div>
                    <div class="wait" hidden><b class="fad fa-spinner fa-pulse me-2"></b>Идет загрузка</div>
                </button>

                <div class="mt-2">
                    <button id="back_to_select_contractor_btn" class="btn btn btn-white btn-pill mt-3">
                        <b class="fad fa-arrow-left me-2"></b>Вернуться назад
                    </button>
                </div>
            </div>

            <div id="preview_document_block" class="" hidden>
                <div class="modal-body text-center p-0">
                    <iframe id="document_preview_iframe" class="mt-2" src="" width="100%" height="300"></iframe>
                </div>
                <div class="modal-body mt-3 text-center">

                    <div class="row">
                        <div class="col-4 form-group">
                            <label class="form-label">Тип документа</label>
                            <select id="new_document_type" class="form-select">
                                <option value="">Выберите тип</option>
                                <option value="contract">Договор</option>
                                <option value="other">Другое</option>
                            </select>
                        </div>
                        <div class="col-4 form-group">
                            <label class="form-label">Номер документа</label>
                            <input id="new_document_number" type="text" class="form-control text-center" placeholder="">
                        </div>
                        <div class="col-4 form-group">
                            <label class="form-label">Дата документа</label>
                            <input id="new_document_date" type="text" class="form-control text-center"
                                placeholder="дд.мм.гггг">
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button id="back_to_upload_document_btn" class="btn btn btn-white btn-pill me-2">
                            <b class="fad fa-arrow-left me-2"></b>Вернуться назад
                        </button>
                        <button id="save_document_btn" class="btn btn btn-primary btn-pill me-2">
                            <div class="text">Сохранить документ!</div>
                            <div class="wait" hidden><b class="fad fa-spinner fa-pulse me-2"></b>Сохранение...</div>
                        </button>

                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
