const ANSWERED = '<span style="margin:auto; display:table;">-</span>';
window.CurrentRound = 0;

window.RenderHostTable = function (rounds, index) {
    $('.pack-selector').hide();
    Questions.unsetQuestion();
    let round = rounds[index];
    let columns = generateColumns(round.maxQuestions);
    let table = $('#gamefield');
    let gameField = $('.gamefield');
    table.bootstrapTable('destroy');
    table.bootstrapTable({
        classes: 'table table-bordered',
        showHeader: false,
        columns: columns,
        data: round,
        onClickCell: function (field, value, row, element) {
            if (!($(value).hasClass('question'))) {
                return;
            }
            if (value === ANSWERED) {
                return;
            }
            let question = Pack.rounds[index]["themes"][row.themeId]["questions"][field];
            axios.post('/api/question/choose', {round: index, theme: row.themeId, question: field, game: SiGnil.getGameId()});
            // window.QuestionRound = index;
            // window.QuestionTheme = row.themeId;
            // window.QuestionId = field;
            // table.bootstrapTable('updateCell', {index: row.themeId, field: field, value: ANSWERED});
            // addHover();
            // gameField.hide();
            // Questions.showQuestion(question);
            // Questions.showAnswer(question);
        }
    });
    controlsByIndex(index);
    $('#roundName').text(round.name);
    window.RoundName = round.name;
    gameField.show();
    addHover();
    localStorage.removeItem('users');
    $('.host-control').hide();
};

window.RenderPLayerTable = function (rounds, index) {
    $('.host-control').hide();

    let round = rounds[index];
    let columns = generateColumns(round.maxQuestions);
    let table = $('#gamefield');
    let gameField = $('.gamefield');
    table.bootstrapTable('destroy');
    table.bootstrapTable({
        classes: 'table table-bordered',
        showHeader: false,
        columns: columns,
        data: round,
        onClickCell: function (field, value, row, element) {
            if (!($(value).hasClass('question'))) {
                return;
            }
            if (element[0].innerHTML === ANSWERED) {
                return;
            }
            if (!window.CanChooseAnswer) {
                return;
            }
            window.CanChooseAnswer = false;
            let question = Pack.rounds[index]["themes"][row.themeId]["questions"][field];
            axios.post('/api/question/choose', {
                round: index,
                theme: row.themeId,
                question: field,
                game: SiGnil.getGameId()
            });
        }
    });
    $('#roundName').text(round.name);
    gameField.show();
    localStorage.removeItem('users');
    localStorage.removeItem('question_start');
    SiGnil.playerDemocracy();
};

function generateColumns(questionCount) {
    let columns = [];
    columns.push({field: "theme"});
    for (let i = 0; i < questionCount; i++) {
        columns.push({field: i, class: "hoverable"})
    }
    return columns;
}

window.addHover = function() {
    $('.hoverable').hover(function () {
        if ($(this).text() !== '-') {
            $(this).toggleClass('bg');
        }
    })
};

function controlsByIndex(index) {
    if (index === 0) {
        $('#previousRound').hide()
    } else {
        $('#previousRound').show()
    }
    if (index === (Rounds.length - 1)) {
        $('#nextRound').hide()
    } else {
        $('#nextRound').show();
    }
}

window.ChangeRound = function (direction) {
    if (!Rounds) {
        console.log('Rounds not loaded');
        return;
    }
    if (!CurrentRound && CurrentRound !== 0) {
        console.log('Current round is not found. Something went totally wrong');
        return;
    }
    if (direction !== 'next' && direction !== 'previous') {
        console.log('direction can be only "next" or "previous"');
        return;
    }
    if (direction === 'next') {
        CurrentRound++;
    } else {
        CurrentRound--;
    }
    if (CurrentRound < 0 || CurrentRound > (Rounds.length - 1)) {
        console.log('Wrong direction. "CurrentRound" returned to default');
        CurrentRound = 0;
    }
    RenderHostTable(Rounds, CurrentRound);
    axios.post('/api/round/change', {game: SiGnil.getGameId(), round: CurrentRound})
};
