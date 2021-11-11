$(document).on('click','.itdelta__small-basket',function(e) {
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
