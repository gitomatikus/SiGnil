const nameError = function () {
    return $('#name-error');
};
const playersField = function () {
    return $('.content-answers');
};

const usernameInput = function () {
    return $('#username');
};

export default class game {

    askForAnswer() {
        let time = this.getTime();
        let user = this.getUser();
        let gameId = this.getGameId();

        let users = JSON.parse(localStorage.getItem('users'));
        if (!users) {
            users = {};
        }
        if (user && time && !users.hasOwnProperty(user)) {
            axios.post('/api/ask/answer', {
                time: time,
                user: user,
                game: gameId
            });
        }
    }

    askForClear() {
        let gameId = this.getGameId();
        axios.post('/api/ask/clear', {
            game: gameId
        });
    }

    askForQuestion(id) {
        let gameId = this.getGameId();
        let alreadyShown = localStorage.getItem('question_start');
        if (alreadyShown) {
            return;
        }
        axios.post('/api/ask/question', {
            game: gameId,
            question: id
        });
    }

    getTime() {
        let finishTime = new Date().getTime();
        let startTime = localStorage.getItem('question_start');
        if (!startTime) {
            return;
        }
        let seconds = (finishTime-startTime)/1000;
        return seconds;
    }

    answerTemplate(user, time) {
        return '<div class="answers">' + user + ', time: ' + time + ' seconds</div>'
    }

    getUser() {
        let userName = usernameInput().val();
        if (userName.length === 0) {
            nameError().show();
            return;
        }
        nameError().hide();
        return userName;
    }

    getGameId() {
        return 1;
    }

    refreshAsks(users) {
        let sortable = [];
        for (let user in users) {
            if (users.hasOwnProperty(user)) {
                sortable.push([user, users[user]]);
            }
        }
        sortable.sort(function (a, b) {
            return a[1] - b[1];
        });
        let gameContext = this;
        playersField().empty();
        sortable.forEach(function (element) {
            playersField().append(gameContext.answerTemplate(element[0], element[1]));
        });
    }

    clearField() {
        localStorage.removeItem('users');
        playersField().empty();
    }
}
