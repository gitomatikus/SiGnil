const nameError = function () {
    return $('#name-error');
};
const playersField = function () {
    return $('#playersAnswers');
};

const usernameInput = function () {
    return $('#username');
};
window.CanChooseAnswer = false;
export default class game {

    askForAnswer() {
        let time = this.getTime();
        let user = this.getUser();
        let gameId = this.getGameId();

        let users = JSON.parse(localStorage.getItem('users'));
        if (!users) {
            users = {};
        }
        let canTakeAnswer = $('.takeAnswer').is(":visible");
        if (user && time && !users.hasOwnProperty(user) && canTakeAnswer) {
            $('.takeAnswer').hide();
            axios.post('/api/ask/answer', {
                time: time,
                user: user,
                game: gameId
            })
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
        let seconds = (finishTime - startTime) / 1000;
        return seconds;
    }

    answerTemplate(user, time) {
        return '<div data-user="' + user + '" class="answers">' + user + ', Время: ' + time + ' секунд. ' +
            '<span class="right-answer answer-control">Правильно</span> <span  class="answer-control">/</span> <span class="answer-control wrong-answer">Неправильно</span></div>'
    }

    getUser() {
        return localStorage.getItem('username');
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
        $('.right-answer').click(function () {
            let answer = $(this).parent('.answers');
            let user = answer.data('user');
            let price = CurrentQuestion.price;

            let currentScore = parseInt($('.score[data-user="' + user + '"]').val());
            if (!currentScore) {
                currentScore = 0;
            }
            let resultScore = parseInt(currentScore) + parseInt(price);
            $('.answer-control').hide();
            axios.put('/api/user', {
                game: SiGnil.getGameId(),
                username: user,
                score: resultScore,
                control: true
            });
        });
        $('.wrong-answer').click(function () {
            let answer = $(this).parent('.answers');
            let user = answer.data('user');
            let price = CurrentQuestion.price;

            let currentScore = parseInt($('.score[data-user="' + user + '"]').val());
            if (!currentScore) {
                currentScore = 0;
            }
            let resultScore = parseInt(currentScore) - parseInt(price);
            $($('.right-answer')[0]).parent('.answers').children('.answer-control').hide();
            axios.put('/api/user', {
                game: SiGnil.getGameId(),
                username: user,
                score: resultScore,
                control: false
            });
        })
    }

    clearField() {
        localStorage.removeItem('users');
        playersField().empty();
    }

    updatePlayers(players) {
        window.CanChooseAnswer = false;
        let that = this;
        $('.playersList').empty();
        Object.entries(players).forEach(function (val) {
            let player = val[1];
            $('.playersList').append(that.userTemplate(player.name, player.img, player.score));
        });
        if (SiGnil.getUser()) {
            let user = SiGnil.getUser().trim();
        }
        $('.hoverable').removeClass('bgc');
        this.playerDemocracy
    }

    playerDemocracy() {
        $('.hoverable').removeClass('bgc');
        let players = window.Players;
        if (!SiGnil.isHost()) {
            if (players[user] !== undefined &&
                players[user]["control"] !== undefined &&
                players[user]["control"] === true
            ) {
                $('.hoverable').unbind('mouseenter mouseleave');
                $('.hoverable').hover(function () {
                    if ($(this).text() !== '-') {
                        $(this).toggleClass('bgc');
                    }
                });
                let disabled = false;
                $('.hoverable').click(function () {
                    if ($(this)[0].innerText!=='-')
                        if (!disabled) {
                            disabled = true;
                            $('.hoverable').hover(function () {
                                if ($(this).text() !== '-') {
                                    $(this).toggleClass('bgc');
                                }
                            });
                        }
                    }
                );
                window.CanChooseAnswer = true;

            } else {
                $('.hoverable').unbind('mouseenter mouseleave');
            }
        }

    };

    userTemplate(name, image, score) {
        return '                <div class="col-md playerPhoto img-fluid" style="">\n' +
            '                    <img class="img-fluid photo" src="data:image/png;base64, ' + image + '"/>\n' +
            '                    <div class="playersNames"><span class="username">' + name + '</span><br><input data-user="' + name + '" disabled type="text" class="score scoreInput" value="' + score + '"></div>\n' +
            '                </div>';
    }

    questionTime() {
        return 15;
    }

    answerTime() {
        return 15;
    }

    isHost() {
        return ($('body').hasClass('host-mode'));
    }
}
window.Countdown = false;
window.StartTimer = function (start) {
    $('#timer').show();
    var seconds = document.getElementById("countdown").textContent = start;
    window.Countdown = setInterval(function () {
        seconds--;
        document.getElementById("countdown").textContent = seconds;
        if (seconds <= 0) clearInterval(Countdown);
    }, 1000);
};

window.HideTimer = function () {
    $('#timer').hide();
    if (Countdown) {
        clearInterval(Countdown);
    }
};

