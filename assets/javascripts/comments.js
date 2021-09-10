const commentsNumber = document.querySelector('#admin_comments_length');
const $localeUser = commentsNumber.dataset.userLocale;

fetch("/"+$localeUser+"/admin/comment/length_comment/notHandled", {
    method: "GET",
})
    .then(function (response) {
        return response.json();
    })
    .then(function (response) {
        const nbComments = response.nbComments;
        if(Number(nbComments) > 0) {
            // Add nb messages
            const nbMessage = document.createTextNode(nbComments);
            commentsNumber.appendChild(nbMessage);
        }
    })
    .catch(function (error) {
        console.error("Error:", error);
    });