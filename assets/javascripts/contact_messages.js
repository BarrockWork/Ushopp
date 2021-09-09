const notifNumber = document.querySelector('#admin_notifications_number');
const notifTitle = document.querySelector('#admin_notifications_title');
const notifNewMessages = document.querySelector('#admin_notifications_menu');
const $localeUser = notifNewMessages.dataset.userLocale;

fetch("/"+$localeUser+"/contact_messages/length_message/notHandled", {
    method: "GET",
})
    .then(function (response) {
        return response.json();
    })
    .then(function (response) {
        const nbContacts = response.nbContacts;
        const textNewMessages = response.textNewMessage;
        if(Number(nbContacts) > 0) {
            // Add nb messages
            const nbMessage = document.createTextNode(nbContacts);
            notifNumber.appendChild(nbMessage);
            const $notiftitle = document.createTextNode(nbContacts+' notifications');
            notifTitle.appendChild($notiftitle);
            const textMessage = document.createTextNode(textNewMessages);
            notifNewMessages.appendChild(textMessage);

        }
    })
    .catch(function (error) {
        console.error("Error:", error);
    });