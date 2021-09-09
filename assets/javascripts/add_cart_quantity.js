(function() {
    const btnAdd = document.querySelector("#btnAddToCart");
    const quantityCart = document.querySelector('#inputCartQuantity');
    const $localeUser = quantityCart.dataset.userLocale;
    const $productId = quantityCart.dataset.productId;
    const minusSpan = document.querySelector('.dec');
    const plusSpan = document.querySelector('.inc');

    // Add to cart event
    btnAdd.addEventListener('click', () => {
        fetch("/"+$localeUser+"/cart/add_quantity/"+$productId+"/"+quantityCart.value, {
            method: "GET",
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (response) {
                const urlRedirect = response.urlRedirect;
                window.location.replace(urlRedirect);
            })
            .catch(function (error) {
                console.error("Error:", error);
            });
    })

    // Dec event
    minusSpan.addEventListener('click', (e) => {
        if(Number(quantityCart.value) <= 1) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    })

    // Inc event
    plusSpan.addEventListener('click', (e) => {
        if(Number(quantityCart.value) >= 12) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    })

    // Quantity event
    quantityCart.addEventListener('change', (e) => {
        const $quantValue = quantityCart.value;

        if($quantValue > 12) {
            quantityCart.value = 12;
        }

        if($quantValue < 1){
            quantityCart.value = 1;
        }
    })
})();


