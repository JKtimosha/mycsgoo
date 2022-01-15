<template>
    <div>
        <div class="kt-subheader kt-grid__item" id="kt_subheader">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Seo</h3>
            </div>
        </div>

        <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Настройки SEO
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="seo">
                        <thead>
                        <tr>
                            <th>Страница</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Keywords</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="seo in seos">
                            <td>{{ seo.name }}</td>
                            <td>{{ seo.title }}</td>
                            <td>{{ seo.description }}</td>
                            <td>{{ seo.keywords }}</td>
                            <td>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" v-on:click="edit(seo.id)"  title="Редактировать">
                                    <i class="la la-edit"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLongTitle">Изменить seo на странице {{ editSeo.name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" onclick="return false;">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" v-model="editSeo.title" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <input type="text" v-model="editSeo.description" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Keywords:</label>
                                <input type="text" v-model="editSeo.keywords" class="form-control">
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
                seos: [],
                editSeo: []
            }
        },
        methods: {
            async get() {
                const request = await axios.post('/api/admin/seo/load');

                this.seos = request.data;

                const table = $('#seo');

                table.DataTable().destroy();
                this.$nextTick(function() {
                    table.DataTable();
                });
            },
            async edit(id) {
                const request = await axios.post('/api/admin/seo/get', {id: id});
                const data = request.data;

                if (data.success) {
                    this.editSeo = request.data.seo;
                    $('#edit').modal('show');
                } else {
                    $.wnoty({
                        type: 'error',
                        message: 'Данное seo не найден'
                    })
                }
            },
            async saveEdit() {
                const request = await axios.post('/api/admin/seo/edit', {seo: this.editSeo});
                const data = request.data;

                this.get();

                $.wnoty({
                    type: data.type,
                    message: data.message
                });
            },
        },
        mounted() {
            this.get();
        }
    }
</script>