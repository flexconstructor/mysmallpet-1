$(document).ready(function(){

  var $product = $('.detail__product.js-product'),
      arProduct = $product.data(),
      $picbox = $('.picbox'),
      incViewedCounterfunction = function(arProduct){
        $.ajax({
          type: 'POST',
          url: '/bitrix/components/bitrix/catalog.element/ajax.php',
          data: {
            AJAX: 'Y',
            SITE_ID: BX.message('SITE_ID'),
            PARENT_ID: arProduct.productId,
            PRODUCT_ID: arProduct.offerId ? arProduct.offerId : arProduct.productId
          }
        });
      };

  if (arProduct != undefined) {
    incViewedCounterfunction(arProduct);
  }
  $product.on('offerChecked.rs', function(){
    var arProduct = $(this).data();
    incViewedCounterfunction(arProduct);
  });

  var extOwlOptions = {
      autoHeight: true,
      nav: true,
      items: 1,
      dots: true,
      dotsData: true,
      margin: 18,
      dotsContainer: '.picbox__dots',
      responsive: {},
      onInitialized: function () {
        this.$element.addClass('owl-carousel');

        if (this.$element.closest('.fancybox-sline').length) {
          $.fancybox.update();
        }

        //this._plugins.navigation._controls.$absolute.scrollbar({
        $picbox.find('.picbox__scroll').scrollbar({
          showArrows: true,
          scrollx: $picbox.find('.picbox__bar'),
          scrollStep: 107
        });
        //this.$element.closest('.picbox').rsToggleDark();
        this.$stage.find('.cloned > .picbox__canvas').removeAttr('data-fancybox');
      },
      onChange: function () {
        this.$stage.find('.cloned > .picbox__canvas').removeAttr('data-fancybox');
      }
    };

  extOwlOptions.responsive[appSLine.grid.xs] = {
    items: 2
  };
  extOwlOptions.responsive[appSLine.grid.md] = {
    items: 1,
    autoHeight: false
  };

  $detailCarousel = $picbox.find('.picbox__carousel');

  $detailCarousel.find('img:last').onImageLoad(function(){
    $detailCarousel.owlCarousel($.extend({}, appSLine.owlOptions, extOwlOptions));
  });

  $('.detail__preview').scrollbar({
    "scrollx": "none"
  });
});