function GenerateDataTable(settings)
{
	$.fn.dataTableExt.sErrMode = 'none';

	/**
	 * Получаем настройки
	 */
	var table_id = settings["table_id"];
	var cover_block_id = settings["cover_block_id"];
	var ajax_url = settings["ajax_url"];
	var columns = settings["columns"];
	var headers = settings["headers"];

	if (settings["order"]) {
		var order = settings["order"];
	} else {
		var order = [[0, 'desc']];
	}

	var default_length = settings["default_length"];
	var createdRow = settings["createdRow"];
	var rowCallback = settings["rowCallback"];
	var drawCallback = settings["drawCallback"];
	var button_links = settings["button_links"];
	var copy_btn = settings["copy_btn"];
	var excel_btn = settings["excel_btn"];
	var colvis_btn = settings["colvis_btn"];
	var action_with_select_btn = settings["action_with_select_btn"];
	var keys = settings["keys"];
	var select = settings["select"];
	var show_pagination = settings["show_pagination"];
	var show_search = settings["show_search"];
	var show_change_length = settings["show_change_length"];
	

	if (settings["card_header"]) {
		var card_header = settings["card_header"];
	} else {
		var card_header = null;
	}

	if (settings["ajax_data"]) {
		var ajax_data = settings["ajax_data"];
	} else {
		var ajax_data = null;
	}

	if(!settings["default_length"]){
		var iDisplayLength = 10;
	} else {
		var iDisplayLength = settings["default_length"];
	}

	var colReorder = settings["colReorder"];
	var localization = settings["localization"];

	var buttons = [];
	if (copy_btn==true) {
		buttons.push("copy");
	}
	if (excel_btn==true) {
		buttons.push("excel");
	}
	if (colvis_btn==true) {
		buttons.push("colvis");
	}

	/**
	 * Сначала создаем карточку с окружением для таблицы
	 */
 	var t = '';
	t += '<div class="card datatables-card">';
	t += '	<div class="card-header p-3" id="' + table_id + '_header">';
	t += '		<div class="row w-100">';
	if (card_header != null) {
		t += '		<div class="col" >';
		t += card_header;
		t += '		</div>';
	}
	t += '			<div class="col-md-7" id="' + table_id + '_buttons">';
	t += '			</div>';
	if (show_search == true) {
		t += '		<div class="col-md-5">';
		t += '			<div class="d-lg-none d-md-none" style="height:10px;"></div>';
		t += '			<input type="text" id="' + table_id + '_search" class="form-control" placeholder="' + localization["search_placeholder"] + '">';
		t += '		</div>';
	}
	t += '		</div>';
	t += '	</div>';
	t += '	<div class="card-body p-0">';
	t += '		<table style="" id="' + table_id + '" class="table table-striped no-footer table-hover" cellspacing="0">';
	t += '		</table>';
	t += '	</div>';
	t += '	<div class="card-footer" id="' + table_id + '_footer">';
	t += '		<div id="' + table_id + '_action_with_select" hidden class="action_with_select_cover text-center">';
	t += '			<button id="' + table_id + '_action_with_select_button" class="btn btn-outline-dark">' + localization.action_with_select + ' (<span id="' + table_id + '_action_with_select_count"></span>)</button>';
	t += '		</div>';
	t += '		<div id="' + table_id + '_info" class="table_info text-center"></div>';
	t += '		<div id="' + table_id + '_pagination" class="text-center"></div>';
	t += '	</div>';
	t += '</div>';
	$("#" + cover_block_id).html(t);


	/**
	 * Пользовательские кнопки-ссылки
	 * Если они есть - вставляем их в страницу
	 */
	if(button_links) {
		$.each(button_links, function(i, item){
			if (item.id != null) {
				id = item.id;
			} else {
				id = "_";
			}
			var btn = '<a id="' + id + '" href="' + item.href + '" class="btn btn-outline-dark block-on-sm">' + item.text + '</a> ';
			$('#' + table_id + '_buttons').append(btn);
		});
	}


	/**
	 * Настройки для Datatable
	 */
	var datatable_options = {};
	datatable_options["processing"] = true;
	datatable_options["serverSide"] = true;
	datatable_options["stateSave"] = true;
	datatable_options["keys"] = keys;
	datatable_options["select"] = select;
	datatable_options["colReorder"] = colReorder;
	datatable_options["responsive"] = false;
	datatable_options["deferRender"] = true;
	datatable_options["oLanguage"] = localization;
	datatable_options["ajax"] = {url: ajax_url, type: "POST", headers:headers, data:ajax_data};
	datatable_options["columns"] = columns;
	datatable_options["buttons"] = buttons;
	datatable_options["iDisplayLength"] = iDisplayLength;
	datatable_options["dom"] = '<"hidden-btn-panel"B><"no-padding overflow-x-scroll"rt><"text-center"i><"text-center"p><"text-center"l>';

	/**
	 * Коллбек после отрисовки таблицы
	 */
	datatable_options["drawCallback"] = function( settings ) {
		/**
		 * Выполняем пользовательский коллбек
		 */
		if(drawCallback) {
			drawCallback(settings);
		}
		/**
		 * Перемещаем инфо о страницах место
		 */
		var info = $("#" + table_id).parents(".dataTables_wrapper").eq(0).find(".dataTables_info");
		$(info).detach().appendTo("#" + table_id + "_info");
		$("#" + table_id + "_pagination").html("");

		/**
		 * Перемещаем пагинацию в нужное место
		 * или удаляем (исходя из настроек)
		 */
		if (show_pagination == true) {
			var pagination = $("#" + table_id).parents(".dataTables_wrapper").eq(0).find(".pagination").eq(0);
			$(pagination).detach().appendTo("#" + table_id + "_pagination");
			$(pagination).addClass("justify-content-center");
		} else {
			$(pagination).remove();
		}

		/**
		 * Обновим кнопку "действия с выбранными"
		 */
		if (action_with_select_btn == true) {
			var selected = dt.rows( { selected: true } ).data()
			$('#' + table_id + '_action_with_select_count').html(selected.length)
			if(selected.length == 0) {
				$('#' + table_id + '_action_with_select').prop("hidden", true);
			}
		}
	}

	/**
	 * Коллбек отрисовки строки
	 */
	datatable_options["rowCallback"] = function(row, data, index)
	{
		if (rowCallback) {
			rowCallback(row, data, index)
		}

		/**
		 * Если поиск
		 */
	
		var searched_text = dt.search();
		if (searched_text != "") {

			/**
			 * Получим отсортированный массив с видимыми колонками
			 */
			var temp_vis_columns = [];
			$.each(columns, function(column_n, item){
				var transpose = dt.colReorder.transpose(column_n);
				var column_t = (transpose === -1) ? column_n : transpose;
				if(dt.column(column_t).visible() == true) {
					temp_vis_columns[transpose] = item;
				}
			});

			/**
			 * Возьмем только значения
			 */
			var visible_columns = [];
			for (key in temp_vis_columns) {
				visible_columns[visible_columns.length] = temp_vis_columns[key];
			}

/*			$.each(visible_columns, function(n, item){
				var is_searchable = item.searchable;
				var original_text = $("td:eq(" + n + ")", row).html();
				var finded_text = searchAndMark(searched_text, original_text);
				if (is_searchable == true) {
					$("td:eq('" + n + "')", row).html(finded_text);
				}
			});*/
		}
	}


	/**
	 * Коллбек создание строки
	 */
	datatable_options["createdRow"] = function(row, data, index) {
		if (createdRow) {
			createdRow(row, data, index)
		}
	}

	var dt = $('#' + table_id).DataTable(datatable_options);

	if (show_search == true) {

		/**
		 * Если что-то искали - вставляем в поле поиска
		 */
		var searched_text = dt.search();
		if (searched_text != null) {
			$('#' + table_id + '_search').val(searched_text)
		}

		/**
		 * Байндим кейап в поиске
		 */
		$('#' + table_id + '_search').bind("keyup",function(e) {
			var search_length = $(this).val().length
			if(e.keyCode == 13) {
				dt.search($(this).val()).draw();
			}
		});

		$('#' + table_id + '_search').bind('blur', function(){
			dt.search($(this).val()).draw();
		});
	}

	/**
	 * Работа с кнопкой "Действия с выбранными"
	 */
	if (action_with_select_btn == true) {
		$('#' + table_id + '_action_with_select_button').bind('click', function(){
			if (action_with_select) {
				var selected = dt.rows( { selected: true } ).data()
				var sel = [];
				$.each(selected, function(i, item){
					sel.push(item.uuid);
				});
				action_with_select(sel);
			}
		});

		/**
		 * При выделении новой строки
		 */
		dt.on( 'select', function ( e, dt, type, indexes ) {
			var selected = dt.rows( { selected: true } ).data()
			$('#' + table_id + '_action_with_select_count').html(selected.length)
			$('#' + table_id + '_action_with_select').prop("hidden", false);
		});

		/**
		 * При РАЗвыделении строки
		 */
		dt.on( 'deselect', function ( e, dt, type, indexes ) {
			var selected = dt.rows( { selected: true } ).data();
			$('#' + table_id + '_action_with_select_count').html(selected.length);
			if(selected.length == 0) {
				$('#' + table_id + '_action_with_select').prop("hidden", true);
			}
		});
	}


	if (colvis_btn == true) {
		/**
		 * Генерируем список колонок
		 */
		var colvis_dropdown = '';
		colvis_dropdown += '<div class="btn-group block-on-sm">';
		colvis_dropdown += '<button type="button" class="btn  btn-outline-dark dropdown-toggle block-on-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
		colvis_dropdown += '<b class="fad fa-table mr-2"></b>' + localization["cols"] + '';
		colvis_dropdown += '</button>';
		colvis_dropdown += '<div class="dropdown-menu dropdown-200" id="' + table_id + '_colvis_dropdown">';
		colvis_dropdown += '<a class="dropdown-item" id="' + table_id + '_colvis_reset" href="#">' + localization["reset_colvis"] + '</a>';
		colvis_dropdown += '<div class="dropdown-divider"></div>';

		$.each(columns, function(column_n, item){
			transpose = dt.colReorder.transpose(column_n);
			column_t = (transpose === -1) ? column_n : transpose
			var is_visible = dt.column(column_t).visible();
			var visible_class = (is_visible == true) ? 'active' : '';
			var is_visible_attr = (is_visible == true) ? '1' : '0';
			colvis_dropdown += '<div class="dropdown-item column-visible-switcher ' + visible_class + '" data-column="' + column_n + '" data-isvisible="' + is_visible_attr + '">' + item.title + '</div>';
		});

		colvis_dropdown += '</div>';
		colvis_dropdown += '</div>';
		$('#' + table_id + '_buttons').append(colvis_dropdown);
		
		/**
		 * Смена настроек отображения колонки
		 */	
		$('.column-visible-switcher', '#' + table_id + '_buttons').bind('click', function(e){
			e.preventDefault();
			var column_n = Math.floor($(this).attr("data-column"));
			var is_visible = $(this).attr("data-isvisible");
			transpose = dt.colReorder.transpose(column_n);
			column_t = (transpose === -1) ? column_n : transpose;
			if (is_visible == 1) {
				$(this).attr("data-isvisible", "0");
				$(this).removeClass("active");
				dt.column(column_t).visible(false);
			} else {
				$(this).attr("data-isvisible", "1");
				$(this).addClass("active");
				dt.column(column_t).visible(true);
			}
		});
		
		/**
		 * Сброс отображения колонок
		 */
		$('#' + table_id + '_colvis_reset').bind('click', function(i, item){
			if (colReorder == true) {
				dt.colReorder.reset();
			}
			$.each(columns, function(column_n, item){
				var visible = (item.visible === true) ? true : false;
				transpose = dt.colReorder.transpose(column_n);
				column_t = transpose === -1 ? column_n : transpose
				dt.column(column_t).visible(visible);
				var colvis_item = $('.column-visible-switcher[data-column=' + column_n + ']', '#' + table_id + '_buttons');
				if (visible == true) {
					$(colvis_item).attr("data-isvisible", "1").addClass("active");
				} else {
					$(colvis_item).attr("data-isvisible", "0").removeClass("active");
				}
			});
			toastr.info(localization.reset_columns);
		});
	}

	/**
	 * Меню ЕЩЕ
	 */
	if (excel_btn == true || excel_btn==true || show_change_length == true) {
		var more_dropdown = ' ';
		more_dropdown += '<div class="btn-group block-on-sm">';
		more_dropdown += '<button type="button" class="btn btn-outline-dark dropdown-toggle block-on-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
		more_dropdown += "<span class='d-sm-none d-md-none d-lg-inline-block'>" + localization["more"] + "</span><span class='d-none d-sm-inline-block d-md-inline-block d-lg-none'><i class='fa fa-ellipsis-h'></i></span>";
		more_dropdown += '</button>';
		more_dropdown += '<div class="dropdown-menu" id="' + table_id + '_more_dropdown">';

		if (copy_btn == true) {
			more_dropdown += '<a class="dropdown-item" id="' + table_id + '_copy" href="#"><b class="fad fa-copy mr-2"></b> ' + localization["copy"] + '</a>';
		}

		if (excel_btn == true) {
			more_dropdown += '<a class="dropdown-item" id="' + table_id + '_excel" href="#"><b class="fad fa-file-excel mr-2"></b> ' + localization["excel"] + '</a>';
		}

		if ((excel_btn == true || excel_btn==true) && show_change_length == true) {
			more_dropdown += '<div class="dropdown-divider"></div>';
		}

		if (show_change_length == true) {
			more_dropdown += '<a class="dropdown-item" id="' + table_id + '_show_10" href="#">' + localization["show_10_rows"] + '</a>';
			more_dropdown += '<a class="dropdown-item" id="' + table_id + '_show_20" href="#">' + localization["show_20_rows"] + '</a>';
			more_dropdown += '<a class="dropdown-item" id="' + table_id + '_show_50" href="#">' + localization["show_40_rows"] + '</a>';
			more_dropdown += '<a class="dropdown-item" id="' + table_id + '_show_100" href="#">' + localization["show_100_rows"] + '</a>';
			more_dropdown += '<a class="dropdown-item" id="' + table_id + '_show_all" href="#">' + localization["show_all_rows"] + '</a>';
		}

		more_dropdown += '</div>'
		more_dropdown += '</div>';

		$('#' + table_id + '_buttons').append(more_dropdown);

		if (copy_btn == true) {
			$('#' + table_id + '_copy').bind("click", function(){
				console.log("click btn copy")
				$(".buttons-copy", "#" + table_id + "_wrapper").trigger("click");
			});
		}

		if (excel_btn == true) {
			$('#' + table_id + '_excel').bind("click", function(){
				console.log("click btn excel")
				$(".buttons-excel", "#" + table_id + "_wrapper").trigger("click");
			});
		}

		if (show_change_length == true) {

			$('#' + table_id + '_show_10').bind("click", function(){
				dt.page.len(10).draw();
				toastr.info(localization.rows_shown);
			});

			$('#' + table_id + '_show_20').bind("click", function(){
				dt.page.len(20).draw();
				toastr.info(localization.rows_shown);
			});

			$('#' + table_id + '_show_50').bind("click", function(){
				dt.page.len(50).draw();
				toastr.info(localization.rows_shown);
			});

			$('#' + table_id + '_show_100').bind("click", function(){
				dt.page.len(100).draw();
				toastr.info(localization.rows_shown);
			});

			$('#' + table_id + '_show_all').bind("click", function(){
				dt.page.len(-1).draw();
				toastr.info(localization.rows_shown);
			});
		}
	}

return dt;
}