<template>
    <div class="wrapper">
        <div>
            <div class="header">
                <div class="main-width clear">
                    <router-link tag="a" class="logo" :to="{name: 'index'}"></router-link>
                    <div class="sep"></div>
                    <a :href="$root.config.vk_group" target="_blank" rel="noopener noreferrer" class="btn-vk"></a>

                    <a v-if="$root.user === null" href="/auth/steam" class="btn-steam">авторизация steam</a>
                    <a v-if="$root.user === null" href="/auth/vk" class="btn-auth-vk" style="float: right;margin: 63px 130px 0 -30px;">авторизация vk</a>
                    <a v-if="$root.user === null" href="/auth/fb" class="btn-auth-fb" style="float: right;margin: 63px 130px 0 -30px;">авторизация fb</a>

                    <div v-else class="user-panel">
                        <div class="avatar">
                            <router-link tag="a" :to="{name: 'user', params: {id: $root.user.id}}"><img
                                    :src="$root.user.avatar"
                                    width="52" height="52" alt=""></router-link>
                        </div>
                        <div class="info">
                            <div class="name">
                                <router-link tag="a" :to="{name: 'user', params: {id: $root.user.id}}">{{
                                    $root.user.username }}
                                </router-link>
                                <a href="/auth/logout" class="btn-exit"></a>
                            </div>
                            <div class="balance">{{ $t('header.balance') }}: <span>{{ $root.user.balance }} {{ $root.getCurrency() }}</span></div>
                            <button class="btn-pay" v-on:click="$root.openFill">+</button>
                        </div>
                    </div>

                    <div class="nav">
                        <ul>
                            <li>
                                <router-link tag="a" :to="{name: 'index'}">{{ $t('header.index') }}</router-link>
                            </li>
                            <li>
                                <router-link tag="a" :to="{name: 'contracts'}">{{ $t('header.contracts') }}</router-link>
                            </li>
                            <li>
                                <router-link tag="a" :to="{name: 'battles'}">{{ $t('header.battles') }}</router-link>
                            </li>
                            <li>
                                <router-link tag="a" :to="{name: 'upgrade'}">{{ $t('header.upgrade') }}</router-link>
                            </li>
                            <li>
                                <router-link tag="a" :to="{name: 'faq'}">{{ $t('header.faq') }}</router-link>
                            </li>
                            <li>
                                <router-link tag="a" :to="{name: 'top'}">{{ $t('header.top') }}</router-link>
                            </li>
                            <li v-if="$root.user !== null && $root.user.is_admin">
                                <a href="/admin">{{ $t('header.admin') }}</a>
                            </li>
                            <li>
                                <img v-if="$i18n.locale === 'ru'" v-on:click="changeLang('en')" src="/img/flag_ru.svg" style="width: 20px; cursor: pointer">
                                <img v-if="$i18n.locale === 'en'" v-on:click="changeLang('ru')" src="/img/flag_en.svg" style="width: 20px; cursor: pointer">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="live-drop clear">
                <div class="heading"><span>{{ $t('header.live') }}</span>
                    <router-link :to="{name: 'top'}">{{ $t('header.top') }}</router-link>
                </div>
                <div class="items">
                    <div class="overview">
                        <router-link tag="a" :to="{name: 'user', params: {id: drop.user.id}}" v-for="drop in live"
                                     :key="drop.id" :class="'item '+drop.item.style">
                            <div class="image">
                                <img :src="'https://steamcdn.io/economy/image/'+drop.item.image+'/97fx97f/image.png'"
                                     :alt="drop.item.market_hash_name"/>
                                <div class="tooltip">
                                    <div class="image">
                                        <div class="align">
                                            <img :src="drop.box.image" height="80" alt=""/>
                                        </div>
                                    </div>
                                    <div class="name">{{ drop.user.username }}</div>
                                    <div class="desc">{{ drop.item.market_hash_name}}</div>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="loader" v-if="$root.isLoading">
            <div class="loader__inner">
                <div class="loader__loading"></div>
            </div>
        </div>
        <router-view v-show="!$root.isLoading" :key="$route.fullPath"></router-view>
        <div class="footer">
            <div class="main-width clear">
                <div class="stat clear">
                    <div class="left">
                        <div class="item">
                            <div class="num">{{ stats.opens }}</div>
                            <div class="desc">{{ $t('footer.cases')}}</div>
                        </div>
                        <div class="sep"></div>
                        <div class="item">
                            <div class="num">{{ stats.contracts }}</div>
                            <div class="desc">{{ $t('footer.contracts') }}</div>
                        </div>
                        <div class="sep"></div>
                        <div class="item">
                            <div class="num">{{ stats.battles }}</div>
                            <div class="desc">{{ $t('footer.battles') }}</div>
                        </div>
                        <div class="sep"></div>
                        <div class="item">
                            <div class="num">{{ stats.users }}</div>
                            <div class="desc">{{ $t('footer.users') }}</div>
                        </div>
                        <div class="sep"></div>
                        <div class="item">
                            <div class="num">{{ stats.online }}</div>
                            <div class="desc">{{ $t('footer.online') }}</div>
                        </div>
                    </div>
                </div>
                <div class="copyright"><span>© 2019 {{ $root.config.sitename }} - {{ $t('footer.topDrop') }} <span
                        v-if="$root.config.appId === '730'">CS:GO</span><span
                        v-if="$root.config.appId === '570'">Dota 2</span></span><br>{{ $t('footer.desc_1') }} <br><span v-if="$root.config.appId === '730'">CS:GO</span><span
                            v-if="$root.config.appId === '570'">Dota 2</span> {{ $t('footer.desc_2') }} <br>{{ $t('footer.desc_3') }}
                </div>
                <div class="links">
                    <router-link :to="{name: 'agreement'}">{{ $t('footer.agreement') }}</router-link>
                    <br>
                    <router-link :to="{name: 'contacts'}">{{ $t('footer.contacts') }}
                    </router-link>
                </div>
            </div>
        </div>
        <div v-if="$root.modal.active" id="modal">
            <div class="styles_overlay__CLSq- styles_overlayCenter__YHoO7">
                <div class="styles_modal__gNwvD modal">
                    <div class="modal__title">{{ $t('payment.head') }}</div>
                    <div class="modal__desc">{{ $t('payment.desc_1') }}<br> {{ $t('payment.desc_2') }}
                    </div>
                    <div class="promocode">
                    <input v-model="sum" :placeholder="$t('payment.sum')" autocomplete="off" type="number" value="" class="promocode__input" style="margin-bottom: 20px;">
                    <input v-model="$root.modal.promocode" :placeholder="$t('payment.promocode')"
                                                  class="promocode__input"
                                                  autocomplete="off" type="text" value="">
                        <div rel="promocode_modal" class="promocode__value"></div>
                    </div>
                    <div class="modal__form-wrapper">					
                     <button class="modal__balance-btn" v-on:click="setFill">{{ $t('payment.pay') }}</button>
                    </div>
                    <div class="payments">
                        <ul class="payments__list">
                            <li class="payments__item"><img class="payments__image"
                                                            src="/images/payments/payment_qiwi.png" alt=""></li>
                            <li class="payments__item"><img class="payments__image"
                                                            src="/images/payments/payment_card.png" alt=""></li>
                            <li class="payments__item"><img class="payments__image"
                                                            src="/images/payments/payment_alfabank.png" alt=""></li>
                            <li class="payments__item"><img class="payments__image"
                                                            src="/images/payments/payment_yandex.png" alt=""></li>
                            <li class="payments__item"><img class="payments__image"
                                                            src="/images/payments/payment_wmr.png" alt=""></li>
                        </ul>
                    </div>
                    <button class="styles_closeButton__20ID4 modal__close">
                        <svg class="styles_closeIcon__1QwbI" xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                             viewBox="0 0 36 36">
                            <path d="M28.5 9.62L26.38 7.5 18 15.88 9.62 7.5 7.5 9.62 15.88 18 7.5 26.38l2.12 2.12L18 20.12l8.38 8.38 2.12-2.12L20.12 18z"></path>
                        </svg>
                    </button>
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
                stats: {
                    opens: 0,
                    contracts: 0,
                    battles: 0,
                    users: 0,
                    online: 0
                },
                live: [],
                payment: {
                    sum: 0
                }
            }
        },
        mounted() {
            this.getLiveDrop();
        },
        methods: {
            setOnline(online) {
                this.stats.online = online;
            },
            setStatistic(stats) {
                this.stats.opens = stats.opens;
                this.stats.contracts = stats.contracts;
                this.stats.users = stats.users;
                this.stats.battles = stats.battles;
                this.live = stats.live;
            },
            async getLiveDrop() {
                const request = await axios.post('/api/cases/liveDrop');

                this.setStatistic(request.data.stats);
            },
            async setFill() {
                if (this.$root.user !== null) {
                    const request = await axios.post('/api/payments/create', {promo: this.$root.modal.promocode, sum: this.sum});

                    setTimeout(() => {
                        window.location.href = request.data;
                    }, 100);
                }
            },
            changeLang(lang) {
                this.$root.getBalance();
                this.$router.push({
                    name: this.$route.name,
                    params: {
                        lang: lang
                    }
                })
            }
        },
        sockets: {
            online: function (online) {
                this.setOnline(online);
            },
            liveDrop: function (live) {
                if (live.type === 'default') {
                    setTimeout(() => {
                        this.setStatistic(live.live.stats);
                    }, 11000)
                } else if (live.type === 'upgrade') {
                    setTimeout(() => {
                        this.setStatistic(live.live.stats);
                    }, 15000)
                } else {
                    this.setStatistic(live.live.stats);
                }
            },
            notify: function (notify) {
                if (this.$root.user !== null && parseInt(notify.user_id) === parseInt(this.$root.user.id)) {
                    $.wnoty({
                        type: notify.type,
                        message: notify.message
                    });
                }
            }
        }
    }
</script>
