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

    showQuestion(question) {
        localStorage.removeItem('question_start');
        let that = this;
        let marker = false;
        question.scenario.forEach(function (question) {
            let questionType = that.getQuestionByType(question);
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
        let start = new Date().getTime();
        localStorage.setItem('question_start', start)
    }

    showAnswer(question) {
        question.answer.forEach(function (answer) {
            $('<div>' + answer + ' </div><br>').appendTo(answerField());
        });
        answerField().show();
    }

    hideQuestions() {
        localStorage.removeItem('question_start');
        questionField().empty().hide();
        answerField().empty().hide();
        $('.gamefield').show();

    }

    getQuestionByType(question, host = true) {
        let property = '';
        if (host) {
            property = 'autoplay';
        } else {
            property = 'autoplay'
        }

        if (question.hasOwnProperty('say')) {
            return '<h5 style="max-width: 80%; margin:auto">'+question.say+'</h5>';
        }
        if (question.hasOwnProperty('image')) {
            console.log(question);
            return '<img src="data:image/png;base64, ' + question.image + '"/>';
        }
        if (question.hasOwnProperty('voice')) {
            return '<audio ' +property +'> <source type="audio/mpeg" src="data:audio/mp3;base64,' + question.voice +'";</audio>'
        }
        if (question.hasOwnProperty('video')) {
            return '<video ' +property +'> <source type="video/webm" src="data:video/webm;base64,' + question.video +'";</video>'
        }
        if (question.hasOwnProperty('marker')) {
            return 'marker';
        }
    }
}
