<template>
    <div>
        <div class="kt-subheader kt-grid__item" id="kt_subheader">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Настройки</h3>
            </div>
        </div>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet kt-portlet--tabs">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#site" role="tab" aria-selected="true">
                                    Настройки сайта
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#bot" role="tab" aria-selected="false">
                                    Настройки бота
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#description" role="tab" aria-selected="false">
                                    Настройки описания
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#payment" role="tab" aria-selected="false">
                                    Настройки оплаты
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#bonus" role="tab" aria-selected="false">
                                    Настройки бонусов
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <form class="kt-form" onclick="return false;">
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="site" role="tabpanel">
                                <div class="kt-section">
                                    <h3 class="kt-section__title">
                                        Общие настройки:
                                    </h3>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label>Доменное имя:</label>
                                            <input type="text" class="form-control" placeholder="domain.ru" v-model="config.domain" name="domain">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Имя сайта:</label>
                                            <input type="text" class="form-control" placeholder="sitename.ru" v-model="config.sitename" name="sitename">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Заголовок сайта (титул):</label>
                                            <input type="text" class="form-control" placeholder="sitename.ru - краткое описание" v-model="config.title" name="title">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label>Описание для поисковых систем:</label>
                                            <input type="text" class="form-control" placeholder="Описание для сайта..." v-model="config.description" name="description">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Ключевые слова для поисковых систем:</label>
                                            <input type="text" class="form-control" placeholder="сайт, имя, домен и тд..." v-model="config.keywords" name="keywords">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Игра:</label>
                                            <select v-model="config.game_id" class="form-control" name="game_id">
                                                <option value="570" :selected="config.game_id === 570">Dota 2</option>
                                                <option value="730" :selected="config.game_id === 730">CS:GO</option>
                                                <option value="0" :selected="config.game_id === 0">Мульти-кейсы</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="bot" role="tabpanel">
                                <div class="kt-section">
                                    <h3 class="kt-section__title">
                                        Настройки бота:
                                    </h3>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label>STEAMID64:</label>
                                            <input type="text" class="form-control" placeholder="76561198320695562" v-model="config.bot_steamid" name="bot_steamid">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>MARKET API KEY:</label>
                                            <input type="text" class="form-control" placeholder="FJ82K8941c213KDSJ" v-model="config.bot_key" name="bot_key">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label>Username:</label>
                                            <input type="text" class="form-control" v-model="config.bot_username" name="bot_username">
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Password:</label>
                                            <input type="text" class="form-control" v-model="config.bot_password" name="bot_password">
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Shared secret:</label>
                                            <input type="text" class="form-control" v-model="config.bot_shared_secret" name="bot_shared_secret">
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Identity secret:</label>
                                            <input type="text" class="form-control" v-model="config.bot_identity_secret" name="bot_identity_secret">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="description" role="tabpanel">
                                <div class="kt-section">
                                    <h3 class="kt-section__title">
                                        Описание:
                                    </h3>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label>Группа ВКонтакте:</label>
                                            <input type="text" class="form-control" placeholder="http://vk.com/" v-model="config.vk_group">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Почта:</label>
                                            <input type="text" class="form-control" placeholder="support@gmail.com" v-model="config.support_email">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Название домена (с протоколом):</label>
                                            <input type="text" class="form-control" placeholder="https://site.com" v-model="config.site_name_protocol">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="payment" role="tabpanel">
                                <div class="kt-section">
                                    <h3 class="kt-section__title">
                                        Платежная система:
                                    </h3>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label>Платежная система:</label>
                                            <select v-model="config.payment" class="form-control" name="game_id">
                                                <option value="freekassa" :selected="config.payment === 'freekassa'">FreeKassa</option>
                                                <option value="unitpay" :selected="config.payment === 'unitpay'">UnitPay</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row" v-show="config.payment === 'freekassa'">
                                        <div class="col-lg-3">
                                            <label>ID Проекта:</label>
                                            <input type="text" class="form-control" v-model="config.freekassa_id" name="bot_username">
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Секретный ключ 1 из кабинета:</label>
                                            <input type="text" class="form-control" v-model="config.freekassa_secret1" name="bot_password">
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Секретный ключ 2 из кабинета:</label>
                                            <input type="text" class="form-control" v-model="config.freekassa_secret2" name="bot_shared_secret">
                                        </div>
                                    </div>
                                    <div class="form-group row" v-show="config.payment === 'unitpay'">
                                        <div class="col-lg-3">
                                            <label>Публичный ключ:</label>
                                            <input type="text" class="form-control" v-model="config.unitpay_public" name="bot_username">
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Секретный ключ:</label>
                                            <input type="text" class="form-control" v-model="config.unitpay_secret" name="bot_password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="bonus" role="tabpanel">
                                <div class="kt-section">
                                    <h3 class="kt-section__title">
                                        Бонусы:
                                    </h3>
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label>Сумма пополнений за последние 7 дней для бесплатного кейса:</label>
                                            <input type="number" class="form-control" v-model="config.bonus_free" name="bot_username">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary" v-on:click="save">Сохранить</button>
                            <button type="reset" class="btn btn-secondary" v-on:click="reset">Сбросить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                oldConfig: {},
                config: {
                    domain: null,
                    sitename: null,
                    title: null,
                    description: null,
                    keywords: null,
                    game_id: null,
                    bot_steamid: null,
                    bot_key: null,
                    bot_username: null,
                    bot_password: null,
                    bot_shared_secret: null,
                    bot_identity_secret: null,
                    vk_group: null,
                    support_email: null,
                    site_name_protocol: null,
                    payment: null,
                    freekassa_id: null,
                    freekassa_secret1: null,
                    freekassa_secret2: null,
                    unitpay_public: null,
                    unitpay_secret: null,
                    bonus_free: null
                }
            }
        },
        methods: {
            async load() {
                const request = await axios.post('/api/admin/settings/load');

                this.config = request.data;
                this.oldConfig = request.data;
            },
            async reset() {
                this.config = this.oldConfig;
            },
            async save() {
                const request = await axios.post('/api/admin/settings/save', {config: this.config});
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