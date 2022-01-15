<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"></div>
        </div>
        <div class="contracts__header">
            <span class="contracts__header__text">{{ $t('upgrade.head') }}</span>
        </div>

        <div class="content upg__bg" style="width: 1500px;margin: -270px auto;">
            <div class="row">
                <div class="upg__wrap" ref="winToggle">
                    <div class="upg__top">
                        <div class="upg__selected-skins upg__my-added-skins"
                             :class="{'upg__no-skins': !mySelectedSkin.length}">
                            <div class="upg__skin"
                                 :class="[mySelectedSkin.length ? 'upg-item_'+ mySelectedSkin[0].item.style : '']">
                                <div class="upg__skin-bg"></div>
                                <div class="upg__main">
                                    <div class="upg__thing-main">
                                        <div v-if="!mySelectedSkin.length" class="upg__need-add-skin">
                                            {{ $t('upgrade.selectItem') }}
                                        </div>
                                        <div class="upg__thing-info" v-if="mySelectedSkin.length && $i18n.locale === 'ru'">
                                            <span class="ell">{{ mySelectedSkin[0].item.name_first }}</span>
                                            <span class="ell">{{ mySelectedSkin[0].item.name_second }}</span>
                                        </div>
                                        <div class="upg__thing-info" v-if="mySelectedSkin.length && $i18n.locale !== 'ru'">
                                            <span class="ell">{{ mySelectedSkin[0].item.name_first_en }}</span>
                                            <span class="ell">{{ mySelectedSkin[0].item.name_second_en }}</span>
                                        </div>
                                        <div class="upg__thing-price" style="z-index: 1000;"
                                             v-if="mySelectedSkin.length">
                                            <span v-if="$i18n.locale === 'ru'">
                                                {{ mySelectedSkin[0].item.price }}
                                                Р
                                            </span>
                                            <span v-else >
                                                {{ mySelectedSkin[0].item.price_en }}
                                                $
                                            </span>
                                        </div>
                                    </div>
                                    <div class="upg__reset" v-on:click="resetMySkin"><span>{{ $t('upgrade.reset') }}</span></div>
                                </div>
                                <div class="upg__thing-view" v-if="mySelectedSkin.length">
                                    <button class="upg__skin_remove" v-on:click="resetMySkin"></button>
                                    <img alt="xxxxxx" :src="mySelectedSkin[0].item.images" class="upg__thing-img">
                                </div>
                            </div>
                        </div>
                        <div class="upg__center">
                            <div ref="circlePrize" class="upg__circle-prize"></div>
                            <div ref='circleRoulette' class="upg__circle-roulette">
                                <div ref="circleProgress" class="upg__circle-progress circle">
                                    <div class="upg__circle-stats">
                                        <span class="upg__win-percent">{{upgradeCircle.percentsDisplay}}<i>%</i></span>
                                        <span class="upg__win-text">{{ $t('upgrade.success') }}</span>
                                    </div>

                                    <div ref="circleCursor" class="upg_cursor"></div>
                                </div>
                            </div>
                        </div>
                        <div class="upg__selected-skins upg__can-get"
                             :class="[siteSelectedSkin.length ? 'upg__can-get' : 'upg__no-skins']">
                            <div class="upg__skin"
                                 :class="[siteSelectedSkin.length ? 'upg-item_'+ siteSelectedSkin[0].item.style : '']">
                                <div class="upg__skin-bg"></div>
                                <button class="upg__skin_remove" v-on:click="resetSiteSkin"></button>
                                <img v-if="siteSelectedSkin.length" :src="siteSelectedSkin[0].item.images" alt="#"
                                     class="upg__thing-img"></div>
                            <div class="upg__main">
                                <div class="upg__thing-main">
                                    <div v-if="!siteSelectedSkin.length" class="upg__need-add-skin">
                                        {{ $t('upgrade.selectItem_2') }}
                                    </div>
                                    <div class="upg__thing-info" v-if="siteSelectedSkin.length && $i18n.locale === 'ru'">
                                        <span class="ell">{{ siteSelectedSkin[0].item.name_first }}</span>
                                        <span class="ell">{{ siteSelectedSkin[0].item.name_second }}</span>
                                    </div>
                                    <div class="upg__thing-info" v-if="siteSelectedSkin.length && $i18n.locale !== 'ru'">
                                        <span class="ell">{{ siteSelectedSkin[0].item.name_first_en }}</span>
                                        <span class="ell">{{ siteSelectedSkin[0].item.name_second_en }}</span>
                                    </div>
                                    <div class="upg__thing-price" style="z-index: 1000;" v-if="siteSelectedSkin.length">
                                        <span v-if="$i18n.locale === 'ru'">{{ siteSelectedSkin[0].item.price }} Р</span>
                                        <span v-else>{{ siteSelectedSkin[0].item.price_en }} $</span>
                                    </div>
                                </div>
                                <div class="upg__reset upg__upgrade" v-on:click="upgrade"><span>{{ $t('upgrade.upgrade') }}</span></div>
                                <div class="upg__multiple">
                                    <ul class="upg__multiple-count">
                                        <li rel="x1" v-on:click="setMultiple(1.5)"
                                            :class="{'upg__multiple_active': (selectedMultiple === 1.5)}">1.5x
                                        </li>
                                        <li rel="x2" v-on:click="setMultiple(2)"
                                            :class="{'upg__multiple_active': (selectedMultiple === 2)}">2x
                                        </li>
                                        <li rel="x5" v-on:click="setMultiple(5)"
                                            :class="{'upg__multiple_active': (selectedMultiple === 5)}">5x
                                        </li>
                                        <li rel="x10" v-on:click="setMultiple(10)"
                                            :class="{'upg__multiple_active': (selectedMultiple === 10)}">10x
                                        </li>
                                        <li rel="x20" v-on:click="setMultiple(20)"
                                            :class="{'upg__multiple_active': (selectedMultiple === 20)}">20x
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="upg__wrap">
                    <div class="upg__bottom">
                        <div class="upg__my-thing upg__bg_big">
                            <div class="upg__my-thing_top">
                                <div class="upg__title"><img alt=""
                                                             src="/img/upgrade/your-skins_title.png">
                                </div>
                                <div rel="my_filter" v-on:click="setMyFilter"
                                     v-bind:class="[myFilter === 'asc' ? 'upg__my-filter': 'upg__my-filter upg__my-filter-active']">
                                    <span>{{ $t('upgrade.price') }}</span></div>
                            </div>
                            <div class="upg__my-thing_middle">
                                <div class="upg__empty-noti" :style="{display: myItems.length > 0 ? 'none': 'block'}">
                                    <span>{{ $t('upgrade.empty') }}</span>
                                </div>
                                <div class="upg__my-skins_main">

                                    <div v-for="(item, i) in myItems" :key="i" v-on:click="addSkin(i)"
                                         :class="[mySelectedSkin.length && mySelectedSkin[0].id === item.id ? 'upg__thing upg_viewn_'+item.item.style+' upg__thing_selected': 'upg__thing upg_viewn_'+item.item.style]">
                                        <div class="upg__thing_icons">
                                            <div class="upg__skin_remove">
                                                <div class="upg__skin_bg-status"></div>
                                            </div>
                                        </div>
                                        <div class="upg__add-help">
                                            <div class="upg__help-toolTip"><p>{{ $t('upgrade.selectOne') }}</p></div>
                                        </div>
                                        <span v-if="$i18n.locale === 'ru'" class="upg__thing-price">{{ item.item.price }} Р</span>
                                        <span v-else class="upg__thing-price">{{ item.item.price_en }} $</span><img
                                            :src="item.item.image"
                                            alt="#">
                                        <div v-if="$i18n.locale === 'ru'" class="upg__thing_info"><span
                                                class="ell name">{{ item.item.name_first }}</span> <span
                                                class="ell">{{ item.item.name_second }}</span>
                                        </div>
                                        <div v-else class="upg__thing_info"><span
                                                class="ell name">{{ item.item.name_first_en }}</span> <span
                                                class="ell">{{ item.item.name_second_en }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="upg__thing_bottom">
                                <div class="upg__nav"><span class="upg__nav-prev" v-on:click="prevMyPage"></span> <span
                                        class="upg__nav-current">{{ myPages.current }}</span> <span
                                        class="upg__nav-next" v-on:click="nextMyPage"></span>
                                    <div class="upg__thing-pages"><span v-if="myPages.last > 1">{{ myPages.current }} {{ $t('upgrade.of') }} {{ myPages.last }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="upg__jc-skins upg__bg_big">
                            <div class="upg__title"><img alt="" src="/img/upgrade/jc-skins_bg.png">
                            </div>
                            <div class="upg__search">
                                <div v-on:click="setActiveSearch"
                                     v-bind:class="[activeSearch ? 'switch_search active' : 'switch_search']"></div>
                                <input v-model="siteSearch" :placeholder="$t('upgrade.selectItem')" type="text"
                                       class="upg__search-input">
                                <div v-on:click="setActiveFilters"
                                     v-bind:class="[activeFilters ? 'switch_more-filter active' : 'switch_more-filter']"></div>
                                <div v-if="activeFilters" class="upg__filters">
                                    <div class="upg__filters-quality"><span>{{ $t('upgrade.rarity') }}</span>
                                        <div class="upg__quality-drop">
                                            <ul>
                                                <li v-on:click="setSiteRarity('all')"
                                                    v-bind:class="[siteRarity === 'all' ? 'upg__quality_0 upg__quality_1 selected' : 'upg__quality_0 upg__quality_1']">
                                                    All
                                                </li>
                                                <li v-on:click="setSiteRarity('Ширпотреб')"
                                                    v-bind:class="[siteRarity === 'Ширпотреб' ? 'upg__quality_1 selected' : 'upg__quality_1']">
                                                    Consumer
                                                </li>
                                                <li v-on:click="setSiteRarity('Промышленное качество')"
                                                    v-bind:class="[siteRarity === 'Промышленное качество' ? 'upg__quality_2 selected' : 'upg__quality_2']">
                                                    Industrial
                                                </li>
                                                <li v-on:click="setSiteRarity('Армейское качество')"
                                                    v-bind:class="[siteRarity === 'Армейское качество' ? 'upg__quality_3 selected' : 'upg__quality_3']">
                                                    Milspec
                                                </li>
                                                <li v-on:click="setSiteRarity('Запрещённое')"
                                                    v-bind:class="[siteRarity === 'Запрещённое' ? 'upg__quality_4 selected' : 'upg__quality_4']">
                                                    Restricted
                                                </li>
                                                <li v-on:click="setSiteRarity('Засекреченное')"
                                                    v-bind:class="[siteRarity === 'Засекреченное' ? 'upg__quality_5 selected' : 'upg__quality_5']">
                                                    Classified
                                                </li>
                                                <li v-on:click="setSiteRarity('Тайное')"
                                                    v-bind:class="[siteRarity === 'Тайное' ? 'upg__quality_6 selected' : 'upg__quality_6']">
                                                    Covert
                                                </li>
                                                <li v-on:click="setSiteRarity('★')"
                                                    v-bind:class="[siteRarity === '★' ? 'upg__quality_7 selected' : 'upg__quality_7']">
                                                    Knife
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="upg__filters-money"><span class="upg__filters-title">{{ $t('upgrade.cost') }}</span>
                                        <div class="upg__input"><input v-model="siteFrom" placeholder="от" type="text"
                                                                       class="price-from">
                                        </div>
                                        <span class="upg__seporate"> - </span>
                                        <div class="upg__input"><input v-model="siteTo" placeholder="до" type="text"
                                                                       class="price-until">
                                        </div>
                                        <div v-on:click="setSiteFilter"
                                             v-bind:class="[siteFilter ? 'upg__my-filter upg__my-filter-active' : 'upg__my-filter']">
                                            <span>{{ $t('upgrade.price') }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="upg__jc-thing_middle">
                                <div class="upg__my-skins_main">
                                    <div v-for="(item, i) in siteItems" :key="item.id"
                                         :class="[siteSelectedSkin.length && siteSelectedSkin[0].id === item.id ? 'upg__thing upg_viewn_'+item.item.style+' upg__thing_selected': 'upg__thing upg_viewn_'+item.item.style]"
                                         v-on:click="addSiteSkin(i)">
                                        <div class="upg__thing_icons">
                                            <div class="upg__skin_remove">
                                                <div class="upg__skin_bg-status"></div>
                                            </div>
                                        </div>
                                        <div class="upg__add-help">
                                            <div class="upg__help-toolTip"><p>{{ $t('upgrade.selectOne') }}</p></div>
                                        </div>
                                        <span class="upg__thing-price" v-if="$i18n.locale === 'ru'">{{ item.item.price }} Р</span>
                                        <span class="upg__thing-price" v-else>{{ item.item.price_en }} $</span><img
                                            :src="item.item.image"
                                            alt="#">
                                        <div v-if="$i18n.locale === 'ru'" class="upg__thing_info"><span
                                                class="ell name">{{ item.item.name_first }}</span> <span
                                                class="ell">{{ item.item.name_second }}</span>
                                        </div>
                                        <div v-else class="upg__thing_info"><span
                                                class="ell name">{{ item.item.name_first_en }}</span> <span
                                                class="ell">{{ item.item.name_second_en }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upg__thing_bottom">
                                <div class="upg__nav"><span class="upg__nav-prev" v-on:click="prevSitePage"></span>
                                    <span
                                            class="upg__nav-current">{{ sitePages.current }}</span> <span
                                            class="upg__nav-next" v-on:click="nextSitePage"></span>
                                    <div class="upg__thing-pages"><span v-if="sitePages.last > 1">{{ sitePages.current }} {{ $t('upgrade.of') }} {{ sitePages.last }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    $.fn.animateRotate = function(angle, duration, easing, complete) {
        let args = $.speed(duration, easing, complete);
        let step = args.step;

        return this.each(function(i, e) {
            args.complete = $.proxy(args.complete, e);
            args.step = function(now) {
                $.style(e, 'transform', 'rotate(' + now + 'deg)');
                if(step) return step.apply(e, arguments);
            };

            $({deg: 0}).animate({deg: angle}, args);
        });
    };

    import axios from 'axios';

    export default {
        data() {
            return {
                selectedMultiple: '',
                myFilter: 'asc',
                myPages: {
                    current: 1,
                    last: 1
                },
                myItems: [],
                mySelectedSkin: [],
                activeSearch: false,
                activeFilters: false,
                siteFrom: 0,
                siteTo: 9999999,
                siteFilter: 'asc',
                siteRarity: 'all',
                siteSearch: '',
                sitePages: {
                    current: 1,
                    last: 1
                },
                siteItems: [],
                siteSelectedSkin: [],
                upgradeCircle: {
                    element: null,
                    canvas: null,
                    active: false,
                    progressFn: null,
                    percentsPortion: 0,
                    percentsDisplay: Number(0).toFixed(2),
                },
                activeUpgrade: false
            }
        },
        methods: {
            async setMultiple(multiple) {
                this.selectedMultiple = multiple;
                if (this.mySelectedSkin.length) {
                    const price = this.mySelectedSkin[0].item.price;
                    const request = await axios.post('/api/upgrades/getOneItem', {price: price, multiple: multiple});
                    const data = request.data;
                    if (data.success) {
                        this.siteSelectedSkin = [data.item];
                    } else {
                        $.wnoty({
                            type: 'error',
                            message: this.$t('upgrade.notFound')
                        })
                    }
                }
            },
            async setMyFilter() {
                if (this.myFilter === 'desc') {
                    this.myFilter = 'asc';
                } else {
                    this.myFilter = 'desc';
                }
                this.getMyItems();
            },
            async prevMyPage() {
                if (this.myPages.last === 1) return;
                this.myPages.current--;
                this.getMyItems();
            },
            async nextMyPage() {
                if (this.myPages.last === this.myPages.current) return;
                this.myPages.current++;
                this.getMyItems();
            },
            async prevSitePage() {
                if (this.sitePages.last === 1) return;
                this.sitePages.current--;
                this.getSiteItems();
            },
            async nextSitePage() {
                if (this.sitePages.last === this.sitePages.current) return;
                this.sitePages.current++;
                this.getSiteItems();
            },
            async setActiveSearch() {
                if (this.activeSearch) {
                    this.activeSearch = false;
                } else {
                    this.activeSearch = true;
                }
            },
            async setActiveFilters() {
                if (this.activeFilters) {
                    this.activeFilters = false;
                } else {
                    this.activeFilters = true;
                }
            },
            async setSiteRarity(rarity) {
                this.siteRarity = rarity;
                this.getSiteItems();
            },
            async setSiteFilter() {
                if (this.siteFilter === 'desc') {
                    this.siteFilter = 'asc';
                } else {
                    this.siteFilter = 'desc';
                }
                this.getSiteItems();
            },
            async addSkin(position) {
                if (this.mySelectedSkin.length) {
                    if (this.mySelectedSkin[0].id === this.myItems[position].id) return this.resetMySkin();
                }
                const array = this.myItems;
                let item = array[position];
                item.item.images = item.item.image;
                this.mySelectedSkin = [item];
            },
            async addSiteSkin(position) {
                if (this.siteSelectedSkin.length) {
                    if (this.siteSelectedSkin[0].id === this.siteItems[position].id) return this.resetSiteSkin();
                }
                const array = this.siteItems;
                let item = array[position];
                item.item.images = item.item.image;
                this.siteSelectedSkin = [item];
            },
            async resetMySkin() {
                this.mySelectedSkin = [];
            },
            async resetSiteSkin() {
                this.siteSelectedSkin = [];
            },
            async getMyItems() {
                const request = await axios.post('/api/upgrades/getMyItems', {
                    myFilter: this.myFilter,
                    page: this.myPages.current
                });
                const data = request.data;

                if (data.success) {
                    this.myItems = data.items;
                    this.myPages.current = data.current;
                    this.myPages.last = data.last;
                    if (this.myPages.last === 1) this.myPages.current = 1;
                    this.$root.hideLoading();
                } else {
                    this.$router.go(-1);
                }
            },
            async getSiteItems() {
                const request = await axios.post('/api/upgrades/getSiteItems', {
                    siteFilter: this.siteFilter,
                    siteRarity: this.siteRarity,
                    siteTo: this.siteTo,
                    siteFrom: this.siteFrom,
                    siteSearch: this.siteSearch,
                    page: this.sitePages.current
                });
                const data = request.data;

                if (data.success) {
                    this.siteItems = data.items;
                    this.sitePages.current = data.current;
                    this.sitePages.last = data.last;
                    if (this.sitePages.last === 1) this.sitePages.current = 1;
                    this.$root.hideLoading();
                } else {
                    this.$router.go(-1);
                }
            },
            async upgrade() {
                if (this.activeUpgrade) return;
                if (!this.mySelectedSkin.length || !this.siteSelectedSkin.length) return;
                if (this.mySelectedSkin[0].item.price > this.siteSelectedSkin[0].item.price) return $.wnoty({type: 'error', message: this.$t('upgrade.upgrd')})

                const request = await axios.post('/api/upgrades/upgrade', {
                    myItem: this.mySelectedSkin[0],
                    siteItem: this.siteSelectedSkin[0]
                });
                const data = request.data;

                if (data.success) {
                    this.activeUpgrade = true;
                    this.animateCursor(data.rand, data.is_win, function () {
                        this.showResultRoulette(data.is_win, data.drop, function () {
                            this.myPages.current = 1;
                            this.getMyItems();
                        }.bind(this));
                    }.bind(this));
                } else {
                    $.wnoty({
                        type: 'error',
                        message: this.$t(`upgrade.${data.message}`)
                    });
                }
            },
            animateCursor(rand, is_win, complete) {
                const openTime = 12.35 * 1000;

                if (!is_win) {
                    const maxVal = 100;
                    const minVal = this.getPercentsAnimate();

                    if (this.isAsClose(rand, maxVal, 1.25)) {
                        rand -= 1.1;
                    }
                    if (this.isAsClose(rand, minVal, 1.25)) {
                        rand += 1.1;
                    }
                }

                let portion = rand / 100;

                const angle = 360 * this.getRandomInt(5, 8) + 360 * portion;

                this.defaultCursor();
                $(this.$refs.circleCursor).animateRotate(angle, {
                    duration: openTime,
                    easing: 'easeInOutQuad',
                    complete: complete
                });
            },
            showResultRoulette(is_win, win, callback) {
                setTimeout(() => {
                    if (is_win) {
                        this.showWin();
                    } else {
                        this.showLose();
                    }

                    this.activeUpgrade = false;

                    if (!is_win) {
                        this.mySelectedSkin = [];
                    } else {
                        this.mySelectedSkin = [win];
                    }

                    this.siteSelectedSkin = [];
                    this.defaultCursor();

                    callback && callback();
                    this.clearResultRoulette();
                }, 1000);
            },
            showWin() {
                this.$refs.circleRoulette.style.opacity = '0';
                this.$refs.circlePrize.style.opacity = '1';
                this.$refs.winToggle.classList.remove('upg__lose');
                this.$refs.winToggle.classList.add('upg__result', 'upg__win');
            },
            showLose() {
                this.$refs.circleRoulette.style.opacity = '0';
                this.$refs.circlePrize.style.opacity = '1';
                this.$refs.winToggle.classList.remove('upg__win');
                this.$refs.winToggle.classList.add('upg__result', 'upg__lose');
            },
            clearResultRoulette() {
                setTimeout(() => {
                    this.$refs.circlePrize.style.opacity = '0';
                    this.$refs.circleRoulette.style.opacity = '1';

                    this.removeClassInWinToggle();
                }, 2000);
            },
            removeClassInWinToggle() {
                setTimeout(() => {
                    this.$refs.winToggle.classList.remove('upg__result', 'upg__lose', 'upg__win');
                }, 750);
            },
            isAsClose(value, target, precision) {
                return Math.abs(value - target) <= precision;
            },
            getRandomInt(min, max) {
                let rand = min + Math.random() * (max + 1 - min);
                return Math.floor(rand);
            },
            defaultCursor() {
                this.$refs.circleCursor.style.transform = 'rotate(0deg)';
            },
            getPercentsSkin() {
                if (this.mySelectedSkin.length && this.siteSelectedSkin.length) {
                    return (this.mySelectedSkin[0].item.price / this.siteSelectedSkin[0].item.price * 100).toFixed(2);
                }
                return 0;
            },
            getPercentsAnimate() {
                let percentsAnimate = this.getPercentsSkin();
                if (percentsAnimate > 80) {
                    percentsAnimate = 80;
                }

                return percentsAnimate;
            },
            calcCirclePercents() {
                if (this.upgradeCircle.active) {
                    this.upgradeCircle.canvas.stop();
                }

                const newPercents = this.getPercentsAnimate();
                const oldPercents = this.upgradeCircle.percentsDisplay;

                this.upgradeCircle.active = true;
                this.upgradeCircle.progressFn = this._createProgressFn(oldPercents, newPercents);

                this.animateCircle(this.upgradeCircle.element, newPercents, this.upgradeCircle.percentsPortion);
            },
            _createProgressFn(oldPercents, newPercents) {
                const vm = this;
                const percentsDiff = oldPercents - newPercents;

                return function (event, progress, stepValue) {
                    const curDiff = percentsDiff * progress;
                    const curPercents = oldPercents - curDiff;

                    vm.upgradeCircle.percentsDisplay = curPercents.toFixed(2);
                    vm.upgradeCircle.percentsPortion = stepValue;
                };
            },
            animateCircle(element, percents, oldPortion) {
                let portion = percents / 100; // convert from percents
                portion -= Math.min(portion * 0.02, 0.01); // prevent user from confusion by cursor

                element.circleProgress({
                    'value': portion,
                    'animationStartValue': oldPortion,
                });
                element.circleProgress();
            },
            initCircle() {
                const vm = this;
                const element = $(this.$refs.circleProgress);

                element.circleProgress({
                    animation: {
                        duration: 1100,
                        easing: 'circleProgressEasing',
                    },
                    size: 247,
                    thickness: 20,
                    startAngle: -Math.PI / 2,
                    emptyFill: 'rgba(0, 0, 0, 0)',
                    fill: {
                        image: 'img/upgrade/circle-progress.png',
                    },
                }).on('circle-animation-progress', function (event, progress, stepValue) {
                    if (vm.upgradeCircle.progressFn) {
                        vm.upgradeCircle.progressFn(event, progress, stepValue);
                    }
                }).on('circle-animation-end', function () {
                    vm.upgradeCircle.active = false;
                });

                this.upgradeCircle.element = element;
                this.upgradeCircle.canvas = $(element.circleProgress('widget'));
            }
        },
        mounted() {
            this.$root.showLoading();

            this.$watch(
                'siteFrom',
                (newVal, oldVal) => {
                    this.getSiteItems();
                }
            );

            this.$watch(
                'siteTo',
                (newVal, oldVal) => {
                    this.getSiteItems();
                }
            );

            this.$watch(
                'siteSearch',
                (newVal, oldVal) => {
                    this.getSiteItems();
                }
            );

            this.$watch(
                'mySelectedSkin',
                () => {
                    this.calcCirclePercents();
                }
            );

            this.$watch(
                'siteSelectedSkin',
                () => {
                    this.calcCirclePercents();
                }
            );

            this.getMyItems();
            this.getSiteItems();
            this.initCircle();
        }
    }
</script>

<style scoped>
    .upg__bg {
        position: relative;
    }

    .upg__wrap {
        position: relative;
        margin-left: -15px;
        margin-right: -15px;
    }

    .upg__how-work.upg__no-skins {
        display: none;
    }

    .upg__how-work {
        position: absolute;
        display: block;
        font: 15px 'Intro Head R Base', sans-serif;
        color: #ffd800;
        text-shadow: 0 0 5px rgba(247, 105, 10, .9);
        top: -28px;
        left: 88px;
        border-bottom: 1px dashed #ffd800;
        -webkit-transition: all .3s ease;
        -o-transition: all ease .3s;
        transition: all .3s ease;
    }

    .upg__top {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: justify;
        justify-content: space-between;
        padding: 0 52px;
    }

    .upg__selected-skins {
        height: 325px;
        width: 294px;
        position: relative;
    }

    .upg__skin {
        position: relative;
        height: 285px;
        width: 100%;
        background: url(/img/upgrade/upgrade__skin_bg.png) no-repeat 50%;
    }

    .upg__skin .upg__skin-bg:before {
        content: "";
        position: absolute;
        top: 6px;
        left: 0;
        right: 0;
        width: 270px;
        height: 224px;
        margin: 0 auto;
        background: url(/img/upgrade/upgrade__bg_default.png) no-repeat 50%;
    }

    .upg__no-skins .upg__skin .upg__skin-bg:before {
        background: url(/img/upgrade/upgrade__bg_default.png) no-repeat 50%;
    }

    .upg__main {
        height: 125px;
        top: 150px;
    }

    .upg__main, .upg__thing-img {
        position: absolute;
        left: 0;
        right: 0;
        margin: 0 auto;
    }

    .upg__thing-main {
        position: relative;
        left: 0;
        right: 0;
        margin: 0 auto;
        width: 250px;
        height: 54px;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: row;
        flex-direction: row;
        padding: 10px 7px;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    .upg__no-skins .upg__count-skins, .upg__no-skins .upg__my-skins_nav, .upg__no-skins .upg__selected-skins img, .upg__no-skins .upg__skin_bage, .upg__no-skins .upg__skin_remove, .upg__no-skins .upg__thing-img, .upg__no-skins .upg__thing-info, .upg__no-skins .upg__thing-main .upg__thing-price, .upg__no-skins .upg__thing-main:before, .upg__no-skins .upg__thing-view, .upg__no-skins .upg_total-price {
        opacity: 0;
        visibility: hidden;
    }

    .upg__thing-main:before {
        content: "";
        position: absolute;
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: 0 0;
        width: 292px;
        height: 98px;
        top: -13px;
        left: -21px;
    }

    .upg__no-skins .upg__thing-main .upg__need-add-skin, .upg__no-skins .upg__thing-main .upg__need-use-balance {
        display: block;
        width: 80%;
        position: absolute;
        text-align: center;
        margin: 0 auto;
        left: 0;
        right: 0;
        font: 15px 'Intro Head R Base', sans-serif;
    }

    .upg__no-skins .upg__thing-main .upg__need-use-balance {
        display: none;
    }

    .upg__no-skins .upg__thing-main .upg__need-add-skin, .upg__no-skins .upg__thing-main .upg__need-use-balance {
        display: block;
        width: 80%;
        position: absolute;
        text-align: center;
        margin: 0 auto;
        left: 0;
        right: 0;
        font: 15px 'Intro Head R Base', sans-serif;
    }

    .upg__thing-main .upg__need-add-skin, .upg__thing-main .upg__need-use-balance {
        display: none;
        font: 15px 'Intro Head R Base', sans-serif;
        color: #b6b5ab;
    }

    .upg__thing-main .upg__thing-info {
        z-index: 1000;
        color: #fff;
        font: 15px 'Intro Head R Base', sans-serif;
        max-width: 125px;
        margin-right: 5px;
    }

    .upg__thing-main .upg__thing-info span {
        display: block;
        overflow: hidden;
    }

    .ell {
        white-space: nowrap;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
    }

    .upg__thing-main .upg__thing-price span {
        color: #ffd800;
        /*font: 18px 'Intro Head R Base', sans-serif;*/
        text-shadow: 0 0 15px rgba(247, 105, 10, .9);
    }

    .upg__thing-main .upg__thing-price span:after {
        margin-left: 4px;
    }

    .upg__reset {
        width: 258px;
        height: 54px;
        margin: 6px auto 0;
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        -ms-flex-align: center;
        align-items: center;
        z-index: 3;
    }

    .upg__no-skins .upg__reset:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -300px 0;
        width: 288px;
        height: 96px;
    }

    .upg__reset:after, .upg__reset:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 288px;
        height: 96px;
    }

    .upg__reset:before {
        background-position: 0 -106px;
    }

    .upg__reset:before {
        content: "";
        position: absolute;
        top: -21px;
        left: -15px;
        z-index: -1;
        pointer-events: none;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__no-skins .upg__reset span {
        color: #3a3c44;
        text-shadow: none;
    }

    .upg__reset span {
        font: 19px 'Intro Head R Base', sans-serif;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        -webkit-transition: color .3s ease;
        -o-transition: color .3s ease;
        transition: color .3s ease;
        width: 247px;
        -ms-flex-pack: center;
        justify-content: center;
        text-transform: uppercase;
        color: #ffd800;
        text-shadow: 0 0 5px rgba(247, 96, 97, .9);
    }

    .upg__no-skins .upg__reset span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -535px -404px;
        width: 51px;
        height: 44px;
    }

    .upg__reset span:after, .upg__reset span:before {
        content: "";
        display: block;
    }

    .upg__reset span:before {
        margin-right: 8px;
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -476px -404px;
        width: 51px;
        height: 44px;
    }

    .upg__reset span:after, .upg__reset span:before {
        content: "";
        display: block;
    }

    .upg__counters {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: row;
        flex-direction: row;
        margin-top: 7px;
        margin-left: 8px;
    }

    .upg__counters .upg__count-skins {
        width: 178px;
        height: 53px;
        background: url(/img/upgrade/count_skins_bg.png) no-repeat right -53px, url(/img/upgrade/count_skins_bg.png) no-repeat 4px 0;
        -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, .4);
        box-shadow: 0 0 10px rgba(0, 0, 0, .4);
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        -ms-flex-align: center;
        align-items: center;
        text-transform: uppercase;
        font: 16px 'Intro Head R Base', sans-serif;
    }

    .upg__counters .upg__count-skins span {
        color: #ffd800;
    }

    .upg__counters .upg__count-skins p {
        color: #74736c;
        margin-left: 5px;
    }

    .upg__counters .upg_total-price {
        width: 107px;
        height: 53px;
        background: url(/img/upgrade/total-price_bg.png) no-repeat 0 -3px;
        position: absolute;
        right: 12px;
        padding-left: 7px;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        -ms-flex-align: center;
        align-items: center;
        font: 16px 'Intro Head R Base', sans-serif;
    }

    .upg__counters .upg_total-price span {
        color: #ffd800;
        text-shadow: 0 0 17px rgba(247, 96, 97, .9);
        margin-top: 6px;
    }

    .upg__no-skins .upg__how-work_no-skins {
        -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, .4);
        box-shadow: 0 0 10px rgba(0, 0, 0, .4);
        width: 271px;
        height: 53px;
        margin: 6px auto 0;
        background: url(/img/upgrade/count_skins_bg.png) no-repeat right -53px, url(/img/upgrade/count_skins_bg.png) no-repeat 0 0;
        position: absolute;
        top: 116px;
        left: 0;
        right: 0;
        font: 15px 'Intro Head R Base', sans-serif;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .upg__no-skins .upg__how-work_no-skins a {
        color: #ffd800;
        text-shadow: 0 0 20px rgba(247, 105, 10, .9), 0 0 20px rgba(247, 105, 10, .9);
    }

    .upg__skin_remove {
        position: absolute;
        width: 27px;
        height: 27px;
        border: none;
        top: 24px;
        left: 24px;
        background: transparent;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 51px;
        height: 52px;
    }

    .upg__skin_remove:before {
        background-position: -122px -404px;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        content: "";
        position: absolute;
        top: -7px;
        left: -12px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity ease .3s;
        transition: opacity .3s ease;
        pointer-events: none;
    }

    .upg__skin_remove:after {
        opacity: 0;
        background-position: -181px -404px;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 51px;
        height: 52px;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        content: "";
        position: absolute;
        top: -7px;
        left: -12px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity ease .3s;
        transition: opacity .3s ease;
        pointer-events: none;
    }

    .upg__main, .upg__thing-img {
        position: absolute;
        left: 0;
        right: 0;
        margin: 0 auto;
    }

    .upg__thing-img {
        top: 30px;
        max-width: 150px;
        height: auto;
    }

    .upg__my-skins_nav {
        position: absolute;
        top: 50px;
        left: 0;
        right: 0;
        opacity: 0;
        visibility: hidden;
    }

    .upg__my-skins_nav .upg__my-skin_prev {
        left: 1px;
    }

    .upg__my-skins_nav button {
        border: none;
        background: none;
        position: absolute;
    }

    .upg__my-skins_nav button:after, .upg__my-skins_nav button:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 74px;
        height: 86px;
    }

    .upg__my-skins_nav button:before {
        background-position: -82px -310px;
    }

    .upg__my-skins_nav button:after, .upg__my-skins_nav button:before {
        content: "";
        position: absolute;
        top: -4px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__my-skins_nav button:after {
        opacity: 0;
        background-position: 0 -310px;
    }

    .upg__my-skins_nav button:after, .upg__my-skins_nav button:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 74px;
        height: 86px;
    }

    .upg__my-skins_nav button:after, .upg__my-skins_nav button:before {
        content: "";
        position: absolute;
        top: -4px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__my-skins_nav .upg__my-skin_next {
        right: 0;
        -webkit-transform: rotateY(180deg);
        transform: rotateY(180deg);
    }

    .upg__my-skins_nav button {
        border: none;
        background: none;
        position: absolute;
    }

    .upg__my-skins_nav button:after, .upg__my-skins_nav button:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 74px;
        height: 86px;
    }

    .upg__my-skins_nav button:before {
        background-position: -82px -310px;
    }

    .upg__my-skins_nav button:after, .upg__my-skins_nav button:before {
        content: "";
        position: absolute;
        top: -4px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__my-skins_nav button:after {
        opacity: 0;
        background-position: 0 -310px;
    }

    .upg__my-skins_nav button:after, .upg__my-skins_nav button:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 74px;
        height: 86px;
    }

    .upg__my-skins_nav button:after, .upg__my-skins_nav button:before {
        content: "";
        position: absolute;
        top: -4px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__center {
        width: 481px;
        height: 285px;
        position: relative;
        padding-top: 20px;
    }

    .upg__center .upg__circle-roulette {
        width: 285px;
        height: 285px;
        position: absolute;
        left: 0;
        right: 0;
        margin: 0 auto;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__center .upg__circle-roulette:before {
        display: block;
        content: "";
        pointer-events: none;
        position: absolute;
        width: 652px;
        height: 454px;
        top: -69px;
        left: -175px;
        background: url(/img/upgrade/circle-roulette__bg.png) no-repeat 50%;
    }

    .upg__center .upg__circle-progress {
        position: absolute;
        width: 247px;
        height: 274px;
        top: -5px;
        left: 22px;
        margin: 20px 0;
        display: block;
        text-align: center;
    }

    .upg__center .upg__circle-stats, .upg__center .upg_cursor {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        bottom: 25px;
    }

    .upg__center .upg__circle-stats {
        margin: 0 auto;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-align: center;
        align-items: center;
    }

    .upg__center .upg__win-percent, .upg__center i {
        font: 32px 'Intro Head R Base', sans-serif;
        color: #8df8ff;
        text-shadow: 0 0 25px rgba(0, 90, 255, .9);
    }

    .upg__center .upg__win-percent, .upg__center i {
        font: 32px 'Intro Head R Base', sans-serif;
        color: #8df8ff;
        text-shadow: 0 0 25px rgba(0, 90, 255, .9);
    }

    .upg__center .upg__win-text {
        color: #fff;
        font: 16px 'Intro Head R Base', sans-serif;
        text-transform: uppercase;
    }

    .upg__center .upg__circle-stats, .upg__center .upg_cursor {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        bottom: 25px;
    }

    .upg__center .upg_cursor:before {
        top: 3px;
        position: absolute;
        display: block;
        content: "";
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: 0 -513px;
        width: 38px;
        height: 51px;
    }

    .upg__can-get .upg__reset.upg__upgrade span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -358px -404px;
        width: 51px;
        height: 44px;
    }

    .upg__selected-skins {
        height: 325px;
        width: 294px;
        position: relative;
    }

    .upg__skin {
        position: relative;
        height: 285px;
        width: 100%;
    }

    .upg__no-skins .upg__skin .upg__skin-bg:before {
        background: url(/img/upgrade/upgrade__bg_default.png) no-repeat 50%;
    }

    .upg__skin .upg__skin-bg:before {
        content: "";
        position: absolute;
        top: 6px;
        left: 0;
        right: 0;
        width: 270px;
        height: 224px;
        margin: 0 auto;
        background: url(/img/upgrade/upgrade__bg_default.png) no-repeat 50%;
    }

    .upg__can-get .upg__skin_remove {
        left: inherit;
        right: 21px;
    }

    .upg__skin_remove {
        position: absolute;
        width: 27px;
        height: 27px;
        border: none;
        top: 24px;
        left: 24px;
        background: transparent;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 51px;
        height: 52px;
    }

    .upg__skin_remove:before {
        background-position: -122px -404px;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        content: "";
        position: absolute;
        top: -7px;
        left: -12px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity ease .3s;
        transition: opacity .3s ease;
        pointer-events: none;
    }

    .upg__skin_remove:after {
        opacity: 0;
        background-position: -181px -404px;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 51px;
        height: 52px;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        content: "";
        position: absolute;
        top: -7px;
        left: -12px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity ease .3s;
        transition: opacity .3s ease;
        pointer-events: none;
    }

    .upg__main, .upg__thing-img {
        position: absolute;
        left: 0;
        right: 0;
        margin: 0 auto;
    }

    .upg__main {
        height: 125px;
        top: 150px;
    }

    .upg__thing-main {
        position: relative;
        left: 0;
        right: 0;
        margin: 0 auto;
        width: 250px;
        height: 54px;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: row;
        flex-direction: row;
        padding: 10px 7px;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    .upg__thing-main:before {
        content: "";
        position: absolute;
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: 0 0;
        width: 292px;
        height: 98px;
        top: -13px;
        left: -21px;
    }

    .upg__thing-main .upg__thing-info {
        font: 15px 'Intro Head R Base', sans-serif;
        max-width: 125px;
        margin-right: 5px;
    }

    .upg__thing-main .upg__thing-info span {
        display: block;
        overflow: hidden;
    }

    .upg__multiple {
        -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, .4);
        box-shadow: 0 0 10px rgba(0, 0, 0, .4);
        width: 271px;
        height: 53px;
        margin: 6px auto 0;
        background: url(/img/upgrade/count_skins_bg.png) no-repeat right -53px, url(/img/upgrade/count_skins_bg.png) no-repeat 0 0;
        display: -ms-flexbox;
        display: flex;
    }

    .upg__multiple .upg__multiple-count {
        color: #fff;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: row;
        flex-direction: row;
        width: 100%;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: distribute;
        justify-content: space-around;
        font: 15px 'Intro Head R Base', sans-serif;
        margin-top: 6px;
    }

    .upg__multiple .upg__multiple-count li {
        position: relative;
        cursor: pointer;
    }

    .upg__multiple .upg__multiple-count li + li:before {
        content: "";
        display: block;
        width: 1px;
        height: 12px;
        background: #272628;
        position: absolute;
        left: -14px;
        pointer-events: none;
    }

    .upg__multiple .upg__multiple-count li:hover {
        -webkit-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
        color: #ffd800;
        text-shadow: 0 0 20px rgba(247, 105, 10, .9), 0 0 20px rgba(247, 105, 10, .9);
    }

    .upg__multiple .upg__multiple_active {
        color: #ffd800;
        text-shadow: 0 0 20px rgba(247, 105, 10, .9), 0 0 20px rgba(247, 105, 10, .9);
    }

    .upg__bottom {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: justify;
        justify-content: space-between;
        position: relative;
        padding-top: 23px;
        margin: 0 12px;
    }

    .upg__bg_big {
        display: block;
        width: 582px;
        height: 566px;
        position: relative;
        padding-top: 29px;
        background-color: #17181e;
        -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, .5);
        box-shadow: 0 5px 10px rgba(0, 0, 0, .5);
    }

    .upg__my-thing {
        z-index: 25;
    }

    .upg__bg_big:before {
        display: block;
        content: "";
        position: absolute;
        width: 688px;
        height: 142px;
        left: -53px;
        top: -41px;
        background: url(/img/upgrade/skins__bg.png) no-repeat top;
    }

    .upg__my-thing_top {
        position: relative;
        height: 71px;
    }

    .upg__title {
        width: 100%;
        text-align: center;
    }

    .upg__my-filter {
        position: absolute;
        width: 90px;
        height: 37px;
        top: 3px;
        right: 30px;
        background: url(/img/upgrade/filters_bg.png) no-repeat right -37px, url(/img/upgrade/filters_bg.png) no-repeat 0 0;
        -webkit-box-shadow: 0 6px 13px rgba(15, 16, 18, .57);
        box-shadow: 0 6px 13px rgba(15, 16, 18, .57);
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        -ms-flex-align: center;
        align-items: center;
        padding-right: 20px;
        cursor: pointer;
    }

    .upg__my-filter.upg__my-filter-active span {
        text-shadow: 0 0 13px #ff6000, 0 0 13px #ff6000;
    }

    .upg__my-filter span, .upg__my-filter span:after, .upg__my-filter span:before {
        -webkit-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
    }

    .upg__my-filter span {
        position: relative;
        font: 12px 'Intro Head R Base', sans-serif;
        color: #f6b52a;
        text-transform: uppercase;
        text-shadow: 0 0 13px rgba(255, 96, 0, .92);
    }

    .upg__my-filter.upg__my-filter-active span:after, .upg__my-filter.upg__my-filter-active span:before {
        opacity: 1;
        -webkit-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .upg__my-filter span:after, .upg__my-filter span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 56px;
        height: 57px;
    }

    .upg__my-filter span:before {
        background-position: -464px -310px;
    }

    .upg__my-filter span:after, .upg__my-filter span:before {
        content: "";
        top: -22px;
        left: 24px;
        position: absolute;
        display: block;
    }

    .upg__my-filter span, .upg__my-filter span:after, .upg__my-filter span:before {
        -webkit-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
    }

    .upg__jc-thing_middle .upg__empty-noti, .upg__my-thing_middle .upg__empty-noti {
        display: none;
    }

    .upg__jc-thing_middle .upg__my-skins_main, .upg__my-thing_middle .upg__my-skins_main {
        min-height: 396px;
    }

    .upg__my-skins_main {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        position: relative;
        padding-left: 1px;
        -ms-flex-line-pack: start;
        align-content: flex-start;
    }

    .upg__thing.upg_viewn_consumer {
        background: url(/img/upgrade/item__bg_1.png) no-repeat 50%;
    }

    .upg__thing.upg_viewn_industrial {
        background: url(/img/upgrade/item__bg_2.png) no-repeat 50%;
    }

    .upg__thing.upg_viewn_milspec {
        background: url(/img/upgrade/item__bg_3.png) no-repeat 50%;
    }

    .upg__thing.upg_viewn_restricted {
        background: url(/img/upgrade/item__bg_4.png) no-repeat 50%;
    }

    .upg__thing.upg_viewn_classified {
        background: url(/img/upgrade/item__bg_5.png) no-repeat 50%;
    }

    .upg__thing.upg_viewn_covert {
        background: url(/img/upgrade/item__bg_6.png) no-repeat 50%;
    }

    .upg__thing.upg_viewn_knife {
        background: url(/img/upgrade/item__bg_7.png) no-repeat 50%;
    }

    .upg__thing {
        margin-left: 2px;
        width: 143px;
        height: 132px;
        background-color: #1b1c22;
        position: relative;
        z-index: 10;
    }

    .upg__thing .upg__thing_icons {
        display: none;
    }

    .upg__skin_remove {
        position: absolute;
        width: 27px;
        height: 27px;
        border: none;
        top: 24px;
        left: 24px;
        background: transparent;
    }

    .upg__thing .upg__add-help {
        display: block;
        width: 28px;
        height: 28px;
        position: absolute;
        cursor: help;
        top: 12px;
        left: 12px;
        opacity: 0;
        visibility: hidden;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
        z-index: 4;
    }

    .upg__thing .upg__add-help:after, .upg__thing .upg__add-help:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 40px;
        height: 40px;
    }

    .upg__thing .upg__add-help:before {
        background-position: -46px -513px;
        opacity: 1;
    }

    .upg__thing .upg__add-help:after, .upg__thing .upg__add-help:before {
        pointer-events: none;
        left: -6px;
        top: -2px;
        content: "";
        display: block;
        position: absolute;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__thing .upg__add-help .upg__help-toolTip {
        opacity: 0;
        visibility: hidden;
        bottom: 40px;
        left: -2px;
        position: absolute;
        -webkit-transition: all .3s ease .1s;
        -o-transition: all .3s ease .1s;
        transition: all .3s ease .1s;
        width: 261px;
        height: 75px;
        cursor: default;
    }

    .upg__thing .upg__add-help:after {
        background-position: -94px -513px;
        opacity: 0;
    }

    .upg__thing .upg__add-help .upg__help-toolTip:before {
        content: "";
        background: url(/img/upgrade/tool-tip_bg.png) no-repeat;
        width: 286px;
        height: 99px;
        position: absolute;
        top: -6px;
        left: -12px;
        z-index: -1;
        pointer-events: none;
    }

    .upg__thing .upg__add-help p {
        font: 13px 'Intro Head R Base', sans-serif;
        text-align: center;
        height: 100%;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        color: #ada493;
    }

    .upg__thing .upg__thing-price {
        display: block;
        text-align: right;
        padding: 12px 10px 0 0;
        color: #ffd800;
        text-shadow: 0 0 20px rgba(247, 105, 10, .9), 0 0 20px rgba(247, 105, 10, .9);
        font: 15px 'Intro Head R Base', sans-serif;
        position: relative;
    }

    .upg__thing .upg__thing_info {
        text-align: center;
        font: 14px 'Intro Head R Base', sans-serif;
        color: #fff;
    }

    .upg__thing img {
        max-width: 115px;
        height: auto;
        margin: -25px auto 0;
        display: block;
    }

    .upg__thing .upg__thing_info {
        margin-top: -30px;
        text-align: center;
        font: 14px 'Intro Head R Base', sans-serif;
        color: #fff;
    }

    .upg__thing_bottom, .upg__thing_bottom .upg__nav {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .upg__thing_bottom {
        height: 69px;
        background-color: #14151a;
        text-align: center;
    }

    .upg__thing_bottom .upg__nav {
        width: 110px;
        height: 36px;
        padding-top: 3px;
        background-color: #111215;
    }

    .upg__thing_bottom, .upg__thing_bottom .upg__nav {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .upg__thing_bottom .upg__nav .upg__nav-prev {
        left: 0;
        top: 0;
    }

    .upg__thing_bottom .upg__nav .upg__nav-next, .upg__thing_bottom .upg__nav .upg__nav-prev {
        position: absolute;
        cursor: pointer;
        width: 36px;
        height: 36px;
    }

    .upg__thing_bottom .upg__nav .upg__nav-next:after, .upg__thing_bottom .upg__nav .upg__nav-next:before, .upg__thing_bottom .upg__nav .upg__nav-prev:after, .upg__thing_bottom .upg__nav .upg__nav-prev:before {
        top: -6px;
        left: -11px;
        pointer-events: none;
    }

    .upg__thing_bottom .upg__nav .upg__nav-next:before, .upg__thing_bottom .upg__nav .upg__nav-prev:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -356px -210px;
        width: 60px;
        height: 60px;
        opacity: 1;
    }

    .upg__thing_bottom .upg__nav .upg__nav-next:after, .upg__thing_bottom .upg__nav .upg__nav-next:before, .upg__thing_bottom .upg__nav .upg__nav-prev:after, .upg__thing_bottom .upg__nav .upg__nav-prev:before {
        content: "";
        display: block;
        position: absolute;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__thing_bottom .upg__nav .upg__nav-current {
        color: #ffd800;
        text-shadow: 0 0 20px rgba(247, 105, 10, .9), 0 0 20px rgba(247, 105, 10, .9);
    }

    .upg__thing_bottom .upg__nav .upg__nav-next {
        right: 0;
        top: 0;
        -webkit-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .upg__thing_bottom .upg__nav .upg__nav-next, .upg__thing_bottom .upg__nav .upg__nav-prev {
        position: absolute;
        cursor: pointer;
        width: 36px;
        height: 36px;
    }

    .upg__thing_bottom .upg__nav .upg__nav-next:after, .upg__thing_bottom .upg__nav .upg__nav-next:before, .upg__thing_bottom .upg__nav .upg__nav-prev:after, .upg__thing_bottom .upg__nav .upg__nav-prev:before {
        top: -6px;
        left: -11px;
        pointer-events: none;
    }

    .upg__thing_bottom .upg__nav .upg__nav-next:before, .upg__thing_bottom .upg__nav .upg__nav-prev:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -356px -210px;
        width: 60px;
        height: 60px;
        opacity: 1;
    }

    .upg__thing_bottom .upg__nav .upg__nav-next:after, .upg__thing_bottom .upg__nav .upg__nav-next:before, .upg__thing_bottom .upg__nav .upg__nav-prev:after, .upg__thing_bottom .upg__nav .upg__nav-prev:before {
        content: "";
        display: block;
        position: absolute;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__thing_bottom .upg__thing-pages {
        position: absolute;
        left: calc(50% + 40px);
        top: 11px;
        font: 12px 'Intro Head R Base', sans-serif;
        color: #c6b797;
        text-transform: uppercase;
        width: 100px;
        z-index: 0;
    }

    .upg__thing .upg__thing_info span {
        display: block;
        position: relative;
        overflow: hidden;
    }

    .upg__my-filter span:after {
        opacity: 0;
        background-position: -528px -310px;
    }

    .upg__thing .upg__thing_info span {
        display: block;
        position: relative;
        overflow: hidden;
    }

    .upg__thing:hover {
        cursor: pointer;
    }

    .upg__thing:hover .upg__add-help {
        opacity: 1;
        visibility: visible;
    }

    .upg__thing:hover .upg__add-help {
        cursor: pointer;
    }

    .upg__add-help:before {
        opacity: 1;
        cursor: pointer;
    }

    .upg__thing .upg__add-help:hover:after {
        display: none;
    }

    .upg__thing .upg__add-help:hover .upg__help-toolTip {
        opacity: 0;
        visibility: hidden;
        cursor: pointer;
    }

    .upg__jc-skins .upg__title {
        height: 72px;
        position: relative;
    }

    .upg__jc-skins .upg__title {
        height: 72px;
        position: relative;
    }

    .upg__search .switch_search {
        width: 22px;
        height: 22px;
        right: 31px;
        top: -3px;
        position: absolute;
    }

    .upg__search .switch_search:after, .upg__search .switch_search:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 53px;
        height: 54px;
    }

    .upg__search .switch_search:before {
        background-position: 0 -404px;
        opacity: 1;
    }

    .upg__search .switch_search:after, .upg__search .switch_search:before {
        content: "";
        display: block;
        position: absolute;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__search .switch_search:after {
        background-position: -61px -404px;
        opacity: 0;
    }

    .upg__search-input {
        -webkit-box-shadow: inset 0 0 4px rgba(0,0,0,.21);
        box-shadow: inset 0 0 4px rgba(0,0,0,.21);
        background: #131418;
        display: block;
        height: 100%;
        width: 100%;
        border: none;
        outline-color: transparent;
        padding-left: 25px;
        font: 14px 'Intro Head R Base', sans-serif;
        color: #a1a5af;
        opacity: 0;
        visibility: hidden;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__search .switch_more-filter {
        width: 22px;
        height: 22px;
        right: 49px;
        top: 14px;
        position: absolute;
    }

    .upg__search .switch_more-filter:after, .upg__search .switch_more-filter:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 22px;
        height: 22px;
    }

    .upg__search .switch_more-filter:before {
        background-position: -560px -210px;
        opacity: 1;
    }

    .upg__search .switch_more-filter:after, .upg__search .switch_more-filter:before {
        content: "";
        display: block;
        position: absolute;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__search .switch_more-filter:after {
        background-position: -560px -240px;
        opacity: 0;
    }

    .upg__jc-thing_middle {
        position: relative;
    }

    .upg__jc-skins .upg__my-skins_main {
        z-index: 0;
    }

    .upg__jc-thing_middle .upg__my-skins_main, .upg__my-thing_middle .upg__my-skins_main {
        min-height: 396px;
    }

    .upg__my-skins_main {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        position: relative;
        padding-left: 1px;
        -ms-flex-line-pack: start;
        align-content: flex-start;
    }

    .upg__search {
        position: absolute;
        left: 0;
        right: 0;
        top: 26px;
        display: block;
        height: 48px;
        width: 95%;
        margin: 0 auto;
        z-index: 5;
    }

    .switch_search, .switch_more-filter:hover {
        cursor: pointer;
    }

    .upg__filters-quality:hover span {
        text-shadow: 0 0 13px rgba(255, 96, 0, 1), 0 0 13px rgba(255, 96, 0, 1);
    }

    .upg__filters-quality:hover span:before {
        transform: rotate(180deg);
    }

    .switch_search.active+.upg__search-input {
        opacity: 1;
        visibility: visible;
    }

    .upg__search .upg__filters, .upg__search .upg__filters .upg__filters-quality {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        position: relative;
    }

    .upg__search .upg__filters {
        background: #17181c;
        width: 582px;
        height: 57px;
        left: -14px;
        top: 25px;
        -webkit-box-shadow: 0 4px 18px #0f1012;
        box-shadow: 0 4px 18px #0f1012;
        -ms-flex-pack: distribute;
        justify-content: space-around;
    }

    .upg__search .upg__filters .upg__filters-quality {
        width: 123px;
        height: 37px;
        background: url(/img/upgrade/filters_bg.png) no-repeat right -37px,url(/img/upgrade/filters_bg.png) no-repeat 0 0;
        -webkit-box-shadow: 0 6px 13px rgba(15,16,18,.57);
        box-shadow: 0 6px 13px rgba(15,16,18,.57);
        -ms-flex-pack: center;
        justify-content: center;
        padding-right: 20px;
        cursor: pointer;
    }

    .upg__search .upg__filters .upg__filters-quality span {
        -ms-flex-pack: center;
        justify-content: center;
        text-transform: uppercase;
        font: 12px 'Intro Head R Base', sans-serif;
        color: #f6b52a;
        text-shadow: 0 0 13px rgba(255,96,0,.92);
        position: relative;
    }

    .upg__search .upg__filters .upg__filters-quality span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -464px -310px;
        width: 56px;
        height: 57px;
    }

    .upg__search .upg__filters .upg__filters-quality span:after, .upg__search .upg__filters .upg__filters-quality span:before {
        content: "";
        top: -22px;
        left: 43px;
        position: absolute;
        display: block;
        -webkit-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop {
        color: #fff;
        padding: 13px 10px 13px 24px;
        width: 123px;
        background: #1e1c1e;
        position: absolute;
        top: 38px;
        left: 0;
        -webkit-box-shadow: 7px 0 18px #0f1012;
        box-shadow: 7px 0 18px #0f1012;
        visibility: hidden;
        opacity: 0;
        -webkit-transform: translateY(-35%);
        -ms-transform: translateY(-35%);
        transform: translateY(-35%);
        -webkit-transition: all .3s ease-in-out 0s,visibility 0s linear .3s,z-index 0s linear .01s;
        -o-transition: all .3s ease-in-out 0s,visibility 0s linear .3s,z-index 0s linear .01s;
        transition: all .3s ease-in-out 0s,visibility 0s linear .3s,z-index 0s linear .01s;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul .upg__quality_1.selected {
        color: #b0c3d9;
        text-shadow: 0 0 7px rgba(176,195,217,.39);
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul li {
        position: relative;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul .upg__quality_1:before {
        background-color: #b0c3d9;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul .upg__quality_2:before {
        background-color: #5e98d9;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul .upg__quality_3:before {
        background-color: #01a4f0;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul .upg__quality_4:before {
        background-color: #831bd6;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul .upg__quality_5:before {
        background-color: #f008ee;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul .upg__quality_6:before {
        background-color: #ba0e15;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul .upg__quality_7:before {
        background-color: #bb850b;
    }

    .upg__search .upg__filters .upg__filters-quality .upg__quality-drop ul li:before {
        content: "";
        display: block;
        position: absolute;
        left: -13px;
        top: 5px;
        width: 6px;
        height: 2px;
    }

    .upg__search .upg__filters .upg__filters-money {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: row;
        flex-direction: row;
        -ms-flex-align: center;
        align-items: center;
    }

    .upg__search .upg__filters .upg__filters-money .upg__filters-title {
        font: 11px 'Intro Head R Base', sans-serif;
        color: #7b7b7b;
        margin-right: 15px;
    }

    .upg__search .upg__filters .upg__filters-money .upg__input {
        position: relative;
    }

    .upg__search .upg__filters .upg__filters-money .upg__input:before {
        position: absolute;
        top: 11px;
        left: 6px;
        color: #f6b52a;
        text-shadow: 0 0 13px rgba(255,96,0,.92);
        font: 16px 'Intro Head R Base', sans-serif;
    }

    .upg__search .upg__filters .upg__filters-money input {
        outline-width: 0;
        border: none;
        padding-left: 30px;
        background-color: #131317;
        width: 60px;
        height: 38px;
        font: 11px 'Intro Head R Base', sans-serif;
        display: block;
        color: #fff;
    }

    .upg__search .upg__filters .upg__filters-money .upg__seporate {
        margin: 0 5px;
    }

    .upg__search .upg__filters .upg__filters-money .upg__input {
        position: relative;
    }

    .upg__search .upg__filters .upg__filters-money .upg__input:before {
        position: absolute;
        top: 11px;
        left: 6px;
        color: #f6b52a;
        text-shadow: 0 0 13px rgba(255,96,0,.92);
        font: 16px 'Intro Head R Base', sans-serif;
    }

    .upg__search .upg__filters .upg__filters-money input {
        outline-width: 0;
        border: none;
        padding-left: 5px;
        background-color: #131317;
        width: 60px;
        height: 38px;
        font: 11px 'Intro Head R Base', sans-serif;
        display: block;
    }

    .upg__search .upg__filters .upg__filters-money .upg__my-filter {
        position: relative;
        top: inherit;
        right: inherit;
        margin-left: 20px;
    }

    .upg__my-filter {
        position: absolute;
        width: 90px;
        height: 37px;
        top: 3px;
        right: 30px;
        background: url(/img/upgrade/filters_bg.png) no-repeat right -37px,url(/img/upgrade/filters_bg.png) no-repeat 0 0;
        -webkit-box-shadow: 0 6px 13px rgba(15,16,18,.57);
        box-shadow: 0 6px 13px rgba(15,16,18,.57);
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        -ms-flex-align: center;
        align-items: center;
        padding-right: 20px;
        cursor: pointer;
    }

    .upg__my-filter span, .upg__my-filter span:after, .upg__my-filter span:before {
        -webkit-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
    }

    .upg__my-filter span {
        position: relative;
        font: 12px 'Intro Head R Base', sans-serif;
        color: #f6b52a;
        text-transform: uppercase;
        text-shadow: 0 0 13px rgba(255,96,0,.92);
    }

    .upg__my-filter span:after, .upg__my-filter span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 56px;
        height: 57px;
    }

    .upg__my-filter span:before {
        background-position: -464px -310px;
    }

    .upg__my-filter span:after, .upg__my-filter span:before {
        content: "";
        top: -22px;
        left: 17px;
        position: absolute;
        display: block;
    }

    .upg__my-filter span, .upg__my-filter span:after, .upg__my-filter span:before {
        -webkit-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
    }

    .upg__my-filter span:after {
        opacity: 0;
        background-position: -528px -310px;
    }

    .upg__filters .upg__filters-quality:hover .upg__quality-drop {
        transform: translateY(0%);
        visibility: visible;
        opacity: 1;
        transition-delay: 0s, 0s, 0.3s;
    }

    .upg-item_consumer .upg__skin-bg:before {
        background: url(/img/upgrade/upgrade__bg_1.png) no-repeat center center;
    }
    .upg-item_industrial .upg__skin-bg:before {
        background: url(/img/upgrade/upgrade__bg_2.png) no-repeat center center;
    }
    .upg-item_milspec .upg__skin-bg:before {
        background: url(/img/upgrade/upgrade__bg_3.png) no-repeat center center;
    }
    .upg-item_restricted .upg__skin-bg:before {
        background: url(/img/upgrade/upgrade__bg_4.png) no-repeat center center;
    }
    .upg-item_classified .upg__skin-bg:before {
        background: url(/img/upgrade/upgrade__bg_5.png) no-repeat center center;
    }
    .upg-item_covert .upg__skin-bg:before {
        background: url(/img/upgrade/upgrade__bg_6.png) no-repeat center center;
    }
    .upg-item_knife .upg__skin-bg:before {
        background: url(/img/upgrade/upgrade__bg_7.png) no-repeat center center;
    }

    .upg__thing.upg__thing_selected {
        position: relative;
    }

    .upg__thing.upg__thing_selected:before {
        content: "";
        position: absolute;
        z-index: 999;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        display: block;
        /*background: url(/img/upgrade/selected-skin_bg.png) no-repeat 50%;*/
    }

    .upg__thing_selected .upg__thing_icons {
        display: block;
        position: absolute;
        z-index: 1000;
        width: 27px;
        height: 27px;
    }

    .upg__thing_selected .upg__thing_icons .upg__skin_remove {
        top: 12px;
        left: 9px;
    }

    .upg__skin_remove {
        position: absolute;
        width: 27px;
        height: 27px;
        border: none;
        top: 24px;
        left: 24px;
        background: transparent;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 51px;
        height: 52px;
    }

    .upg__skin_remove:before {
        background-position: -122px -404px;
    }

    .upg__skin_remove:after, .upg__skin_remove:before {
        content: "";
        position: absolute;
        top: -7px;
        left: -12px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity ease .3s;
        transition: opacity .3s ease;
        pointer-events: none;
    }

    .upg__skin_remove:after {
        opacity: 0;
        background-position: -181px -404px;
    }

    .upg__thing.upg__thing_selected .upg__thing_icons .upg__skin_bg-status {
        position: absolute;
        top: -11px;
        left: -9px;
        width: 143px;
        height: 128px;
        pointer-events: none;
    }

    .upg__thing.upg__thing_selected .upg__thing_icons .upg__skin_bg-status:before {
        background: url(/img/upgrade/added-skin_bg.png) no-repeat 14px 0;
    }

    .upg__thing.upg__thing_selected .upg__thing_icons .upg__skin_bg-status:after, .upg__thing.upg__thing_selected .upg__thing_icons .upg__skin_bg-status:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 118px;
        height: 119px;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__thing.upg__thing_selected .upg__thing_icons .upg__skin_bg-status:after {
        opacity: 0;
        background: url(/img/upgrade/remove-skin_bg.png) no-repeat 12px -3px;
        background-size: 123px 123px;
    }

    .upg__thing_selected:hover {
        cursor: default;
    }

    .upg__skin_remove:hover {
        cursor: pointer;
    }

    .upg__thing_selected .upg__thing_icons .upg__skin_remove:hover .upg__skin_bg-status:before {
        opacity: 0;
    }

    .upg__thing_selected .upg__thing_icons .upg__skin_remove:hover .upg__skin_bg-status:after {
        opacity: 1;
    }

    .upg__reset:after {
        opacity: 0;
        background-position: -300px -104px;
    }

    .upg__reset:after, .upg__reset:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        width: 288px;
        height: 96px;
    }

    .upg__reset:after, .upg__reset:before {
        content: "";
        position: absolute;
        top: -21px;
        left: -15px;
        z-index: -1;
        pointer-events: none;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease;
    }

    .upg__selected-skins:not(.upg__no-skins) .upg__reset:hover {
        cursor: pointer;
    }

    .upg__selected-skins:not(.upg__no-skins) .upg__reset:hover span {
        color: #8df8ff;
        text-shadow: 0 0 5px rgba(141,248,255,.9);
    }

    .upg__selected-skins:not(.upg__no-skins) .upg__reset:hover:after {
        opacity: 1;
    }

    .upg__selected-skins:not(.upg__no-skins) .upg__reset:hover span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -240px -404px;
        width: 51px;
        height: 44px;
    }

    .upg__selected-skins:not(.upg__no-skins).upg__can-get .upg__reset:hover span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -417px -404px;
        width: 51px;
        height: 44px;
    }

    .upg__selected-skins:not(.upg__no-skins).upg__can-get .upg__reset span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -299px -404px;
        width: 51px;
        height: 44px;
    }

    .upg__selected-skins:not(.upg__no-skins) .upg__reset:hover span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -240px -404px;
        width: 51px;
        height: 44px;
    }

    .upg__can-get .upg__reset.upg__upgrade span:before {
        background-image: url(/img/upgrade/spriteUpgrade.png?6005660);
        background-position: -358px -404px;
        width: 51px;
        height: 44px;
    }

    .upg__result .upg__circle-prize {
        opacity: 0;
        -webkit-transition: opacity .3s ease;
        -o-transition: opacity .3s ease;
        transition: opacity .3s ease
    }

    .upg__result.upg__lose .upg__circle-prize:before, .upg__result.upg__win .upg__circle-prize:before {
        content: "";
        width: 600px;
        height: 420px;
        z-index: 9999;
        position: absolute;
        display: block;
        top: -50px;
        left: 50%;
        -webkit-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        transform: translateX(-50%)
    }

    .upg__result.upg__win .upg__circle-prize:before {
        background: url(/img/upgrade/circle-roulette_win__bg.png) no-repeat 50%
    }

    .upg__result.upg__lose .upg__circle-prize:before {
        background: url(/img/upgrade/circle-roulette_lose__bg.png) no-repeat 50%
    }
</style>