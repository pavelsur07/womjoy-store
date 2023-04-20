import './styles/app.scss';
// start the Stimulus application
import '@tabler/core'
import './bootstrap';

const $ = require('jquery');
global.$ = global.jQuery = $;
$(document).ready(function () {
    $('[data-toggle="popover"]').popover()
})
