<template>
    <div>
        <div class="kt-subheader kt-grid__item" id="kt_subheader">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Кейсы</h3>
            </div>
        </div>

        <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Кейсы
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
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="cases">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Картинка</th>
                            <th>Цена (р.)</th>
                            <th>Цена ($)</th>
                            <th>Тип</th>
                            <th>Игра</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="box in cases">
                                <td>{{ box.name }}</td>
                                <td>{{ box.category.name }}</td>
                                <td><img :src="box.image" width="64" height="64"></td>
                                <td>{{ box.price }}</td>
                                <td>{{ box.price_en }}</td>
                                <td v-if="box.type === 'default'">Обычный</td>
                                <td v-if="box.type === 'free'">Бесплатный</td>
                                <td v-if="box.type === 'limited'">Лимитированный ({{ box.max_open - box.open }})</td>
                                <td v-if="box.appId === 730">CS:GO</td>
                                <td v-if="box.appId === 570">Dota 2</td>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" v-on:click="edit(box.id)" title="Редактировать">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <router-link tag="a" :to="{name: 'cases.items', params: {id: box.id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Предметы">
                                        <i class="la la-briefcase"></i>
                                    </router-link>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" v-on:click="del(box.id)" title="Удалить">
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
                        <h5 class="modal-title" id="exampleModalLongTitle">Создать кейс</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" onclick="return false;">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Название:</label>
                                <input type="text" v-model="newCase.name" placeholder="Армейское" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Название (en):</label>
                                <input type="text" v-model="newCase.name_en" placeholder="Milspec" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Название для ссылки:</label>
                                <input type="text" v-model="newCase.name_url" placeholder="milspec" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Категория:</label>
                                <select v-model="newCase.category_id" class="form-control">
                                    <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Изображение:</label>
                                <input type="text" v-model="newCase.image" placeholder="/cases/milspec.png" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Старая цена:</label>
                                <input type="number" v-model="newCase.old_price" placeholder="39" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Старая цена ($):</label>
                                <input type="number" v-model="newCase.old_price_en" placeholder="0.39" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Цена:</label>
                                <input type="number" v-model="newCase.price" placeholder="29" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Цена ($):</label>
                                <input type="number" v-model="newCase.price_en" placeholder="0.29" class="form-control">
                            </div>
                            <div class="form-group" v-if="gameId === '0'">
                                <label>Игра</label>
                                <select v-model="newCase.appId" class="form-control">
                                    <option value="570">Dota 2</option>
                                    <option value="730">CS:GO</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Тип кейса</label>
                                <select v-model="newCase.type" class="form-control">
                                    <option value="default">Обычный</option>
                                    <option value="free">Бесплатный</option>
                                    <option value="limited">Лимитированный</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Кол-во открытий</label>
                                <input type="number" v-model="newCase.max_open" :disabled="newCase.type !== 'limited'" class="form-control">
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
                        <h5 class="modal-title" id="editModalLongTitle">Редактировать кейс</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" onclick="return false;">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Название:</label>
                                <input type="text" v-model="editCase.name" placeholder="Армейское" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Название (en):</label>
                                <input type="text" v-model="editCase.name_en" placeholder="Milspec" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Название для ссылки:</label>
                                <input type="text" v-model="editCase.name_url" placeholder="milspec" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Категория:</label>
                                <select v-model="editCase.category_id" class="form-control">
                                    <option v-for="category in categories" :selected="category.id === editCase.category_id" :value="category.id">{{ category.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Изображение:</label>
                                <input type="text" v-model="editCase.image" placeholder="/cases/milspec.png" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Старая цена:</label>
                                <input type="number" v-model="editCase.old_price" placeholder="39" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Старая цена ($):</label>
                                <input type="number" v-model="editCase.old_price_en" placeholder="0.43" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Цена:</label>
                                <input type="number" v-model="editCase.price" placeholder="29" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Цена ($):</label>
                                <input type="number" v-model="editCase.price_en" placeholder="0.28" class="form-control">
                            </div>
                            <div class="form-group" v-if="gameId === '0'">
                                <label>Игра</label>
                                <select v-model="editCase.appId" class="form-control">
                                    <option :selected="editCase.appId === 570" value="570">Dota 2</option>
                                    <option :selected="editCase.appId === 730" value="730">CS:GO</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Тип кейса</label>
                                <select v-model="editCase.type" class="form-control">
                                    <option :selected="editCase.type === 'default'" value="default">Обычный</option>
                                    <option :selected="editCase.type === 'free'" value="free">Бесплатный</option>
                                    <option :selected="editCase.type === 'limited'" value="limited">Лимитированный</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Кол-во открытий</label>
                                <input type="number" v-model="editCase.max_open" :disabled="editCase.type !== 'limited'" class="form-control">
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
                cases: [],
                categories: [],
                gameId: null,
                editCase: {
                    name: null,
                    name_en: null,
                    name_url: null,
                    category_id: null,
                    image: null,
                    old_price: null,
                    old_price_en: null,
                    price: null,
                    price_en: null,
                    type: null,
                    max_open: null,
                    appId: null
                },
                newCase: {
                    name: null,
                    name_en: null,
                    name_url: null,
                    category_id: null,
                    image: null,
                    old_price_en: null,
                    price: null,
                    price_en: null,
                    type: null,
                    max_open: null,
                    appId: null
                }
            }
        },
        methods: {
            async load() {
                const request = await axios.post('/api/admin/cases/load');

                this.cases = request.data.cases;
                this.categories = request.data.categories;
                this.gameId = request.data.gameId;

                const table = $('#cases');

                table.DataTable().destroy();
                this.$nextTick(function() {
                    table.DataTable();
                });
            },
            async create() {
                await axios.post('/api/admin/cases/create', {cases: this.newCase});

                $.wnoty({
                    type: 'success',
                    message: 'Кейсы создан'
                });

                $('#new').modal('hide');

                this.newCase = {
                    name: null,
                    name_en: null,
                    name_url: null,
                    category_id: null,
                    image: null,
                    old_price_en: null,
                    price: null,
                    price_en: null,
                    type: null,
                    max_open: null,
                    appId: null
                };

                this.load();
            },
            async edit(id) {
                const request = await axios.post('/api/admin/cases/get', {id: id});
                const data = request.data;

                if (data.success) {
                    this.editCase = request.data.cases;
                    $('#edit').modal('show');
                } else {
                    $.wnoty({
                        type: 'error',
                        message: 'Данный кейс не найден'
                    })
                }
            },
            async saveEdit() {
                const request = await axios.post('/api/admin/cases/edit', {cases: this.editCase});
                const data = request.data;

                this.load();

                $.wnoty({
                    type: data.type,
                    message: data.message
                });
            },
            async del(id) {
                const request = await axios.post('/api/admin/cases/del', {id: id});
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