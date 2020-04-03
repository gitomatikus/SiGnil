const questionField = function () {
    return $('#question');
};
const timer = function () {
    return $('#timer');
};

export default class questions {

    showQuestion(id) {
        localStorage.removeItem('question_start');
        questionField().show();
        let start = new Date().getTime();
        localStorage.setItem('question_start', start)
    }


}
