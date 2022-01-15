import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueSocketIO from 'vue-socket.io'
import VueI18n from 'vue-i18n'
import VueCookies from 'vue-cookies'
import Messages from './messages.js'

Vue.use(VueCookies);
Vue.use(VueI18n);

let lang = 'ru';
if (VueCookies.get('lang')) {
    lang = VueCookies.get('lang');
}

const i18n = new VueI18n({
    locale: lang,
    fallbackLocale: lang,
    messages: Messages
});

window.user = JSON.parse(document.head.querySelector('meta[name="user"]').content);
window.config = JSON.parse(document.head.querySelector('meta[name="settings"]').content);

let port = '8081';
if (window.location.protocol === 'https:') port = '8443';

Vue.use(new VueSocketIO({
    connection: window.location.origin + ':' + port
}));
Vue.use(VueRouter);

import App from './components/App';
import Index from './components/pages/index';
import Case from './components/pages/case';
import User from './components/pages/user';
import Top from './components/pages/top';
import Faq from './components/pages/faq';
import Agreement from './components/pages/agreement';
import Contacts from './components/pages/contacts';

import Contracts from './components/pages/contracts';

import BattleList from './components/pages/battles/list';
import BattleCreate from './components/pages/battles/create';
import Battle from './components/pages/battles/battle';
import BattleHistory from './components/pages/battles/history';

import Upgrade from './components/pages/upgrade/index';

const router = new VueRouter({
    routes: [
        {
            path: '/:lang',
            component: {
                template: '<router-view></router-view>'
            },
            children: [
                {
                    path: '',
                    name: 'index',
                    component: Index
                },
                {
                    path: 'case/:name',
                    name: 'case',
                    component: Case
                },
                {
                    path: 'user/:id',
                    name: 'user',
                    component: User
                },
                {
                    path: 'contracts',
                    name: 'contracts',
                    component: Contracts
                },
                {
                    path: 'battles',
                    name: 'battles',
                    component: BattleList
                },
                {
                    path: 'battles/create',
                    name: 'battles.create',
                    component: BattleCreate
                },
                {
                    path: 'battle/:id',
                    name: 'battle',
                    component: Battle
                },
                {
                    path: 'battles/history',
                    name: 'battles.history',
                    component: BattleHistory
                },
                {
                    path: 'upgrade',
                    name: 'upgrade',
                    component: Upgrade
                },
                {
                    path: 'top',
                    name: 'top',
                    component: Top
                },
                {
                    path: 'faq',
                    name: 'faq',
                    component: Faq
                },
                {
                    path: 'agreement',
                    name: 'agreement',
                    component: Agreement
                },
                {
                    path: 'contacts',
                    name: 'contacts',
                    component: Contacts
                },
                {
                    path: '*',
                    redirect: '/'
                }
            ]
        }
    ],
    mode: 'history'
});

router.beforeEach((to, from, next) => {
    let lang = to.params.lang;

    if (!['en', 'ru'].includes(lang)) {
        let lang = 'ru';
        if (VueCookies.get('lang')) {
            lang = VueCookies.get('lang');
        }
        return next(lang);
    }

    if (i18n.locale !== lang) {
        i18n.locale = lang;
    }

    VueCookies.set('lang', lang);

    return next();
});

const app = new Vue({
    el: '#app',
    i18n,
    data: {
        user: null,
        config: null,
        isLoading: false,
        modal: {
            active: false,
            promocode: ''
        }
    },
    methods: {
        async getUser() {
            this.user = window.user;
        },
        async getConfig() {
            this.config = window.config;
        },
        async hideLoading() {
            this.isLoading = false;
        },
        async showLoading() {
            this.isLoading = true;
        },
        async getBalance() {
            if (this.user !== null) {
                const request = await axios.post('/api/users/getBalance');

                if (this.$i18n.locale === 'ru') {
                    this.user.balance = request.data;
                } else {
                    this.user.balance = (request.data / 64.30).toFixed(2);
                }
            }
        },
        getCurrency() {
            if (this.$i18n.locale === 'ru') {
                return 'Р';
            } else {
                return '$';
            }
        },
        async openFill() {
            if (this.user !== null) {
                const vm = this;

                this.modal.active = true;

                setTimeout(() => {
                    $('body').click(function (event) {
                        if ($(event.target).is('.styles_overlayCenter__YHoO7')) {
                            vm.modal.active = false;
                        }
                    });

                    $('.modal__close').click(function (event) {
                        vm.modal.active = false;
                    });
                }, 100);
            }
        },
        async getSeo() {
            const name = this.$route.name;

            const request = await axios.post('/api/loadSeo', {name: name}),
                seo = request.data;
            $('title').text(seo.title);
            $('meta[name=description]').text(seo.description);
            $('meta[name=keywords]').text(seo.keywords);
        }
    },
    async created() {
        (function ($) {
            var Roulette = function (options) {
                var defaultSettings = {
                    maxPlayCount: null,
                    speed: 10,
                    stopImageNumber: null,
                    rollCount: 3,
                    duration: 3,
                    timing_func: 'ease-out',
                    moveUp: false,
                    isRunUp: true,
                    cache: {},
                    stopCallback: function () {
                    },
                    startCallback: function () {
                    },
                }
                var defaultProperty = {
                    playCount: 0,
                    $rouletteTarget: null,
                    imageCount: null,
                    $images: null,
                    originalStopImageNumber: null,
                    totalHeight: null,
                    maxDistance: null,
                    slowDownStartDistance: null,
                    isReady: false,
                    isRandom: false,
                    isSlowdown: false,
                    isStop: false,
                    distance: 0,
                    runUpDistance: null,
                    slowdownTimer: null,
                    isIE: navigator.userAgent.toLowerCase().indexOf('msie') > -1
                };
                var p = $.extend({}, defaultSettings, options, defaultProperty);
                var reset = function () {
                    p.maxDistance = defaultProperty.maxDistance;
                    p.slowDownStartDistance = defaultProperty.slowDownStartDistance;
                    p.distance = defaultProperty.distance;
                    p.isStop = defaultProperty.isStop;
                    p.isReady = defaultProperty.isStop;
                    p.timing_func = defaultProperty.timing_func;
                }
                var init = function ($roulette) {
                    var rollkey = $roulette.attr('id') + $roulette.attr('class');
                    if (p.cache[rollkey] == null) {
                        p.cache[rollkey] = $roulette.html();
                        p.isRandom = $.isNumeric(p.stopImageNumber) && Number(p.stopImageNumber) >= 0 ? false : true;
                    } else {
                        $roulette.html(p.cache[rollkey]);
                        p.$images = false;
                    }
                    $roulette.css({'overflow': 'hidden'});
                    if (p.isRandom == false) {
                        defaultProperty.originalStopImageNumber = p.stopImageNumber;
                    }
                    if (!p.$images) {
                        p.$images = $roulette.find('>').remove();
                        p.imageCount = p.$images.length;
                        p.stopImageNumber = $.isNumeric(defaultProperty.originalStopImageNumber) && Number(defaultProperty.originalStopImageNumber) >= 0 ? Number(defaultProperty.originalStopImageNumber) : Math.floor(Math.random() * p.imageCount);
                        if (p.$images.eq(0).find('img').length > 0) {
                            p.$images.eq(0).find('img').bind('load', function () {
                                p.imageHeight = p.$images.eq(0).height();
                                $roulette.css({'height': (p.imageHeight + 'px')});
                                p.totalHeight = p.imageCount * p.imageHeight * (p.rollCount + 1);
                                p.runUpDistance = 2 * p.imageHeight;
                                fill_cells($roulette);
                            });
                            p.$images.find('img').each(function () {
                                if (this.complete || this.complete === undefined) {
                                    var src = this.src;
                                    this.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                                    this.src = src;
                                }
                            });
                        } else {
                            p.$images.eq(0).bind('load', function () {
                                p.imageHeight = $(this).height();
                                $roulette.css({'height': (p.imageHeight + 'px')});
                                p.totalHeight = p.imageCount * p.imageHeight * (p.rollCount + 1);
                                p.runUpDistance = 2 * p.imageHeight;
                                fill_cells($roulette);
                            }).each(function () {
                                if (this.complete || this.complete === undefined) {
                                    var src = this.src;
                                    this.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                                    this.src = src;
                                }
                            });
                        }
                    }
                    $roulette.find('div').remove();
                    p.$images.css({'display': 'block'});
                    p.$rouletteTarget = $('<div>').css({
                        'position': 'relative',
                        'top': '0',
                        'transition-property': 'all',
                        'transition-delay': '0s',
                        'transition-duration': p.duration + 'ms',
                        'transition-timing-function': p.timing_func,
                    }).attr('class', "rollme-inner");
                    $roulette.append(p.$rouletteTarget);
                    p.$rouletteTarget.append(p.$images);
                    p.$roulette = $roulette;
                }
                var fill_cells = function ($roulette) {
                    var rollkey = $roulette.attr('id') + $roulette.attr('class');
                    for (let a = 0; a < p.rollCount; a++) {
                        for (let b = 0; b < p.imageCount; b++) {
                            p.$rouletteTarget.append(p.$images.eq(b).clone());
                        }
                    }
                    if (p.isRunUp) {
                        p.$rouletteTarget.append(p.$images.eq(p.stopImageNumber).clone());
                        p.$rouletteTarget.append(p.$images.eq(0).clone());
                    } else {
                        p.$rouletteTarget.append(p.$images.eq(0).clone());
                        p.$rouletteTarget.prepend(p.$images.eq(p.stopImageNumber).clone());
                    }
                    if (p.isRunUp) {
                        p.$rouletteTarget.css({'transition-duration': '0ms', 'top': '0px',});
                    } else {
                        p.$rouletteTarget.css({
                            'transition-duration': '0ms',
                            'top': '-' + (p.totalHeight + p.imageHeight) + 'px',
                        });
                    }
                    $roulette.show();
                    p.isReady = true;
                    if (p.playCount > 1) {
                        _roll_trans();
                    }
                }
                var roll_trans = function () {
                    if (p.playCount > 1) {
                        init(p.$roulette);
                    }
                    if (p.isReady) {
                        _roll_trans();
                    }
                }
                var _roll_trans = function () {
                    if (p.isRunUp) {
                        p.$rouletteTarget.css({'transition-duration': '0ms', 'top': '0px',});
                    } else {
                        p.$rouletteTarget.css({
                            'transition-duration': '0ms',
                            'top': '-' + (p.totalHeight + p.imageHeight) + 'px',
                        });
                    }
                    setTimeout(function () {
                        if (p.isRunUp) {
                            p.$rouletteTarget.css({'transition-duration': p.duration + 'ms',}).css('top', '-' + p.totalHeight + 'px');
                        } else {
                            p.$rouletteTarget.css({'transition-duration': p.duration + 'ms',}).css('top', '0px');
                        }
                    }, 300);
                    p.$rouletteTarget.one('transitionend', function () {
                        p.stopCallback(p.$rouletteTarget.find('img').eq(p.stopImageNumber), p.stopImageNumber);
                        return;
                    });
                }
                var start = function () {
                    p.playCount++;
                    if (p.maxPlayCount && p.playCount > p.maxPlayCount) {
                        return;
                    }
                    p.stopImageNumber = $.isNumeric(defaultProperty.originalStopImageNumber) && Number(defaultProperty.originalStopImageNumber) >= 0 ? Number(defaultProperty.originalStopImageNumber) : Math.floor(Math.random() * p.imageCount);
                    p.startCallback();
                    setTimeout(function () {
                        roll_trans();
                    }, 200);
                }
                var stop = function (option) {
                    if (!p.isSlowdown) {
                        p.$rouletteTarget.css({'transition-duration': '10ms',});
                    }
                }
                var option = function (options) {
                    p = $.extend(p, options);
                    p.speed = Number(p.speed);
                    p.duration = Number(p.duration);
                    p.duration = p.duration > 1 ? p.duration - 1 : 1;
                    defaultProperty.originalStopImageNumber = options.stopImageNumber;
                }
                var ret = {start: start, stop: stop, init: init, option: option}
                return ret;
            }
            var pluginName = 'roulette';
            $.fn[pluginName] = function (method, options) {
                return this.each(function () {
                    var self = $(this);
                    var roulette = self.data('plugin_' + pluginName);
                    if (roulette && roulette[method]) {
                        if (roulette[method]) {
                            roulette[method](options);
                        } else {
                            console && console.error('Method ' + method + ' does not exist on jQuery.roulette');
                        }
                    } else {
                        roulette = new Roulette(method);
                        roulette.init(self, method);
                        $(this).data('plugin_' + pluginName, roulette);
                    }
                });
            }
        })(jQuery);

        this.getUser();
        this.getConfig();
        this.getBalance();
    },
    async mounted() {
        this.$watch(
            'modal.promocode',
            async () => {
                const request = await axios.post('/api/checkPromocode', {code: this.modal.promocode});
                const data = request.data;

                if (data.success) {
                    $('.promocode__value').html(`Промокод на +${data.percent}%`);
                } else {
                    $('.promocode__value').html(``);
                }
            }
        );

        this.getSeo();
    },
    watch:{
        $route (to, from) {
            this.getSeo();
        }
    },
    components: {
        App
    },
    router
});