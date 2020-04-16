// var ProgressBar = require('progressbar.js');
// window.circle = new ProgressBar.Line('#progress', {
//     color: '#FCB03C',
//     duration: 1000,
//     easing: 'easeInOut'
// });
//
// window.downloadPack = function () {
//     let update = true;
//     axios.get('/api/file/853aafe9dc0b6057bb49dc5fd6cd20e3', {
//         onDownloadProgress: (progressEvent => {
//             const totalLength = progressEvent.lengthComputable ? progressEvent.total : progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length');
//             let progress = progressEvent.loaded  / totalLength;
//             progress =  parseFloat(progress).toFixed(2);
//
//             if (update && progress>0.05) {
//                 update = false;
//                 setTimeout(() => {
//                     update = true;
//                 }, 1100);
//                 circle.animate(progress)
//             }
//         })
//     }).then(response => {
//         console.log(response);
//         setTimeout(() => {
//             circle.animate(1)
//         }, 1100);
//         window.Pack = response.data;
//     }).catch(e => {
//         console.log(e);
//     });
// };
