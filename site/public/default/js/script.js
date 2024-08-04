document.addEventListener('DOMContentLoaded', () => {
	if (window.innerWidth <= 500) {
		mobMenuInit('.burger', '.burger-menu');
		mobMenuInit('.c-open', '.catalog-menu');
		mobMenuInit('.mob-bar__drop_trigger', '.mob-bar__drop_list');

		const burgerItems = document.querySelectorAll('.burger-menu__item.dropable');
		burgerItems.forEach(item => {
			item.querySelector('a').addEventListener('click', e => e.preventDefault());
			item.addEventListener('click', () => {
				toggleItem(item.querySelector('.burger-menu__title'), item.querySelector('.burger-menu__sublist'));
			})
		});
		const catalogItems = document.querySelectorAll('.catalog-menu__item');
		catalogItems.forEach(item => item.addEventListener('click', () => {
			toggleItem(item.querySelector('.catalog-menu__title'), item.querySelector('.catalog-menu__sublist'));
		}));

		const sort = document.querySelector('.sort');
		const sortTrigger = document.querySelector('.sort__trigger');

		if (sort) {
			sortTrigger.addEventListener('click', () => sort.classList.toggle('active'));

			document.querySelectorAll('.sort__list li').forEach(item => item.addEventListener('click', () => {
				const activeLi = document.querySelector('.sort__list li.active');
				if (activeLi) activeLi.classList.remove('active');

				sort.classList.remove('active');
				sortTrigger.textContent = item.textContent;
				item.classList.add('active');
			}));
		}


		const filter = document.querySelector('.filter');
		const filterTrigger = document.querySelector('.filter-trigger');
		const filterClose = document.querySelector('.filter__close');
		if (filter) {
			filterTrigger.addEventListener('click', () => filter.classList.add('active'));
			filterClose.addEventListener('click', () => filter.classList.remove('active'));
		}

		const pagesTrigger = document.querySelector('.pages__top');
		const pagesList = document.querySelector('.pages__list');
		if (pagesTrigger) {
			pagesTrigger.addEventListener('click', () => {
				toggleItem(pagesTrigger, pagesList);
			});
		}
	}


	const phoneFields = document.querySelectorAll('.phone-masked-field');
	if (phoneFields.length > 0) {
		phoneFields.forEach(field => {
			let im = new Inputmask('+7 999 999-99-99');
			im.mask(field);
		});
	}

	const historyItemsBlock = document.querySelector('.history__items');
	const historyBack = document.querySelector('.history__back');
	const historyItems = document.querySelectorAll('.history__item');
	const historyOrders = document.querySelectorAll('.order');
	if (historyItems.length > 0) {
		historyItems.forEach((item, i) => {
			item.addEventListener('click', () => {
				document.querySelector('.history__item.active')?.classList.remove('active');
				document.querySelector('.history__back.active')?.classList.remove('active');
				document.querySelector('.order.active')?.classList.remove('active');

				if (window.innerWidth <= 500) {
					historyItemsBlock.classList.add('hidden');
					historyBack.classList.add('active');

					historyBack.addEventListener('click', () => {
						document.querySelector('.history__item.active')?.classList.remove('active');
						document.querySelector('.history__back.active')?.classList.remove('active');
						document.querySelector('.order.active')?.classList.remove('active');
						historyItemsBlock.classList.remove('hidden');
						historyBack.classList.remove('active');
					})
				}

				item.classList.add('active');
				historyOrders[i].classList.add('active');
			});
		});
	}

	const modalCallers = document.querySelectorAll('.call-modal');
	modalCallers.forEach(modalCaller => modalCaller.addEventListener('click', () => {
		const modal = document.querySelector('#' + modalCaller.dataset.target);
		modal.classList.add('active');
		modal.querySelectorAll('.modal__close, .modal__bg').forEach(closer => closer.addEventListener('click', () => modal.classList.remove('active')));
	}));

	const sizeTabs = document.querySelectorAll('.size-table__tab');
	const sizeTitles = document.querySelectorAll('.size-table__title');
	const sizeContents = document.querySelectorAll('.size-table__content');

	sizeTabs.forEach(sizeTab => sizeTab.addEventListener('click', function() {
		classToggle([sizeTabs, sizeTitles, sizeContents]);
	}));
	function classToggle(items) {
		items.forEach(item => item.forEach(subItem => {
			subItem.classList.toggle('active');
		}));
	}


	const faqItems = document.querySelectorAll('.faq__item');
	if (faqItems.length > 0) {
		faqItems.forEach(item => item.querySelector('.faq__ask').addEventListener('click', () => {
			toggleItem(item.querySelector('.faq__ask'), item.querySelector('.faq__answer'));
		}));
	}
	const filterItems = document.querySelectorAll('.filter__row');
	if (filterItems.length > 0) {
		filterItems.forEach(item => item.querySelector('.filter__name').addEventListener('click', () => {
			toggleItem(item.querySelector('.filter__name'), item.querySelector('.filter__list'));
		}));
	}
	const cardFaqItems = document.querySelectorAll('.i-card__faq_item');
	if (cardFaqItems.length > 0) {
		cardFaqItems.forEach(item => item.querySelector('.i-card__faq_ask').addEventListener('click', () => {
			toggleItem(item.querySelector('.i-card__faq_ask'), item.querySelector('.i-card__faq_answer'));
		}));
	}

	const countryBtn = document.querySelector('.country__crnt');
	const countryClose = document.querySelector(['.country__close'])
	const countryContent = document.querySelector('.country__content');
	if (countryBtn) {
		countryBtn.addEventListener('click', () => {
			countryBtn.classList.toggle('active');
			countryContent.classList.toggle('active');
		});
		countryClose.addEventListener('click', () => {
			countryBtn.classList.remove('active');
			countryContent.classList.remove('active');
		});
	}

	if (document.querySelector('.hero-swiper')) {
		new Swiper('.hero-swiper', {
			slidesPerView: 1,
			autoHeight: true,
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
					500: {
						slidesPerView: 4,
						spaceBetween: 20
					},
				}
			});
		});
	}

	if (document.querySelector('.i-cat-swiper')) {
		new Swiper('.i-cat-swiper', {
			slidesPerView: 'auto',
			spaceBetween: 20,
			breakpoints: {
				0: {
					spaceBetween: 5,
				},
				500: {
					spaceBetween: 20,
				},
			}
		});
	}

	if (document.querySelector('.i-card-swiper')) {
		const thumbs = new Swiper('.i-card-smswiper', {
			slidesPerView: 'auto',
			direction: 'vertical',
			spaceBetween: 20,
		});

		new Swiper('.i-card-swiper', {
			slidesPerView: 1,
			spaceBetween: 10,
			thumbs: {
				swiper: thumbs
			},
			breakpoints: {
				0: {
					slidesPerView: 1.2,
					spaceBetween: 5,
				},
				500: {
					slidesPerView: 1,
					spaceBetween: 10,
				}
			}
		});
	}

	if (document.querySelector('.p-review-swiper')) {
		const productReviewSwiper = document.querySelector('.p-review__slider');
		new Swiper('.p-review-swiper', {
			slidesPerView: 'auto',
			spaceBetween: 15,
			navigation: {
				nextEl: '.p-review-btn-next',
				prevEl: '.p-review-btn-prev',
			},
			on: {
				slideChange: function(swiper) {
					if (swiper.isEnd) {
						productReviewSwiper.classList.add('active');
					} else {
						productReviewSwiper.classList.remove('active');
					}
				},
			},
			breakpoints: {
				0: {
					spaceBetween: 10,
				},
				500: {
					spaceBetween: 15,
				},
			}
		});
	}

	if (document.querySelector('.review-swiper')) {
		new Swiper('.review-swiper', {
			slidesPerView: 3,
			spaceBetween: 20,
			navigation: {
				prevEl: '.review-btn-prev',
				nextEl: '.review-btn-next',
			},
			breakpoints: {
				0: {
					slidesPerView: 1.1,
					spaceBetween: 10,
				},
				500: {
					slidesPerView: 3,
					spaceBetween: 20,
				}
			}
		});
	}

	if (document.querySelector('.recent-swiper')) {
		new Swiper('.recent-swiper', {
			slidesPerView: 3,
			spaceBetween: 20,
			navigation: {
				prevEl: '.recent-btn-prev',
				nextEl: '.recent-btn-next',
			},
			breakpoints: {
				0: {
					slidesPerView: 2.1,
					spaceBetween: 10,
				},
				500: {
					slidesPerView: 3,
					spaceBetween: 20,
				}
			}
		});
	}
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

function tabInit(headsSel, contentsSel) {
    const heads = document.querySelectorAll(headsSel);
    const contents = document.querySelectorAll(contentsSel);

    if (heads.length > 0) {
		heads.forEach((head, i) => head.addEventListener('click', () => {
			document.querySelector(`${headsSel}.active`).classList.remove('active');
			document.querySelector(`${contentsSel}.active`).classList.remove('active');
			head.classList.add('active');
			contents[i].classList.add('active');
		}));
    }
}
