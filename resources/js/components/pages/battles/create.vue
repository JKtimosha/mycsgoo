<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"></div>
        </div>
        <div class="contracts__header">
            <span class="contracts__header__text">{{ $t('battles.create.head') }}</span>
        </div>
        <div class="content casebattle" id="casebattle">
            <div class="casebattle-bar">
                <div class="casebattle-bar-button">
                    <router-link :to="{name: 'battles'}" class="casebattle-button blue active">
                        <span>
                            {{ $t('battles.create.active') }}
                        </span>
                    </router-link>
                    <router-link :to="{name: 'battles.history'}" class="casebattle-button velvet">
                        <span>
                           {{ $t('battles.create.head') }}
                        </span>
                    </router-link>
                </div>
                <div class="casebattle-bar-create">
                    <router-link :to="{name: 'battles.create'}" class="casebattle-create-button">
                        <span>
                            {{ $t('battles.create.head') }}
                        </span>
                    </router-link>
                </div>
            </div>
            <div class="main-width" style="width: 1500px">
                <div class="modal-hellcase " :class="{'show': showModal}">
                    <div class="modal-background"></div>
                    <div class="modal-card is-large animated fadeInDown faster">
                        <header class="modal-card-head"><p class="modal-card-title">{{ $t('battles.create.addCases') }}</p> <a
                                v-on:click="closeModal" style="transition: fill .3s ease;fill: #fff;" aria-label="close"
                                class="close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 36 36"
                                 class="styles_closeIcon__1QwbI">
                                <path d="M28.5 9.62L26.38 7.5 18 15.88 9.62 7.5 7.5 9.62 15.88 18 7.5 26.38l2.12 2.12L18 20.12l8.38 8.38 2.12-2.12L20.12 18z"></path>
                            </svg>
                        </a></header>
                        <section class="modal-card-body">
                            <div class="modal-hellcase-status-bar">
                                <div>{{ $t('battles.create.addedCases') }} <span>{{ selectedCases }} </span></div>
                                <div v-if="$i18n.locale === 'ru'">{{ $t('battles.create.fullPrice') }}: {{ priceCases }}Р</div>
                                <div v-else>{{ $t('battles.create.fullPrice') }}: {{ priceCases }}$</div>
                            </div>
                            <div class="caselist-select">
                                <div v-for="(box, i) in loadedCases" class="caselist-case"
                                     :class="{'active': loadedCases[i].selected >= 1}">
                                    <div class="caselist-case-bg">
                                        <img :src="box.image"
                                             :alt="box.name"></div>
                                    <div class="case-title">{{ box.name }}</div>
                                    <div v-if="$i18n.locale === 'ru'" class="case-price">{{ box.price }}Р</div>
                                    <div v-else class="case-price">{{ box.price_en }}$</div>
                                    <div class="case-action">
                                        <a v-if="loadedCases[i].selected >= 1" class="close" v-on:click="deleteCase(i)"><i
                                                class="fal fa-times"></i></a>
                                        <div v-if="loadedCases[i].selected >= 1" class="case-counter">
                                            <a v-on:click="removeCase(i)"><i aria-hidden="true"
                                                                             class="fal fa-minus"></i></a>
                                            <span>{{ loadedCases[i].selected }}</span>
                                            <a v-on:click="addCase(i)"><i aria-hidden="true"
                                                                          class="fal fa-plus"></i></a>
                                        </div>
                                        <a v-if="typeof loadedCases[i].selected === 'undefined' || loadedCases[i].selected === 0"
                                           class="case-btn-add" v-on:click="addCase(i)"><span>{{ $t('battles.create.addCase') }}</span></a>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <footer class="modal-card-foot">
                            <a v-on:click="addCases" class="casebattle-create-button round">
                                <span v-if="$i18n.locale === 'ru'"> {{ $t('battles.create.add') }} {{ priceCases }}Р</span>
                                <span v-else> {{ $t('battles.create.add') }} {{ priceCases }}$</span>
                            </a>
                        </footer>
                    </div>
                </div>
                <div class="casebattle-breadcrumb">
                    <div class="casebattle-breadcrumb-left">
                        <a v-on:click="$router.go(-1)"><i aria-hidden="true" class="fa fa-angle-left"></i> {{ $t('battles.create.back') }}</a>
                    </div>
                    <div class="casebattle-breadcrumb-round">
                        <span class="casebattle-round-text-left">{{ $t('battles.create.rounds') }}</span>
                        <div class="casebattle-round-number"><span>{{ rounds }}</span></div>
                    </div>
                    <div class="casebattle-breadcrumb-right">
                        <span v-if="$i18n.locale === 'ru'">
                            {{ $t('battles.create.fullPrice') }} {{ allPrice }}Р
                        </span>
                        <span v-else>
                            {{ $t('battles.create.rounds') }} {{ allPrice }}$
                        </span>
                    </div>
                </div>
                <div class="casebattle-selected-cases">
                    <div v-for="(box, i) in selected" class="casebattle-selected-case bg-green">
                        <a class="close" v-on:click="deleteAddedCase(i)">
                            <i aria-hidden="true" class="fal fa-times"></i>
                        </a>
                        <div class="casebattle-case-image">
                            <img :src="box.image" alt="">
                        </div>
                        <div class="casebattle-case-name">
                            {{ box.name }}
                        </div>
                        <div v-if="$i18n.locale === 'ru'" class="casebattle-case-price">
                            {{ box.price }}Р
                        </div>
                        <div v-else class="casebattle-case-price">
                            {{ box.price_en }}$
                        </div>
                        <div class="casebattle-count-action">
                            <a v-on:click="removeAddedCase(i)">
                                <i aria-hidden="true" class="fal fa-angle-left"></i>
                            </a>
                            <span>{{ box.selected }}</span>
                            <a v-on:click="addAddedCase(i)">
                                <i aria-hidden="true" class="fal fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                    <a v-if="unicCases < 10" v-on:click="openModal" class="casebattle-selected-case empty">
                        <div class="plus">+</div>
                        <span>{{ $t('battles.create.addCase') }}</span>
                    </a>
                </div>
                <div class="casebattle-footer-action">
                    <div class="casebattle-create-footer-left">
                        <div class="players-number"><h3>{{ $t('battles.create.users') }}</h3>
                            <ul>
                                <li :class="{'active': players === 2}">
                                    <a v-on:click="players = 2"><span>2 <i>{{ $t('battles.create.user') }}</i></span></a>
                                </li>
                                <li :class="{'active': players === 3}">
                                    <a v-on:click="players = 3" class="up"><span>3 <i>{{ $t('battles.create.user') }}</i></span></a>
                                </li>
                                <li :class="{'active': players === 4}">
                                    <a v-on:click="players = 4" class="up"><span>4 <i>{{ $t('battles.create.user') }}</i></span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="cb-privacy"><h3>{{ $t('battles.create.privateDescr') }}</h3> <span>{{ $t('battles.create.type') }}</span>
                            <div class="cb-privacy-status">
                                <button v-on:click="type = 'private'" type="button" :class="{'active': type === 'private'}">{{ $t('battles.create.public') }}</button>
                                <button v-on:click="type = 'public'" type="button" :class="{'active': type === 'public'}">{{ $t('battles.create.private') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="casebattle-create-footer-right">
                        <div class="casebattle-create-footer-cost"><h3>{{ $t('battles.create.priceDescr') }}</h3> <span>{{ $t('battles.create.sum') }}</span>
                            <div v-if="$i18n.locale === 'ru'">{{ allPrice }}Р</div>
                            <div v-else>{{ allPrice }}$</div>
                        </div>
                        <div class="casebattle-create-footer-button">
                            <a class="casebattle-create-button" v-on:click="createBattle" :class="{'disabled': !((players >= 2 && players <= 4) && rounds > 0 && allPrice > 0 && (type === 'public' || type === 'private') && $root.user !== null && (parseFloat($root.user.balance) >= allPrice))}"><span>{{ $t('battles.create.create') }}</span></a>
                        </div>
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
                showModal: false,
                loadedCases: [],
                selected: [],
                unicCases: 0,
                selectedCases: 0,
                priceCases: 0,
                blockAddCases: false,
                rounds: 0,
                allPrice: 0,
                players: 2,
                type: 'public',
                create: false
            }
        },
        methods: {
            async openModal() {
                this.showModal = true;
            },
            async closeModal() {
                this.showModal = false;
            },
            async loadCases() {
                const request = await axios.post('/api/battles/loadCases');

                this.loadedCases = request.data;
            },
            async addCase(i) {
                if (typeof this.loadedCases[i].selected !== "undefined" && this.loadedCases[i].selected === 0) {
                    if (this.blockAddCases) {
                        return $.wnoty({
                            type: 'error',
                            message: this.$t('battles.create.unic')
                        });
                    }
                }
                if (typeof this.loadedCases[i].selected !== "undefined") {
                    if (this.loadedCases[i].selected === 50) {
                        return $.wnoty({
                            type: 'error',
                            message: this.$t('battles.create.max')
                        });
                    }
                    this.loadedCases[i].selected += 1;
                } else {
                    if (this.blockAddCases) {
                        return $.wnoty({
                            type: 'error',
                            message: this.$t('battles.create.unic')
                        });
                    }
                    this.loadedCases[i].selected = 1;
                }

                this.recountSelectedCases();
                this.$forceUpdate();
            },
            async removeCase(i) {
                this.loadedCases[i].selected -= 1;
                this.recountSelectedCases();
                this.$forceUpdate();
            },
            async deleteCase(i) {
                this.loadedCases[i].selected = 0;
                this.recountSelectedCases();
                this.$forceUpdate();
            },
            async recountSelectedCases() {
                this.selectedCases = 0;
                this.priceCases = 0;
                this.unicCases = 0;
                for (let i in this.loadedCases) {
                    if (typeof this.loadedCases[i].selected !== "undefined" && this.loadedCases[i].selected >= 1) {
                        this.unicCases++;
                        if (this.unicCases === 10) {
                            this.blockAddCases = true;
                        } else {
                            this.blockAddCases = false;
                        }
                        this.selectedCases += this.loadedCases[i].selected;
                        if (this.$i18n.locale === 'ru') {
                            this.priceCases += (this.loadedCases[i].selected * this.loadedCases[i].price);
                        } else {
                            this.priceCases += (this.loadedCases[i].selected * this.loadedCases[i].price_en);
                            this.priceCases = this.priceCases.toFixed(2);
                        }
                    }
                }
            },
            async addCases() {
                this.selected = [];

                for (let i in this.loadedCases) {
                    if (typeof this.loadedCases[i].selected !== "undefined" && this.loadedCases[i].selected >= 1) {
                        const box = this.loadedCases[i];
                        box.lastID = i;
                        this.selected.push(box);
                    }
                }

                this.allPrice = this.priceCases;
                this.rounds = this.selectedCases;

                this.closeModal();
                this.$forceUpdate();
            },
            async addAddedCase(i) {
                if (this.selected[i].selected === 50) {
                    return $.wnoty({
                        type: 'error',
                        message: this.$t('battles.create.max')
                    });
                }
                this.selected[i].selected += 1;

                this.recountSelectCases();
                this.$forceUpdate();
            },
            async removeAddedCase(i) {
                if (this.selected[i].selected === 1) return;
                this.selected[i].selected -= 1;

                this.recountSelectCases();
                this.$forceUpdate();
            },
            async deleteAddedCase(i) {
                this.loadedCases[this.selected[i].lastID].selected = 0;
                this.selected.splice(i, 1);

                this.recountSelectCases();
                this.$forceUpdate();
            },
            async recountSelectCases() {
                this.selectedCases = 0;
                this.priceCases = 0;
                this.unicCases = 0;
                this.rounds = 0;
                this.allPrice = 0;
                for (let i in this.selected) {
                    if (typeof this.selected[i].selected !== "undefined" && this.selected[i].selected >= 1) {
                        this.unicCases++;
                        if (this.unicCases === 10) {
                            this.blockAddCases = true;
                        } else {
                            this.blockAddCases = false;
                        }
                        this.selectedCases += this.selected[i].selected;
                        this.rounds += this.selected[i].selected;

                        if (this.$i18n.locale === 'ru') {
                            this.priceCases += (this.selected[i].selected * this.selected[i].price);
                            this.allPrice += (this.selected[i].selected * this.selected[i].price);
                        } else {
                            this.priceCases += parseFloat(this.selected[i].selected * this.selected[i].price_en);
                            this.allPrice += parseFloat(this.selected[i].selected * this.selected[i].price_en);

                            this.priceCases = this.priceCases.toFixed(2);
                            this.allPrice = this.allPrice.toFixed(2);
                        }
                    }
                }
            },
            async createBattle() {
                if (this.create) return;
                if (!((this.players >= 2 && this.players <= 4) && this.rounds > 0 && this.allPrice > 0 && (this.type === 'public' || this.type === 'private') && this.$root.user !== null && (parseFloat(this.$root.user.balance) >= this.allPrice))) return;
                this.create = true;

                const request = await axios.post('/api/battles/create', {
                    players: this.players,
                    type: this.type,
                    cases: this.selected
                }), data = request.data;

                if (data.success) {
                    this.$root.getBalance();
                    $.wnoty({
                        type: 'success',
                        message: this.$t(`battles.create.created`)
                    });
                    this.$router.replace({
                        name: 'battle',
                        params: {
                            id: data.id
                        }
                    });
                } else {
                    $.wnoty({
                        type: 'error',
                        message: this.$t(`battles.create.${data.message}`)
                    });
                    this.create = false;
                }
            }
        },
        mounted() {
            const vm = this;

            $('body').click(function (event) {
                if ($(event.target).is('.modal-background')) {
                    vm.closeModal();
                }
            });

            this.loadCases();
        }
    }
</script>