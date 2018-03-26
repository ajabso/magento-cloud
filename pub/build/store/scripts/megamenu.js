/*!
 * @preserve Generated by nwayo 3.2.2 for btp:store
 */

 (function(global, undefined) { 
	//-------------------------------------
	//-- Collection starter kit
	//-------------------------------------
	//-------------------------------------
	//-- Exclusion starter kit
	//-------------------------------------
	
	/* eslint-disable no-unused-vars */
	var define = undefined;
	var require = undefined;
	
	
	/* eslint-disable strict, no-unused-vars, no-redeclare, prefer-destructuring */
	var PROJECT = global.nwayo.project;
	var app = global[PROJECT];
	var konstan = app.konstan;
	
	var DOM_PARSE = global.nwayo.promises.DOMParse;
	var DOCUMENT_LOAD = global.nwayo.promises.documentLoad;
	
	var __ = global.nwayo.shortcuts;
	
	var jQuery = global.nwayo.vendor.jQuery;
	var $ = global.nwayo.vendor.jQuery;
	var $Global = global.nwayo.vendor.jQueryGlobal;
	var _ = global.nwayo.vendor.lodash;
	var Modernizr = global.nwayo.vendor.Modernizr;
	var PubSub = global.nwayo.vendor.PubSub;

//-------------------------------------
//-- Store - Mega menu
//-------------------------------------
(function (global, undefined) {var kafe = global.kafe,$ = kafe.dependencies.jQuery;kafe.bonify({ name: 'plugin.menu', version: '1.0.0', obj: function () {

			/**
                                                                                                                                                          * ### Version 1.0.0
                                                                                                                                                          * Attaches javascript behaviors to an HTML menu structure to create a *dropdown* style navigation.
                                                                                                                                                          *
                                                                                                                                                          * To preserve flexibility, the plugin only controls events, speeds, delays and callbacks. It will only manage a single custom class (`kafemenu-open`) on the handle elements upon opening or closing, leaving the positioning, visibility and other asthetic responsabilities to its css.
                                                                                                                                                          *
                                                                                                                                                          * @module kafe.plugin
                                                                                                                                                          * @class kafe.plugin.menu
                                                                                                                                                          */
			var menu = {};

			/**
                  * Attach behaviors to the menu structure.
                  *
                  * @method init
                  * @param {Object} options Initial configurations.
                  *	@param {String|jQueryObject|DOMElement} options.selector Root element of the menu structure.
                  *	@param {String} [options.handle='li'] Children element of the container that will serve as a handle to open and close the submenu.
                  *	@param {String} [options.submenus='ul'] Children element of the handle that will serve as a submenu, opening and closing when the handle is used.
                  *	@param {String} [options.animation='slide'] Animation used when opening and closing the submenus.
                  *	@param {Number} [options.openSpeed=200] Duration (in milliseconds) of the opening animation.
                  *	@param {Number} [options.openDelay=500] Delay (in milliseconds) used when entering the handle before starting the opening animation.
                  *	@param {Number} [options.closeSpeed=150] Duration (in milliseconds) of the closing animation.
                  *	@param {Number} [options.closeDelay=400] Delay (in milliseconds) used when exiting the handle before starting the closing animation.
                  *	@param {Function} [options.enterCallback] Upon entering a handle, will be triggered after the delay but before the animation. The current submenu is passed as a first argument.
                  *	@param {Function} [options.leaveCallback] Upon exiting a handle, will be triggered after the delay but before the animation. The current submenu is passed as a first argument.
                  *
                  * @example
                  *	// Sample menu structure
                  *	<nav id="main-menu">
                  *		<ul>
                  *			<li><a href="#">Menu 1</a>
                  *				<ul>
                  *					<li><a href="#">Submenu 1.1</a></li>
                  *					<li><a href="#">Submenu 1.2</a></li>
                  *					<li><a href="#">Submenu 1.3</a></li>
                  *				</ul>
                  *			</li>
                  *			<li><a href="#">Menu 2</a>
                  *				<ul>
                  *					<li><a href="#">Submenu 2.1</a></li>
                  *					<li><a href="#">Submenu 2.2</a></li>
                  *					<li><a href="#">Submenu 2.3</a></li>
                  *				</ul>
                  *			</li>
                  *		</ul>
                  *	</nav>
                  *
                  * @example
                  *	// Attach behaviors using...
                  *	kafe.plugin.menu.init({ selector: '#main-menu > ul' });
                  *
                  * @example
                  *	// Or use the jQuery alternative...
                  *	$('#main-menu > ul').kafeMenu('init', {});
                  */
			menu.init = function () {
				var
				options = arguments ? arguments[0] : {},
				c = {
					$menu: $(options.selector),
					handle: options.handle ? options.handle : 'li',
					handleBtn: options.handleBtn ? options.handleBtn : 'a',
					submenus: options.submenus ? options.submenus : 'ul',
					animation: options.animation ? options.animation : '',
					openSpeed: !isNaN(Number(options.openSpeed)) ? Number(options.openSpeed) : 200,
					openDelay: !isNaN(Number(options.openDelay)) ? Number(options.openDelay) : 500,
					closeSpeed: !isNaN(Number(options.closeSpeed)) ? Number(options.closeSpeed) : 150,
					closeDelay: !isNaN(Number(options.closeDelay)) ? Number(options.closeDelay) : 400,
					enterCallback: typeof options.enterCallback == 'function' ? options.enterCallback : undefined,
					leaveCallback: typeof options.leaveCallback == 'function' ? options.leaveCallback : undefined,
					clickOnly: !!options.clickOnly };



				if (!c.$menu.length) {
					return false;
				}

				var $handles = c.$menu.children(c.handle);

				$handles.
				bind('kafemenu:open', function () {_openMenu(this, 0);}).
				bind('kafemenu:close', function () {_closeMenu(this, 0);});


				if (!c.clickOnly) {
					$handles.
					bind('mouseenter', function (e) {_openMenu(this, c.openDelay);}).
					bind('mouseleave', function (e) {_closeMenu(this, c.closeDelay);});

				} else

				{
					$handles.each(function () {
						var $handle = $(this);
						if ($handle.children(c.submenus).length > 0) {
							$handle.children(c.handleBtn).on('click', function (e) {
								e.preventDefault();
								e.stopPropagation();
								var $handle = $(this).parent();
								if ($handle.hasClass('kafemenu-open')) {
									$handle.trigger('kafemenu:close');
									document.location = $(this).attr('href');
								} else {
									$handles.filter('.kafemenu-open').trigger('kafemenu:close');
									$handle.trigger('kafemenu:open');
								}
							});
						}
					});
					$('html').on('click', function () {
						c.$menu.children(c.handle).filter('.kafemenu-open').trigger('kafemenu:close');
					});
				}

				_closeMenu = function _closeMenu(_handle, _delay) {
					var
					$parent = $(_handle),
					$sub = $parent.children(c.submenus),
					_clearclass = function _clearclass() {
						$parent.removeClass('kafemenu-open');
					};


					if ($sub.data('kafemenu-timer') !== undefined) {
						clearTimeout($sub.data('kafemenu-timer'));
					}

					if ($sub.length > 0) {
						$sub.data('kafemenu-timer', setTimeout(function () {
							var returnCallback = true;
							if (!!c.leaveCallback) {
								returnCallback = c.leaveCallback($sub);
							}
							if (returnCallback) {
								switch (c.animation) {
									case 'fade':
										$sub.fadeOut(c.closeSpeed, _clearclass);
										break;

									case 'slide':
										$sub.slideUp(c.closeSpeed, _clearclass);
										break;

									default:
										$sub.hide(c.closeSpeed, _clearclass);
										break;}

							}
						}, _delay));
					}
				};

				_openMenu = function _openMenu(_handle, _delay) {
					var
					$parent = $(_handle),
					$sub = $parent.children(c.submenus);


					if ($sub.data('kafemenu-timer') !== undefined) {
						clearTimeout($sub.data('kafemenu-timer'));
					}

					if ($sub.length > 0) {
						$sub.data('kafemenu-timer', setTimeout(function () {
							$parent.addClass('kafemenu-open');
							var returnCallback = true;
							if (!!c.enterCallback) {
								returnCallback = c.enterCallback($sub);
							}
							if (returnCallback) {
								switch (c.animation) {
									case 'fade':
										$sub.fadeIn(c.openSpeed);
										break;

									case 'slide':
										$sub.slideDown(c.openSpeed);
										break;

									default:
										$sub.show(c.openSpeed);
										break;}

							}
						}, _delay));
					}
				};
			};


			// Add as jQuery plugin
			kafe.fn.plugIntojQuery('Menu', {
				init: function init(obj, parameters) {
					menu.init($.extend({}, parameters[0], { selector: obj }));
				} });


			return menu;

		}() });})(typeof window !== 'undefined' ? window : this);

//= jshtml_tree components/store-megamenu/templates
app.tmpl.storeMegamenuDrilldownBack = $.templates('storeMegamenuDrilldownBack', '<li class="js-drilldown-back"><a>{{:~translate(\'Back\')}}</a></li>');


(function () {
	'use strict';

	var local = {};


	var isDrilldownSize = function isDrilldownSize() {
		return !Foundation.MediaQuery.atLeast('large');
	};

	//-- Open by default first level or active level
	var showSubmenu = function showSubmenu() {

		local.$firstLevelMenu.each(function () {
			var $menu = $(this).find('> .submenu');
			if ($menu.find('> .has-active, > .active').length === 0) {
				$menu.find('> .first').addClass('is-active');
			}
		});

	};


	//-- Init Drilldown menu
	var drilldown = function drilldown() {
		local.drilldownMenu = new Foundation.Drilldown(local.$megamenu.find('> ul'), { autoHeight: true, parentLink: true });
	};


	//-- Init kafe menu
	var kafemenu = function kafemenu() {

		local.$megamenu.
		find('> ul').kafeMenu('init', {
			openDelay: 0,
			animation: 'fade',
			clickOnly: Modernizr.touchevents === true }).
		end().

		find('.level0.submenu').kafeMenu('init', {
			openDelay: 0,
			closeDelay: 0,
			openSpeed: 0,
			closeSpeed: 0,
			clickOnly: Modernizr.touchevents === true,
			enterCallback: function enterCallback(data) {
				var $this = $(data.prevObject[0]);
				if ($this.hasClass('level0')) {
					$this = $this.find('.level1.active');
				}

				var $lazyload = $this.find(__.component('lazyload-image'));
				if ($lazyload.length) {
					setTimeout(app.lazyload.repass, 1);
				}

				return !isDrilldownSize();
			} }).
		end();

	};







	//-- Cache data instantly
	local.cache = function () {

		local.$megamenu = __.$component('boutik-mega-menu');
		local.$firstLevelMenu = local.$megamenu.find('li.level-top.parent');

		app.lazyload.register({
			'megamenu-image': {
				firstPass: function firstPass($this, options) {
					$this.find('img').attr('src', options.url);
				} } });


	};


	//-- Bind events once DOM is loaded
	local.bind = function () {

		__.$window.on('resize', function (e) {var data = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

			//-- Activate or Destroy the mobile drilldown menu
			if (isDrilldownSize()) {
				if (!local.drilldownMenu) {
					drilldown();
				}

			} else if (local.drilldownMenu || data.init) {

				if (!data.init) {
					local.$megamenu.
					find('.is-drilldown > ul').foundation('destroy').end().
					find('*').removeClass('invisible is-drilldown-submenu is-drilldown-submenu-parent is-drilldown-submenu-item').end();

					local.drilldownMenu = undefined;
				}

				showSubmenu();
				kafemenu();
			}
		});
	};


	//-- Execute once DOM is loaded
	local.start = function () {
		Foundation.Drilldown.defaults.backButton = app.tmpl.storeMegamenuDrilldownBack.render();

		__.$window.trigger('resize', { init: true });
	};






	// Outline
	local.cache();

	// DOM Ready
	$.when(DOM_PARSE).done(function () {
		local.bind();
		local.start();
	});

})();

 })(typeof window !== 'undefined' ? window : this);
