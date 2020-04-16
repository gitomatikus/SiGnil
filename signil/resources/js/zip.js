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
        data.append('title', 'kek');

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
        })
    }


    var files = evt.target.files;
    for (var i = 0; i < files.length; i++) {
        handleFile(files[i]);
    }
});

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
