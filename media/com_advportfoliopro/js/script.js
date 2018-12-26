/**
 * @copyright    Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

if (!ExtStore) {
	var ExtStore = {};
}

ExtStore.AdvPortfolioPro = {
	live_site: null,

	init: function() {
		jQuery(function() {

			jQuery('.portfolio-list, .portfolio-module').each(function() {

				var container   		= jQuery(this);
				var displayType			= container.data('display_type');
				var gutterWidth 		= container.data('gutter-width');
				var columnWidth 		= get_colunm_width(container);
				var columns				= container.data('columns');
				var width_odd			= (gutterWidth * (columns - 1))/ columns;
				var feature_width_odd 	= (gutterWidth * (columns - 2))/ columns;


				if (displayType == 1) {
					// module carousel
					container.flexslider({
						animation: container.data('animation'),
						animationLoop: true,
						direction: 'horizontal',
						slideshowSpeed: container.data('speed'),
						controlNav: container.data('show-navigation'),
						directionNav: container.data('show-direction-navigation'),
						itemWidth: Math.floor(columnWidth - width_odd),
						itemMargin: gutterWidth,
						minItems: 1,
						maxItems: columns,
						start: function(slider){
							jQuery('div.loading').removeClass('loading');
						}
					});

					if (container.data('animation') != 'fade') {
						container.find('ul > li').css({
							"margin-right": gutterWidth
						});
					}

				} else if (displayType == 0) {

					jQuery(".module-projects-wrapper .isotope-item").css ({
						"width": Math.floor(columnWidth - width_odd) + "px",
						"margin-bottom": gutterWidth,
						"margin-top": 0,
						"margin-right": 0,
						"margin-left": 0
					});

					jQuery(".module-projects-wrapper .isotope-item.featured").css ({
						"width": Math.floor((2*columnWidth) - feature_width_odd) + "px",
						"margin-bottom": gutterWidth,
						"margin-top": 0,
						"margin-right": 0,
						"margin-left": 0
					});
					// initialize isotope
					jQuery('.module-projects-wrapper').isotope({
						itemSelector: '.isotope-item',
						masonry: {
							columnWidth: Math.floor(columnWidth - width_odd),
							gutter: gutterWidth
						}
					});
					// isotop
					var $optionSets = jQuery('.module-projects-filter .option-set');
					var $optionLinks = $optionSets.find('a');

					$optionLinks.click(function() {
						var $this = jQuery(this);
						$this.stop();

						// don't proceed if already selected
						if ($this.hasClass('selected')) {
							return false;
						}

						var $optionSet = $this.parents('.option-set');
						$optionSet.find('.selected').removeClass('selected');
						$this.addClass('selected');

						// make option object dynamically, i.e. { filter: '.my-filter-class' }
						var options = {},
								key = $optionSet.attr('data-option-key'),
								value = $this.attr('data-option-value');

						// parse 'false' as false boolean
						value = value === 'false' ? false : value;
						options[key] = value;

						// otherwise, apply new options
						var portfolioModule = $this.closest('.portfolio-module').find('.module-projects-wrapper');
						portfolioModule.isotope(options);

						return false;

					});

				} else {
					jQuery(".container-isotop .isotope-item").css ({
						"width": Math.floor(columnWidth - width_odd) + "px",
						"margin-bottom": gutterWidth,
						"margin-top": 0,
						"margin-right": 0,
						"margin-left": 0
					});

					jQuery(".container-isotop .isotope-item.featured").css ({
						"width": Math.floor((2*columnWidth) - feature_width_odd) + "px",
						"margin-bottom": gutterWidth,
						"margin-top": 0,
						"margin-right": 0,
						"margin-left": 0
					});
					// initialize isotope
					jQuery('#projects-wrapper').isotope({
						itemSelector: '.isotope-item',
						masonry: {
							columnWidth: Math.floor(columnWidth - width_odd),
							gutter: gutterWidth
						}
					});
					// isotop
					var $optionSets = jQuery('#projects-filter .option-set');
					var $optionLinks = $optionSets.find('a');

					$optionLinks.click(function() {
						var $this = jQuery(this);
						$this.stop();

						// don't proceed if already selected
						if ($this.hasClass('selected')) {
							return false;
						}

						var $optionSet = $this.parents('.option-set');
						$optionSet.find('.selected').removeClass('selected');
						$this.addClass('selected');

						// make option object dynamically, i.e. { filter: '.my-filter-class' }
						var options = {},
								key = $optionSet.attr('data-option-key'),
								value = $this.attr('data-option-value');

						// parse 'false' as false boolean
						value = value === 'false' ? false : value;
						options[key] = value;

						// otherwise, apply new options
						jQuery('#projects-wrapper').isotope(options);

						return false;

					});
				}

				// gallery button
				jQuery('a.gallery-icon, a.gallery-popup').click(function(event) {
					var $this = jQuery(this);

					event.preventDefault();

					jQuery.ajax({
						url: ExtStore.AdvPortfolioPro.live_site + '/?option=com_advportfoliopro&view=project&format=raw&id=' + $this.data('project-id')
					}).done(function (data) {
								jQuery.fancybox.open(jQuery.parseJSON(data));
							});
				});

				// hover dir
				var overlayEffect 		= container.data('overlay_effect');
				var hoverdirEasing 		= container.data('hoverdir_easing');
				var hoverdirSpeed 		= container.data('hoverdir_speed');
				var hoverdirHoverDelay 	= container.data('hoverdir_hover_delay');
				var hoverdirInverse 	= container.data('hoverdir_inverse');

				if (overlayEffect == 'hoverdir') {
					var projectImg = container.find('.project-img');

					projectImg.each(function() {
						jQuery(this).hoverdir({
							speed : hoverdirSpeed,
							easing : hoverdirEasing,
							hoverDelay : hoverdirHoverDelay,
							inverse : hoverdirInverse,
							hoverElem : 'div'
						});
					});
				}
			});

			function get_colunm_width($container) {
				var view_port_w;

				var columnWidth = $container.width() / $container.data('columns');

				if (self.innerWidth !== undefined) {
					view_port_w = self.innerWidth;
				} else {
					var D = document.documentElement;
					if (D) view_port_w = D.clientWidth;
				}

				if ($container.data('columns') >= 5) {
					if (view_port_w >= 768 && view_port_w <= 979) {
						columnWidth = $container.width() / 4;
					} else if (view_port_w >= 640 && view_port_w <= 767) {
						columnWidth = $container.width() / 4;
					} else if (view_port_w >= 600 && view_port_w <= 639) {
						columnWidth = $container.width() / 3;
					} else if (view_port_w >= 480 && view_port_w <= 599) {
						columnWidth = $container.width() / 2;
					} else if (view_port_w >= 360 && view_port_w <= 479) {
						columnWidth = $container.width() / 2;
					} else if (view_port_w <= 359) {
						columnWidth = $container.width() / 1;
					}

					return columnWidth;
				}

				if ($container.data('columns') == 4 || $container.data('columns') == 3) {
					if (view_port_w >= 768 && view_port_w <= 979) {
						columnWidth = $container.width() / 3;
					} else if (view_port_w >= 640 && view_port_w <= 767) {
						columnWidth = $container.width() / 3;
					} else if (view_port_w >= 600 && view_port_w <= 639) {
						columnWidth = $container.width() / 3;
					} else if (view_port_w >= 480 && view_port_w <= 599) {
						columnWidth = $container.width() / 2;
					} else if (view_port_w >= 360 && view_port_w <= 479) {
						columnWidth = $container.width() / 2;
					} else if (view_port_w <= 359) {
						columnWidth = $container.width() / 1;
					}

					return columnWidth;
				}

				if ($container.data('columns') == 2) {
					if (view_port_w >= 768 && view_port_w <= 979) {
						columnWidth = $container.width() / 2;
					} else if (view_port_w >= 640 && view_port_w <= 767) {
						columnWidth = $container.width() / 2;
					} else if (view_port_w >= 600 && view_port_w <= 639) {
						columnWidth = $container.width() / 2;
					} else if (view_port_w >= 480 && view_port_w <= 599) {
						columnWidth = $container.width() / 2;
					} else if (view_port_w >= 360 && view_port_w <= 479) {
						columnWidth = $container.width() / 2;
					} else if (view_port_w <= 359) {
						columnWidth = $container.width() / 1;
					}

					return columnWidth;
				}
			}

		});

	}
};

jQuery(window).load(function() {
	ExtStore.AdvPortfolioPro.init();
});

jQuery(window).resize(function(){
	ExtStore.AdvPortfolioPro.init();
});
