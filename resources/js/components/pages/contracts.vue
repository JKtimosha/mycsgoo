<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"></div>
        </div>
        <div class="contracts__header">
            <span class="contracts__header__text">{{ $t('contracts.head') }}</span>
        </div>
        <div class="contracts__container">
            <div class="contracts__block" v-if="!showPrize">
                <div class="contracts__block__slots">
                    <div v-for="(slot, i) in contracts.slots" class="contracts__block__slots__slot">
                        <div v-if="slot.type === 'empty'" class="empty">
                            <span class="value">{{ i+1 }}</span>
                            <span class="text">{{ $t('contracts.empty') }}</span>
                        </div>
                        <div v-else>
                            <div class="market__pic-wrapper"><img
                                    :src="'//steamcommunity-a.akamaihd.net/economy/image/'+slot.item.image+'/250fx115f'"
                                    alt="" class="market__pic"></div>
                            <div v-if="$i18n.locale === 'ru'" class="market__bottom"><span
                                    class="market__name">{{ slot.item.name_first }}</span><span
                                    class="market__description">{{ slot.item.name_second }}</span></div>
                            <div v-else class="market__bottom"><span
                                    class="market__name">{{ slot.item.name_first_en }}</span><span
                                    class="market__description">{{ slot.item.name_second_en }}</span></div>
                            <span v-if="$i18n.locale === 'ru'" class="market__price-contracts">{{ slot.item.price }}Р</span>
                            <span v-else class="market__price-contracts">{{ slot.item.price_en }}$</span>
                        </div>
                    </div>
                </div>
                <div class="contracts__block__info">
                    <div class="value">
                        {{ contracts.items }}/10 {{ $t('contracts.empty') }}
                        <span v-if="$i18n.locale === 'ru'" class="contracts__block__info__price">{{ contracts.price }}Р</span>
                        <span v-else class="contracts__block__info__price">{{ contracts.price }}$</span>
                    </div>
                    <span class="text">{{ $t('contracts.descr', {min: contracts.min, max: contracts.max}) }}</span>
                    <button :disabled="contracts.items < 3"
                            :style="{'background-image': (contracts.items >= 3) ? 'linear-gradient(87deg, #a3e000 0%, #64ca00 100%)' : ''}"
                            v-on:click="createContract" class="button">{{ $t('contracts.create') }}
                    </button>
                </div>
                <div class="contracts__block__items">
                    <div class="contracts__block__items__header">
                        <span class="text">Ваши предметы</span>
                    </div>
                    <div v-if="this.items.length === 0 && !loadingMore">{{ $t('contracts.emptyItems') }}</div>
                    <div v-else>
                        <div class="case-items profile-items clear">
                            <div class="list clear">
                                <div v-for="(item, i) in items"
                                     :class="'item contract_item '+item.item.style"
                                     :key="i"
                                >
                                    <div class="actions">
                                        <div v-if="$i18n.locale === 'ru'" class="iprice" :style="{'color': item.added ? '#ffab32' : ''}">
                                            {{ item.item.price }} Р
                                        </div>
                                        <div v-else class="iprice" :style="{'color': item.added ? '#ffab32' : ''}">
                                            {{ item.item.price_en }} $
                                        </div>
                                    </div>
                                    <div class="image"><img
                                            :src="'https://steamcdn.io/economy/image/'+item.item.image+'/100fx100f/image.png'"
                                            alt=""/></div>
                                    <div class="name" v-if="$i18n.locale === 'ru'">
                                        <div class="name-text">
                                            {{ item.item.name_first }}
                                        </div>
                                        <div class="name-text">
                                            {{ item.item.name_second }}
                                        </div>
                                    </div>
                                    <div class="name" v-else>
                                        <div class="name-text">
                                            {{ item.item.name_first_en }}
                                        </div>
                                        <div class="name-text">
                                            {{ item.item.name_second_en }}
                                        </div>
                                    </div>
                                    <div class="item-actions">
                                        <div class="item-actions__btn-wrapper">
                                            <button v-if="!item.added" class="item-actions__btn item-actions__btn--sell"
                                                    v-on:click="addSkin(i)">
                                                {{ $t('contracts.add') }}
                                            </button>
                                            <button v-if="item.added" class="item-actions__btn item-actions__btn--get"
                                                    v-on:click="removeSkin(i)">
                                                {{ $t('contracts.remove') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-show="loadingMore">
                            <div class="load-more">
                                <div class="spinner">loading...</div>
                            </div>
                        </div>
                        <div v-if="morePage">
                            <div class="load-more">
                                <button class="load-more__btn" v-on:click="loadItems">
                                    {{ $t('contracts.more') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contracts__block" v-if="showPrize">
                <div class="game-wrapper">
                    <div class="game-roulette-wrapper game-roulette-wrapper--contracts">
                        <div class="game-win-item game-win-item--contract">
                            <div class="game-win-item__pic-wrapper">
                                <img :src="winItem.image"
                                    alt="" class="game-win-item__pic">
                            </div>
                            <div class="game-win-item__text">
                                <span v-if="$i18n.locale === 'ru'" class="game-win-item__title">{{ winItem.name }}</span>
                                <span v-else class="game-win-item__title">{{ winItem.name_en }}</span>
                                <span class="game-win-item__description"></span></div>
                        </div>
                    </div>
                </div>
                <div class="game-buttons-group game-buttons-group--contracts">
                    <a v-if="$i18n.locale === 'ru'" v-on:click="sell" class="button--sell">{{ $t('contracts.sellFor') }} {{ winItem.price }}Р</a>
                    <a v-else v-on:click="sell" class="button--sell">{{ $t('contracts.sellFor') }} {{ winItem.price_en }}$</a>
                    <a v-on:click="refresh" class="button--refresh">{{ $t('contracts.newContract') }}</a>
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
                items: [],
                loadingMore: false,
                morePage: false,
                page: 0,
                contracts: {
                    price: 0,
                    min: 0,
                    max: 0,
                    items: 0,
                    slots: []
                },
                create: false,
                showPrize: false,
                winItem: []
            }
        },
        methods: {
            async loadItems() {
                this.loadingMore = true;

                const request = await axios.post('/api/contracts/loadItems', {page: this.page += 1});

                const array = this.items;
                Array.prototype.push.apply(array, request.data.items);

                this.items = array;
                this.morePage = request.data.more;
                this.loadingMore = false;

                this.$root.hideLoading();
            },
            async loadSlots() {
                for (let i = 1; i <= 10; i++) {
                    this.contracts.slots.push({type: 'empty'});
                }
            },
            async addSkin(i) {
                const item = this.items[i];
                let num = -1;

                for (const i in this.contracts.slots) {
                    if (this.contracts.slots[i].type === 'empty') {
                        num = i;
                        break;
                    }
                }

                if (this.contracts.items.length === 10 || num === -1) {
                    return $.wnoty({
                        type: 'error',
                        message: this.$t('contracts.fill')
                    })
                }

                if (this.items[i].added) {
                    return $.wnoty({
                        type: 'error',
                        message: this.$t('contracts.exists')
                    })
                }

                item.type = 'slot';
                this.contracts.slots[num] = item;

                this.reCount();

                this.items[i].added = true;
                this.$forceUpdate();
            },
            async removeSkin(i) {
                const item = this.items[i];

                let num = -1;

                for (const i in this.contracts.slots) {
                    if (this.contracts.slots[i].type === 'slot' && this.contracts.slots[i].id === item.id) {
                        num = i;
                        break;
                    }
                }

                if (!this.items[i].added || num === -1) {
                    return $.wnoty({
                        type: 'error',
                        message: this.$t('contracts.nonItem')
                    })
                }

                this.contracts.slots[num] = {type: 'empty'};

                this.reCount();

                this.items[i].added = false;
                this.$forceUpdate();
            },
            async reCount() {
                let items = 0, price = 0, min = 0, max = 0;

                for (const i in this.contracts.slots) {
                    if (this.contracts.slots[i].type === 'slot') {
                        items++;
                        if (this.$root.$i18n.locale === 'ru') {
                            price += parseInt(this.contracts.slots[i].item.price);
                        } else {
                            price += this.contracts.slots[i].item.price_en;
                        }
                    }
                }

                if (this.$root.$i18n.locale === 'ru') {
                    min = Math.ceil((price > 0 ? price * 0.1 : 0));
                    max = parseInt((price > 0 ? price * 3 : 0));
                } else {
                    min = (price > 0 ? price * 0.1 : 0);
                    max = (price > 0 ? price * 3 : 0);

                    if (min > 0) min = min.toFixed(2);
                    if (max > 0) max = max.toFixed(1);
                    price = price.toFixed(2);
                }

                this.contracts.items = items, this.contracts.price = price, this.contracts.min = min, this.contracts.max = max;
            },
            async createContract() {
                if (this.create) return;
                this.create = true;

                if (this.contracts.items < 3) {
                    return $.wnoty({
                        type: 'error',
                        message: this.$t('contracts.min')
                    });
                }

                const request = await axios.post('/api/contracts/create', {slots: this.contracts.slots}),
                    data = request.data;

                if (data.success) {
                    this.winItem = data.data;
                    this.showPrize = true;
                } else {
                    $.wnoty({
                        type: 'error',
                        message: this.$t(`contracts.${data.message}`)
                    });
                    this.create = false;
                }
            },
            async sell() {
                if (this.showPrize && this.winItem !== []) {
                    const request = await axios.post('/api/users/sell', {id: this.winItem.id});
                    const data = request.data;

                    this.$root.getBalance();

                    $.wnoty({
                        type: data.type,
                        message: this.$t(`contracts.${data.message}`)
                    });

                    this.refresh();
                }
            },
            async refresh() {
                if (this.showPrize && this.winItem !== []) {
                    this.items = [];
                    this.loadingMore = false;
                    this.morePage = false;
                    this.page = 0;
                    this.contracts = {
                        price: 0,
                        min: 0,
                        max: 0,
                        items: 0,
                        slots: []
                    };
                    this.create = false;
                    this.showPrize = false;
                    this.winItem = [];

                    this.loadSlots();
                    this.loadItems();
                }
            }
        },
        mounted() {
            this.$root.showLoading();

            this.loadItems();
            this.loadSlots();
        }
    }
</script>