const ANSWERED = '<span style="margin:auto; display:table;">-</span>';

$(document).ready(function () {
    let name = localStorage.getItem('username');
    if (!name) {
        $('.name').show();
    }
});
window.Echo.channel('game.1')
    .listen('PackHosted', function (message) {
        downloadPack(message.hash)
    });


window.circle = new window.ProgressBar.Line('#progress', {
    color: '#FCB03C',
    duration: 1000,
    easing: 'easeInOut'
});

window.Echo.channel('game.1')
    .listen('ChooseQuestion', function (message) {
        if (Pack !== undefined &&
            Rounds !== undefined) {
            let row = parseInt(message.theme);
            row++;
            let cell = parseInt(message.question);
            cell++;
            $('tr').eq(row).find('td').eq(cell).addClass('bg');
        }
    });

window.Echo.channel('game.1')
    .listen('ShowQuestion', function (message) {
        $('.takeAnswer').show();
        let question = Pack.rounds[message.round]["themes"][message.theme]["questions"][message.question];
        $('.gamefield').hide();
        Questions.showQuestion(question, false);
    });

window.Echo.channel('game.1')
    .listen('ShowAnswer', function (message) {
        $('.takeAnswer').hide();
        let question = Pack.rounds[message.round]["themes"][message.theme]["questions"][message.question];
        $('#question').hide();
        Questions.showAnswer(question);
        if (marker) {
            //autoplay on chrome work only if user clicked at least ON SOMETHING
            let music = $('audio').last()[0];
            if (music !== undefined) {
                music.volume = 0.2;
                music.play();
            }
            let video = $('video').last()[0];
            if (video !== undefined) {
                video.volume = 0.2;
                video.play();
            }
        }
        marker = false;
    });

window.Echo.channel('game.1')
    .listen('HideQuestion', function (message) {
        Questions.hideQuestions();
        $('.gamefield').show();
        $('.bg').html(ANSWERED).removeClass('bg');
    });


window.Echo.channel('game.1')
    .listen('ChangeRound', function (message) {
        window.CurrentRound = message.random;
        RenderPLayerTable(Rounds, message.round);
    })
    .listen('UpdatePlayers', function (message) {
        SiGnil.updatePlayers(message.players);
    });


window.downloadPack = function (hash) {
    let update = true;
    axios.get('/api/file/' + hash, {
        onDownloadProgress: (progressEvent => {
            const totalLength = progressEvent.lengthComputable ? progressEvent.total : progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length');
            let progress = progressEvent.loaded / totalLength;
            progress = parseFloat(progress).toFixed(2);

            if (update && progress > 0.05) {
                update = false;
                setTimeout(() => {
                    update = true;
                }, 1100);
                circle.animate(progress)
            }
        })
    }).then(response => {
        setTimeout(() => {
            circle.animate(1)
        }, 1100);
        window.Pack = response.data;
        $('#pack-status').text('Пак скачался');
        setTimeout(() => {
            let rounds = PrepareRounds(Pack);
            RenderPLayerTable(rounds, 0);
            $('.pack-progress').hide();
        }, 2000);
    }).catch(e => {
        console.log('error', e);
    });
};

window.SubmitName = function () {
    let name = $('#username').val();
    let img = $('#img').prop('files')[0];
    if (img === undefined) {
        img = false;
    }
    let data = new FormData();
    data.append('img', img, img.name);
    data.append('game', SiGnil.getGameId());
    data.append('username', name);

    axios.post('/api/user/', data, {
        headers: {
            'Content-Type': 'multipart/form-data'
        },
    });
    localStorage.setItem('username', name);
    $('.name').hide();
    showLogout()
};

document.addEventListener("DOMContentLoaded", function (event) {
    setTimeout(function () {
        let username = localStorage.getItem('username');
        if (localStorage.getItem('username')) {
            axios.post('/api/user/', {
                game: SiGnil.getGameId(),
                username: username
            })
        }
    }, 1000)
});


window.StrangerThing = false;
if (document.addEventListener) {
    document.addEventListener('contextmenu', function (e) {
        SiGnil.askForAnswer();
        e.preventDefault();
    }, false);
} else {
    document.attachEvent('oncontextmenu', function () {
        if (!window.StrangerThing) {
            alert("Скажите фантому, что сработала штука, которая непонятно что должна делать");
            window.StrangerThing = true;
        }
        SiGnil.askForAnswer();
        window.event.returnValue = false;
    });
}

function run() {
    if (window.jQuery) {
        $(window).keypress(function (e) {
            if (e.key === ' ' || e.key === 'Spacebar') {
                if ($('.answerInput').is(":visible")) {
                    SiGnil.askForAnswer();
                }
            }
        })
    } else {
        window.setTimeout("run()", 100);
    }
}

run();
let user = localStorage.getItem('username');

function showLogout() {
    let user = localStorage.getItem('username');
    let logOutField = $('.logout');
    if (user) {
        logOutField.append('<span class="logout-username"></span><br>');
        logOutField.append('<input type="button" class="takeAnswer" value="Выйти" onclick="logout()">\n');
        $('.logout-username').text(user);
        $('.name').hide();
        logOutField.show();
    } else {
        logOutField.hide();
        $('.name').show();
    }
}

function logout() {
    let logOutField = $('.logout');
    let user = localStorage.getItem('username');
    logOutField.empty();
    axios.delete('/api/user?game=' + SiGnil.getGameId() + '&username=' + user)
    localStorage.removeItem('username');
    $('.name').show();

}

showLogout();
