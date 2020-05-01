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
                $('<div>' + questionType + ' </div><br>').appendTo(questionField());
            } else {
                $('<div>' + questionType + ' </div><br>').appendTo(answerField());
            }
        });
        questionField().show();
        //autoplay on chrome work only if user clicked at least ON SOMETHING
        let music = $('audio')[0];
        if (music !== undefined){
            music.volume = 0.2;
            music.play();
        }
        let video = $('video')[0];
        if (video !== undefined){
            video.volume = 0.2;
            video.play();
        }

        let start = new Date().getTime();
        localStorage.setItem('question_start', start);
        $('.playersAnswers').show();
        window.CurrentQuestion = question;
        $('.showQuestion').show();
    }

    showAnswer(question) {
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
    }

    getQuestionByType(question, host = true, marker = false) {
        let property = '';
        if (host) {
            property = '';
        } else {
            property = 'autoplay'
        }
        if (marker) {
            property = '';
        }

        if (question.hasOwnProperty('say')) {
            return '<h5 style="max-width: 80%; margin:auto; text-align: center">' + question.say + '</h5>';
        }
        if (question.hasOwnProperty('image')) {
            return '<img src="data:image/png;base64, ' + question.image + '"/>';
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
    }

    showAnswerToPlayers() {
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
