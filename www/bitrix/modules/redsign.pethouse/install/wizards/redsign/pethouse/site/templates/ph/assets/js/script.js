;(function(window){

	if (window.rsSline) {
		return;
	}

	window.rsSline = function() {

		var self = this;
		var app = this;
		this.offers = {};
		this.cartList = {};
		this.favoriteList = {};
		this.compareList = {};
		this.stocks = {};
		/*
		this.domClicked = [];
		this.iDomRefreshTimeout = 250;
		this.iDomRefreshDelay = 250;
		this.onReady = {};
		*/
		this.fancyTimeout = 2000;
		this.ajaxTimeout = 0;
		this.ajaxTimeoutTime = 1000;
		this.ajaxExec = false;
		this.grid = {
			0: 0,
			xs: 480,
			sm: 768,
			md: 992,
			lg: 1220,
		};
		this.pageWidth = this.getPageWidth();
		this.pageHeight = this.getPageHeight();

		this.fancyOptions = {
			infobar: false,
			buttons: false,
			slideShow: false,
			smallBtn: true,
			title: true,
			//animationEffect: 'zoom-in-out',
			caption: function (){
				return ''
			},
			afterClose: function()
			{
				if (this.$content != undefined)
				{
					this.$content.find('.fancybox-title').remove();
				}
			},
			beforeLoad: function(instance, slide)
			{
				var	slide = this;
				if (slide.type == 'ajax')
				{
					//slide.opts.ajax.settings.data.rs_ajax__page = 'Y';
				}

				if (!!slide.opts.$orig)
				{
					var ajaxData = slide.opts.$orig.data('insert_data');

					if (ajaxData != undefined)
					{
						slide.opts.ajax.settings.data.ajax_data = ajaxData;
					}
				}
			},
			afterLoad: function(instance, slide)
			{
				if (slide.$content.filter('[id^=comp_]').length > 0)
				{
					slide.$content = slide.$content.filter('[id^=comp_]').wrap('<div class="fancybox-data"></div>').parent();
					slide.$slide.find('.fancybox-close').appendTo(slide.$content);
				}

				var title = !!slide.opts.title && slide.opts.title.length
						? slide.opts.title
						: !!instance.opts.title && instance.opts.title.length
							? instance.opts.title
							: !!slide.opts.$orig
								? slide.opts.$orig.data('fancybox-title') || slide.opts.$orig.attr('title')
								: undefined;

				if (title !== undefined)
				{
					slide.$slide.children(':first').prepend('<div class="fancybox-title">' + title + '</div>');
				}

				if (slide.type == 'ajax' && slide.opts.ajax.settings.data.ajax_data != undefined)
				{
					elementData = BX.parseJSON(slide.opts.ajax.settings.data.ajax_data);

					for (var fieldName in elementData)
					{
						slide.$slide.find('[name="' + fieldName + '"]').val(BX.util.htmlspecialcharsback(elementData[fieldName]));
					}
				}
			},
			afterShow: function(instance, slide)
			{
				appSLine.ready(slide.$content);
			},
			spinnerTpl: '<div class="fancybox-loading" class="loading">\
					<div class="loading__in loading__1"></div>\
					<div class="loading__in loading__2"></div>\
					<div class="loading__in loading__3"></div>\
					<div class="loading__in loading__4"></div>\
					<div class="loading__in loading__5"></div>\
					<div class="loading__in loading__6"></div>\
					<div class="loading__in loading__7"></div>\
					<div class="loading__in loading__8"></div>\
				</div>',
			btnTpl: {
				close: '<svg class="fancybox-close icon-close icon-svg" data-fancybox-close><use xlink:href="#svg-close"></use></svg>',
				smallBtn: '<svg class="fancybox-close icon-close icon-svg" data-fancybox-close><use xlink:href="#svg-close"></use></svg>',
			},
		};

		this.owlOptions = {
			loop:true,
			nav:true,
			navClass:['owl-nav__prev js-click', 'owl-nav__next js-click'],
			navText:['<svg class="icon-left icon-svg"><use xlink:href="#svg-left"></use></svg>','<svg class="icon-right icon-svg"><use xlink:href="#svg-right"></use></svg>'],
			responsive: {
				0: {items: 2},

			},
			onInitialize: function () {
				if (this.$element.hasClass('grid-wrap') || this.$element.hasClass('js-grid')) {
					this._breakpoint = 0;
				}
			},

			onInitialized: function () {
				this.$element.addClass('owl-carousel');

				if (this.$element.closest('.fancybox-inner').length) {
					$.fancybox.update();
				}
			},
			onChanged : function () {
				this.settings.loop = (this._items.length <= this.settings.items) ? false : this.options.loop;
			},
		};

		this.owlOptions.responsive[this.grid.xs] = {
			items: 2
		};
		this.owlOptions.responsive[this.grid.sm] = {
			items: 3
		};
		this.owlOptions.responsive[this.grid.md] = {
			items: 3
		};
		this.owlOptions.responsive[this.grid.lg] = {
			items: 5
		};
	};

	sec = -1;
	rsSline.prototype.timer = function() {
		$('.js_timer').timer({
			days: ".js_timer-d",
			hours: ".js_timer-H",
			minute: ".js_timer-i",
			second: ".js_timer-s",
			blockTime: ".timer__cell",
			linePercent: ".js_timer-progress",
			textLinePercent: ".num_percent",
		});
	};

	rsSline.prototype.setProductCart = function($productItem) {

		var offerId = parseInt($productItem.find('.js-product_id').val());

		if (!!this.cartList) {
			if (!!this.cartList[offerId]) {
				$productItem.addClass('is-incart');
			} else {
				$productItem.removeClass('is-incart');
			}
		}
	};

	rsSline.prototype.setProductCompare = function($productItem) {

		var offerId = parseInt($productItem.find('.js-product_id').val());

		if (!!this.compareList) {
			if (!!this.compareList[offerId]) {
				$productItem.find('.js-compare').addClass('checked');
			} else {
				$productItem.find('.js-compare').removeClass('checked');
			}
		}
	};

	rsSline.prototype.setProductFavorite = function($productItem) {
		var productId = $productItem.data('product-id');

		if (!!this.favoriteList) {
			if (!!this.favoriteList[productId]) {
				$productItem.find('.js-favorite').addClass('checked');
			} else {
				$productItem.find('.js-favorite').removeClass('checked');
			}
		}
	};

	rsSline.prototype.setProductItems = function(options) {
		var opt = $.extend({
				'wrap': '',
				'items': ''
				}, options),
				self = this;

		if (opt.items == '') {
			if (opt.wrap == '') {
				var $productItems = $('.js-product');
			} else {
				var $productItems = $(opt.wrap).find('.js-product');
			}
		} else {
			var $productItems = opt.items;
		}

		$productItems.each(function() {

			var $productItem = $(this);

			if (!!self.cartList) {
				self.setProductCart($productItem);
			}
			if (!!self.compareList) {
				self.setProductCompare($productItem);
			}
			if (!!self.favoriteList) {
				self.setProductFavorite($productItem);
			}
		});
	};

	rsSline.prototype.gridInit = function(options) {
		var self = this,
				opt = $.extend({
					'wrap': '',
					'items': ''
				}, options);

		var sOwlSlider = '.js-catalog_slider';
			//sGridWrap = '.grid-wrap';
		if (1024 > this.pageWidth) {
			sOwlSlider += ', .js-grid';
			//sGridWrap += ', .js-grid';
		}
/*		else {
			$('.js-grid.owl-carousel').trigger('destroy.owl.carousel').removeClass('owl-carousel').removeAttr('style');
		}
*/
		if (1024 >= this.pageWidth) {
			sOwlSlider += ', .rs_set-default';
		} else {
			$('.rs_set-default.owl-carousel').trigger('destroy.owl.carousel').removeClass('owl-carousel').removeAttr('style');
		}

		/*
		var $gridWrap = $();
		if (!!opt.items) {
			$gridWrap = $(opt.items);
		} else {
			if (!!opt.wrap) {
				$gridWrap = $(opt.wrap).find('.js-grid');
			} else {
				$gridWrap = $(sGridWrap);
			}
		}

		$gridWrap.each(function() {
			var $wrap = $(this).show();
			if (parseInt($wrap.closest('.wrap').width()) > parseInt($wrap.width() + parseInt($wrap.css('marginLeft')))) {
				$wrap.removeClass('grid-wrap-full');
			} else {
				$wrap.addClass('grid-wrap-full');
			}
			$wrap.removeAttr('style');
		});
		*/

		$(sOwlSlider).each(function() {

			var $slider = $(this);

			if (!$slider.hasClass('owl-carousel')) {

				var extOwlOptions = {
					dots:false,
					responsive: {}
				};
				extOwlOptions.responsive[0] = {
					items: 2
				};

				extOwlOptions.responsive[self.grid.xs] = {
					items: 2
				};

				extOwlOptions.responsive[self.grid.sm] = {
					items: 3
				};

				extOwlOptions.responsive[self.grid.md] = {
					items: 4
				};

				extOwlOptions.responsive[self.grid.lg] = {
					items: 5
				};

				$slider.width($slider.parent().width() - 1)
					.owlCarousel($.extend(true, {}, self.owlOptions, extOwlOptions));

			} else {
				$slider.find('.owl-stage').hide();
				$slider.removeAttr('style').width($slider.parent().width() - 1).find('.owl-stage').show();
			}
		});

	};

	rsSline.prototype.closePopup = function(opts) {
		var defaults = {
			backurl: '',
			iCloseTimeout: this.fancyTimeout,
		};

		opts = $.extend( true, defaults, opts || {} );

		setTimeout(function()
		{
			if (!!opts.backurl && opts.backurl.length > 0)
			{
				if (opts.backurl == window.top.location.href.replace(window.top.location.origin, ''))
				{
					return;
				}
				window.top.location.href = opts.backurl;
				// window.top.location.reload()

			}
			else
			{
				if ($.fancybox.getInstance())
				{
					$.fancybox.getInstance().close();
				}
			}
		}, opts.iCloseTimeout);
	};

	rsSline.prototype.ready = function(rootDom) {

		if (!rootDom)
		{
			rootDom = document;
		}
		
		var self = this;
		this.setup();
		this.setProductItems();

		// Timer
		setInterval(function() {
			self.timer();
		}, 1000);

		// $('[data-fancybox]').fancybox($.extend({}, appSLine.fancyOptions));

		$(rootDom).find('.js-ajax_link, .js-ajax_fancy').on('click.fb-start', function(e){
			e.preventDefault();
			var $this = $(this);

			if (window.appSLine.getPageWidth() < window.appSLine.grid.sm)
			{
				if (!!$this.attr('href'))
				{
					var ajaxData = $this.data('insert_data');

					if (window.appSLine.getPageWidth() <= window.appSLine.grid.xs)
					{
						if (ajaxData != undefined)
						{
							BX.localStorage.set('ajax_data', ajaxData);
						}
					}

//					window.location.replace($this.attr('href'));

					var obForm = document.createElement('form');
					obForm.action = $this.attr('href');
					obForm.method = 'post';
					obForm.enctype = 'multipart/form-data';

					var input = document.createElement('input');
					input.type = 'text';
					input.name = 'backurl';
					input.value = window.location.href;
					obForm.appendChild(input);

					document.body.appendChild(obForm);
					obForm.submit();

					return false;
				}
			}
			else
			{
				var instance = $.fancybox.getInstance();
				if (instance)
				{
					instance.showLoading();
					
					$.ajax({
						type: 'POST',
						dataType: 'html',
						data: {
							backurl: window.location.href.replace(window.location.origin, ''),
						},
						url: $this.attr('href'),
						success: function (data) {
							instance.current.opts.$orig = $this;

							instance.trigger("beforeShow", false);
							instance.trigger("beforeLoad", instance.current);

							instance.setContent(
								instance.current,
								data
							);
							
							// appSLine.ready(instance.current.$content);
						},
						colmplete: function() {
							instance.hideLoading();
						}
					});
					return false;
				}
				else
				{
					$.fancybox.open(
						this,
						$.extend({}, appSLine.fancyOptions, {
							arrows : false,
							loop: false,
							onInit: function(instance)
							{
								var sBackurl = window.location.href
										.replace(window.location.origin, '')
										.replace(!!hash ? '#'+ hash : '', '');

								for (var index in instance.group)
								{
									if (
										!!instance.group[index].opts.$orig && (
											instance.group[index].opts.$orig.hasClass('js-ajax_link') ||
											instance.group[index].opts.$orig.hasClass('js-ajax_fancy')
										)
									)
									{
										if (instance.group[index].type = 'ajax')
										{
											instance.group[index].opts.ajax.settings.type = 'POST';

											var hash = instance.group[index].opts.$orig.data('fancybox');
											instance.group[index].opts.ajax.settings.data.backurl = sBackurl;
										}
									}
								}
							},
						})
					);
				}
			}
		});

		if (window.history.state !== null && BX.type.isNotEmptyObject(window.history.state))
		{
			BX.scrollToNode(window.history.state.id);
			/**/
			setTimeout(function() {
				if ('scrollRestoration' in window.history)
				{
					window.history.scrollRestoration = 'manual';
					BX.scrollToNode(window.history.state.id);

					setTimeout(function() {

						window.history.scrollRestoration = 'auto';
					}, 1000);
				}
			}, 1);
			/**/
		}
	};

	rsSline.prototype.onResize = function() {
		this.setup();
		// console.log('RESIZE');
	}

	rsSline.prototype.setup = function(options) {
		this.pageWidth = this.getPageWidth();
		this.pageHeight = this.getPageHeight();

		var appMain = $('#webpage');
		appMain.children('.l-main').css('height', 'auto');

		if (appMain.height() < this.pageHeight) {
			appMain.children('.l-main').css('min-height', this.pageHeight - appMain.children('.l-header').height() - appMain.children('.l-footer').height());
		}

		this.gridInit();
		// console.log('this.pageWidth', this.pageWidth);
	};

	rsSline.prototype.getPageWidth = function(options) {
		return (window.innerWidth)
			? window.innerWidth
			: (document.documentElement && document.documentElement.clientWidth)
				? document.documentElement.clientWidth
				: 0;
	};

	rsSline.prototype.getPageHeight = function(options) {
		return (window.innerHeight)
			? window.innerHeight
			: (document.documentElement && document.documentElement.clientHeight)
				? document.documentElement.clientHeight
				: 0;
	};

	rsSline.prototype.encodeURI = function(url) {
		var arParts = url.split(/(:\/\/|:\\d+\/|\/|\?|=|&)/),
				encoded = '';

		if (arParts.length > 0) {
			for (var i in arParts) {
				encoded += (i % 2) ? arParts[i] : encodeURIComponent(arParts[i]);
			}
		}

		return encoded;
	};

	window.rsSline = rsSline;

})(window);

var appSLine = new rsSline();

;(function ($) {
	appSLine.gridInit();
})(jQuery);

if (window.frameCacheVars !== undefined) {
		BX.addCustomEvent("onFrameDataReceived" , function(json) {
			appSLine.ready();
		});
} else {
		BX.ready(function() {
			appSLine.ready();
		});
}

$(window).resize(function(){
	appSLine.onResize();
});

;(function ($) {
	$.fn.onImageLoad = function (callback) {
		function isImageLoaded(img) {
			if (!img.complete) {
				return false;
			}
			if (typeof img.naturalWidth !== "undefined" && img.naturalWidth === 0) {
				return false;
			}
			return true;
		}

		return this.each(function () {
			var ele = $(this);
			if (ele.is("img") && $.isFunction(callback)) {
				ele.one("load", callback);
				if (isImageLoaded(this)) {
					ele.trigger("load");
				}
			}
		});
	};
})(jQuery);

;(function ($) {
	$.fn.setHtmlByUrl = function(options) {
		var settings = $.extend({
			'url': ''
		}, options);

		return this.each(function() {
			if ('' != settings.url)
			{
				var $this = $(this);
				$.ajax({
					type: 'GET',
					dataType: 'html',
					url: settings.url,
					beforeSend: function () {
						data = BX.localStorage.get(settings.url);
						if (data)
						{
							BX.localStorage.set(settings.url, data);
							$this.html(data);
							return false;
						}
						return true;
					},
					success: function (data) {
						BX.localStorage.set(settings.url, data);
						$this.html(data);
					},
				});
			}
		});
	};
})(jQuery);

(function($){
	$.fn.rsToggleDark = function(options){

		options = $.extend( $.fn.rsToggleDark.defaults, options );

		return this.each(function(){
			var $this = $(this);

			var $back = $this.children('.overlay__back');

			if (options.progress && $back.length) {
				$status = $back.find('.load__status').html(options.message);
			} else {
				if (!$this.hasClass('overlay')) {
					$this.addClass('overlay');
					$back = $('<div class="overlay__back vcenter">' +
						'<div class="overlay__progress vcenter__in">' +
								'<div class="load">' +
										'<div class="load__ball load__1"><div class="load__inner"></div></div>' +
										'<div class="load__ball load__2"><div class="load__inner"></div></div>' +
										'<div class="load__ball load__3"><div class="load__inner"></div></div>' +
										'<div class="load__ball load__4"><div class="load__inner"></div></div>' +
										'<div class="load__ball load__5"><div class="load__inner"></div></div>' +
								'</div>' +
						'</div>' +
						'</div>');
					$back.appendTo($this);
				} else {
					$this.removeClass('overlay').children('.overlay__back').remove();
				}
			}
		});

		$.fn.rsToggleDark.defaults = {
			progress: false,
			progressLeft: false,
			progressTop: false,
			text: false,
		};
	};
})(jQuery);

$(document).on('submit', '.fancybox-slide .js-ajax_form', function(e){

	var $form = $(this),
			frame_name = 'frame' + Math.round(Math.random() * 1000),
			$input = $('<input>', {
				type: 'hidden',
				name: 'rs_ajax__page',
				value: 'Y'
			});
			$iframe = $('<iframe></iframe>', {
				id: frame_name,
				name: frame_name,
				style: 'display:none',
			});

			$iframe.on('load', function(){
					var content = $(this).contents().find('body').html();
					if (content.length) {
						$.fancybox.getInstance().setContent(
							$.fancybox.getInstance().current,
							$(this).contents().find('body').html()
						);
					}
			});

	$form.append($input).attr('target', frame_name);

	//$(document).trigger('ajaxSuccess');
	$('body').append($iframe);
	BX.onCustomEvent('onAjaxSuccess');
	$.fancybox.getInstance().showLoading();
});

$(document).on('mouseenter', '.js-basket-minus, .js-basket-plus', function(){
	$('html').addClass("disableSelection");
});

$(document).on('mouseleave', '.js-basket-minus, .js-basket-plus', function(){
	$('html').removeClass("disableSelection");
});

$(document).on('click', '.js-basket-plus', function() {
	clearTimeout(rsSline.ajaxTimeout);
	var $input = $(this).siblings('.js-quantity'),
		value = parseFloat($input.val()),
		ratio = parseFloat($input.attr('step')),
		real = ratio.toString().split('.', 2)[1],
		length = 0;
	if (real !== undefined)
	{
		length = real.length;
	}
	$input.val((value + ratio).toFixed(length));
	rsSline.ajaxTimeout = setTimeout(function(){
		$input.trigger('change');
	}, appSLine.ajaxTimeoutTime);
});

$(document).on('click', '.js-basket-minus', function() {
	clearTimeout(rsSline.ajaxTimeout);
	var $input = $(this).siblings('.js-quantity'),
		value = parseFloat($input.val()),
		ratio = parseFloat($input.attr('step')),
		real = ratio.toString().split('.', 2)[1],
		length = 0;
	if (real !== undefined)
	{
		length = real.length;
	}
	if (value > ratio)
	{
		$input.val((value - ratio).toFixed(length));
		rsSline.ajaxTimeout = setTimeout(function(){
			$input.trigger('change');
		}, appSLine.ajaxTimeoutTime);
	}
});

$(document).on('blur', '.js-quantity', function() {
	var $input = $(this),
		value = parseFloat($input.val()),
		ratio = parseFloat($input.attr('step')),
		real = ratio.toString().split('.', 2)[1],
		length = 0;
	if (real !== undefined)
	{
		length = real.length;
	}
	if (0 < value)
	{
		$input.val((ratio * Math.floor(value / ratio)).toFixed(length));
	}
	else
	{
		$input.val(ratio);
	}
});

$(document).on('click','.js-product .js-add2cart',function(e) {
	var $addBtn = $(this),
			$formObj = $(this.form),
			iProductId = parseInt($formObj.find('.js-product_id').val());

	if (iProductId > 0) {

		var $product = $formObj.closest('.js-product'),
			$darkArea = $product.children('.catalog_item__inner'),
			sHref = $addBtn.attr('href'),
			ajaxRequest = {
				type: 'POST',
				dataType: 'html',

				success: function(data) {
					data = BX.parseJSON(data);
					if ((data.STATUS === 'OK')) {
						BX.onCustomEvent('OnBasketChange');
						appSLine.cartList[iProductId] = true;
						appSLine.setProductCart($product);
					}
				},
				error: function() {
					console.warn('add2cart - error responsed?');
				},
				complete:function() {
					$darkArea.rsToggleDark();
				}
			};

		if (!sHref) {
			ajaxRequest.data = $(this.form).serialize() + '&ajax_basket=Y';
		} else {
			ajaxRequest.url = sHref;
			ajaxRequest.data = 'ajax_basket=Y';
		}

		var $productProps = $product.find('.product_props').find('input');

		if ($productProps.length > 0) {
			ajaxRequest.data += '&' + $product.find('.product_props').find('input').serialize();
		} else {
			ajaxRequest.data += '&props[0]=0';
		}

		if ($darkArea.length < 1) {
			$darkArea = $product;
		}

		$darkArea.rsToggleDark({progress: true});
		$.ajax(ajaxRequest);

	} else {
		console.warn( 'add product to basket failed' );
	}
	return false;
});

$(document).on('click','.js-product .js-compare',function(e) {
	e.preventDefault();
	var $addBtn = $(this),
			$product = $(this).closest('.js-product'),
			arProduct = $product.data();

	if (arProduct != undefined) {
		var iProductId = arProduct.offerId ? arProduct.offerId : arProduct.productId,
				url = $addBtn.attr('href').replace('#ID#', iProductId),
				$darkArea = $product.children('.rs_product-inner'),
				ajaxRequest = {
					type: 'POST',
					data: {ajax_action: 'Y'},
					url: url,
					success: function(data) {
						data = BX.parseJSON(data);
						if (data.STATUS === 'OK') {
							if (!!appSLine.compareList[iProductId]) {
									delete appSLine.compareList[iProductId];
							} else {
									appSLine.compareList[iProductId] = true;
							}

							if (!data.COUNT) {
									data.COUNT = 0;
									for (var i in appSLine.compareList) {
											data.COUNT++;
									}
							}
						}
						appSLine.setProductCompare($product);
						BX.onCustomEvent('OnCompareChange');
					},
					error: function() {
						console.warn('add2cmp - error responsed?');
					},
					complete:function() {
						$darkArea.rsToggleDark();
					}
				};

		if ($darkArea.length < 1) {
				$darkArea = $product;
		}

		if (!!appSLine.compareList[iProductId]) {
				ajaxRequest.url = ajaxRequest.url.replace('ADD_TO_COMPARE_LIST', 'DELETE_FROM_COMPARE_LIST');//bitrixfix
		}

		$darkArea.rsToggleDark({progress: true});
		$.ajax(ajaxRequest);

	} else {
			console.warn('add product to compare failed');
	}
	return false;
});

$(document).on('click','.js-product .js-product__unsubscribe',function(e) {
	e.preventDefault();
	var $link = $(this),
		$product = $(this).closest('.js-product'),
		data = $link.data('subscribe-id');

	if (data != undefined)
	{
		var iProductId,
			url = '/bitrix/components/bitrix/catalog.product.subscribe.list/ajax.php',
			$darkArea = $product.children('.rs_product-inner'),
			deferreds = [];

		if ($darkArea.length < 1)
		{
			$darkArea = $product;
		}

		$darkArea.rsToggleDark({progress: true});

		for (iProductId in data)
		{
			if (data.hasOwnProperty(iProductId))
			{
				var ajaxRequest = {
					type: 'POST',
					data: {
						sessid: BX.bitrix_sessid(),
						deleteSubscribe: 'Y',
						itemId: iProductId,
						listSubscribeId: data[iProductId] ? data[iProductId] : []
					},
					url: url,
					success: function(data) {
						data = BX.parseJSON(data);
						if (data.success)
						{
							location.reload();
						}
					},
					error: function() {
						console.warn('deleteSubscribe - error responsed?');
					},
					complete:function() {
					}
				};

				deferreds.push($.ajax(ajaxRequest));
			}
		}

		$.when.apply($, deferreds).done(function () {
			window.location.reload();
			$darkArea.rsToggleDark();
		});
	}

	return false;
});
