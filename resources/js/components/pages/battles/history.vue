<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"></div>
        </div>
        <div class="contracts__header">
            <span class="contracts__header__text">{{ $t('battles.my.head') }}</span>
        </div>
        <div class="content casebattle" id="casebattle">
            <div class="casebattle-bar">
                <div class="casebattle-bar-button">
                    <router-link :to="{name: 'battles'}" class="casebattle-button blue active">
                        <span>
                            {{ $t('battles.my.active') }}
                        </span>
                    </router-link>
                    <router-link :to="{name: 'battles.history'}" class="casebattle-button velvet">
                        <span>
                            {{ $t('battles.my.my') }}
                        </span>
                    </router-link>
                </div>
                <div class="casebattle-bar-create">
                    <router-link :to="{name: 'battles.create'}" class="casebattle-create-button">
                        <span>
                            {{ $t('battles.my.create') }}
                        </span>
                    </router-link>
                </div>
            </div>
            <div class="casebattle-games-list">
                <table class="casebattle-game-table" v-if="games.length > 0">
                    <thead>
                    <tr>
                        <th width="200px">{{ $t('battles.my.status') }}</th>
                        <th width="100%">{{ $t('battles.my.cases') }}</th>
                        <th width="200px">{{ $t('battles.my.date') }}</th>
                        <th width="100px">{{ $t('battles.my.price') }}</th>
                        <th width="150px">{{ $t('battles.my.human') }}</th>
                        <th width="160px">{{ $t('battles.my.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="" v-for="game in games">
                        <td :class="{'status-winner': $root.user.id === game.user_win_id, 'status-looser': $root.user.id !== game.user_win_id}">
                            <span v-if="$root.user.id === game.user_win_id">{{ $t('battles.my.win') }}</span>
                            <span v-if="$root.user.id !== game.user_win_id">{{ $t('battles.my.lose') }}</span>
                        </td>
                        <td class="shadow">
                            <div class="caselist">
                                <div class="caselist-case" v-for="box in game.cases">
                                    <span class="caselist-case-count">x{{ box.selected }}</span>
                                    <img alt="" :src="box.image"
                                         class="lazy">
                                    <div class="case-title">{{ box.name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size: 14px;" class="text-success" v-text="game.updated_at"></span>
                        </td>
                        <td v-if="$i18n.locale === 'ru'">
                            {{ game.win_price }}Р
                        </td>
                        <td v-else>
                            {{ (game.win_price / 63.67).toFixed(2) }}$
                        </td>
                        <td>
                            <span class="text-success">{{ game.users.length }}</span>/{{ game.players }}
                            <div>
                                <router-link tag="a" :to="{name: 'user', params: {id: user.id}}" v-for="user in game.users" :key="user.id">
                                    <img :src="user.avatar"
                                         :alt="user.username" class="battle-avatar lazy">
                                </router-link>
                            </div>
                        </td>
                        <td>
                            <router-link tag="a" :to="{name: 'battle', params: {id: game.id}}" class="casebattle-button blue">
                                <span>{{ $t('battles.my.watch') }}</span>
                            </router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div v-if="games.length === 0" class="casebattle-games-nogame">{{ $t('battles.my.empty') }}</div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                games: []
            }
        },
        methods: {
            async getGames() {
                const request = await axios.post('/api/battles/history');
                this.games = request.data;
                this.$root.hideLoading();
            }
        },
        mounted() {
            this.$root.showLoading();
            this.getGames();
        }
    }
</script>