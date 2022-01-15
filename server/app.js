const app = require('express')(),
    /**
     * Если нужен SSL расскоментируйте
    fs = require('fs'),
    options = {
        key: fs.readFileSync('/var/www/html/server/privkey.pem', 'utf8'),
        cert: fs.readFileSync('/var/www/html/server/fullchain.pem', 'utf8')
    },
    server = require('https').createServer(options, app),
     **/
    server = require('http').createServer(app), /** Удалите при нужности SSL */
    Redis = require('redis'),
    RedisClient = Redis.createClient(),
    axios = require('axios');

server.listen(8081); /** Поменяйте порт на 8443, если у вас подключен SSL **/

global.io = require('socket.io')(server);

const myArgs = process.argv.slice(2);
const domain = myArgs[0];

/**
 *  Расскоментируйте, если нужен бот
    require('./bot.js', domain);
*/

RedisClient.subscribe('liveDrop');
RedisClient.subscribe('setItemStatus');
RedisClient.subscribe('battleJoin');
RedisClient.subscribe('battleStart');
RedisClient.subscribe('battleNew');
RedisClient.subscribe('battleDelete');

RedisClient.on('message', async (channel, message) => {
    if (channel === 'battleStart') {
        await startRoundsBattles(JSON.parse(message));
    } else if (channel === 'battleNew') {
        const data = JSON.parse(message);
        battles.push(data.battle);
    } else if (channel === 'battleJoin') {
        const data = JSON.parse(message);
        for (const id in battles) {
            if (battles[id].id === data.battle.battle.id) {
                battles[id].users = data.battle.battle.users;
            }
        }
        io.sockets.emit(channel, data);
    } else if (channel === 'battleDelete') {
        const battleID = JSON.parse(message);
        for (const id in battles) {
            if (battles[id].id === battleID) battles.splice(id, 1);
        }
        io.sockets.emit(channel, JSON.parse(message));
    } else {
        io.sockets.emit(channel, JSON.parse(message));
    }
});

io.on('connection', (socket) => {
    const updateOnline = () => {
        io.sockets.emit('online', Object.keys(io.sockets.adapter.rooms).length);
    };

    socket.on('disconnect', () => {
        updateOnline();
    });

    socket.on('getBattles', () => {
        socket.emit('battles', battles);
    });

    setInterval(() => {
        socket.emit('battles', battles);
    }, 1000);

    updateOnline();
});

const checkStatus = async () => {
    await axios.post(`${domain}/api/bot/checkItems`);
};

setInterval(() => checkStatus(), 10000);

let battles = [];

const randomInteger = (min, max) => {
    let rand = min + Math.random() * (max + 1 - min);
    return Math.floor(rand);
};

const startCheckFake = () => {
    const randomTimer = randomInteger(1, 3);

    setTimeout(async() => {
        await axios.post(`${domain}/api/bot/fakeOpen`);
        startCheckFake();
    }, 60000 * randomTimer);
};

startCheckFake();

const startRoundsBattles = async (game) => {
    const battle = game.battle;

    await updateStatusGame(battle, 1);

    let round = 1;

    setTimeout(() => {
        updateRoundCounts(battle, round);
        round++;
        const timer = setInterval(async () => {
            if (round === (battle.rounds_count + 1)) {
                clearInterval(timer);
                await updateStatusGame(battle, 2);
                setTimeout(() => {
                    deleteBattle(battle);
                }, 60000);
            }
            updateRoundCounts(battle, round);
            round++;
        }, 6000);
    }, 4000);
};

const updateRoundCounts = (battle, rounds) => {
    for (const id in battles) {
        if (battles[id].id === battle.id) {
            battles[id].current_round = rounds;
        }
    }
};

const updateStatusGame = async (battle, status) => {
    for (const id in battles) {
        if (battles[id].id === battle.id) {
            battles[id].state = status;
            await axios.post(`${domain}/api/bot/setBattleStatus`, {id: battle.id, status: status});
        }
    }
};

const deleteBattle = (battle) => {
    for (const id in battles) {
        if (battles[id].id === battle.id) battles.splice(id, 1);
    }
};