"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

	var initTable1 = function() {
		var table = $('#ajax-users');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/usersAjax",
				type: "POST"
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "username", visible: false, searchable: true },
				{ data: "username", searchable: false,
					render: function (data, type, row) {
						return '<img src="'+ row.avatar +'" style="width:26px;border-radius:50%;margin-right:10px;vertical-align:middle;">' + data;
					}

				},
				{ data: "balance", searchable: false,
					render: function (data, type, row) {
						return data + ' руб';
					}

				},
				{ data: "bonus", searchable: false,
					render: function (data, type, row) {
						return data + ' руб';
					}

				},
				{ data: null, searchable: false, orderable: false,
					render: function (data, type, row) {
						return '<a href="https://vk.com/id'+ row.user_id +'" target="_blank">Перейти</a>';
					}

				},
				{ data: null, searchable: false, orderable: false,
					render: function (data, type, row) {
						if(row.is_admin) return '<span class="kt-font-bold kt-font-danger">Администратор</span>';
						if(row.is_moder) return '<span class="kt-font-bold kt-font-success">Модератор</span>';
						if(row.is_youtuber) return '<span class="kt-font-bold kt-font-primary">YouTube`r</span>';
						return 'Пользователь';
					}

				},
				{ data: "ip", searchable: true, orderable: false,
					render: function (data, type, row) {
						return data;
					}

				},
				{ data: "ban", searchable: false, orderable: true,
					render: function (data, type, row) {
						if(data) return '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill">Да</span>';
						return '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill">Нет</span>';
					}

				},
				{ data: null, searchable: false, orderable: false,
					render: function (data, type, row) {
						return '<a href="/admin/user/'+ row.id +'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Редактировать"><i class="la la-edit"></i></a>';
					}
				}
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		},

	};

}();

jQuery(document).ready(function() {
	KTDatatablesDataSourceAjaxServer.init();
});