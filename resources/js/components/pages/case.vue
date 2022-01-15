<template>
    <div>
        <div v-if="!ready && !showPrize">
            <div class="branding">
                <div class="main-width clear"></div>
            </div>
            <div class="section-case">
                <div class="main-width clear">
                    <div class="section-case__heading">
                        <div class="name">{{ $t('box.head') }}</div>
                        <div v-if="$i18n.locale === 'ru'" class="section-case__desc covert">{{ box.name }}</div>
                        <div v-else class="section-case__desc covert">{{ box.name_en }}</div>
                    </div>
                    <div class="one-case">
                        <div class="image"><img :src="box.image" :alt="box.name"></div>
                        <div>
                            <div v-if="$root.user === null" class="actions">
                                <a href="/auth/steam" class="btn red">
                                    <div class="ico-error"></div>
                                    {{ $t('box.auth') }}
                                </a>
                            </div>
                            <div v-else-if="!box.active" class="actions">
                                <div class="message-error">
                                    <div class="ico-error"></div>
                                    <div class="align">{{ $t('box.active') }}</div>
                                </div>
                            </div>
                            <div v-else-if="$i18n.locale === 'ru' && box.price > $root.user.balance && box.type !== 'free'" class="actions">
                                <div class="info">{{ $t('box.cost') }}:
                                    <div class="price" v-if="$i18n.locale === 'ru'">{{ box.price }} Р</div>
                                    <div class="price" v-else>{{ box.price_en }} $</div>
                                </div>
                                <div class="message-error">
                                    <div class="ico-error"></div>
                                    <div class="align">{{ $t('box.non_price') }}</div>
                                </div>
                            </div>
                            <div v-else-if="$i18n.locale !== 'ru' && box.price_en > parseFloat($root.user.balance) && box.type !== 'free'" class="actions">
                                <div class="info">{{ $t('box.cost') }}:
                                    <div class="price" v-if="$i18n.locale === 'ru'">{{ box.price }} Р</div>
                                    <div class="price" v-else>{{ box.price_en }} $</div>
                                </div>
                                <div class="message-error">
                                    <div class="ico-error"></div>
                                    <div class="align">{{ $t('box.non_price') }}</div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="info" v-if="box.type !== 'free'">
                                    {{ $t('box.cost') }}:
                                    <div class="price">
                                        {{ price }} <span v-if="$i18n.locale === 'ru'">Р</span><span v-else>$</span>
                                    </div>
                                </div>
                                <div class="info" v-if="box.type === 'free'" style="width: 75%;padding-left: 25%;">
                                    {{ $t('box.free_1') }} {{ $root.config.free }} {{ $t('box.free_2') }}
                                </div>
                                <div v-if="box.type !== 'free'" class="case-controls">
                                    <ul class="case-controls__list">
                                        <li class="case-controls__item">
                                            <input
                                                    type="radio"
                                                    name="toOpen"
                                                    id="radioToOpen1"
                                                    class="case-controls__input"
                                                    value="1"
                                                    v-on:change="selectMethod(1)"
                                                    :checked="selectedMethod === 1"
                                            />
                                            <label for="radioToOpen1" class="case-controls__label">
                                                x1
                                            </label>
                                        </li>
                                        <li class="case-controls__item">
                                            <input
                                                    type="radio"
                                                    name="toOpen"
                                                    id="radioToOpen2"
                                                    class="case-controls__input"
                                                    value="2"
                                                    v-on:change="selectMethod(2)"
                                                    :checked="selectedMethod === 2"
                                            />
                                            <label for="radioToOpen2" class="case-controls__label">
                                                x2
                                            </label>
                                        </li>
                                        <li class="case-controls__item">
                                            <input
                                                    type="radio"
                                                    name="toOpen"
                                                    id="radioToOpen3"
                                                    class="case-controls__input"
                                                    value="3"
                                                    v-on:change="selectMethod(3)"
                                                    :checked="selectedMethod === 3"
                                            />
                                            <label for="radioToOpen3" class="case-controls__label">
                                                x3
                                            </label>
                                        </li>
                                        <li class="case-controls__item">
                                            <input
                                                    type="radio"
                                                    name="toOpen"
                                                    id="radioToOpen4"
                                                    class="case-controls__input"
                                                    value="4"
                                                    v-on:change="selectMethod(4)"
                                                    :checked="selectedMethod === 4"
                                            />
                                            <label for="radioToOpen4" class="case-controls__label">
                                                x4
                                            </label>
                                        </li>
                                        <li class="case-controls__item">
                                            <input
                                                    type="radio"
                                                    name="toOpen"
                                                    id="radioToOpen5"
                                                    class="case-controls__input"
                                                    value="5"
                                                    v-on:change="selectMethod(5)"
                                                    :checked="selectedMethod === 5"
                                            />
                                            <label for="radioToOpen5" class="case-controls__label">
                                                x5
                                            </label>
                                        </li>
                                        <li class="case-controls__item">
                                            <input
                                                    type="radio"
                                                    name="toOpen"
                                                    id="radioToOpen10"
                                                    class="case-controls__input"
                                                    value="10"
                                                    v-on:change="selectMethod(10)"
                                                    :checked="selectedMethod === 10"
                                            />
                                            <label for="radioToOpen10" class="case-controls__label">
                                                x10
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="case-actions">
                                    <button class="case-actions__btn" v-on:click="open('default')">
                                        {{ $t('box.open_default') }}
                                    </button>
                                    <button class="case-actions__btn case-actions__btn--fast" v-on:click="open('fast')">
                                        {{ $t('box.open_fast') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="main-width">
                    <div class="case-items clear">
                        <div class="heading">{{ $t('box.items_desc') }}:</div>
                        <div class="list clear">
                            <div v-for="item in items" :key="item.key" :class="'item '+item.style">
                                <div class="image"><img
                                        :src="'https://steamcdn.io/economy/image/'+item.image+'/120fx120f/image.png'"
                                        :alt="item.name"/></div>
                                <div class="name" v-if="$i18n.locale === 'ru'">
                                    <div class="name-text">
                                        {{ item.name_first }}
                                    </div>
                                    <div class="name-text">
                                        {{ item.name_second }}
                                    </div>
                                </div>
                                <div class="name" v-else>
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
                </div>
            </div>
        </div>
        <div v-show="ready && !showPrize">
            <div>
                <div class="games">
                    <div class="section-case__heading section-case__heading--game">
                        <div class="name">{{ $t('box.head') }}</div>
                        <div v-if="$i18n.locale === 'ru'" class="section-case__desc covert">{{ box.name }}</div>
                        <div v-else class="section-case__desc covert">{{ box.name_en }}</div>
                    </div>
                    <div class="games__inner--list">
                        <div v-for="(game, i) in newItems" class="game">
                            <div class="list">
                                <div class="list__inner">
                                    <div class="overview" :id="'roll-'+i">
                                        <div v-for="item in newItems[i].content">
                                            <div :class="'item '+item.style">
                                                <div class="image"><img
                                                        :src="'https://steamcdn.io/economy/image/'+item.image+'/120fx120f/image.png'"
                                                        :alt="item.market_hash_name"/></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="game-info">
                    <div class="game-info__text">
                        {{ $t('box.open') }}
                    </div>
                </div>

                <div class="main-width">
                    <div class="case-items clear">
                        <div class="heading">{{ $t('box.items_desc') }}:</div>
                        <div class="list clear">
                            <div v-for="item in items" :key="item.key" :class="'item '+item.style">
                                <div class="image"><img
                                        :src="'https://steamcdn.io/economy/image/'+item.image+'/120fx120f/image.png'"
                                        :alt="item.name"/></div>
                                <div class="name" v-if="$i18n.locale === 'ru'">
                                    <div class="name-text">
                                        {{ item.name_first }}
                                    </div>
                                    <div class="name-text">
                                        {{ item.name_second }}
                                    </div>
                                </div>
                                <div class="name" v-else>
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
                </div>

            </div>
        </div>
        <div v-if="showPrize">
            <div class="games">
                <div class="section-case__heading section-case__heading--game">
                    <div class="name">{{ $t('box.head') }}</div>
                    <div v-if="$i18n.locale === 'ru'" class="section-case__desc covert">{{ box.name }}</div>
                    <div v-else class="section-case__desc covert">{{ box.name_en }}</div>
                </div>
                <div v-bind:class="{'prizes': !isMultiple, 'prizes--multiple': isMultiple}">
                    <div v-for="(item, i) in newItems" class="prizes__item">
                        <div :class="'prize prize--'+item.drop.style">
                            <div class="prize__circle">
                                <div class="prize__content">
                                    <div class="prize__image-wrapper">
                                        <img :src="'https://steamcdn.io/economy/image/'+item.drop.image+'/300fx300f/image.png'"
                                             alt=""/>
                                    </div>
                                </div>
                            </div>
                            <div v-if="$i18n.locale === 'ru'">
                                <div class="prize__name">
                                    {{ item.drop.name_first }}
                                </div>
                                <div class="prize__desc">
                                    {{ item.drop.name_second }}
                                </div>
                            </div>
                            <div v-else>
                                <div class="prize__name">
                                    {{ item.drop.name_first_en }}
                                </div>
                                <div class="prize__desc">
                                    {{ item.drop.name_second_en }}
                                </div>
                            </div>
                            <div v-if="isMultiple" class="prizes-buttons prizes-buttons--item">
                                <div class="prizes-buttons__btn-wrapper">
                                    <button v-if="$i18n.locale === 'ru'" class="prizes-buttons__btn prizes-buttons__btn--sell"
                                            v-on:click="multipleSell(item.id, i)">
                                        {{ $t('box.sell_for') }} {{ item.drop.price }} Р
                                    </button>
                                    <button v-else class="prizes-buttons__btn prizes-buttons__btn--sell"
                                            v-on:click="multipleSell(item.id, i)">
                                        {{ $t('box.sell_for') }} {{ item.drop.price_en }} $
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="prizes-buttons">
                    <div class="prizes-buttons__btn-wrapper">
                        <button class="prizes-buttons__btn prizes-buttons__btn--again" v-on:click="refresh">
                            {{ $t('box.refresh_case') }}
                        </button>
                    </div>
                    <div class="prizes-buttons__btn-wrapper">
                        <button v-if="$i18n.locale === 'ru'" class="prizes-buttons__btn prizes-buttons__btn--sell" v-on:click="sell">
                            {{ $t('box.sell_for') }} {{ fullPrice }} Р
                        </button>
                        <button v-else class="prizes-buttons__btn prizes-buttons__btn--sell" v-on:click="sell">
                            {{ $t('box.sell_for') }} {{ fullPrice }} $
                        </button>
                    </div>
                </div>
                <div class="games__text">
                    <span v-if="isMultiple">{{ $t('box.items') }}</span><span v-else>{{ $t('box.item') }}</span> {{ $t('box.sell_desc') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import {TweenLite, Power2, CSSPlugin} from 'gsap';

    export default {
        data() {
            return {
                box: [],
                price: 0,
                items: [],
                selectedMethod: 1,
                audio: {
                    ready: false,
                    scroll: null,
                    open: null
                },
                game: {
                    animationDuration: 10,
                    itemWidth: 118,
                    padding: 6,
                    clearance: 8,
                    startOffset: 3
                },
                currentPosition: {
                    x: 0,
                    prevX: 0
                },
                newItems: [],
                ready: false,
                fastOpen: false,
                animation: null,
                showPrize: false,
                isMultiple: false,
                fullPrice: 0
            }
        },
        methods: {
            async get() {
                const request = await axios.post('/api/cases/one', {name_url: this.$route.params.name});

                if (request.data.success) {
                    this.box = request.data.box;
                    this.items = request.data.items;

                    if (this.$i18n.locale === 'ru') {
                        this.price = this.box.price;
                    } else {
                        this.price = this.box.price_en;
                    }

                    this.$root.hideLoading();
                } else {
                    this.$router.go(-1);
                }
            },
            async selectMethod(number) {
                this.selectedMethod = number;
                if (this.$i18n.locale === 'ru') {
                    this.price = this.box.price * number;
                } else {
                    this.price = (this.box.price_en * number).toFixed(2);
                }
            },
            async setAudio() {
                this.audio.scroll = new Audio();
                this.audio.scroll.src = '/audio/case_scroll.mp3';
                this.audio.scroll.volume = .2;
            },
            async getRandomVal(min, max) {
                return Math.random() * (max - min) + min;
            },
            getRandomItem() {
                return this.items[Math.floor(Math.random() * this.items.length)]
            },
            setGames(destinationCeil) {
                let containers = [];

                this.newItems.forEach((game, i) => {
                    for (let i = 0; i < destinationCeil + 5; i++) {
                        const randomItem = this.getRandomItem();

                        game.content.push(randomItem);
                    }

                    game.content[destinationCeil] = game.drop;
                    containers.push($(`#roll-${i}`));
                });

                containers.unshift(this.currentPosition);

                return containers;
            },
            checkPlaySound() {
                const game = this.game;
                const currPosition = Math.floor(-1 * (this.currentPosition.x + game.itemWidth / 2) / (game.itemWidth + game.padding));

                if (currPosition !== this.currentPosition.prevX) {
                    this.currentPosition.prevX = currPosition;
                    this.playScroll();
                }
            },
            playScroll() {
                this.audio.scroll.currentTime = 0;
                this.audio.scroll.play();
            },
            async open(type) {
                const request = await axios.post('/api/cases/open', {
                    id: this.box.id,
                    count: this.selectedMethod,
                    type: type
                });
                const data = request.data;

                if (!data.success) return $.wnoty({type: 'error', message: this.$t(`box.${data.message}`)});

                this.newItems = data.data.map((item, i) => ({
                    content: [],
                    id: item.id,
                    drop: item.item,
                    node: null
                }));

                await this.$root.getBalance();
                this.audio.open.play();

                if (type === 'default') {
                    this.setAudio();

                    const destinationCeil = Math.floor(await this.getRandomVal(30, 45));
                    let destinationPosition = -1 * ((destinationCeil - this.game.startOffset) * (this.game.itemWidth + this.game.padding)) + this.game.itemWidth / 2;
                    destinationPosition -= await this.getRandomVal(this.game.clearance, this.game.itemWidth - this.game.clearance);

                    const containers = this.setGames(destinationCeil);

                    this.ready = true;
                    this.animation = TweenLite.to(containers, this.game.animationDuration, {
                        x: destinationPosition,
                        force3D: true,
                        onComplete: () => {
                            this.setPrize();
                        },
                        onUpdate: this.checkPlaySound,
                        ease: Power2.easeOut
                    });
                } else {
                    this.setPrize();
                }
            },
            setPrize() {
                const audio = new Audio();
                audio.src = '/audio/case_reveal.mp3';
                audio.volume = .2;

                audio.play();

                if (this.newItems.length > 1) this.isMultiple = true;

                if (!this.isMultiple) {
                    const item = this.newItems[0]['drop'];

                    if (this.$i18n.locale === 'ru') {
                        this.fullPrice = item.price.toFixed(2);
                    } else {
                        this.fullPrice = item.price_en.toFixed(2);
                    }
                } else {
                    if (this.$i18n.locale === 'ru') {
                        this.fullPrice = parseFloat(this.newItems.reduce((sum, current) => sum + current.drop.price, 0).toFixed(2));
                    } else {
                        this.fullPrice = parseFloat(this.newItems.reduce((sum, current) => sum + current.drop.price_en, 0).toFixed(2));
                    }
                }

                this.showPrize = true;
            },
            refresh() {
                this.audio.ready = false;
                this.audio.scroll = false;
                this.game = {
                    animationDuration: 10,
                    itemWidth: 118,
                    padding: 6,
                    clearance: 8,
                    startOffset: 3
                };
                this.currentPosition = {
                    x: 0,
                    prevX: 0
                };
                this.newItems = [];
                this.ready = false;
                this.fastOpen = false;
                this.animation = null;
                this.showPrize = false;
                this.isMultiple = false;
                this.fullPrice = 0;
            },
            multipleSell(id, position) {
                this.sellItem(id);

                const array = this.newItems;
                array.splice(position, 1);

                this.newItems = array;

                if (this.newItems.length === 0) this.refresh();
            },
            sell() {
                if (!this.isMultiple) {
                    this.sellItem(this.newItems[0].id);
                } else {
                    this.sellItem(this.newItems.map(order => order.id));
                }

                this.refresh();
            },
            async sellItem(id) {
                const request = await axios.post('/api/users/sell', {id: id});
                const data = request.data;

                this.$root.getBalance();

                $.wnoty({
                    type: data.type,
                    message: this.$t(`box.${data.message}`)
                });
            }
        },
        mounted() {
            this.audio.open = new Audio();
            this.audio.open.src = '/audio/case_open.mp3';
            this.audio.open.volume = .2;

            this.$root.showLoading();
            this.get();
        },
        destroyed() {
            if (this.animation !== null) {
                this.animation.kill();
            }
        }
    }
</script>
