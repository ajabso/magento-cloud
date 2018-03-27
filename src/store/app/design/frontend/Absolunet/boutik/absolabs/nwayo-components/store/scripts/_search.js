//-------------------------------------
//-- Store - Search
//-------------------------------------

(() => {
	'use strict';

	const local = {};

	// Fix pour le pixel flottant lorsque la recherche est ouverte et que la résolution d'écran est impaire
	const toggleHeight = (element) => {
		if ($(element).hasClass('opened')) {
			$('.page-header').height(local.headerHeight + local.searchHeight);
		} else {
			$('.page-header').height('auto');
		}
	};


	//-- Cache data instantly
	local.cache = () => {

		//

	};


	//-- Cache data once DOM is loaded
	local.cacheDOM = () => {


		// Fix pour le pixel flottant lorsque la recherche est ouverte et que la résolution d'écran est impaire
		local.headerHeight = $('.page-header').height();
		local.searchHeight = $('.block-search.mini-search').height();

		$(global.window).resize(() => {
			if (local.headerHeight !== $('.page-header').height()) {

				if ($('.page-header').find(__.$component('toggle-wrapper')).hasClass('opened')) {
					$('.page-header').height('auto');

					local.headerHeight = $('.page-header').height();

					$('.page-header').height(local.headerHeight + local.searchHeight);
				} else {
					local.headerHeight = $('.page-header').height();
				}


			}
		});

	};


	//-- Bind events once DOM is loaded
	local.bind = () => {

		// focus on search input
		$('.toggle-search').on('click', () => {
			setTimeout(() => {
				$('#search').focus();
			}, 1);
		});

		__.$document.on(Modernizr.touchevents ? 'touchstart' : 'click', __.action('toggle-content'), (e) => {
			toggleHeight($(e).closest(__.$component('toggle-wrapper')));
		});

	};


	//-- Subscribe to topics
	local.subscribe = () => {

		//

	};


	//-- Execute once DOM is loaded
	local.start = () => {

		//

	};


	//-- Execute once page is loaded
	local.delayedStart = () => {

		//

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
