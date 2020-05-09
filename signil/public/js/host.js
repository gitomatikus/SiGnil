const ANSWERED = '<span style="margin:auto; display:table;">-</span>';

window.Echo.channel('game.1')
    .listen('UpdatePlayers', function(message) {
        SiGnil.updatePlayers(message.players);
        let score = $('.score');
        score.removeAttr('disabled');
        score.change(function () {
            axios.put('/api/user/', {
                game: SiGnil.getGameId(),
                username: $(this).data('user'),
                score: $(this).val()
            })
        });
        $('.photo').click(function () {
            if(confirm('Вы точно хотите удалить игрока?')) {
                let username = $(this).parent('.playerPhoto').children('.playersNames').children('.score').data('user');
                axios.delete('/api/user?game='+SiGnil.getGameId()+'&username='+username)
            }
        })
    });
window.Echo.channel('game.1')
    .listen('ChooseQuestion', function (message) {
        let table = $('#gamefield');
        let gameField = $('.gamefield');
        window.QuestionRound = message.round;
        window.QuestionTheme = message.theme;
        window.QuestionId = message.question;
        table.bootstrapTable('updateCell', {index: message.theme, field: message.question, value: ANSWERED});
        addHover();
        gameField.hide();
        let question = Pack.rounds[message.round]["themes"][message.theme]["questions"][message.question];
        Questions.showQuestion(question);
        Questions.showAnswer(question);
    });


function run() {
    if ( window.jQuery){
        let username = localStorage.getItem('username');
        if (localStorage.getItem('username')) {
            axios.post('/api/user/', {
                game: SiGnil.getGameId(),
                host: true
            })
        }



    }
    else{
        window.setTimeout("run()",100);
    }
}
run();
