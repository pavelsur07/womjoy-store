document.addEventListener('DOMContentLoaded', () => {
	if (window.innerWidth <= 480) {
		mobMenuInit('.burger', '.burger-menu');
		mobMenuInit('.c-open', '.catalog-menu');

		const accordeons = document.querySelectorAll('.accordeon');
		accordeons.forEach(accordeon => {
			const accordeonItems = accordeon.querySelectorAll('.accordeon__item');
			accordeonItems.forEach(item => item.addEventListener('click', () => {
				toggleItem(item.querySelector('.accordeon__trigger'), item.querySelector('.accordeon__content'))
			}));
		});

		const productSwipers = document.querySelectorAll('.products-swiper.is-swiper');
		console.log(productSwipers);
		productSwipers.forEach(productSwiper => {
			new Swiper(productSwiper, {
				slidesPerView: 2.1,
				spaceBetween: 10,
			});
		});
	}

	const countryBtn = document.querySelector('.country__crnt');
	const countryClose = document.querySelector(['.country__close'])
	const countryContent = document.querySelector('.country__content');
	countryBtn.addEventListener('click', () => {
		countryBtn.classList.toggle('active');
		countryContent.classList.toggle('active');
	});
	countryClose.addEventListener('click', () => {
		countryBtn.classList.remove('active');
		countryContent.classList.remove('active');
	});

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
});

function mobMenuInit(burgerClass, targetClass) {
	const burgers = document.querySelectorAll(burgerClass);
	const target = document.querySelector(targetClass);

	burgers.forEach(burger => burger.addEventListener('click', e => {
		e.preventDefault();
		target.classList.toggle('active');
		burger.classList.toggle('active');
	}));

}

function toggleItem(drop, list) {
    if (list.jsAnimated) {
        return;
    }

    if (list.classList.contains('active')) {
        if ('animate' in list) {
            jsHeightAnimation(list, true, function() {
                list.classList.remove('active');
                drop.classList.remove('active');
            });
        } else {
            list.classList.remove('active');
            drop.classList.remove('active');
        }

    } else {
        if ('animate' in list) {
            list.classList.add('active');
            drop.classList.add('active');
            jsHeightAnimation(list, false, function() {});
        } else {
            list.classList.add('active');
            drop.classList.add('active');
        }
    }
}

function jsHeightAnimation(el, isReverse, onFinish) {
    if (el.jsAnimated) {
        return;
    } else {
        el.jsAnimated = true;
    }

    let animate = el.animate([
        { height: 0 },
        { height: el.scrollHeight + 'px' }
    ], {
        duration: 280,

        direction: isReverse ? 'reverse' : 'normal',
    });

    animate.addEventListener('finish', function() {
        el.jsAnimated = false
        onFinish();
    });
}