/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// Styles
// any SASSyou import will output into a single css file (app.css in this case)
import '../styles/scss/libraries/bootstrap.scss';
import '../styles/css/app.css';
/* Template customized */
import "../styles/scss/template_custom.scss";

// JQuery
const $ = require('jquery');
// Set jQuery globally
global.$ = global.jQuery = $;

// Bootstrap
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// Violet template
require('./libraries/violet_template/jquery.magnific-popup.min');
require('./libraries/violet_template/jquery.slicknav');
require('./libraries/violet_template/owl.carousel.min');
require('./libraries/violet_template/jquery.nice-select.min');
// require('./libraries/violet_template/mixitup.min');
require('./libraries/violet_template/main');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});