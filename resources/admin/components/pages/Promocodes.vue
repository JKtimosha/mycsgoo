<template>
    <div>
        <div class="kt-subheader kt-grid__item" id="kt_subheader">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Промокоды</h3>
            </div>
        </div>

        <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Список промокодов
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a data-toggle="modal" href="#new" class="btn btn-success btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    Добавить
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="promocodes">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Процент</th>
                            <th>Использований</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="promocode in promocodes">
                            <td>{{ promocode.name }}</td>
                            <td>{{ promocode.percent }}</td>
                            <td>{{ promocode.use }}</td>
                            <td>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" v-on:click="edit(promocode.id)" title="Редактировать">
                                    <i class="la la-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" v-on:click="del(promocode.id)" title="Удалить">
                                    <i class="la la-trash"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Создать промокод</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" onclick="return false;">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Название:</label>
                                <input type="text" v-model="newPromocode.name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Процент:</label>
                                <input type="text" v-model="newPromocode.percent" class="form-control">
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

        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLongTitle">Изменить промокод</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" onclick="return false;">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Название:</label>
                                <input type="text" v-model="editPromocode.name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Процент:</label>
                                <input type="text" v-model="editPromocode.percent" class="form-control">
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
                promocodes: [],
                newPromocode: {
                    name: null,
                    percent: null
                },
                editPromocode: {
                    name: null,
                    percent: null
                }
            }
        },
        methods: {
            async load() {
                const request = await axios.post('/api/admin/promocodes/load');

                this.promocodes = request.data;

                const table = $('#promocodes');

                table.DataTable().destroy();
                this.$nextTick(function() {
                    table.DataTable();
                });
            },
            async create() {
                await axios.post('/api/admin/promocodes/create', {promocode: this.newPromocode});

                $.wnoty({
                    type: 'success',
                    message: 'Промокод создана'
                });

                $('#new').modal('hide');

                this.newPromocode = {
                    name: null,
                    percent: null
                };

                this.load();
            },
            async edit(id) {
                const request = await axios.post('/api/admin/promocodes/get', {id: id});
                const data = request.data;

                if (data.success) {
                    this.editPromocode = request.data.promocode;
                    $('#edit').modal('show');
                } else {
                    $.wnoty({
                        type: 'error',
                        message: 'Данный промокод не найден'
                    })
                }
            },
            async saveEdit() {
                const request = await axios.post('/api/admin/promocodes/edit', {promocode: this.editPromocode});
                const data = request.data;

                this.load();

                $.wnoty({
                    type: data.type,
                    message: data.message
                });
            },
            async del(id) {
                const request = await axios.post('/api/admin/promocodes/del', {id: id});
                const data = request.data;

                this.load();

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