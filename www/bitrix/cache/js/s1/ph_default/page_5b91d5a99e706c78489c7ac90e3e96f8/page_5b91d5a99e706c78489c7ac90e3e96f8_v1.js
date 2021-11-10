
; /* Start:"a:4:{s:4:"full";s:102:"/bitrix/templates/ph_default/components/bitrix/catalog.element/catalog_custom/script.js?16354205096419";s:6:"source";s:87:"/bitrix/templates/ph_default/components/bitrix/catalog.element/catalog_custom/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
$(document).ready(function () {
    var $product = $('.detail__product.js-product'),
        arProduct = $product.data(),
        $picbox = $('.picbox'),
        incViewedCounterfunction = function (arProduct) {
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
    $product.on('offerChecked.rs', function () {
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

    $detailCarousel.find('img:last').onImageLoad(function () {
        $detailCarousel.owlCarousel($.extend({}, appSLine.owlOptions, extOwlOptions));
    });

    $(".offer-plus").on('click', function () {
        let counter = $(this).closest('.add_to_cart-offer').find('.offer_counter');
        let max_val = parseInt(counter.attr('max'));
        let product_weight = parseFloat(parseFloat(counter.attr('product-weight')).toFixed(3));

        let product_price = parseFloat(counter.attr('product-price'));
        if (max_val !== 0) {
            currentCount = parseInt(counter.val());
            if (currentCount < max_val) {
                let current_total = getTotalInfo();
                current_total['weight'] += product_weight;
                current_total['weight'] = parseFloat(parseFloat(current_total['weight']).toFixed(3));
                console.log( current_total['weight']);
                current_total['price'] += product_price;
                current_total['price'] = Math.round(parseFloat(current_total['price']) * 100) / 100;
                current_total['count'] += 1;
                updateTotalInfo(current_total);
                currentCount++;
            }
            counter.val(currentCount);
        }
    });

    $(".offer-minus").on('click', function () {
        let counter = $(this).closest('.add_to_cart-offer').find('.offer_counter');
        let product_weight = parseFloat(counter.attr('product-weight'));
        let product_price = parseFloat(counter.attr('product-price'));
        let min_val = 0;
        currentCount = parseInt(counter.val());
        if (currentCount > min_val) {
            let current_total = getTotalInfo();
            current_total['weight'] -= product_weight;
            //current_total['weight'] = Math.round(parseFloat(current_total['weight']) * 100) / 100;
            current_total['weight'] = parseFloat(parseFloat(current_total['weight']).toFixed(3));
            current_total['price'] -= product_price;
            current_total['price'] = Math.round(parseFloat(current_total['price']) * 100) / 100;
            current_total['count'] -= 1;
            updateTotalInfo(current_total);
            currentCount--;
        }
        counter.val(currentCount);
    });

    function getTotalInfo(){
        let total_info = [];
        let total_weight = $(".total_info .total_div #total_weight");
        let total_price = $(".total_info .total_div #total_price");
        let total_count = $(".total_info .total_div #total_count");

        total_info['weight'] = parseFloat(parseFloat(total_weight.text()).toFixed(3));
        total_info['price'] = Math.round(parseFloat(total_price.text()) * 100) / 100;
        total_info['count'] = parseInt(total_count.text());

        return total_info;
    }

    function updateTotalInfo(currentTotal){
        $(".total_info .total_div #total_weight").text(currentTotal['weight']);
        $(".total_info .total_div #total_price").text(currentTotal['price']);
        $(".total_info .total_div #total_count").text(currentTotal['count']);
    }

    $(".offers_to_cart_btn").on('click', function () {
        var added_offers = {};
        $(".offer_counter").each(function() {
            let product_id = $(this).attr('product-id');
            let product_quant = $(this).val();
            let product_price = $(this).attr('product-price');
            if(product_quant > 0) {
                added_offers[product_id] = {"ID":product_id, "QUANTITY":product_quant, "PRICE":product_price};
            }
        });
        console.log(added_offers);
        $.ajax({
            url: '/local/ajax/addbasket.php',
            type: 'POST',
            data: {offers_data: added_offers},
            dataType : "html",
            success: function (data, textStatus) {
                if(data >= 1){
                    alert('Товары добавлены в корзину!');
                    document.location.reload();
                } else if(data == 'empty'){
                    alert('Не выбраны товары!');
                } else {
                    alert('Произошла ошибка!');
                    console.log(data);
                }
            }
        });
    });

    $('.detail__preview').scrollbar({
        "scrollx": "none"
    });
});

/* End */
;; /* /bitrix/templates/ph_default/components/bitrix/catalog.element/catalog_custom/script.js?16354205096419*/
