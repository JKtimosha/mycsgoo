<template>
    <div>
        <div class="kt-subheader kt-grid__item" id="kt_subheader">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Предметы в кейсе "{{ box.name }}"</h3>
            </div>
        </div>

        <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Предметы
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
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
                            <th>Картинка</th>
                            <th>Цена (р.)</th>
                            <th>Шансы</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in items">
                            <td>{{ item.item.market_hash_name }}</td>
                            <td><img
                                    :src="'https://steamcommunity-a.akamaihd.net/economy/image/'+item.item.image+'/360fx360f'"
                                    width="64" height="64"></td>
                            <td>{{ item.item.price }}</td>
                            <td>{{ item.chance }}</td>
                            <td>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" v-on:click="edit(item.id)"
                                   title="Редактировать">
                                    <i class="la la-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" v-on:click="del(item.id)"
                                   title="Удалить">
                                    <i class="la la-trash"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="newLabel"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLongTitle">Добавить предмет</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" onclick="return false;">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Предмет:</label>
                                <select id="selectItem"></select>
                            </div>
                            <div class="form-group">
                                <label>Шанс:</label>
                                <input type="text" v-model="createItem.chance" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-primary" v-on:click="create">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLongTitle">Изменить предмет</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" onclick="return false;">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Предмет:</label>
                                <select id="editItem">

                                </select>
                            </div>
                            <div class="form-group">
                                <label>Шанс:</label>
                                <input type="text" v-model="editItem.chance" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-primary" v-on:click="saveEdit">Сохранить</button>
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
                box: {},
                items: {},
                createItem: {
                    chance: null
                },
                editItem: {
                    id: null,
                    chance: null
                }
            }
        },
        methods: {
            async get() {
                const request = await axios.post('/api/admin/cases/items/load', {id: this.$route.params.id});
                const data = request.data;

                if (data.success) {
                    this.box = data.box;
                    this.items = data.items;

                    const table = $('#items');

                    table.DataTable().destroy();
                    this.$nextTick(function () {
                        table.DataTable();
                    });
                } else {
                    this.$router.go(-1);
                }
            },
            async create() {
                $.wnoty({
                    type: 'info',
                    message: 'Добавляем предмет...'
                });

                const request = await axios.post('/api/admin/cases/items/create', {
                    case_id: this.box.id,
                    item_id: $('#selectItem option').last().val(),
                    chance: this.createItem.chance
                });
                const data = request.data;

                if (data.type === 'success') {
                    this.get();
                   	$('#selectItem').html('');
                    this.createItem = {
                        id: null,
                        chance: null
                    };
                    $('#create').modal('hide');
                }

                $.wnoty({
                    type: data.type,
                    message: data.message
                });
            },
            async edit(id) {
                const request = await axios.post('/api/admin/cases/items/get', {id: id});
                const data = request.data;

                if (data.success) {
                    this.editItem = request.data.cases;

                    const item = {
                        id: request.data.item.id,
                        text: request.data.item.text
                    };

                    const newOption = new Option(item.text, item.id, true, true);
                    $('#editItem').prepend(newOption).trigger('change');

                    $('#edit').modal('show');
                } else {
                    $.wnoty({
                        type: 'error',
                        message: 'Данный предмет не найден'
                    })
                }
            },
            async saveEdit() {
                const request = await axios.post('/api/admin/cases/items/edit', {id: this.editItem.id, chance: this.editItem.chance, item_id: $('#editItem').first().val()});
                const data = request.data;

                this.get();

                $.wnoty({
                    type: data.type,
                    message: data.message
                });
            },
            async del(id) {
                const request = await axios.post('/api/admin/cases/items/del', {id: id});
                const data = request.data;

                this.get();

                $.wnoty({
                    type: data.type,
                    message: data.message
                });
            }
        },
        mounted() {
            this.get();
            const vm = this;
            $('#selectItem').select2({
                theme: 'bootstrap4',
                dropdownParent: $("#create"),
                ajax: {
                    delay: 250,
                    url: '/api/admin/cases/items/all',
                    type: 'POST',
                    data: function (params) {
                        const query = {
                            search: params.term,
                            page: params.page || 1,
                            caseId: vm.box.id
                        };

                        return query;
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results,
                            pagination: {
                                more: data.more
                            }
                        };
                    }
                }
            });
            $('#editItem').select2({
                theme: 'bootstrap4',
                dropdownParent: $("#edit"),
                ajax: {
                    delay: 250,
                    url: '/api/admin/cases/items/all',
                    type: 'POST',
                    data: function (params) {
                        const query = {
                            search: params.term,
                            page: params.page || 1
                        };

                        return query;
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results,
                            pagination: {
                                more: data.more
                            }
                        };
                    }
                }
            });
        }
    }
</script>