window.JSZip = require('jszip');
var zip = new JSZip();
var $result = $("#result");
$("#file").on("change", function(evt) {
    // remove content
    $result.html("");
    // be sure to show the results
    $("#result_block").removeClass("hidden").addClass("show");

    // Closure to capture the file information.
    function handleFile(f) {
        let data = new FormData();
        data.append('file', f, f.name);
        data.append('game', 1);

        axios.post('/api/file', data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: (progressEvent) => {
                const totalLength = progressEvent.lengthComputable ? progressEvent.total : progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length');
                console.log(Math.round( (progressEvent.loaded * 100) / totalLength ));
            },
            onDownloadProgress: (progressEvent => {
                const totalLength = progressEvent.lengthComputable ? progressEvent.total : progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length');
                console.log(Math.round( (progressEvent.loaded * 100) / totalLength ));
            })
        }).then(function (response) {
                window.Pack = response.data;
                let rounds = PrepareRounds(Pack);
                RenderHostTable(rounds, 0);
        })
    }


    var files = evt.target.files;
    for (var i = 0; i < files.length; i++) {
        handleFile(files[i]);
    }
});

window.PrepareRounds = function (pack) {
    let rounds = [];
    pack.rounds.forEach(function (round, roundId) {
        let themes = round.themes;
        let row = [];
        let maxQuestions = 0;
        themes.forEach(function(theme, themeId) {
            row[themeId] = [];
            row[themeId]['theme'] = theme.name;
            row[themeId]['themeId'] = themeId;
            row[themeId]['roundId'] = roundId;
            if (maxQuestions < theme.questions.length) {
                maxQuestions = theme.questions.length;
            }
            theme.questions.forEach(function(question, number){
                row[themeId][number] = '<span style="margin:auto; display:table;" class="question">'+question.price+'</span>'
            })
        });
        rounds[roundId] = [];
        rounds[roundId] = row;
        rounds[roundId]['maxQuestions'] = maxQuestions;
        rounds[roundId]['name'] = round.name;
    });
    window.Rounds = rounds;
    return rounds;
};

function validate(zip) {
    if (!zip.hasOwnProperty('files')) {
        console.log('Wrong file');
        return false;
    }
    if (!zip.files.hasOwnProperty('content.xml')){
        console.log('Wrong file');
        return false;
    }
    return true;
}
