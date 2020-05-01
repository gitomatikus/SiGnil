
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
