<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"></div>
        </div>
        <div class="contracts__header">
            <span class="contracts__header__text">{{ $t('battles.list.head') }}</span>
        </div>
        <div class="content casebattle" id="casebattle">
            <div class="casebattle-bar">
                <div class="casebattle-bar-button">
                    <router-link :to="{name: 'battles'}" class="casebattle-button blue active">
                        <span>
                            {{ $t('battles.list.active') }}
                        </span>
                    </router-link>
                    <router-link :to="{name: 'battles.history'}" class="casebattle-button velvet">
                        <span>
                            {{ $t('battles.list.my') }}
                        </span>
                    </router-link>
                </div>
                <div class="casebattle-bar-create">
                    <router-link :to="{name: 'battles.create'}" class="casebattle-create-button">
                        <span>
                            {{ $t('battles.list.create') }}
                        </span>
                    </router-link>
                </div>
            </div>
            <div class="casebattle-games-list">
                <table class="casebattle-game-table">
                    <thead>
                    <tr>
                        <th width="200px" class="casebattle-game-table-rounds">{{ $t('battles.list.rounds') }}</th>
                        <th width="100%">{{ $t('battles.list.cases') }}</th>
                        <th width="100px">{{ $t('battles.list.price') }}</th>
                        <th width="150px">{{ $t('battles.list.human') }}</th>
                        <th width="160px">{{ $t('battles.list.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="" v-for="game in games">
                        <td :class="{'live': checkLive(game.id)}">
                            <div class="rounds-count active"><span>{{ game.rounds_count }}</span></div>
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
                        <td v-if="$i18n.locale === 'ru'">
                            {{ game.price }}Р
                        </td>
                        <td v-else>
                            {{ (game.price / 63.67).toFixed(2) }}$
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
                            <router-link tag="a" v-if="!checkLive(game.id)" :to="{name: 'battle', params: {id: game.id}}" class="casebattle-button orange" style="width: 115px">
                                <span>{{ $t('battles.list.join') }}</span>
                            </router-link>
                            <router-link tag="a" :to="{name: 'battle', params: {id: game.id}}" class="casebattle-button blue">
                                <span>{{ $t('battles.list.watch') }}</span>
                            </router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                games: []
            }
        },
        methods: {
            checkLive(battle_id) {
                const game = this.games.filter(function (item) {
                    return (item.id === battle_id)
                });
                if (game.length) {
                    if (game[0].state === 1) {
                        return true;
                    } else {
                        return false;
                    }
                }
                return false;
            }
        },
        mounted() {
            this.$socket.emit('getBattles');
        },
        sockets: {
            battles: function (battles) {
                this.games = battles.filter(function (item) {
                    if (typeof item.state !== "undefined") {
                        return (item.state < 2 && !item.type)
                    } else {
                        return (item.status < 2 && !item.type)
                    }
                });
                this.$forceUpdate();
            }
        }
    }
</script>