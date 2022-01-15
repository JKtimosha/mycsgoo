const
    SteamCommunity = require('steamcommunity'),
    SteamTotp = require('steam-totp'),
    TradeOfferManager = require('steam-tradeoffer-manager'),
    SteamUser = require('steam-user'),
    Redis = require('redis'),
    RedisClient = Redis.createClient(),
    axios = require('axios');

const myArgs = process.argv.slice(2);
const domain = myArgs[0];

let bot = null;

RedisClient.subscribe('bot.send');

RedisClient.on('message', (channel, message) => {
    if (channel === 'bot.send') {
        bot.newRequestToWithdraw(JSON.parse(message));
    }
});

const init = async () => {
    const request = await axios.post(`${domain}/api/bot/getSettings`);
    const data = request.data;

    bot = new Bot(data);
};

init();

const checkStatus = async () => {
    await axios.post(`${domain}/api/bot/checkItems`);
};

setInterval(() => checkStatus(), 20000);

class Bot {
    constructor(props) {
        this._client = new SteamUser();
        this._steam = new SteamCommunity();
        this._manager = new TradeOfferManager({
            "steam": this._client,
            "domain": "localhost",
            "language": "ru",
            "pollInterval": 5000
        });

        this.auth = false;
        this.props = props;

        this.logOnOptions = {
            "accountName": this.props.username,
            "password": this.props.password,
            "twoFactorCode": SteamTotp.getAuthCode(this.props.shared_secret)
        };

        console.log(`Запускаем бота #${this.props.username}`);

        this._manager.on('newOffer', async (offer) => {
            await this.newOffer(offer);
        });

        this._manager.on('sentOfferChanged', async (offer, oldState) => {
            await this.sentOfferChanged(offer, oldState);
        });

        this.Auth();
        this.checkExpiredSession();

        if (parseInt(this.props.appId) !== 730) {
            setInterval(async () => {
                await this.checkAcceptedRequests();
            }, 30000);
        }
    }

    Auth() {
        this._client.logOn(this.logOnOptions);

        this._client.on('webSession', (sessionID, cookies) => {
            this._manager.setCookies(cookies, (err) => {
                if (err) {
                    console.log(`Не смог авторизоваться: ${err}`);
                    return 0;
                }

                this.auth = true;

                console.log(`Апикей получен: ${this._manager.apiKey}`);
            });

            this._steam.setCookies(cookies);
        });
    }

    checkExpiredSession() {
        this._client.on('sessionExpired', () => {
            console.log('Умерла сессия, релог');
            this.Auth();
        });
    }

    newRequestToWithdraw(request) {
        console.log(`Поступил запрос #${request.id} от ${request.username} на вывод ${request.market_hash_name}`);
        this._manager.getInventoryContents(this.props.appId, 2, true, async (error, inventory) => {
            if (!error) {
                let item = [];

                for (let j = 0; j < inventory.length; j++) {
                    if (parseInt(inventory[j].id) === parseInt(request.assetID)) {
                        item = inventory[j];
                        break;
                    }
                }

                let offer = this._manager.createOffer(request.trade_link);
                offer.addMyItem(item);
                offer.setMessage(`Ваш выигрыш #${request.id}`);
                offer.send(async (err, status) => {
                    if (err) {
                        await this.setStatus(request.id, 0);
                        io.sockets.emit('setItemStatus', {user_id: request.user_id, id: request.id, status: 0});
                        io.sockets.emit('notify', {
                            user_id: request.user_id,
                            type: 'error',
                            message: `Предмет ${request.market_hash_name} не отправился по не определенным причинам`
                        });
                        return 0;
                    }

                    if (status === 'pending') {
                        this._steam.acceptConfirmationForObject(this.props.identity_secret, offer.id, async (err) => {
                            if (err) {
                                await this.setStatus(request.id, 5, offer.id);
                                io.sockets.emit('setItemStatus', {
                                    user_id: request.user_id,
                                    id: request.id,
                                    status: 5,
                                    trade_id: offer.id
                                });
                                io.sockets.emit('notify', {
                                    user_id: request.user_id,
                                    type: 'success',
                                    message: `Предмет ${request.market_hash_name} отправлен`
                                });
                                return 1;
                            } else {
                                await this.setStatus(request.id, 5, offer.id);
                                io.sockets.emit('setItemStatus', {
                                    user_id: request.user_id,
                                    id: request.id,
                                    status: 5,
                                    trade_id: offer.id
                                });
                                io.sockets.emit('notify', {
                                    user_id: request.user_id,
                                    type: 'success',
                                    message: `Предмет ${request.market_hash_name} отправлен`
                                });
                                return 1;
                            }
                        });
                    } else {
                        await this.setStatus(request.id, 5, offer.id);
                        io.sockets.emit('setItemStatus', {
                            user_id: request.user_id,
                            id: request.id,
                            status: 5,
                            trade_id: offer.id
                        });
                        io.sockets.emit('notify', {
                            user_id: request.user_id,
                            type: 'success',
                            message: `Предмет ${request.market_hash_name} отправлен`
                        });
                        return 1;
                    }
                });
            } else {
                await this.setStatus(request.id, 0);
                io.sockets.emit('setItemStatus', {user_id: request.user_id, id: request.id, status: 0});
                io.sockets.emit('notify', {
                    user_id: request.user_id,
                    type: 'error',
                    message: `Предмет ${request.market_hash_name} не отправился по не определенным причинам`
                });
                return 0;
            }
        });
    }

    async newOffer(offer) {
        if (offer.itemsToGive.length > 0) {
            return offer.decline();
        }

        console.log(`Пришел обмен от маркета`);

        const items = offer.itemsToReceive;

        offer.accept(async (err) => {
            if (!err) {
                for (let i = 0; i < items.length; i++) {
                    const request = await axios.post(`${domain}/api/bot/getOrder`, {classid: items[i].classid});
                    const order = request.data;

                    if (order === null) continue;

                    this._manager.getInventoryContents(this.props.appId, 2, true, async (error, inventory) => {
                        if (!error) {
                            let item = [];
                            for (let j = 0; j < inventory.length; j++) {
                                if ((parseInt(inventory[j].classid) === parseInt(order.item.classid)) && inventory[j].tradable) {
                                    item = inventory[j];
                                    break;
                                }
                            }

                            let offer = this._manager.createOffer(order.user.trade_link);
                            offer.addMyItem(item);
                            offer.setMessage(`Ваш выигрыш #${parseInt(order.id)}`);
                            offer.send(async (err, status) => {
                                if (err) {
                                    await this.setStatus(parseInt(order.id), 0);
                                    io.sockets.emit('setItemStatus', {
                                        user_id: order.user.id,
                                        id: parseInt(order.id),
                                        status: 0
                                    });
                                    io.sockets.emit('notify', {
                                        user_id: order.user.id,
                                        type: 'error',
                                        message: `Предмет ${order.item.market_hash_name} не отправился по не определенным причинам`
                                    });
                                    return 0;
                                }

                                if (status === 'pending') {
                                    this._steam.acceptConfirmationForObject(this.props.identity_secret, offer.id, async (err) => {
                                        if (err) {
                                            await this.setStatus(parseInt(order.id), 5);
                                            io.sockets.emit('setItemStatus', {
                                                user_id: order.user.id,
                                                id: parseInt(order.id),
                                                trade_id: offer.id,
                                                status: 5
                                            });
                                            io.sockets.emit('notify', {
                                                user_id: order.user.id,
                                                type: 'success',
                                                message: `Предмет ${order.item.market_hash_name} отправлен`
                                            });
                                            return 1;
                                        } else {
                                            await this.setStatus(parseInt(order.id), 5);
                                            io.sockets.emit('setItemStatus', {
                                                user_id: order.user.id,
                                                id: parseInt(order.id),
                                                trade_id: offer.id,
                                                status: 5
                                            });
                                            io.sockets.emit('notify', {
                                                user_id: order.user.id,
                                                type: 'success',
                                                message: `Предмет ${order.item.market_hash_name} отправлен`
                                            });
                                            return 1;
                                        }
                                    });
                                } else {
                                    await this.setStatus(parseInt(order.id), 5);
                                    io.sockets.emit('setItemStatus', {
                                        user_id: order.user.id,
                                        id: parseInt(order.id),
                                        trade_id: offer.id,
                                        status: 5
                                    });
                                    io.sockets.emit('notify', {
                                        user_id: order.user.id,
                                        type: 'success',
                                        message: `Предмет ${order.item.market_hash_name} отправлен`
                                    });
                                    return 1;
                                }
                            });
                        } else {
                            await this.setStatus(parseInt(order.id), 0);
                            io.sockets.emit('setItemStatus', {user_id: order.user.id, id: parseInt(order.id), status: 0});
                            io.sockets.emit('notify', {
                                user_id: order.user.id,
                                type: 'error',
                                message: `Предмет ${order.item.market_hash_name} не отправился по не определенным причинам`
                            });
                            return 0;
                        }
                    });
                }
            }
        });
    }

    async sentOfferChanged(offer, oldState) {
        const message = offer.message;

        if (message.indexOf('Ваш выигрыш') !== -1) {
            const id = message.split('#')[1];

            if (offer.state === 7 || offer.state === 5 || offer.state === 6) {
                const request = await axios.post(`${domain}/api/bot/getUserFromOrder`, {id: id});
                const data = request.data;

                this.setStatus(id, 0);
                io.sockets.emit('setItemStatus', {user_id: data.user_id, id: parseInt(id), status: 0});
            }
            if (offer.state === 3) {
                const request = await axios.post(`${domain}/api/bot/getUserFromOrder`, {id: id});
                const data = request.data;

                this.setStatus(id, 6);
                io.sockets.emit('setItemStatus', {user_id: data.user_id, id: parseInt(id), status: 6});
            }
        }
    }

    async checkAcceptedRequests() {
        await axios.post(`https://market.dota2.net/api/v2/trade-request-take?key=${this.props.apiKey}`);
    }

    async setStatus(id, status, tradeID = null) {
        await axios.post(`${domain}/api/bot/setStatus`, {id: id, status: status, trade_id: tradeID});
        console.log(`Запросу ${id} установлен статус ${status}`);
    }
}