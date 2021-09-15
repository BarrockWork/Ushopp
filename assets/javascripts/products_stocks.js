const commentsNumber = document.querySelector('#admin_stocks_length');
const $localeUser = commentsNumber.dataset.userLocale;

fetch("/"+$localeUser+"/admin/product/stock/empty", {
    method: "GET",
})
    .then(function (response) {
        return response.json();
    })
    .then(function (response) {
        const nbOutOfStock = response.nbOutOfStock;
        if(Number(nbOutOfStock) > 0) {
            // Add nb messages
            const nbMessage = document.createTextNode(nbOutOfStock);
            commentsNumber.appendChild(nbMessage);
        }
    })
    .catch(function (error) {
        console.error("Error:", error);
    });