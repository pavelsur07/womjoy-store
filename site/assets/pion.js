import  './styles/style.css'

document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card');

    if (cards.length > 0) {
        cards.forEach((card, i) => {

            const images = card.querySelectorAll('.card__img_slide');

            let finalHTML = '<div class="card__img_hovers">';
            for (let a = 0; a < images.length; a++) {
                finalHTML += '<div class="card__img_hover"></div>';
            }
            finalHTML += '</div>';

            finalHTML += '<div class="card__img_checks">';
            for (let a = 0; a < images.length; a++) {
                finalHTML += '<div class="card__img_check"></div>';
            }
            finalHTML += '</div>';


            if (card.querySelector('.card__img')) {
                card.querySelector('.card__img').insertAdjacentHTML('beforeend', finalHTML);


                const hovers = card.querySelectorAll('.card__img_hover');
                const checks = card.querySelectorAll('.card__img_check');

                hovers.forEach((hover, i) => {
                    hover.addEventListener('mouseover', () => {
                        card.querySelector('.card__img_slide.active')?.classList.remove('active');
                        card.querySelector('.card__img_check.active')?.classList.remove('active');

                        images[i].classList.add('active');
                        checks[i].classList.add('active');
                    });
                });
            }
        });
    }

    const header       = document.querySelector('.header');
    const burgers      = document.querySelectorAll('.burger, .call-catalog');
    const headerMain   = document.querySelector('.header__main');
    const catalogMenu  = document.querySelector('.catalog-menu');
    const catalogClose = document.querySelectorAll('.catalog-menu__close');
    const searchPc     = document.querySelector('.search-pc')

    if (header) {
        burgers.forEach(burger => burger.addEventListener('click', e => {
            e.preventDefault();
            header.classList.toggle('active');
            headerMain.classList.toggle('active');
            catalogMenu.classList.toggle('active');
            burger.classList.toggle('active');

            if (window.innerWidth <= 768) {
                searchPc.classList.remove('active');
                searchCaller.classList.remove('active')
            }
        }));
        catalogClose.forEach(close => close.addEventListener('click', () => {
            header.classList.remove('active');
            headerMain.classList.remove('active');
            catalogMenu.classList.remove('active');
            burgers.forEach(b => b.classList.remove('active'));

            if (window.innerWidth <= 768) {
                searchPc.classList.remove('active');
                searchCaller.classList.remove('active')
            }
        }));


        let isHeaderFixed = false;
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > document.querySelector('.header__alert').offsetHeight && !isHeaderFixed) {
                document.querySelector('.header__main').classList.add('fixed');
                if (header.classList.contains('header-in')) {
                    document.body.style.paddingTop = headerMain.offsetHeight + 'px';
                }
                if (window.innerWidth > 768) {
                    catalogMenu.classList.add('fixed');
                }
                searchPc.classList.add('fixed');

                isHeaderFixed = true;
                return;
            }
            if (window.pageYOffset < document.querySelector('.header__alert').offsetHeight && isHeaderFixed) {
                document.querySelector('.header__main').classList.remove('fixed');
                header.classList.contains('header-in') ? document.body.style.paddingTop = '0px' : '';
                if (window.innerWidth > 768) {
                    catalogMenu.classList.remove('fixed');
                }
                searchPc.classList.remove('fixed');

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
            searchCaller.classList.toggle('active');
        });
    }

    // searchPc.querySelector('.search__close').addEventListener('click', () => {
    // 	header.classList.remove('active');
    // 	searchPc.classList.remove('active');
    // });


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

                    if (catalogMenuDepth > 0) {
                        catalogBack.classList.add('active');
                        catalogMenu.querySelector('.search').classList.add('d-none');
                    }
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

                if (catalogMenuDepth === 0) {
                    catalogBack.classList.remove('active');
                    catalogMenu.querySelector('.search').classList.remove('d-none');
                }
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
            const filterCall        = document.querySelector('.filter__call');
            const filterSortTrigger = document.querySelector('.filter__sort_trigger');
            const filterSortList    = document.querySelector('.filter__sort_list');
            const filterMain        = document.querySelector('.filter__main');
            const filterClose       = document.querySelector('.filter__close');

            filterCall.addEventListener('click', () => filterMain.classList.add('active'));
            filterClose.addEventListener('click', () => filterMain.classList.remove('active'));
            filterSortTrigger.addEventListener('click', () => filterSortList.classList.add('active'));
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
            slidesPerView: 5,
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
            let slider = new Swiper(cardSlider.querySelector('.card-slider-swiper'), {
                slidesPerView: 4,
                slidesPerGroup: 4,
                spaceBetween: 20,
                // loop: true,
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
    const modals = document.querySelectorAll('.modal');

    modals.forEach(modal => {
        modal.querySelector('.modal__close').addEventListener('click', () => {
            modal.classList.remove('active')
            document.querySelector('html').classList.remove('body-block');
            document.body.classList.remove('body-block');
        });
        modal.querySelector('.modal__bg').addEventListener('click', () => {
            modal.classList.remove('active')
            document.querySelector('html').classList.remove('body-block');
            document.body.classList.remove('body-block');
        });
    });

    modalCallers.forEach(modalCaller => {
        const modal = document.querySelector('#' + modalCaller.dataset.modaltarget);

        modalCaller.addEventListener('click', e => {
            e.preventDefault();
            modal.classList.add('active');
            document.querySelector('html').classList.add('body-block');
            document.body.classList.add('body-block');
        });

        modal.querySelector('.modal__close').addEventListener('click', () => modal.classList.remove('active'));
        modal.querySelector('.modal__bg').addEventListener('click', () => modal.classList.remove('active'));
        modal.querySelectorAll('[data-modalclose]').forEach(closer => closer.addEventListener('click', () => modal.classList.remove('active')));
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


            if (Array.from(checkItems).filter(item => item.querySelector('input').checked).length > 0) {
                trigger.textContent = Array.from(checkItems).filter(item => item.querySelector('input').checked)[0].textContent;
            } else if (sel.dataset.wSelDefault) {
                trigger.textContent = sel.dataset.wSelDefault;
            } else {
                trigger.textContent = 'Не выбрано';
            }

            checkItems.forEach((checkItem, i) => {
                if (+checks[i].dataset.quantity === 0) {
                    checkItem.insertAdjacentHTML('beforeend', `
						<strong>
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: 8px; margin-right: 2px">
								<path d="M1.66675 2.91666H18.3334V17.0833H1.66675V2.91666Z" stroke="#141B34" stroke-width="1.25" stroke-linejoin="round"></path>
								<path d="M1.66675 5.83337L10.0001 10L18.3334 5.83337" stroke="#141B34" stroke-width="1.25"></path>
							</svg>
							Уведомить
						</strong>
					`);
                }
            })


            checks.forEach((check, i) => check.addEventListener('change', () => {
                trigger.innerHTML = sel.querySelectorAll('.w-sel__item')[i].querySelector('div').textContent.replace(sel.dataset.wSelEmpty, '');

                if (+check.dataset.quantity === 0) {
                    trigger.innerHTML += `
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: 8px; margin-right: 4px">
							<path d="M1.66675 2.91666H18.3334V17.0833H1.66675V2.91666Z" stroke="#141B34" stroke-width="1.25" stroke-linejoin="round"></path>
							<path d="M1.66675 5.83337L10.0001 10L18.3334 5.83337" stroke="#141B34" stroke-width="1.25"></path>
						</svg>
						Уведомить о поступлении
					`;
                }
                trigger.classList.remove('active');
                dropdown.classList.remove('active');
                sel.classList.remove('active');

                const inp = sel.querySelector('.w-sel__item input[checked]');
                if (inp) inp.removeAttribute('checked');

                sel.querySelectorAll('.w-sel__item')[i].querySelector('input').setAttribute('checked', '');
                sel.querySelectorAll('.w-sel__item')[i].querySelector('input').checked = true;
            }));
        });
    }

    document.addEventListener('click', e => {
        const els = document.querySelectorAll('.w-sel, .filter__item, .filter__sort');
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
                if (el.classList.contains('filter__sort')) {
                    el.querySelector('.filter__sort_list').classList.remove('active');
                }
            }
        });
    });


    window.addEventListener('scroll', () => {
        const floatEls = document.querySelectorAll('[data-float-target]');
        floatEls.forEach(floatEl => {
            const target = document.querySelector(floatEl.dataset.floatTarget);

            if (!target) {
                floatEl?.classList?.add('hidden');
                return;
            }
            let elShown = true;
            if (
                window.pageYOffset + window.innerHeight - 70 > target.getBoundingClientRect().top + window.pageYOffset
            ) {
                floatEl.classList.add('hidden');
                elShown = false;

                if (target.getBoundingClientRect().top < 0) {
                    floatEl.classList.remove('hidden');
                    elShown = true;
                }
            } else {
                floatEl.classList.remove('hidden');
                elShown = true;
            }
        })
    });
});