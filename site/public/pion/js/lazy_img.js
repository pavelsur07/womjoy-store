document.addEventListener('DOMContentLoaded', () => {
	const bLazy = new Blazy();

	if (document.querySelectorAll('.card__img_slide').length > 0) {
	    document.querySelectorAll('.card__img_slide').forEach(imgSlide => {
	    	const observer = new MutationObserver(mutations => {
		        mutations.forEach(mutation => {
		        	const img = mutation.target.querySelector('.b-lazy');

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

	document.querySelectorAll('.card__img_slide:first-child img').forEach(img => {
		bLazy.load(img, true);
	});
});