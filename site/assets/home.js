import 'swiper/css';

if (document.querySelector('.hero-swiper')) {
    new Swiper('.hero-swiper', {
        slidesPerView: 1,
        pagination: {
            el: '.hero-pagination',
            clickable: true,
            bulletClass: 'hero-pagination-bullet',
            bulletActiveClass: 'hero-pagination-bullet-active'
        }
    });
}

const products = document.querySelectorAll('.product');

products.forEach((product, i) => {
    const images = product.querySelectorAll('.product__img_slide');

    let finalHTML = '<div class="product__img_hovers">';
    for (let a = 0; a < images.length; a++) {
        finalHTML += '<div class="product__img_hover"></div>';
    }
    finalHTML += '</div>';

    finalHTML += '<div class="product__img_checks">';
    for (let a = 0; a < images.length; a++) {
        finalHTML += '<div class="product__img_check"></div>';
    }
    finalHTML += '</div>';


    product.querySelector('.product__img').insertAdjacentHTML('beforeend', finalHTML);


    const hovers = product.querySelectorAll('.product__img_hover');
    const checks = product.querySelectorAll('.product__img_check');

    hovers.forEach((hover, i) => {
        hover.addEventListener('mouseover', () => {
            product.querySelector('.product__img_slide.active')?.classList.remove('active');
            product.querySelector('.product__img_check.active')?.classList.remove('active');

            images[i].classList.add('active');
            checks[i].classList.add('active');
        });
    });
});

const productSliders = document.querySelectorAll('.products__slider');
if (productSliders.length > 0) {
    productSliders.forEach(productSlider => {
        new Swiper(productSlider.querySelector('.products-swiper'), {
            slidesPerView: 4,
            spaceBetween: 20,
            navigation: {
                prevEl: productSlider.querySelector('.slider-btn-prev'),
                nextEl: productSlider.querySelector('.slider-btn-next'),
            },
            breakpoints: {
                0: {
                    slidesPerView: 2.1,
                    spaceBetween: 10
                },
                480: {
                    slidesPerView: 4,
                    spaceBetween: 20
                },
            }
        });
    });
}