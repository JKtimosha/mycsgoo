<template>
    <div>
        <div v-if="$root.config.appId === '0'" class="block profile_selector" style="font-size: 20px;text-align: center;">
            <a class="selector" :class="{'active': (activeTab === 730)}" v-on:click="loadTab(730)">CS:GO</a>
            <a class="del">|</a>
            <a class="selector" :class="{'active': (activeTab === 570)}" v-on:click="loadTab(570)">Dota 2</a>
        </div>
        <div v-for="category in cases" class="cases-section">
            <div v-if="$i18n.locale === 'ru'" class="cases-section__title">{{ category.category_name_ru }}</div>
            <div v-else class="cases-section__title">{{ category.category_name_en }}</div>
            <div class="cases-section__list">
                <div v-for="box in category.cases" class="cases-section__item">
                    <router-link tag="a" class="case" :to="{name: 'case', params: {name: box.name_url}}">
                        <div class="case__image-wrapper"><img class="case__image"
                                                              :src="box.image"
                                                              :alt="box.name"></div>
                        <div v-if="$i18n.locale === 'ru'" class="case__name">{{ box.name }}</div>
                        <div v-else class="case__name">{{ box.name_en }}</div>
                        <div v-if="box.old_price && box.old_price_en" class="case__price">
                            <div v-if="$root.$i18n.locale === 'ru'">
                                <span class="case__old-price">{{ box.old_price }}</span>
                                <span class="case__new-price">{{ box.price }}Р</span>
                            </div>
                            <div v-else>
                                <span class="case__old-price">{{ box.old_price_en }}$</span>
                                <span class="case__new-price">{{ box.price_en }}$</span>
                            </div>
                        </div>
                        <div v-else-if="box.type !== 'free'" class="case__price">
                            <div v-if="$root.$i18n.locale === 'ru'">
                                <span>{{ box.price }}$</span>
                            </div>
                            <div v-else>
                                <span>{{ box.price_en }}$</span>
                            </div>
                        </div>
                        <div v-else-if="box.type === 'free'" class="case__price">
                            FREE
                        </div>
                        <div class="limit-case" v-if="box.max_open">
                            <div class="limit-case__progress-wrapper">
                                <div :class="box.class_name"
                                     :style="{width: box.progress+'%'}"></div>
                            </div>
                            <div class="limit-case__content">{{ box.max_open - box.open }} / {{ box.max_open }}</div>
                        </div>
                    </router-link>
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
                activeTab: 730
            }
        },
        methods: {
            async get(appId) {
                const request = await axios.post('/api/cases/get', {appId: appId});
                this.cases = request.data;

                this.$root.hideLoading();
            },
            async loadTab(appId) {
                this.activeTab = appId;
                this.get(appId);
            }
        },
        mounted() {
            this.$root.showLoading();
            this.loadTab(this.$root.config.appId);
        }
    }
</script>
