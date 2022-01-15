<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"/>
        </div>
        <div class="content">
            <div class="main-width">
                <div class="best clear">
                    <div v-for="item in topUsers" :key="item.id" class="item">
                        <div class="image"><img :src="item.photo" alt="" /></div>
                        <div class="name">{{ item.name }}</div>
                        <div class="info clear">
                            <div clasS="item">
                                <div class="num">{{ item.case_count }}</div>
                                <div class="desc">{{ $t('top.cases') }}</div>
                            </div>
                            <div class="item">
                                <div class="num">{{ item.profit }}</div>
                                <div class="desc">{{ $t('top.rating') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="title title-top"/>
                <div class="rating">
                    <div class="item heading clear">
                        <div class="place">{{ $t('top.place') }}</div>
                        <div class="user">{{ $t('top.user') }}</div>
                        <div class="count">{{ $t('top.opened') }}</div>
                        <div class="rate">{{ $t('top.rating_2') }}</div>
                    </div>
                    <div v-for="(item, i) in users" :key="item.id" class="item clear">
                        <div class="place">{{ i + 1 }}</div>
                        <div class="user">
                            <div class="avatar"><img :src="item.photo" alt="" /></div>
                            <router-link :to="{name: 'user', params: {id: item.id}}" className="name">{{ item.name }}</router-link></div>
                        <div class="count">{{ item.case_count }}</div>
                        <div class="rate"><span>{{ item.profit }}</span></div>
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
                users: [],
                topUsers: []
            }
        },
        methods: {
            async loadUsers() {
                const request = await axios.post('/api/top');

                this.users = request.data.users;
                this.topUsers = request.data.topUsers;
                this.$root.hideLoading();
            }
        },
        mounted() {
            this.$root.showLoading();
            this.loadUsers();
        }
    }
</script>