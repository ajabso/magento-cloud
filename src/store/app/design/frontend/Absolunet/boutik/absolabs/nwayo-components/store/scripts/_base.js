//-------------------------------------
//-- Store - Base
//-------------------------------------

//= **require     bower_components/foo/bar
//= **require     components/common/scripts/foobar
//= **jshtml      components/common/templates/foobar
//= **jshtml_tree components/common/templates

(() => {
	'use strict';

	const local = {};


	//-- Cache data instantly
	local.cache = () => {

		// Env
		// app.env.isUniquePage = app.env.pageId === 'UID';
		// app.env.isTypePage   = _.includes(app.env.pageTags, 'TYPE-ID');
		// app.env.isOneOfThese = !!_.intersection(app.env.pageTags, ['ID1', 'ID2']).length;

	};


	//-- Cache data once DOM is loaded
	local.cacheDOM = () => {

		//

	};


	//-- Bind events once DOM is loaded
	local.bind = () => {

		__.$document.on(Modernizr.touchevents ? 'touchstart' : 'click', __.action('toggle-content'), (e) => {
			$(e.currentTarget).closest(__.component('toggle-wrapper')).toggleClass('opened');
		});

	};


	//-- Subscribe to topics
	local.subscribe = () => {
		$.when(GLOBAL_JQUERY_LOAD).then((/* $Global */) => {

			//

		});
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
