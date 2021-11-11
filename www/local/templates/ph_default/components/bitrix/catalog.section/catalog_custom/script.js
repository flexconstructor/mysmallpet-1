
$(document).ready(function () {
  $(".itdelta__small-basket").on('click', function (e) {
     e.preventDefault();
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

  $(".itdelta__small-basket33333333").on('click', function (e) {
    var added_offers = {};
    let $addBtn = $(this);
    let $formObj = $(this.form);
    let product_id = parseInt($formObj.find('.js-product_id').val());
    let product_quant = 1;
    let product_price = $(this).attr('product-price');
      if(product_quant > 0) {
            added_offers[product_id] = {"ID":product_id, "QUANTITY":product_quant, "PRICE":product_price};
        }
    console.log(added_offers);
    $.ajax({
        url: '/local/ajax/addbasket.php',
        type: 'POST',
        data: {offers_data: added_offers},
        dataType : "html",
        success: function (data, textStatus) {
            if(data >= 1){

            } else if(data == 'empty'){
                alert('Не выбраны товары!');
            } else {
                alert('Произошла ошибка!');
                console.log(data);
            }
        }
    });
});

});
