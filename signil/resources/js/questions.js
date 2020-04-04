const questionField = function () {
    return $('#question');
};
const timer = function () {
    return $('#timer');
};

export default class questions {

    showQuestion(id) {

        //random questions for test
        id = Math.floor(Math.random() * Math.floor(5));

        console.log(id);
        localStorage.removeItem('question_start');
        let questionId = 'question['+id+']';
        let question = localStorage.getItem(questionId);
        questionField().text(question);
        questionField().show();
        let start = new Date().getTime();
        localStorage.setItem('question_start', start)
    }

    hideQuestion(id) {
        localStorage.removeItem('question_start');
        questionField().hide();
    }
}
