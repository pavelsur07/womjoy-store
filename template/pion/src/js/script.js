document.addEventListener('DOMContentLoaded', () => {
	const header       = document.querySelector('.header');
	const burger       = document.querySelector('.burger');
	const headerMain   = document.querySelector('.header__main');
	const catalogMenu  = document.querySelector('.catalog-menu');
	const catalogClose = document.querySelectorAll('.catalog-menu__close');
	const searchPc     = document.querySelector('.search-pc')

	burger.addEventListener('click', () => {
		header.classList.toggle('active');
		headerMain.classList.toggle('active');
		catalogMenu.classList.toggle('active');

		if (window.innerWidth <= 768) {
			searchPc.classList.toggle('active');
		}
	});
	catalogClose.forEach(close => close.addEventListener('click', () => {
		header.classList.remove('active');
		headerMain.classList.remove('active');
		catalogMenu.classList.remove('active');

		if (window.innerWidth <= 768) {
			searchPc.classList.remove('active');
		}
	}));


	let isHeaderFixed = false;
	window.addEventListener('scroll', () => {
		if (window.pageYOffset > document.querySelector('.header__alert').offsetHeight && !isHeaderFixed) {
			document.querySelector('.header__main').classList.add('fixed');
			header.classList.contains('header-in') ? document.body.style.paddingTop = headerMain.offsetHeight + 'px' : '';
			if (window.innerWidth > 768) {
				catalogMenu.classList.add('fixed');
				searchPc.classList.add('fixed');
			}

			isHeaderFixed = true;
			return;
		}
		if (window.pageYOffset < document.querySelector('.header__alert').offsetHeight && isHeaderFixed) {
			document.querySelector('.header__main').classList.remove('fixed');
			header.classList.contains('header-in') ? document.body.style.paddingTop = '0px' : '';
			if (window.innerWidth > 768) {
				catalogMenu.classList.remove('fixed');
				searchPc.classList.remove('fixed');
			}

			isHeaderFixed = false;;
			return;
		}
	});

	const searches     = document.querySelectorAll('.search');
	const searchCaller = document.querySelector('.call-search');

	searches.forEach(search => {
		const searchInp    = search.querySelector('.search__inp');
		const searchResult = search.querySelector('.search__result');
		const searchBottom = search.querySelector('.search__bottom');

		let searchTimeout  = null;
		searchInp.addEventListener('input', () => {
			clearTimeout(searchTimeout);
			searchTimeout = setTimeout(() => {
				console.log('Request ....');

				searchResult.classList.add('active');
				searchBottom.classList.add('hidden');

				if (searchInp.value === '') {
					searchResult.classList.remove('active');
					searchBottom.classList.remove('hidden');
				}
			}, 500);
		});

		if (window.innerWidth <= 768) {
			const searchCancel = search.querySelector('.search__cancel');
			searchInp.addEventListener('focus', () => {
				searchBottom.classList.add('active');
				document.querySelector('.catalog-menu__bottom').classList.add('active');
				document.querySelector('.catalog-menu__cols').classList.add('hidden');
				if (searchCancel) {
					searchCancel.classList.remove('d-none');
				}
			});

			if (searchCancel) {
				searchCancel.addEventListener('click', () => {
					searchInp.value = '';
					searchBottom.classList.remove('active');
					document.querySelector('.catalog-menu__bottom').classList.remove('active');
					document.querySelector('.catalog-menu__cols').classList.remove('hidden');
					searchResult.classList.remove('active');
					searchBottom.classList.remove('hidden');
					searchCancel.classList.add('d-none');
				});
			}
		}

	});
	searchCaller.addEventListener('click', () => {
		header.classList.toggle('active');
		searchPc.classList.toggle('active');
	});
	searchPc.querySelector('.search__close').addEventListener('click', () => {
		header.classList.remove('active');
		searchPc.classList.remove('active');
	});


	if (window.innerWidth <= 768) {
		const drops = document.querySelectorAll('.drop');
		if (drops.length > 0) {

			const catalogBack  = document.querySelector('.catalog-menu__back');
			const catalogTitle = document.querySelector('.catalog-menu__title');
			const catalogBase  = document.querySelector('.catalog-menu__cols');
			let prevContents   = [];
			let currentContent = null;

			let catalogMenuDepth = 0;
			drops.forEach(drop => {
				const lists      = drop.querySelectorAll('.drop__content');
				const trigger    = drop.querySelector('.drop__trigger');
				const content    = drop.querySelector('.drop__content');

				trigger.addEventListener('click', e => {
					e.preventDefault();
					catalogMenuDepth++;
					catalogBase.scrollTo({left: 0, top: 0})

					catalogTitle.textContent = trigger.textContent;
					content.classList.add('active');
					currentContent = content;

					if (catalogMenuDepth === 1) {
						catalogBase.classList.add('scroll-blocked');
					} else if (catalogMenuDepth > 1) {
						trigger.parentNode.parentNode.classList.add('scroll-blocked');
					}
					prevContents.push({ title: trigger.textContent, content: trigger.parentNode.parentNode });
				});
			});
			catalogBack.addEventListener('click', () => {
				if (catalogMenuDepth === 0) {
					header.classList.remove('active');
					headerMain.classList.remove('active');
					catalogMenu.classList.remove('active');
					return;
				}

				catalogMenuDepth--;

				currentContent.classList.remove('active');
				prevContents[catalogMenuDepth].content.classList.remove('scroll-blocked');
				currentContent = prevContents[catalogMenuDepth].content;

				if (prevContents[catalogMenuDepth - 1] !== undefined) {
					catalogTitle.textContent = prevContents[catalogMenuDepth - 1].title;
				} else {
					catalogTitle.textContent = 'КАТАЛОГ';
					catalogBase.classList.remove('scroll-blocked')
				}

				prevContents.splice(catalogMenuDepth, 1);
				
			});
		}
	}


	const filter = document.querySelector('.filter');
	if (filter) {
		const filterItems = document.querySelectorAll('.filter__item');
		filterItems.forEach(filterItem => {
			const trigger = filterItem.querySelector('.filter__trigger');
			const content = filterItem.querySelector('.filter__content');
			trigger.addEventListener('click', () => {
				if (content.classList.contains('active')) {
					content.classList.remove('active');
					trigger.classList.remove('active');
					return;
				}

				document.querySelector('.filter__content.active')?.classList.remove('active');
				document.querySelector('.filter__trigger.active')?.classList.remove('active');
				content.classList.add('active');
				trigger.classList.add('active');
			});
		});

		if (window.innerWidth <= 768) {
			const filterCall = document.querySelector('.filter__call');
			const filterMain = document.querySelector('.filter__main');
			const filterClose = document.querySelector('.filter__close');

			filterCall.addEventListener('click', () => filterMain.classList.add('active'));
			filterClose.addEventListener('click', () => filterMain.classList.remove('active'));
		}
	}

	if (document.querySelector('.hero-swiper')) {
		new Swiper('.hero-swiper', {
			slidesPerView: 1,
			speed: 1000,
			loop: true,
			pagination: {
				el: '.hero-pagination',
				clickable: true,
				bulletClass: 'hero-pagination-bullet',
				bulletActiveClass: 'hero-pagination-bullet-active'
			},
			navigation: {
				prevEl: '.hero-btn-prev',
				nextEl: '.hero-btn-next',
			},
			autoplay: {
				delay: 4000,
				disableOnInteraction: false,
			}
		});
	}


	if (document.querySelector('.i-card-smswiper')) {
		const smSwiper = new Swiper('.i-card-smswiper', {
			slidesPerView: 4,
			spaceBetween: 7,
			direction: 'vertical',
			navigation: {
				prevEl: '.i-card-smbtn-prev',
				nextEl: '.i-card-smbtn-next',
			}
		});

		new Swiper('.i-card-lgswiper', {
			slidesPerView: 1,
			spaceBetween: 10,
			thumbs: {
				swiper: smSwiper
			},
			pagination: {
				el: '.i-card-pagination',
				clickable: true,
				bulletClass: 'i-card-pagination-bullet',
				bulletActiveClass: 'i-card-pagination-bullet-active',
			}
		});
	}

	// const sizes = document.querySelectorAll('.i-card__size');
	// if (sizes.length > 0) {
	// 	sizes.forEach(size => {
	// 		const trigger  = size.querySelector('.i-card__size_trigger');
	// 		const dropdown = size.querySelector('.i-card__size_dropdown');

	// 		trigger.addEventListener('click', () => {
	// 			trigger.classList.toggle('active');
	// 			dropdown.classList.toggle('active');
	// 		});

	// 		const checks = size.querySelectorAll('.i-card__size_item input');
	// 		checks.forEach((check, i) => check.addEventListener('change', () => {
	// 			trigger.textContent = document.querySelectorAll('.i-card__size_item')[i].textContent;
	// 			trigger.classList.remove('active');
	// 			dropdown.classList.remove('active');
	// 		}));
	// 	});
	// }

	const cardFaqItems = document.querySelectorAll('.i-card__faq_item')
	if (cardFaqItems.length > 0) {
		cardFaqItems.forEach(item => {
			const trigger  = item.querySelector('.i-card__faq_trigger');
			const dropdown = item.querySelector('.i-card__faq_dropdown');

			trigger.addEventListener('click', () => {
				trigger.classList.toggle('active');
				dropdown.classList.toggle('active');
			});
		});
	}

	const cardSliders = document.querySelectorAll('.card-slider');
	if (cardSliders.length > 0) {
		cardSliders.forEach(cardSlider => {
			new Swiper(cardSlider.querySelector('.card-slider-swiper'), {
				slidesPerView: 4,
				slidesPerGroup: 4,
				spaceBetween: 20,
				loop: true,
				navigation: {
					prevEl: cardSlider.querySelector('.card-slider-btn-prev'),
					nextEl: cardSlider.querySelector('.card-slider-btn-next'),
				},
				breakpoints: {
					0: {
						slidesPerView: 1.5,
						spaceBetween: 8,
					},
					480: {
						slidesPerView: 2,
						spaceBetween: 8,
					},
					600: {
						slidesPerView: 3,
						spaceBetween: 8,
					},
					769: {
						slidesPerView: 3,
						spaceBetween: 20,
					},
					900: {
						slidesPerView: 4,
					}
				}
			});
		});
	}


	const modalCallers = document.querySelectorAll('[data-modaltarget]');
	modalCallers.forEach(modalCaller => {
		console.log('#' + modalCaller.dataset.modaltarget);
		const modal = document.querySelector('#' + modalCaller.dataset.modaltarget);

		modalCaller.addEventListener('click', e => {
			e.preventDefault();
			modal.classList.add('active');
		});

		modal.querySelector('.modal__close').addEventListener('click', () => modal.classList.remove('active'));
		modal.querySelector('.modal__bg').addEventListener('click', () => modal.classList.remove('active'));
	});


	const tabs = document.querySelectorAll('.w-tab');
	tabs.forEach(tab => {
		const heads    = tab.querySelectorAll('.w-tab__head');
		const contents = tab.querySelectorAll('.w-tab__content');

		heads.forEach((head, i) => head.addEventListener('click', () => {
			tab.querySelector('.w-tab__head.active').classList.remove('active');
			tab.querySelector('.w-tab__content.active').classList.remove('active');

			head.classList.add('active');
			contents[i].classList.add('active');
		}));
	});


	const customSels = document.querySelectorAll('.w-sel');
	if (customSels.length > 0) {
		customSels.forEach(sel => {
			const trigger  = sel.querySelector('.w-sel__trigger');
			const dropdown = sel.querySelector('.w-sel__dropdown');


			trigger.addEventListener('click', () => {
				trigger.classList.toggle('active');
				dropdown.classList.toggle('active');
				sel.classList.toggle('active');
			});

			const checks = sel.querySelectorAll('.w-sel__item input');
			const checkItems = sel.querySelectorAll('.w-sel__item');
			trigger.textContent = Array.from(checkItems).filter(item => item.querySelector('input').checked)[0].textContent;


			checks.forEach((check, i) => check.addEventListener('change', () => {
				trigger.textContent = sel.querySelectorAll('.w-sel__item')[i].textContent;
				trigger.classList.remove('active');
				dropdown.classList.remove('active');
				sel.classList.remove('active');

				const inp = sel.querySelector('.w-sel__item input[checked]');
				inp.removeAttribute('checked');

				sel.querySelectorAll('.w-sel__item')[i].querySelector('input').setAttribute('checked', '');
				sel.querySelectorAll('.w-sel__item')[i].querySelector('input').checked = true;
			}));
		});
	}

	document.addEventListener('click', e => {
		const els = document.querySelectorAll('.w-sel, .filter__item');
		els.forEach(el => {
			if (!el.contains(e.target)) {

				if (el.classList.contains('w-sel')) {
					el.querySelector('.w-sel__trigger').classList.remove('active');
					el.querySelector('.w-sel__dropdown').classList.remove('active');
					el.classList.remove('active');
					return;
				}

				if (el.classList.contains('filter__item')) {
					el.querySelector('.filter__trigger').classList.remove('active');
					el.querySelector('.filter__content').classList.remove('active');
					el.classList.remove('active');
				}
			}
		});
	});
});