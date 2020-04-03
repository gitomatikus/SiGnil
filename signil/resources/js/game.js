const nameError = function () {
    return $('#name-error');
};

export default class game {

    takeAnswer() {
        let time = this.getTime();
        let user = this.getUser();
        if (user && time) {
            this.sendTime(user, time, this.getGameId());
        }
    }

    getTime() {
        let date = new Date();
        return date.getTime()
    }

    answerTemplate(user, time) {
        return '<div class="answers">' + user + ', time: ' + time + '</div>'
    }

    getUser() {
        let userName =  $('#username').val();
        if (userName.length===0) {
            nameError().show();
            return;
        }
        nameError().hide();
        return userName;
    }

    getGameId() {
        return 1;
    }

    sendTime(user, time, gameId) {
        axios.post('/api/ask/answer', {
            time: time,
            user: user,
            game: gameId
        })
    }

    refreshAsks(users) {
        let sortable = [];
        for (let user in users) {
            if (users.hasOwnProperty(user)) {
                sortable.push([user, users[user]]);
            }
        }
        sortable.sort(function(a, b) {
            return a[1] - b[1];
        });
        let gameContext = this;
        $('.content-answers').empty();
        sortable.forEach(function (element) {
            $('.content-answers').append(gameContext.answerTemplate(element[0], element[1]));
        });
    }
}
