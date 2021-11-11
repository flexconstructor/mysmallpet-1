
$(document).ready(function(){
	$('.js_popup_detail').fancybox({
		width : 1170,
		wrapCSS : 'popup_detail',
		fitToView : true,
		autoSize : true,
		openEffect : 'fade',
		closeEffect : 'fade',
		padding : [25,20,25,20],
		helpers : {
			title: null
		},
		ajax : {
			dataType : 'html',
			headers	: { 'popup_detail': 'Y' }
		},
		beforeLoad	: function(){
			this.href = this.href + (0 < this.href.indexOf('?') ? '&' : '?') + 'popup_detail=Y';
		},
		beforeShow	: function(){
			appSLine.setProductItems();
		},
		afterClose	: function(){
			appSLine.setProductItems();
		},
		afterShow	:function(){
			$('.fancybox-inner').css('overflow','visible');
			//RSAL_RefresDetailjJScollPane();
		}
	});
});

	// catalog element -> hover //
$(document).on('mouseenter', '.catalog_item', function(){
	//$('.catalog_item').removeClass('is-hover');
	$(this).addClass('is-hover');
});

$(document).on('mouseleave', '.catalog_item', function(){
	$(this).removeClass('is-hover')
		.find('.div_select.opened').removeClass('opened').addClass('closed');
});

$(window).on('scroll', function() {
	$('.js-ajaxpages_auto').each(function() {
		var $ajaxpObj = $ (this);
		if (200 > ($ajaxpObj.offset().top - window.pageYOffset - $(window).height()) && !appSLine.ajaxExec) {
			$ajaxpObj.find('a').trigger('click');
		}
	});
});

function rsCatalogSectionAjaxRefresh (options, e)
{
	var $refreshArea = $('#'+options.ajaxId),
		ajaxRequest = {
			type: 'POST',
			url: options.url,
			data: {},
			success: function(data) {
				var json = BX.parseJSON(data);

				if (options.historyPush)
				{
					if ('replaceState' in window.history)
					{
						if (options.type != 'popstate')
						{
							var historyState = null;
							historyState = {
								"isAjax": true,
								"url": options.url,
								"id": options.ajaxId,
							};

							options.url = options.url.indexOf('#') < 0
								? options.url + '#' + options.ajaxId
								: options.url;

							window.history.replaceState(historyState, null, options.url);

							if ($refreshArea.length > 0)
							{
								var docViewTop = $(window).scrollTop(),
									docViewBottom = docViewTop + $(window).height(),
									elemTop = $refreshArea.offset().top;

								if (
									elemTop < docViewTop
									|| elemTop > docViewBottom
								) {
									$('html, body').animate({
										scrollTop: elemTop
									}, 1000);
								}
							}
						}
						else
						{
							window.location.hash = options.ajaxId;
						}
					}
				}

				if (json == null)
				{
					$refreshArea.html(data);
				}
				else
				{
					for (var id in json)
					{
						$('#' + id).html(json[id]);
					}
				}

				$(e.target).parent().addClass('active').siblings().removeClass('active');

				appSLine.setProductItems();
			},
			error: function() {
				console.warn('sorter - change template -> error responsed');
			},
			complete: function() {
				appSLine.ajaxExec = false;
				$refreshArea.rsToggleDark();
			}
		};

	if (
		!appSLine.ajaxExec &&
		ajaxRequest.url != '#' && ajaxRequest.url != undefined
	) {

		$refreshArea.rsToggleDark({progress: true, progressTop: '100px'});

		appSLine.ajaxExec = true;

		ajaxRequest.url += (
			ajaxRequest.url.indexOf('?') < 0
				? '?'
				: ajaxRequest.url.slice(-1) != '&'
					? '&'
					: ''
		) + 'rs_ajax=Y';

		if (options.ajaxId != undefined)
		{
			ajaxRequest.url += '&ajax_id=' + options.ajaxId;
		}

		$.ajax(ajaxRequest);
	}
};

$(document).on('click', '.js-catalog_refresh a', function(e){

	var $link = $(this),
		sUrl = $link.attr('href')
			.split('+').join('%2B')/*.replace(' ', '+')*/, // url fix
		$loadElement = $link.closest('.js-catalog_refresh'),
		ajaxId = $loadElement.data('ajax-id');

	var ajaxParams = {
			"url": sUrl,
			"ajaxId": ajaxId,
		};

	if ($loadElement.data('history-push') != undefined) {
		ajaxParams.historyPush = true;
	}

	rsCatalogSectionAjaxRefresh(ajaxParams, e);
	e.preventDefault();
});

$(document).on('click', '.js-ajaxpages a', function(e){
	var $link = $(this),
		ajaxUrl = $link.attr('href'),
		$loadElement = $link.closest('.js-ajaxpages'),
		ajaxId = $loadElement.data('ajax-id'),
		ajaxRequest = {
			type: 'POST',
			url: ajaxUrl,
			success: function(data) {
				var json = BX.parseJSON(data);
				if ('replaceState' in window.history)
				{
					var historyState = null;
					historyState = {
						"url": ajaxUrl,
						"id": ajaxId,
					};

					ajaxUrl = ajaxUrl.indexOf('#') < 0
						? ajaxUrl + '#' + ajaxId
						: ajaxUrl;

					// if ('scrollRestoration' in history)
					// {
						// history.scrollRestoration = 'manual';
					// }
					window.history.replaceState(historyState, null, ajaxUrl);
				}

				if (json == null)
				{
					$loadElement.replaceWith(data);
				}
				else
				{
					for (var id in json)
					{
						$('#' + id).html(json[id]);
					}
				}
			},
			error: function() {
				console.warn('ajaxpages -> error responsed');
			},
			complete: function() {
				appSLine.ajaxExec = false;
				$loadElement.rsToggleDark();
			}
		};

	ajaxRequest.url += (
		ajaxRequest.url.indexOf('?') < 1
			? '?'
			: ajaxRequest.url.slice(-1) != '&'
				? '&'
				: ''
	) + 'rs_ajax=Y&ajax_type=pages';

	if ($loadElement.length > 0 && !appSLine.ajaxExec)
	{
		appSLine.ajaxExec = true;
		$loadElement.rsToggleDark();

		$.ajax(ajaxRequest);
	}
	e.preventDefault();
});

window.addEventListener("popstate", function(e)
{
	if (e !== null)
	{
		if (e.state === null || $.isEmptyObject(e.state))
		{
			// window.location.reload();
		}
		else
		{
			if (!!e.state.isAjax)
			{
				if (!!e.state.id && !!e.state.url)
				{
					rsCatalogSectionAjaxRefresh({
						"url": e.state.url,
						"ajaxId": e.state.id,
						"type": "popstate",

					}, e);
				}
				else if (!!e.state.url)
				{
					window.location.href = e.state.url;
				}
				else
				{
					window.location.reload();
				}
			}
			else
			{
				window.location.reload();
			}
		}
	}


}, false);
