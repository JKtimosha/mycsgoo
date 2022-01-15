<template>
    <div>
        <div class="kt-subheader kt-grid__item" id="kt_subheader">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">База предметов</h3>
            </div>
        </div>

        <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Список предметов
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a style="color: #fff; cursor: pointer" class="btn btn-success btn-elevate btn-icon-sm" v-on:click="update(570)">
                                    Обновить цены DotA 2
                                </a>
                                <a style="color: #fff; cursor: pointer" class="btn btn-success btn-elevate btn-icon-sm" v-on:click="update(730)">
                                    Обновить цены CS:GO
                                </a>
                                <a data-toggle="modal" href="#create" class="btn btn-success btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    Добавить
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="items">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>ClassID</th>
                            <th>Изображение</th>
                            <th>Цена</th>
                            <th>Цена ($)</th>
                            <th>Тип</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Редактировать предмет</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" onclick="return false;">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Название:</label>
                                <input type="text" v-model="editItem.market_hash_name" class="form-control" name="market_hash_name">
                            </div>
                            <div class="form-group">
                                <label>ClassID:</label>
                                <input type="text" v-model="editItem.classid" class="form-control" name="classid">
                            </div>
                            <div class="form-group">
                                <label>Изображение:</label>
                                <input type="text" v-model="editItem.image" class="form-control" name="image">
                            </div>
                            <div class="form-group">
                                <label>Цена:</label>
                                <input type="text" v-model="editItem.price" class="form-control" name="price">
                            </div>
                            <div class="form-group">
                                <label>Цена ($):</label>
                                <input type="text" v-model="editItem.price_en" class="form-control" name="price">
                            </div>
                            <div class="form-group">
                                <label>Тип:</label>
                                <input type="text" v-model="editItem.type" class="form-control" name="type">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-primary" v-on:click="editSave">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLongTitle">Создать предмет</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" onclick="return false;">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>ClassID предмета:</label>
                                <input type="text" v-model="createItem.classid" class="form-control" name="classid">
                            </div>
                            <div class="col-lg-4">
                                <label>Игра:</label>
                                <select v-model="createItem.appId" class="form-control" name="game_id">
                                    <option value="570" :selected="createItem.appId === 570">Dota 2</option>
                                    <option value="730" :selected="createItem.appId === 730">CS:GO</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-primary" v-on:click="create">Создать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                editItem: {
                    id: null,
                    market_hash_name: null,
                    classid: null,
                    image: null,
                    price: null,
                    price_en: null,
                    type: null
                },
                createItem: {
                    classid: null,
                    appId: null
                }
            }
        },
        methods: {
            async load() {
                const app = this;
                const table = $('#items');

                table.dataTable().fnDestroy();

                table.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/api/admin/items/load",
                        type: "POST"
                    },
                    columns: [
                        { data: "market_hash_name", searchable: true },
                        { data: "classid", searchable: true },
                        { data: "image", searchable: false,
                            render: function (data, type, row) {
                                return '<img width="60px" height="60px" src="https://steamcommunity-a.akamaihd.net/economy/image/'+row.image+'/360fx360f">'
                            }
                        },
                        { data: "price", searchable: true },
                        { data: "price_en", searchable: true },
                        { data: "type", searchable: true },
                        { data: null, searchable: false, orderable: false,
                            render: function (data, type, row) {
                                return '                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md editItem" data-id="'+row.id+'" title="Редактировать">\n' +
                                    '                                    <i class="la la-edit"></i>\n' +
                                    '                                </a>\n' +
                                    '                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delItem" data-id="'+row.id+'" title="Удалить">\n' +
                                    '                                    <i class="la la-trash"></i>\n' +
                                    '                                </a>';
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

                $(document).on('click', '.editItem', function() {
                    app.edit($(this).attr('data-id'));
                });

                $(document).on('click', '.delItem', function() {
                    app.del($(this).attr('data-id'));
                });
            },
            async edit(id) {
                const request = await axios.post('/api/admin/items/get', {id: id});
                const data = request.data;

                if (data.success) {
                    this.editItem = request.data.item;
                    $('#edit').modal('show');
                } else {
                    $.wnoty({
                        type: 'error',
                        message: 'Данный предмет не найден'
                    })
                }
            },
            async editSave() {
                const request = await axios.post('/api/admin/items/edit', {item: this.editItem});
                const data = request.data;

                this.load();

                $.wnoty({
                   type: data.type,
                   message: data.message
                });
            },
            async del(id) {
                const request = await axios.post('/api/admin/items/del', {id: id});
                const data = request.data;

                this.load();

                $.wnoty({
                    type: data.type,
                    message: data.message
                });
            },
            async create() {
                $.wnoty({
                    type: 'info',
                    message: 'Добавляем предмет...'
                });

                const request = await axios.post('/api/admin/items/create', {classid: this.createItem.classid, appId: this.createItem.appId});
                const data = request.data;

                if (data.type === 'success') {
                    this.load();

                    $('#create').modal('hide');

                    this.createItem = {
                    	classid: null,
                        appId: null
                	};
                }

                $.wnoty({
                    type: data.type,
                    message: data.message
                });
            },
            async update(appId) {
                $.wnoty({
                    type: 'info',
                    message: 'Обновляем цены, это может занять пару минут'
                });

                const request = await axios.post('/api/admin/items/updatePrices', {appId: appId});
                const data = request.data;

                $.wnoty({
                    type: data.type,
                    message: data.message
                });
            }
        },
        mounted() {
            this.load();
        }
    }
</script>