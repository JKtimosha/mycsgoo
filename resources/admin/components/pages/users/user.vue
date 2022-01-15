<template>
    <div>
        <div class="kt-subheader kt-grid__item" id="kt_subheader">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Редактирование пользователя</h3>
            </div>
        </div>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="row">
                <div class="col-xl-4">
                    <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay">
                        <div class="kt-portlet__head kt-portlet__space-x">
                            <div class="kt-portlet__head-label" style="width: 100%;">
                                <h3 class="kt-portlet__head-title text-center" style="width: 100%;">
                                    {{ user.username }}
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-widget28">
                                <div class="kt-widget28__visual" :style="'background: url('+user.avatar+') bottom center no-repeat'"></div>
                                <div class="kt-widget28__wrapper kt-portlet__space-x">
                                    <div class="tab-content">
                                        <div id="menu11" class="tab-pane active">
                                            <div class="kt-widget28__tab-items">
                                                <div class="kt-widget12">

                                                    <div class="kt-widget12__content">
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Открыто кейсов</span>
                                                                <span class="kt-widget12__value">{{ info.case_count }}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Сумма пополнений</span>
                                                                <span class="kt-widget12__value">{{ info.payments }} р.</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="kt-widget12__content">
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Создано контрактов</span>
                                                                <span class="kt-widget12__value">{{ info.contracts }}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Сыграно битв</span>
                                                                <span class="kt-widget12__value">{{ info.battles }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Информация о пользователе
                                </h3>
                            </div>
                        </div>

                        <form class="kt-form" v-on:submit="save" onsubmit="return false;">
                            <div class="kt-portlet__body">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Имя:</label>
                                        <input type="text" class="form-control" v-model="user.username" disabled>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Ссылка на обмен:</label>
                                        <input type="text" class="form-control" v-model="user.trade_link" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Баланс:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="balance" v-model="user.balance">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="la la-rub"></i></span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Профит:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="balance" v-model="user.profit">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="la la-rub"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Привилегии:</label>
                                        <select class="form-control" v-model="user.is_admin">
                                            <option :selected="user.is_admin" value="1">Администратор</option>
                                            <option :selected="!user.is_admin" value="0">Пользователь</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Профиль STEAM:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" :value="'https://steamcommunity.com/profiles/'+user.steamid64+'/'" disabled>
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="la la-steam"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Подкрутка билетов:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="balance" v-model="info.chance">
                                        </div>
                                        <p>Ко всем вещам, которые стоят дороже кейса, прибавляется эта подкрутка</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Тип:</label>
                                        <select class="form-control" v-model="user.type">
                                            <option :selected="user.type === 'default'" value="default">Пользователь</option>
                                            <option :selected="user.type === 'fake'" value="fake">Бот</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__foot kt-portlet__foot--solid">
                                <div class="kt-form__actions">
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-brand">Сохранить</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
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
                user: [],
                info: []
            }
        },
        methods: {
            async load() {
                const request = await axios.post('/api/admin/users/get', {id: this.$route.params.id});
                const data = request.data;

                if (data.success) {
                    this.user = data.user;
                    this.info = data.info;
                } else {
                    this.$router.go(-1);
                }
            },
            async save() {
                const request = await axios.post('/api/admin/users/save', {id: this.$route.params.id, user: this.user, chance: this.info.chance});
                const data = request.data;

                $.wnoty({
                    type: data.type,
                    message: data.message
                })
            }
        },
        mounted() {
            this.load();
        }
    }
</script>