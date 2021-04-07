import Axios from "axios";

const questionField = function () {
    return $('#question');
};
const answerField = function () {
    return $('#answers');
};
const timer = function () {
    return $('#timer');
};

export default class questions {

    showQuestion(question, host = true) {
        $('.host-control').hide();
        localStorage.removeItem('question_start');
        let that = this;
        window.marker = false;

        question.special.forEach(function (special) {
            $('<div> <h2 style="max-width: 80%; margin:auto">' + special + '</h2></div><br>').appendTo(questionField());
        });
        question.scenario.forEach(function (question) {
            let questionType = that.getQuestionByType(question, host, marker);
            if (questionType === 'marker') {
                marker = true;
                return;
            }
            if (!marker) {
                $('<div class="media-question">' + questionType + ' </div><br>').appendTo(questionField());
            } else {
                $('<div class="media-answer">' + questionType + ' </div><br>').appendTo(answerField());
            }
        });
        questionField().show();
        //autoplay on chrome work only if user clicked at least ON SOMETHING
        let start = new Date().getTime();
        localStorage.setItem('question_start', start);
        $('.playersAnswers').show();
        window.CurrentQuestion = question;
        $('.showQuestion').show();
        if (!SiGnil.isHost()) {
            let mediaQuestion = $('.media-question');
            let music = mediaQuestion.children('audio')[0];
            if (music !== undefined){
                music.volume = 0.2;
                music.play();
            }
            let video = mediaQuestion.children('video')[0];
            if (video !== undefined){
                video.volume = 0.2;
                video.play();
            }
            this.startTimer(SiGnil.questionTime(), music, video)
        }
    }

    startTimer(time, music, video) {
        let media = false;
        if (music !== undefined){
            media = music;
        } else if (video !== undefined){
            media = music;
        }
        if (media) {
            let i = setInterval(function () {
                if (!media.ended) {
                    return;
                }
                StartTimer(time);
                clearInterval(i);
            }, 1000);
        } else {
            StartTimer(time);
        }
    }

    showAnswer(question) {
        HideTimer();
        question.answer.forEach(function (answer) {
            $('<div>' + answer + ' </div><br>').appendTo(answerField());
        });
        answerField().show();
    }


    unsetQuestion() {
        window.QuestionRound = undefined;
        window.QuestionTheme = undefined;
        window.QuestionId = undefined;
        window.CurrentQuestion = undefined;
    }

    hideQuestions(host = false) {
        localStorage.removeItem('question_start');
        questionField().empty().hide();
        answerField().empty().hide();
        this.unsetQuestion();
        $('.gamefield').show();
        SiGnil.clearField();
        if (host) {
            Axios.post('/api/question/hide', {game: SiGnil.getGameId()})
        }
        $('.playersAnswers').hide();
        $('.host-control').hide();
        SiGnil.playerDemocracy();
    }

    getQuestionByType(question, host = true, marker = false) {
        let property = '';
        if (host) {
            property = '';
        } else {
            property = ''
        }
        if (marker) {
            property = '';
        }

        if (question.hasOwnProperty('say')) {
            return '<h5 style="max-width: 80%; margin:auto; text-align: center">' + question.say + '</h5>';
        }
        if (question.hasOwnProperty('image')) {
            return '<img style="max-width: 800px;max-height: 600px" src="data:image/png;base64, ' + question.image + '"/>';
        }
        if (question.hasOwnProperty('voice')) {
            return '<iframe src="data:audio/mp3;base64,==" allow="autoplay" id="audio" style="display: none"></iframe>' +
                '<audio controls ' + property + '> <source type="audio/mpeg" src="data:audio/mp3;base64,' + question.voice + '";</audio>'
        }
        if (question.hasOwnProperty('video')) {
            return '<video controls ' + property + '> <source type="video/webm" src="data:video/webm;base64,' + question.video + '";</video>'
        }
        if (question.hasOwnProperty('marker')) {
            return 'marker';
        }
    }

    pauseMedia() {
        Axios.post('/api/media/', {
            state: "pause",
            game: SiGnil.getGameId()
        });
    }
    playMedia() {
        Axios.post('/api/media/', {
            state: "play",
            game: SiGnil.getGameId()
        });
    }

    showToPlayers() {
        $('.host-control').hide();
        if (QuestionRound === undefined || QuestionTheme === undefined || QuestionId === undefined) {
            console.log('Questions Undefined. Something went totally wrong');
        }
        Axios.post('/api/question/show', {
            round: QuestionRound,
            theme: QuestionTheme,
            question: QuestionId,
            game: SiGnil.getGameId()
        });
        $('.showAnswer').show();
        let music = $('audio')[0];
        let video = $('video')[0];

        if (music !== undefined){
            music.volume = 0.2;
            music.play();
        }
        if (video !== undefined){
            video.volume = 0.2;
            video.play();
        }
        this.startTimer(SiGnil.questionTime(), music, video)
    }

    showAnswerToPlayers() {
        HideTimer();
        $('.host-control').hide();
        if (QuestionRound === undefined || QuestionTheme === undefined || QuestionId === undefined) {
            console.log('Questions Undefined. Something went totally wrong');
        }
        Axios.post('/api/answer/show', {
            round: QuestionRound,
            theme: QuestionTheme,
            question: QuestionId,
            game: SiGnil.getGameId()
        });
        $('.clearField').show();
        this.unsetQuestion();
    }
}
