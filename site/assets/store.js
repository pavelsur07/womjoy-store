// import './styles/store.css'
// start the Stimulus application
/* import './bootstrap'; */

/* const $ = require('jquery'); */
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
/* require('bootstrap'); */

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

/*
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
}); */

import Blazy from 'blazy'

/*const bLazy = new Blazy()*/

document.addEventListener('DOMContentLoaded', () => {
    const bLazy = new Blazy();

    if (document.querySelectorAll('.product__img_slide').length > 0) {
        document.querySelectorAll('.product__img_slide').forEach(imgSlide => {
            const observer = new MutationObserver(mutations => {
                mutations.forEach(mutation => {
                    const img = mutation.target.querySelector('img');

                    if (!img.classList.contains('b-loaded')) {
                        bLazy.load(img, true);
                    }
                });
            });
            observer.observe(imgSlide, {
                attributes: true,
            });
        });
    }
});
