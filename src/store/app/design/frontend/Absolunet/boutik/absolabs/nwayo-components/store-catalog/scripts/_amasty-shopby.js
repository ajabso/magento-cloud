//-------------------------------------
//-- Store catalog - ShopBy (Amasty)
//-------------------------------------

(() => {
	'use strict';

	const local = {};
	const catalog = {};


	//-- Cache data instantly
	local.cache = () => {

		catalog.options = {};
		catalog.options.offcanvas = {
			transition: 'overlap'
		};
		catalog.options.equalizer = {
			equalizeByRow: true
		};

	};


	//-- Cache data once DOM is loaded
	local.cacheDOM = () => {

		catalog.components = {};
		catalog.components.offcanvas = __.$body.find('[data-off-canvas]');
		catalog.components.equalizer = __.$body.find('[data-equalizer]');

	};


	//-- Bind events once DOM is loaded
	local.bind = () => {

		// Keep product and action equalized
		__.$window.on('resize', Foundation.util.throttle(() => {

			new Foundation.Equalizer(catalog.components.equalizer, catalog.options.equalizer); // eslint-disable-line no-new

		}, konstan.transition.ui));

		// To show/hide the off-canvas overlay
		__.$window.on('changed.zf.mediaquery', (event, newSize, oldSize) => {

			catalog.components.offcanvas.each((i, el) => {
				if ($(el).attr('data-off-canvas')) {
					if ((oldSize === 'large') && (newSize === 'medium')) {
						$(el).foundation('_destroy');
						new Foundation.OffCanvas($(el), catalog.options.offcanvas); // eslint-disable-line no-new
					}
					if ((oldSize === 'medium') && (newSize === 'large')) {
						$(el).foundation('close');
						$('.js-off-canvas-overlay').remove();
					}
				}
			});

		});

	};


	//-- Subscribe to topics
	local.subscribe = () => {

		// When global jQuery is ready
		$.when(GLOBAL_JQUERY_LOAD).then(($Global) => {

			// Reflow binding when filters applied
			$Global('body').on('contentUpdated', '#amasty-shopby-product-list', (event) => {

				catalog.components.equalizer = $Global('body').find('[data-equalizer]');
				catalog.components.offcanvas = $Global('body').find('[data-off-canvas]');

				__.$window.trigger('resize');
				Foundation.reflow($Global(event.target).find('[data-off-canvas]'), ['off-canvas']);

			});

		});

	};


	//-- Execute once DOM is loaded
	local.start = () => {

		//

	};


	//-- Execute once page is loaded
	local.delayedStart = () => {


		Foundation.onImagesLoaded($('.product-image-photo'), () => {
			__.$window.trigger('resize');
		});

	};






	// Outline
	local.cache();
	local.subscribe();

	// DOM Ready
	$.when(DOM_PARSE).then(() => {
		local.cacheDOM();
		local.bind();
		local.start();
	});

	// Document loaded
	$.when(DOCUMENT_LOAD).then(() => {
		local.delayedStart();
	});

})();
