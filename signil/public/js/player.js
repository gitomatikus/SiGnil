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
        console.log(message, CurrentRound);
        if (Pack !== undefined &&
            Rounds !== undefined) {
            let row = parseInt(message.theme);
            row++;
            let cell = parseInt(message.question);
            cell++;
            console.log(row, cell);
            $('tr').eq(row).find('td').eq(cell).addClass('bg');
        }
    });

window.Echo.channel('game.1')
    .listen('ShowQuestion', function (message) {
        let question = Pack.rounds[message.round]["themes"][message.theme]["questions"][message.question];
        $('.gamefield').hide();
        Questions.showQuestion(question, false);
        if (marker) {
            let music = $('audio').last()[0];
            if (music !== undefined){
                music.volume = 0.2;
                music.pause();
            }
            let video = $('video').last()[0];
            if (video !== undefined){
                video.volume = 0.2;
                video.pause();
            }
        }
    });

window.Echo.channel('game.1')
    .listen('ShowAnswer', function (message) {
        let question = Pack.rounds[message.round]["themes"][message.theme]["questions"][message.question];
        $('#question').hide();
        Questions.showAnswer(question);
        if (marker) {
            //autoplay on chrome work only if user clicked at least ON SOMETHING
            let music = $('audio').last()[0];
            if (music !== undefined){
                music.volume = 0.2;
                music.play();
            }
            let video = $('video').last()[0];
            if (video !== undefined){
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
        $('#pack-status').text('Pack Downloaded');
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
    localStorage.setItem('username', $('#username').val());
    $('.name').hide();
};

