/**
 * https://symfony.com/doc/current/form/form_collections.html
 */
const $divImages = $('#div_product_images');

let $collectionImageHolder;
let $collectionImageHolderCurrent;

$('fieldset').hide();

$(document).ready(function()
{
    // Retrieves th text translated from twig
    $translations = $('#product_collection_translations')[0];
    $textAddImage = $translations.dataset.textAddImage;
    $textDeleteImage = $translations.dataset.textDeleteImage;
    $textChooseImage = $translations.dataset.textChooseImage;

    // Init html element
    let $addImageButton = $('<button type="button" class="btn btn-primary add_image_link mt-3" style="margin-bottom: 20px" title="'+$textAddImage+'"><i class="fas fa-image"></i> '+$textAddImage+'</button>');
    let $newLinkImageDiv = $('<div></div>').append($addImageButton);

    //Image Collection
    $collectionImageHolder = $('div.product_images_new');
    $collectionImageHolderCurrent = $('div.product_images_current');
    $collectionImageHolder.append($newLinkImageDiv);
    $collectionImageHolder.data('index', ($collectionImageHolderCurrent.find('.product_image').length + $collectionImageHolder.find('.product_image').length));


    $addImageButton.on('click', function(e)
    {
        e.preventDefault();
        addImageForm($collectionImageHolder, $newLinkImageDiv);
    })

    $collectionImageHolder.find('.product_image').each(function() {
        addImageFormDeleteLink($(this));
    });

    //Image functions
    function addImageForm($collectionImageHolder, $newLinkImageDiv) {
        let prototype = $collectionImageHolder.data('prototype');
        let index = $collectionImageHolder.data('index');

        let newForm = prototype;
        newForm = newForm.replace(/__name__label__/g, index);
        newForm = newForm.replace(/__name__/g, index);
        $collectionImageHolder.data('index', index + 1);
        let $newFormDiv = $('<div class="image"></div>').append(newForm).append('<hr>');
        $newFormDiv.find('#product_images_'+index).find('label.custom-file-label').text($textChooseImage);
        $newLinkImageDiv.before($newFormDiv);
        addImageFormDeleteLink($newFormDiv);
    }

    function addImageFormDeleteLink($newFormDiv) {
        let $removeFormButton = $('<button type="button" class="btn btn-danger" title="'+$textDeleteImage+'"><i class="fas fa-minus-circle"></i> '+$textDeleteImage+'</button>');
        $newFormDiv.append($removeFormButton);

        $removeFormButton.on('click', function(e) {
            $newFormDiv.remove();
        });
    }
})