<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"></div>
        </div>
        <div class="contracts__header">
            <span class="contracts__header__text">{{ $t('battles.battle.head') }}</span>
        </div>
        <div class="content casebattle" id="casebattle">
            <div class="casebattle-bar">
                <div class="casebattle-bar-button">
                    <router-link :to="{name: 'battles'}" class="casebattle-button blue active">
                        <span>
                            {{ $t('battles.battle.active') }}
                        </span>
                    </router-link>
                    <router-link :to="{name: 'battles.history'}" class="casebattle-button velvet">
                        <span>
                            {{ $t('battles.battle.my') }}
                        </span>
                    </router-link>
                </div>
                <div class="casebattle-bar-create">
                    <router-link :to="{name: 'battles.create'}" class="casebattle-create-button">
                        <span>
                            {{ $t('battles.battle.created') }}
                        </span>
                    </router-link>
                </div>
            </div>
            <div class="main-width" style="width: 1500px">
                <div class="casebattle-breadcrumb">
                    <div class="casebattle-breadcrumb-black">
                        <a v-on:click="$router.go(-1)"><i aria-hidden="true" class="fa fa-angle-left"></i> {{ $t('battles.battle.back') }}</a>
                        <a v-on:click="copyLink" class="casebattle-breadcrumb-link" style="display: block;">
                            <i aria-hidden="true" class="fal fa-link"></i>
                            {{ location }}/battle/<span>{{ game.id }}</span></a>
                    </div>

                    <div class="casebattle-breadcrumb-round">
                        <span class="casebattle-round-text-left">{{ $t('battles.battle.round') }}</span>
                        <div class="casebattle-round-number" :class="{loading: game.is_start, waiting: is_loading}"
                             v-if="game.is_start || is_loading">
                            <span v-if="game.current_round > 0">{{ game.current_round }}</span>
                            <span v-if="game.current_round === 0">GO</span>
                            <span v-if="game.current_round === ''">&nbsp;</span>
                        </div>
                        <div class="casebattle-round-number" :class="{loading: game.is_start, waiting: is_loading}"
                             v-if="!game.is_start && !is_loading">
                            <span>{{ game.round_count }}</span>
                        </div>
                        <span class="casebattle-round-text-right"
                              v-show="!is_loading && game.is_start">{{ $t('battles.battle.of') }} {{ game.round_count }}</span>
                    </div>

                    <div class="casebattle-breadcrumb-case" v-if="is_loading">
                        <span>Загрузка</span>
                    </div>
                    <div class="casebattle-breadcrumb-case" v-if="!is_loading && current_case !== {}">
                        <span v-if="!game.is_show_result  && game.is_start">{{ $t('battles.battle.box') }}</span>
                        <span v-if="is_loading">Загрузка</span>
                        <span v-if="game.is_show_result || !game.is_start">{{ $t('battles.battle.allPrice') }}</span>
                        <img v-if="!game.is_show_result  && game.is_start" :src="current_case.image" alt="">
                        <strong v-if="!game.is_show_result  && game.is_start" v-text="current_case.name"></strong>

                        <strong v-if="(game.is_show_result || game.is_start) && game.state !== 2 && $i18n.locale === 'ru'"
                                id="current_case_price">{{ current_case.price }}Р</strong>
                        <strong v-if="(game.is_show_result || game.is_start) && game.state !== 2 && $i18n.locale !== 'ru'"
                                id="current_case_price">{{ current_case.price_en }}$</strong>
                        <strong v-if="game.is_show_result && game.state === 2 && $i18n.locale === 'ru'">{{ game.win_price }}Р</strong>
                        <strong v-if="game.is_show_result && game.state === 2 && $i18n.locale !== 'ru'">{{ (game.win_price / 63.67).toFixed(2) }}$</strong>

                        <strong v-if="!game.is_show_result && !game.is_start && $i18n.locale === 'ru'" id="game_price">{{ game.price }}Р</strong>
                        <strong v-if="!game.is_show_result && !game.is_start && $i18n.locale !== 'ru'" id="game_price">{{ (game.price / 63.67).toFixed(2) }}$</strong>
                        <strong v-if="!game.is_show_result && !game.is_start && game.state === 2 && $i18n.locale === 'ru'">{{ game.win_price }}Р</strong>
                        <strong v-if="!game.is_show_result && !game.is_start && game.state === 2 && $i18n.locale !== 'ru'">{{ (game.win_price / 63.67).toFixed(2) }}$</strong>
                    </div>
                </div>
                <div class="casebattle-battle-place">
                    <div class="casebattle-battle-place-cell"
                         :id="'u' + u.id"
                         :class="{winner: (game.is_finish && u.items.length > 0 && u.items[u.items.length - 1].is_win === 1) || (game.is_show_result && u.items.length > 0), loose: ((game.is_finish && u.items.length > 0 && u.items[u.items.length - 1].is_win === 0 && !game.is_show_result) || (game.is_show_result && u.items.length === 0)), ready: !game.is_finish}"
                         v-for="(u, i) in game.users">

                        <div class="cell-items animated fadeIn" v-if="game_view === 'result_wihout_winner'">
                            <div class="cell-item-status">
                                <div v-if="$i18n.locale === 'ru'" class="cell-item-status-text">{{ u.final_price }}Р</div>
                                <div v-else class="cell-item-status-text">{{ (u.final_price / 63.67).toFixed(2) }}$</div>
                            </div>
                        </div>

                        <div class="cell-items animated fadeIn"
                             v-if="!game.is_start && !game.is_finish && game_view === 'active'">
                            <div class="cell-item-status">
                                <div class="cell-item-status-ready">
                                    <img src="https://cdn.hellcase.com/hellcase/img/web/casebattle/ready_icon.svg"
                                         alt="">
                                </div>
                                <div class="cell-item-status-text">{{ $t('battles.battle.ready') }}</div>

                                <div class="cell-items-buttons"
                                     v-if="$root.user !== null && (game.user_id === u.id && game.user_id === $root.user.id && game.users.length === 1)">
                                    <a class="cell-items-button" v-on:click="declineBattle"><i
                                            class="fal fa-times text-danger"></i> {{ $t('battles.battle.cancel') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="cell-items animated fadeIn"
                             v-if="game.is_finish && u.items.length > 0 && !game.is_show_result && game_view === 'active'">
                            <div class="cell-item">
                                <div class="cell-item-image">
                                    <img :src="'https://steamcdn.io/economy/image/'+u.items[u.items.length - 1].item.image + '/360fx360f.png/image.png'"
                                         alt="">
                                </div>
                                <div v-if="$i18n.locale === 'ru'" class="cell-item-name" v-text="u.items[u.items.length - 1].item.name_first"></div>
                                <div v-else class="cell-item-name" v-text="u.items[u.items.length - 1].item.name_first_en"></div>
                                <div class="cell-item-exterior"
                                     v-text="u.items[u.items.length - 1].item.name_second"></div>
                            </div>
                        </div>


                        <div class="cell-items animated fadeIn" v-if="game.is_show_result && game_view === 'active'">
                            <div class="cell-item-status">
                                <div class="cell-item-status-text text-success"
                                     v-if="game.is_show_result && game.user_win_id === u.id">{{ $t('battles.battle.winner') }}
                                </div>
                                <div v-if="$i18n.locale === 'ru'"  class="cell-item-status-text"
                                     :class="{'text-danger': game.is_show_result && game.user_win_id !== u.id}">
                                    {{u.final_price }}Р
                                </div>
                                <div v-else class="cell-item-status-text"
                                     :class="{'text-danger': game.is_show_result && game.user_win_id !== u.id}">
                                    {{ (u.final_price / 63.67).toFixed(2) }}$
                                </div>
                            </div>
                        </div>

                        <div class="cell-items roulette" :id="'roulette'+u.id"
                             v-show="game.is_start && !game.is_finish">
                            <div class="cell-item" v-for="item in userItemsRoulette[i]">
                                <div class="cell-item-image">
                                    <img :src="'https://steamcdn.io/economy/image/'+item.image+'/360fx360f/image.png'"></div>
                                <div v-if="$i18n.locale === 'ru'">
                                    <div class="cell-item-name">{{ item.name_first }}</div>
                                    <div class="cell-item-exterior">{{ item.name_second }}</div>
                                </div>
                                <div v-else>
                                    <div class="cell-item-name">{{ item.name_first_en }}</div>
                                    <div class="cell-item-exterior">{{ item.name_second_en }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="cell-user-profile">
                            <router-link :to="{name: 'user', params: {id: u.id}}"><img
                                    :src="u.avatar"
                                    alt=""></router-link>
                            <span>{{ u.username }}</span>
                            <span v-if="(game.is_start || game.is_finish) && u.items.length > 0 && !game.is_show_result && $i18n.locale === 'ru'">{{ u.final_price }}Р</span>
                            <span v-if="(game.is_start || game.is_finish) && u.items.length > 0 && !game.is_show_result && $i18n.locale !== 'ru'">{{ (u.final_price / 63.67).toFixed(2) }}$</span>
                        </div>

                        <div class="cell-progress" v-show="game.is_start"
                             :style="'width: '+(game.current_round / game.round_count * 100)+'%;'"></div>

                        <transition-group name="list" tag="div" class="case-items"
                                          style="flex-wrap: wrap; display: flex;width: 100%;">
                            <div v-for="(item, i) in u.items" :class="'item '+item.item.style"
                                 style="margin-right: 6px;">
                                <div class="price"
                                     style="background: #131313;text-align: left;overflow: hidden;font-size: 10px;line-height: 14px;color: #fff;text-transform: uppercase;padding: 5px 10px 4px;background: #0a0a0a;">
                                    <span v-if="$i18n.locale === 'ru'">{{ item.item.price }}Р</span>
                                    <span v-else>{{ item.item.price_en }}$</span>
                                </div>
                                <div class="image" style="height: 113px;"><img
                                        :src="'https://steamcdn.io/economy/image/'+item.item.image+'/120fx120f/image.png'"
                                        :alt="item.name"/></div>
                                <div v-if="$i18n.locale === 'ru'" class="name">
                                    <div class="name-text">
                                        {{ item.item.name_first }}
                                    </div>
                                    <div class="name-text">
                                        {{ item.item.name_second }}
                                    </div>
                                </div>
                                <div v-else class="name">
                                    <div class="name-text">
                                        {{ item.item.name_first_en }}
                                    </div>
                                    <div class="name-text">
                                        {{ item.item.name_second_en }}
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </div>
                    <div class="casebattle-battle-place-cell waiting" v-for="x in players_required">
                        <div class="cell-items waiting">
                            <div class="cell-item-status" v-if="!cant_join">
                                <div class="cell-item-status-ready">
                                    <img src="https://cdn.hellcase.com/hellcase/img/web/casebattle/waiting_icon.svg"
                                         alt="">
                                </div>
                                <div class="cell-item-status-text"></div>
                            </div>

                            <div class="cell-item-status" v-else>
                                <div class="cell-item-status-text">{{ $t('battles.battle.youReady') }}</div>
                                <div class="cell-items-buttons"><a v-on:click="joinBattle" class="cell-items-button"><i
                                        class="fal fa-check text-success"></i> {{ $t('battles.battle.join') }}</a> <a v-on:click="$router.go(-1)"
                                                                                            class="cell-items-button"><i
                                        class="fal fa-times text-danger"></i> {{ $t('battles.battle.decline') }}</a></div>
                            </div>

                        </div>
                        <div class="cell-user-profile"><span>{{ $t('battles.battle.waiting') }}</span></div>
                    </div>
                </div>
                <div onclick="$('.casebattle-items').slideToggle('slow')" class="casebattle-show-cases"><span>{{ $t('battles.battle.show') }}</span>
                </div>
                <div class="casebattle-items" style="display: none;">
                    <div class="case-items animated slideInDown" style="width: 100%;">
                        <div v-for="item in game.items_swarm" :key="item.key" :class="'item '+item.style"
                             style="margin-right: 6px;">
                            <div class="image"><img
                                    :src="'https://steamcdn.io/economy/image/'+item.image+'/120fx120f/image.png'"
                                    :alt="item.name"/></div>
                            <div v-if="$i18n.locale === 'ru'" class="name">
                                <div class="name-text">
                                    {{ item.name_first }}
                                </div>
                                <div class="name-text">
                                    {{ item.name_second }}
                                </div>
                            </div>
                            <div v-else class="name">
                                <div class="name-text">
                                    {{ item.name_first_en }}
                                </div>
                                <div class="name-text">
                                    {{ item.name_second_en }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-hellcase is-success modal-casebattle" v-cloak :class="{show: sub_view == 'winner'}"
                     v-if="game.is_finish && game.user_win_id > 0">
                    <div class="modal-background animated fadeIn fast" @click="close_winner_modal"></div>
                    <div class="modal-card is-medium animated bounceIn">
                        <header class="modal-card-head">
                            <p class="modal-card-title">{{ $t('battles.battle.youWin') }}</p>
                            <a class="close" @click="close_winner_modal" aria-label="close"></a>
                        </header>

                        <section class="modal-card-body">
                            <h2 v-if="$i18n.locale === 'ru'"><span><strong v-text="game.win_items.length">0</strong> {{ $t('battles.battle.itemsSum') }} <strong
                                    v-text="game.win_price"></strong></span>Р</h2>
                            <h2 v-else ><span><strong v-text="game.win_items.length">0</strong> {{ $t('battles.battle.itemsSum') }} <strong
                                    v-text="(game.win_price / 63.67).toFixed(2)"></strong></span>$</h2>
                            <div class="modal-hellcase-items-list">
                                <div class="hell-winner-item" v-for="(item, i) in game.win_items">
                                    <div class="hell-winner-item-image">
                                        <div class="hell-winner-image classified">
                                            <div class="image"
                                                 :style="{ 'background-image': 'url(https://steamcdn.io/economy/image/'+item.item.image+'/360fx360f/image.png)' }"></div>
                                        </div>
                                    </div>
                                    <div class="hell-winner-item-text">
                                        <div v-if="$i18n.locale === 'ru'" class="name"
                                             v-html="item.item.name_first + '<br><span>' + item.item.name_second + '</span>'"></div>
                                        <div v-else class="name"
                                             v-html="item.item.name_first_en + '<br><span>' + item.item.name_second_en + '</span>'"></div>
                                        <div class="hell-winner-action-item">
                                            <a v-on:click="itemSell(item.item.open_id, i)" class="hellcase-btn-success">
                                                <span v-if="$i18n.locale === 'ru'"><i class="fal fa-shopping-basket"></i> {{ item.item.price }}Р</span>
                                                <span v-else><i class="fal fa-shopping-basket"></i> {{ item.item.price_en }}$</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="modal-card-body">
                            <div class="hell-modal-card-action">
                                <a v-on:click="allSell" class="hellcase-btn-success">
                                    <span v-if="$i18n.locale === 'ru'"><i class="hellicon-sell"></i> {{ $t('battles.battle.sellFor') }} {{ game.win_price }}Р</span>
                                    <span v-else><i class="hellicon-sell"></i> {{ $t('battles.battle.sellFor') }} {{ (game.win_price / 63.67).toFixed(2) }}$</span>
                                </a>
                                <router-link tag="a" :to="{name: 'contracts'}" class="hellcase-btn-info"><span><i
                                        class="hellicon-contract" aria-hidden="true"></i> {{ $t('battles.battle.contracts') }} </span>
                                </router-link>
                                <router-link tag="a" :to="{name: 'battles.create'}" class="hellcase-btn-cb"><span><i
                                        class="fas fa-plus"></i> {{ $t('battles.battle.create') }}</span></router-link>
                            </div>
                        </section>

                    </div>
                </div>
                <div class="modal-hellcase is-success modal-casebattle" v-cloak
                     :class="{show: sub_view === 'game_over'}"
                     v-if="game.is_finish && game.user_win_id > 0">
                    <div class="modal-background animated fadeIn"></div>
                    <div class="modal-card is-small animated bounceIn">
                        <header class="modal-card-head">
                            <p class="modal-card-title">{{ $t('battles.battle.end') }}</p>
                            <h2 v-if="$i18n.locale === 'ru'"><span>{{ $t('battles.battle.allWin') }} <strong
                                    v-text="game.win_price"></strong>Р</span></h2>
                            <h2 v-else><span>{{ $t('battles.battle.allWin') }} <strong
                                    v-text="(game.win_price / 63.67).toFixed(2)"></strong>$</span></h2>
                            <a class="close" aria-label="close"></a>
                        </header>

                        <section class="modal-card-body">
                            <div class="modal-users">
                                <router-link tag="a" :to="{name: 'user', params: {id: u.id}}"
                                             v-for="(u, index) in game.users"
                                             :key="index"
                                             v-if="game.user_id !== '0'" target="_blank"
                                             :class="{winner: game.user_win_id === u.id}" data-text="Winner">
                                    <img :src="u.avatar" alt="avatar">
                                    <span v-html="u.username"></span>
                                </router-link>
                            </div>

                            <div class="hell-modal-card-action">
                                <router-link tag="a" :to="{name: 'battles'}" class="hellcase-btn-default"><span><i
                                        class="hellicon-back" aria-hidden="true"></i> {{ $t('battles.battle.backList') }} </span>
                                </router-link>
                            </div>
                        </section>
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
                location: window.location.origin,
                id: this.$route.params.id,
                link: `${window.location.origin}/battle/${this.$route.params.id}`,
                game: {
                    battle_id: null,
                    current_round: 1,
                    price: 0,
                    user_win_id: 0,
                    win_price: 0,
                    cases: [],
                    players: [],
                    items_swarm: [],
                    is_start: false,
                    is_finish: false,
                    round: {},
                    win_items: [],
                    is_complete: false
                },
                is_loading: true,
                players_required: 1,
                timers: {
                    next_round_time: 600,
                    wait_users: 2000
                },
                current_case: {},
                times: {
                    wait_other_users: 4,
                    next_round: 1000,
                    counter: 0,
                    timer: null
                },
                is_started: false,
                cant_join: true,
                joined: false,
                max_items: 10,
                game_view: 'active',
                sub_view: '',
                userItemsRoulette: [],
                games: []
            }
        },
        methods: {
            async copyLink() {
                const textarea = document.createElement("textarea");
                textarea.textContent = this.link;
                textarea.style.position = "fixed";
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);

                $.wnoty({
                    type: 'success',
                    message: this.$t('battles.battle.copy')
                })
            },
            async getBattle() {
                const request = await axios.post('/api/battles/getBattle', {id: this.$route.params.id}),
                    data = request.data;

                if (!data.success) {
                    this.$root.hideLoading();
                    return this.$router.go(-1);
                }

                this.is_loading = false;

                this.setBattle(data.battle);
                this.getCurrentCase();
                const self = this;

                if (this.$root.user !== null) {
                    for (const u of this.game.users) {
                        if (u.id === this.$root.user.id) this.cant_join = false;
                    }
                } else {
                    this.cant_join = false;
                }

                this.$root.hideLoading();
            },
            async setBattle(data) {
                const self = this;
                self.game.battle_id = data.id;
                self.game.is_run = false;
                self.game.id = data.id;
                self.game.state = data.status;
                self.game.price = data.price;
                self.game.round_count = parseInt(data.rounds_count);
                self.game.rounds = data.rounds;
                self.game.is_private = data.type;
                self.game.users = data.users;
                self.game.items_swarm = data.caseItems;
                self.game.cases = data.cases;
                self.game.win_price = data.win_price;
                self.game.user_win_id = data.user_win_id;
                self.game.user_id = data.user_id;
                self.game.players = parseInt(data.players);
                self.game.current_round = 1;
                self.players_required = 0;
                if (data.users.length < parseInt(data.players)) {
                    self.players_required = parseInt(parseInt(data.players) - data.users.length);
                }

                this.$forceUpdate();

                if (self.is_live(self.game.id) && self.game.state === 1 && !self.is_started) {
                    self.is_loading = true;
                    await self.startGame();
                    return true;
                }

                if (self.game.users.length >= 2 && this.game.state !== 2 && !self.is_started) {
                    if (self.game.users.length === parseInt(self.game.players)) {
                        self.is_loading = true;
                        self.game.current_round = self.times.wait_other_users;
                        await self.fetchImages();
                        self.timer(function (index) {
                            self.game.current_round = index;
                        }, async () => {
                            await self.startGame();
                        }, self.times.wait_other_users);
                    }
                    return true;
                }

                if (self.game.state === 2 && !self.is_started) {
                    await self.set_finished_game();
                    return true;
                }
            },
            async getCurrentCase() {
                let case_data = {};
                if (typeof (this.game.rounds) === 'object') {
                    const round_data = this.game.rounds[(this.game.users.length * this.game.current_round) - this.game.users.length];
                    if (typeof (round_data) !== 'undefined') {
                        case_data = this.game.cases[round_data.case_id];
                    }
                }
                this.current_case = case_data;
                return case_data;
            },
            async joinBattle() {
                if (this.joined) return;
                const request = await axios.post('/api/battles/join', {id: this.game.id}), data = request.data;

                if (data.success) {
                    this.$root.getBalance();

                    $.wnoty({
                        type: 'success',
                        message: this.$t('battles.battle.connected')
                    })
                } else {
                    this.joined = false;
                    $.wnoty({
                        type: 'error',
                        message: this.$t(`battles.battle.${data.message}`)
                    })
                }
            },
            async fetchImages(cb) {
                const self = this;
                let images_loaded = 0;
                let max_items = self.max_items;
                let items = await this.current_game_items();
                if (items.length < self.max_items) max_items = items.length - 1;
                $.each(items, function (idx, item) {
                    if (idx >= max_items) {
                        return false;
                    }
                    let img = new Image();
                    img.src = `https://steamcdn.io/economy/image/${item.image}/360fx360f.png/image.png`;
                    img.onload = function () {
                        images_loaded = images_loaded + 1;
                        if (images_loaded === max_items) {
                            if (typeof (cb) === 'function') {
                                cb();
                            }
                            return false;
                        }
                    };
                });
            },
            async current_game_items() {
                const self = this;
                let weapons = [];
                if (typeof (this.game.rounds) === 'object') {
                    const round_data = this.game.rounds[(this.game.users.length * this.game.current_round) - this.game.users.length];
                    if (typeof (round_data) !== 'undefined') {
                        const case_data = this.game.cases[round_data.case_id];
                        if (typeof (case_data.case_items) === 'object') {
                            $.each(case_data.case_items, function (index, value) {
                                weapons.push(self.game.items_swarm[value]);
                            });
                        }
                    }
                }
                return weapons;
            },
            async startGame() {
                const self = this;

                if (this.is_started) return false;
                if (this.game.is_complete) return false;

                const node_game = self.get_node_game(self.game.battle_id);

                this.is_loading = true;
                this.is_started = true;
                this.game.is_complete = true;

                const request = await axios.post('/api/battles/getBattle', {id: this.$route.params.id}),
                    data = request.data;
                this.setBattle(data.battle);

                self.is_loading = false;

                $.each(self.game.users, function (index, u) {
                    if (typeof (u.items) === 'undefined') {
                        u.items = [];
                        u.final_price = 0;
                    }
                });

                self.game.is_finish = false;
                self.game.is_run = false;
                self.game.is_show_result = false;
                self.game.state = 1;

                if (typeof (node_game.current_round) !== 'undefined') {
                    self.game.current_round = node_game.current_round;
                    self.recount_rounds_items_recount(self.game.current_round);
                }
                await self.fetchImages(async () => {
                    await self.playRound(self.game.current_round);
                });
            },
            timer(cb_interval, cb_finish, sec) {
                const self = this;
                if (self.times.counter > 0) return false;
                this.times.counter = sec;
                let time = setInterval(function () {
                    if (self.times.counter === 0) {
                        clearInterval(self.times.timer);
                        cb_finish(self.times.counter);
                        return false;
                    }
                    self.times.counter = self.times.counter - 1;
                    cb_interval(self.times.counter);
                }, 1000);
                this.times.timer = time;
            },
            async playRound(round_id) {
                const self = this;
                this.game.is_start = true;
                this.game.is_finish = false;
                this.game.is_run = false;
                let stopIndex = 0;
                let rounds = {};
                let is_fetched = false;
                this.getCurrentCase();

                $.each(this.game.rounds, function (idx, r) {
                    if (typeof (r.round_number) === 'number') {
                        if (typeof (rounds[r.round_number]) === 'undefined') {
                            rounds[r.round_number] = [r];
                        } else {
                            rounds[r.round_number].push(r);
                        }
                    }
                });

                if (typeof (rounds[round_id]) === 'undefined') {
                    return false;
                }

                self.userItemsRoulette = [];

                this.game.round = rounds[round_id];
                $.each(self.game.users, function (index, user) {
                    $('#roulette' + user.id).html('');
                    self.userItemsRoulette[index] = [];
                });

                const items = await self.current_game_items();

                $.each(items, function (idx, item) {
                    if (idx >= self.max_items) {
                        return false;
                    }

                    stopIndex = idx;
                    $.each(self.game.users, function (index, user) {
                        self.userItemsRoulette[index].push(item);
                    });
                });

                $.each(self.game.round, function (index, item) {
                    self.userItemsRoulette[index].push(item.item);
                });

                this.$forceUpdate();

                stopIndex = stopIndex + 1;

                self.$nextTick(async () => {
                    const option = {
                        duration: 3000, rollCount: 2, stopImageNumber: stopIndex, startCallback: function () {
                        }, slowDownCallback: function () {
                        }, stopCallback: function ($stopElm, stopIndex) {
                            if (self.game.is_run === true) {
                                $.each(self.game.round, function (index, item) {
                                    $.each(self.game.users, function (index, u) {
                                        if (parseInt(item.user_id) === parseInt(u.id)) {
                                            u.items.push(item);
                                            self.game.win_items.push(item);
                                            u.final_price = parseFloat(u.final_price) + parseFloat(item.win_price);
                                        }
                                    });
                                });
                                self.game.is_run = false;
                                setTimeout(async () => {
                                    if (parseInt(self.$route.params.id) !== parseInt(self.game.battle_id)) {
                                        return false;
                                    }
                                    self.game.is_finish = true;
                                    if (parseInt(self.game.round_count) > parseInt(self.game.current_round)) {
                                        setTimeout(function () {
                                            self.game.current_round = self.game.current_round + 1;
                                            self.playRound(self.game.current_round);
                                        }, self.times.next_round);
                                    } else {
                                        self.game.is_start = false;
                                        self.is_loading = false;
                                        setTimeout(async () => {
                                            if (parseInt(self.$route.params.id) !== parseInt(self.game.battle_id)) {
                                                return false;
                                            }
                                            self.game.is_show_result = true;
                                            self.is_started = false;
                                            self.game.state = 2;
                                            let winners = await self.check_once_winner();
                                            if (winners.length > 1) {
                                                await self.show_random_fair_winner(winners);
                                            } else {
                                                await self.set_finished_game();
                                                if (self.$root !== null && (parseInt(self.game.user_win_id) !== parseInt(self.$root.user.id))) {
                                                    await self.show_game_over_modal();
                                                } else {
                                                    await self.show_winner_modal();
                                                }
                                            }
                                        }, 2000);
                                    }
                                }, 500);
                            }
                        }
                    };
                    if (is_fetched) return false;
                    if (self.game.is_run) return false;
                    await self.fetchImages(function () {
                        is_fetched = true;
                        $.each(self.game.round, function (index, value) {
                            let r = $('#roulette' + value.user_id).roulette(option);
                            self.game.is_run = true;
                            self.$nextTick(function () {
                                r.roulette('start');
                            });
                        });
                    });
                });
            },
            async check_once_winner() {
                let more_winners = [];
                let last_max_price = 0;

                $.each(this.game.users, function (index, u) {
                    if (u.final_price > last_max_price) {
                        last_max_price = u.final_price;
                    }
                });

                $.each(this.game.users, function (index, u) {
                    if (u.final_price === last_max_price) {
                        more_winners.push(u);
                    }
                });

                return more_winners;
            },
            async show_random_fair_winner(winners) {
                const self = this;
                this.game_view = 'result_wihout_winner';
                this.timer.count_timer = 0;
                this.timer.last_user_id = 0;
                this.timer.timeer_speed = 600;
                await this.startFindWinner(winners, async () => {
                    self.game_view = 'active';
                    await self.set_finished_game();
                    if (self.$root.user !== null && (parseInt(self.game.user_win_id) !== parseInt(self.$root.user.id))) {
                        await self.show_game_over_modal();
                    } else {
                        await self.show_winner_modal();
                    }
                });
            },
            async startFindWinner(winners, cb) {
                const self = this;
                setTimeout(async () => {
                    self.timer.count_timer = self.timer.count_timer + 1;
                    $.each(self.game.users, function (index, u) {
                        $('#u' + u.id).removeClass('ready').removeClass('winner').removeClass('loose').addClass('ready');
                    });
                    $.each(winners, function (index, u) {
                        if (self.timer.last_user_id !== u.id) {
                            self.timer.last_user_id = u.id;
                            $('#u' + u.id).removeClass('ready').removeClass('winner').removeClass('loose').addClass('winner');
                            return false;
                        }
                    });
                    if (self.timer.count_timer === 5) {
                        self.timer.timeer_speed = 300;
                    }
                    if (self.timer.count_timer === 10) {
                        self.timer.timeer_speed = 200;
                    }
                    if (self.timer.count_timer === 20) {
                        self.timer.timeer_speed = 100;
                    }
                    if (self.timer.count_timer <= 40) {
                        await self.startFindWinner(winners, cb);
                    } else {
                        if (parseInt(self.timer.last_user_id) === parseInt(self.game.user_win_id)) {
                            setTimeout(function () {
                                cb();
                            }, 1000);
                        } else {
                            await self.startFindWinner(winners, cb);
                        }
                    }
                }, this.timer.timeer_speed);
            },
            async set_finished_game() {
                const self = this;
                $.each(self.game.users, function (index, u) {
                    u.items = [];
                    u.final_price = 0;
                });
                this.game.current_round = this.game.round_count;
                this.getCurrentCase();
                this.game.win_items = [];
                $.each(self.game.rounds, function (index, item) {
                    $.each(self.game.users, function (index, u) {
                        if (parseInt(self.game.user_win_id) === parseInt(u.id)) {
                            u.items.push(item);
                            self.game.win_items.push(item);
                        }
                        if (parseInt(item.user_id) === parseInt(u.id)) {
                            u.final_price = parseFloat(u.final_price) + parseFloat(item.win_price);
                        }
                    });
                });
                this.game.is_finish = true;
                this.game.is_show_result = true;

                this.$forceUpdate();

                if (self.$root.user !== null) {
                    await self.$root.getBalance();
                }
            },
            async show_game_over_modal() {
                this.sub_view = 'game_over';
            },
            async show_winner_modal() {
                this.sub_view = 'winner';
            },
            async close_winner_modal() {
                this.sub_view = '';
            },
            is_live(battle_id) {
                const current_game = this.get_node_game(battle_id);
                if (current_game && current_game.state === 1) {
                    return true;
                }
                return false;
            },
            get_node_game(battle_id) {
                const game = this.games.filter(function (item) {
                    return (item.id === battle_id)
                });
                if (game.length) {
                    return game[0];
                }
                return false;
            },
            recount_rounds_items_recount(round) {
                const self = this;
                if (parseInt(round) === 1) {
                    return false;
                }
                self.game.win_items = [];
                $.each(self.game.users, function (index, u) {
                    u.items = [];
                    u.final_price = 0;
                });
                $.each(this.game.rounds, function (idx, item) {
                    if (parseInt(item.round_number) >= parseInt(round)) {
                        return false;
                    }
                    $.each(self.game.users, function (index, u) {
                        if (parseInt(item.user_id) === parseInt(u.id)) {
                            u.items.push(item);
                            self.game.win_items.push(item);
                            u.final_price = parseFloat(u.final_price) + parseFloat(item.win_price);
                        }
                    });
                });
            },
            async itemSell(id, position) {
                await this.sell(id);
                this.game.win_price -= this.game.win_items[position].item.price;

                const array = this.game.win_items;
                array.splice(position, 1);

                this.game.win_items = array;

                if (this.game.win_items.length === 0) this.close_winner_modal();
            },
            async allSell() {
                let ids = [];
                for (let i in this.game.win_items) {
                    ids.push(this.game.win_items[i].item.open_id);
                }
                await this.sell(ids);
                await this.close_winner_modal();
            },
            async sell(id) {
                const request = await axios.post('/api/users/sell', {id: id});
                const data = request.data;

                this.$root.getBalance();

                $.wnoty({
                    type: data.type,
                    message: this.$t(`battles.battle.${data.message}`)
                });
            },
            async declineBattle() {
                const request = await axios.post('/api/battles/decline', {id: this.$route.params.id});
                const data = request.data;

                this.$root.getBalance();

                if (data.type !== 'success') {
                    $.wnoty({
                        type: data.type,
                        message: this.$t(`battles.battle.${data.message}`)
                    });
                }
            }
        },
        created() {
            this.$socket.emit('getBattles');
        },
        mounted() {
            const self = this;
            this.$root.showLoading();
            this.getBattle();
        },
        sockets: {
            battleJoin: function (data) {
                if (parseInt(data.battle_id) === parseInt(this.$route.params.id)) {
                    this.setBattle(data.battle.battle);
                }
            },
            battles: function (battles) {
                this.games = battles;
            },
            battleDelete: function (id) {
                if (parseInt(id) === parseInt(this.$route.params.id)) {
                    $.wnoty({
                        type: 'info',
                        message: this.$t('battles.battle.deleted')
                    });
                    this.$router.go(-1);
                }
            }
        }
    }
</script>