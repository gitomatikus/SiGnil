window.Echo.channel('game.1')
    .listen('PackHosted', function(message) {
        downloadPack(message.hash)
    });


window.circle = new window.ProgressBar.Line('#progress', {
    color: '#FCB03C',
    duration: 1000,
    easing: 'easeInOut'
});

window.downloadPack = function (hash) {
    let update = true;
    axios.get('/api/file/' + hash, {
        onDownloadProgress: (progressEvent => {
            const totalLength = progressEvent.lengthComputable ? progressEvent.total : progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length');
            let progress = progressEvent.loaded  / totalLength;
            progress =  parseFloat(progress).toFixed(2);

            if (update && progress>0.05) {
                update = false;
                setTimeout(() => {
                    update = true;
                }, 1100);
                circle.animate(progress)
            }
        })
    }).then(response => {
        console.log(response);
        setTimeout(() => {
            circle.animate(1)
        }, 1100);
        window.Pack = response.data;
        $('#pack-status').text('Pack Downloaded')
    }).catch(e => {
        console.log(e);
    });
};
