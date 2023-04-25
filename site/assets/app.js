import './styles/app.scss';
import '@tabler/core'

import './bootstrap';

const $ = require('jquery');
global.$ = global.jQuery = $;
$(document).ready(function () {
    $('[data-toggle="popover"]').popover()
})
