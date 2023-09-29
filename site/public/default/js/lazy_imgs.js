document.addEventListener('DOMContentLoaded', () => {
	const bLazy = new Blazy();

	if (document.querySelectorAll('.product__img_slide').length > 0) {
	    document.querySelectorAll('.product__img_slide').forEach(imgSlide => {
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
});