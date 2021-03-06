
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
                    alert('???????????? ?????????????????? ?? ??????????????!');
                    document.location.reload();
                } else if(data == 'empty'){
                    alert('???? ?????????????? ????????????!');
                } else {
                    alert('?????????????????? ????????????!');
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
;
; /* Start:"a:4:{s:4:"full";s:108:"/bitrix/templates/ph_default/components/bitrix/sale.prediction.product.detail/al/script.min.js?1635417090337";s:6:"source";s:90:"/bitrix/templates/ph_default/components/bitrix/sale.prediction.product.detail/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function bx_sale_prediction_product_detail_load(a,t,e){t=t||{},e=e||{},BX.ajax({url:"/bitrix/components/bitrix/sale.prediction.product.detail/ajax.php",method:"POST",data:BX.merge(t,e),dataType:"html",processData:!1,start:!0,onsuccess:function(t){var e=BX.processHTML(t);BX(a).innerHTML=e.HTML,BX.ajax.processScripts(e.SCRIPT)}})}window;
/* End */
;
; /* Start:"a:4:{s:4:"full";s:95:"/bitrix/templates/ph_default/components/bitrix/sale.gift.product/al/script.min.js?1635417090304";s:6:"source";s:77:"/bitrix/templates/ph_default/components/bitrix/sale.gift.product/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function bx_sale_gift_product_load(a,t,s){t=t||{},s=s||{},BX.ajax({url:"/bitrix/components/bitrix/sale.gift.product/ajax.php",method:"POST",data:BX.merge(t,s),dataType:"html",processData:!1,start:!0,onsuccess:function(t){var s=BX.processHTML(t);BX(a).innerHTML=s.HTML,BX.ajax.processScripts(s.SCRIPT)}})}
/* End */
;
; /* Start:"a:4:{s:4:"full";s:89:"/bitrix/templates/ph_default/template_ext/catalog.section/al/script.min.js?16354170903365";s:6:"source";s:70:"/bitrix/templates/ph_default/template_ext/catalog.section/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function rsCatalogSectionAjaxRefresh(e,a){var t=$("#"+e.ajaxId),o={type:"POST",url:e.url,data:{},success:function(o){var r=BX.parseJSON(o);if(e.historyPush&&"replaceState"in window.history)if("popstate"!=e.type){var i=null;if(i={isAjax:!0,url:e.url,id:e.ajaxId},e.url=e.url.indexOf("#")<0?e.url+"#"+e.ajaxId:e.url,window.history.replaceState(i,null,e.url),t.length>0){var s=$(window).scrollTop(),n=s+$(window).height(),l=t.offset().top;(l<s||l>n)&&$("html, body").animate({scrollTop:l},1e3)}}else window.location.hash=e.ajaxId;if(null==r)t.html(o);else for(var c in r)$("#"+c).html(r[c]);$(a.target).parent().addClass("active").siblings().removeClass("active"),appSLine.setProductItems()},error:function(){console.warn("sorter - change template -> error responsed")},complete:function(){appSLine.ajaxExec=!1,t.rsToggleDark()}};appSLine.ajaxExec||"#"==o.url||void 0==o.url||(t.rsToggleDark({progress:!0,progressTop:"100px"}),appSLine.ajaxExec=!0,o.url+=(o.url.indexOf("?")<0?"?":"&"!=o.url.slice(-1)?"&":"")+"rs_ajax=Y",void 0!=e.ajaxId&&(o.url+="&ajax_id="+e.ajaxId),$.ajax(o))}$(document).ready(function(){$(".js_popup_detail").fancybox({width:1170,wrapCSS:"popup_detail",fitToView:!0,autoSize:!0,openEffect:"fade",closeEffect:"fade",padding:[25,20,25,20],helpers:{title:null},ajax:{dataType:"html",headers:{popup_detail:"Y"}},beforeLoad:function(){this.href=this.href+(0<this.href.indexOf("?")?"&":"?")+"popup_detail=Y"},beforeShow:function(){appSLine.setProductItems()},afterClose:function(){appSLine.setProductItems()},afterShow:function(){$(".fancybox-inner").css("overflow","visible")}})}),$(document).on("mouseenter",".catalog_item",function(){$(this).addClass("is-hover")}),$(document).on("mouseleave",".catalog_item",function(){$(this).removeClass("is-hover").find(".div_select.opened").removeClass("opened").addClass("closed")}),$(window).on("scroll",function(){$(".js-ajaxpages_auto").each(function(){var e=$(this);200>e.offset().top-window.pageYOffset-$(window).height()&&!appSLine.ajaxExec&&e.find("a").trigger("click")})}),$(document).on("click",".js-catalog_refresh a",function(e){var a=$(this),t=a.attr("href").split("+").join("%2B"),o=a.closest(".js-catalog_refresh"),r=o.data("ajax-id"),i={url:t,ajaxId:r};void 0!=o.data("history-push")&&(i.historyPush=!0),rsCatalogSectionAjaxRefresh(i,e),e.preventDefault()}),$(document).on("click",".js-ajaxpages a",function(e){var a=$(this),t=a.attr("href"),o=a.closest(".js-ajaxpages"),r=o.data("ajax-id"),i={type:"POST",url:t,success:function(e){var a=BX.parseJSON(e);if("replaceState"in window.history){var i=null;i={url:t,id:r},t=t.indexOf("#")<0?t+"#"+r:t,window.history.replaceState(i,null,t)}if(null==a)o.replaceWith(e);else for(var s in a)$("#"+s).html(a[s])},error:function(){console.warn("ajaxpages -> error responsed")},complete:function(){appSLine.ajaxExec=!1,o.rsToggleDark()}};i.url+=(i.url.indexOf("?")<1?"?":"&"!=i.url.slice(-1)?"&":"")+"rs_ajax=Y&ajax_type=pages",o.length>0&&!appSLine.ajaxExec&&(appSLine.ajaxExec=!0,o.rsToggleDark(),$.ajax(i)),e.preventDefault()}),window.addEventListener("popstate",function(e){null!==e&&(null===e.state||$.isEmptyObject(e.state)||(e.state.isAjax?e.state.id&&e.state.url?rsCatalogSectionAjaxRefresh({url:e.state.url,ajaxId:e.state.id,type:"popstate"},e):e.state.url?window.location.href=e.state.url:window.location.reload():window.location.reload()))},!1);

/* End */
;
; /* Start:"a:4:{s:4:"full";s:102:"/bitrix/templates/ph_default/components/bitrix/sale.gift.main.products/al/script.min.js?16354170902525";s:6:"source";s:83:"/bitrix/templates/ph_default/components/bitrix/sale.gift.main.products/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
BX.namespace("BX.Sale"),BX.Sale.GiftMainProductsClass=function(){var t=function(t){this.ajaxUrl="/bitrix/components/bitrix/sale.gift.main.products/ajax.php",this.contextAjaxData=t.contextAjaxData||{},this.mainProductState=t.mainProductState||null,this.injectId=t.injectId||null,this.isGift=!!t.isGift,this.productId=t.productId,this.offerId=t.offerId,this.offers=t.offers||[],this.setEvents(),document.location.hash.match(/as_gift/g)&&(this.isGift?this.enableGift():this.raiseNonGiftEvent()),BX.bindDelegate(BX(this.injectId),"click",{tagName:"a"},BX.proxy(this.clickNavLink,this))};return t.prototype.clickNavLink=function(t){if(this.onPageNavigationByLink(BX.proxy_context))return BX.PreventDefault(t)},t.prototype.setEvents=function(){BX.addCustomEvent("onCatalogStoreProductChange",BX.proxy(this.onCatalogStoreProductChange,this)),BX.addCustomEvent("onAddToBasketMainProduct",BX.proxy(this.onAddToBasketMainProduct,this))},t.prototype.unsubscribeEvents=function(){BX.removeCustomEvent("onCatalogStoreProductChange",BX.proxy(this.onCatalogStoreProductChange,this))},t.prototype.onAddToBasketMainProduct=function(t){this.enableGift()},t.prototype.onCatalogStoreProductChange=function(t){t!=this.offerId&&BX.ajax({url:this.ajaxUrl,method:"POST",data:BX.merge(this.contextAjaxData,{offerId:t,mainProductState:this.mainProductState,SITE_ID:BX.message("SITE_ID")}),dataType:"html",processData:!1,start:!0,onsuccess:BX.delegate(function(e){this.offerId=t;var i=BX.processHTML(e);if(!i.HTML)return void(document.location.hash.match(/as_gift/g)&&(this.isGift?this.raiseGiftEvent():this.raiseNonGiftEvent()));this.unsubscribeEvents(),BX(this.injectId).innerHTML=i.HTML,BX.ajax.processScripts(i.SCRIPT)},this)})},t.prototype.onPageNavigationByLink=function(t){return!!BX.delegate(function(t){return!(!BX.type.isElementNode(t)||!t.href)&&(t.href.indexOf(this.ajaxUrl)>=0||-1!==t.href.indexOf("PAGEN_"))},this)(t)&&(BX.ajax({url:t.href,method:"POST",data:BX.merge(this.contextAjaxData,{SITE_ID:BX.message("SITE_ID")}),dataType:"html",processData:!1,start:!0,onsuccess:BX.delegate(function(t){var e=BX.processHTML(t);e.HTML&&(this.unsubscribeEvents(),BX(this.injectId).innerHTML=e.HTML,BX.ajax.processScripts(e.SCRIPT))},this)}),!0)},t.prototype.enableGift=function(){this.isGift=!0,this.raiseGiftEvent()},t.prototype.raiseGiftEvent=function(){BX.onCustomEvent("onSaleProductIsGift",[this.productId,this.offerId])},t.prototype.raiseNonGiftEvent=function(){BX.onCustomEvent("onSaleProductIsNotGift",[this.productId,this.offerId])},t}();
/* End */
;
; /* Start:"a:4:{s:4:"full";s:99:"/bitrix/templates/ph_default/components/bitrix/forum.topic.reviews/al/script.min.js?163541709015159";s:6:"source";s:79:"/bitrix/templates/ph_default/components/bitrix/forum.topic.reviews/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
!function(e){BX.namespace("BX.Forum");var t=function(e){if(this.id="FTRList"+e.form.id,this.mess={},this.form=e.form,e.id)for(var t=0;t<e.id.length;t++)this.bind(e.id[t]);this.params={preorder:"Y"==e.preorder,pageNumber:e.pageNumber,pageCount:e.pageCount},BX.addCustomEvent(this.form,"onAdd",BX.delegate(this.add,this)),BX.addCustomEvent(this.form,"onRequest",BX.delegate(function(){if(void 0!==this.params.pageNumber){var e=this.form.elements.pageNumber;e||(e=BX.create("input",{props:{type:"hidden",name:"pageNumber"}}),this.form.appendChild(e)),e.value=this.params.pageNumber}if(void 0!==this.params.pageCount){var t=BX.findChild(this.form,{attr:{name:"pageCount"}});t||(t=BX.create("input",{props:{type:"hidden",name:"pageCount"}}),this.form.appendChild(t)),t.value=this.params.pageCount}},this)),BX.addCustomEvent(this.form,"onResponse",BX.delegate(function(){var e=BX.findChild(this.form,{attr:{name:"pageNumber"}},!0);e&&BX.remove(e)},this))};t.prototype={add:function(t,s){var i,a=BX(this.form.id+"container"),r={className:/reviews-reply-form|reviews-collapse/},o=e.fTextToNode(s.message);if(a||(a=BX.create("div",{attrs:{id:this.form.id+"container"},props:{className:"reviews-block-container reviews-reviews-block-container"}}),e.fReplaceOrInsertNode(a,null,BX.findChild(document,r,!0).parentNode,r),a=BX(this.form.id+"container")),i=a?BX.findChild(a,{className:"reviews-block-inner"},!0):null,o&&i){if(s.allMessages){if(e.fReplaceOrInsertNode(o,i,BX.findChild(document,r,!0).parentNode,r),s.navigation&&s.pageNumber){var n,l=e.fTextToNode(s.navigation),d=l?BX.findChildren(a.parentNode,{className:"reviews-navigation-box"},!0):null;if(l){if(!d){a.parentNode.insertBefore(BX.create("div",{props:{className:"reviews-navigation-box reviews-navigation-top"}}),a);var c=a;do{c=c.nextSibling}while(c&&1!=c.nodeType);var h=BX.create("div",{props:{className:"reviews-navigation-box reviews-navigation-bottom"}});c?a.parentNode.insertBefore(h,c):a.parentNode.appendChild(h),d=BX.findChildren(a.parentNode,{className:"reviews-navigation-box"},!0)}for(n=0;n<d.length;n++)d[n].innerHTML=l.innerHTML}this.params.pageNumber=s.pageNumber,this.params.pageCount=s.pageCount}if(s.messagesID&&"object"==typeof s.messagesID)for(var m=0;m<s.messagesID.length;m++)s.messagesID[m]!=t&&this.bind(s.messagesID[m])}else void 0!==s.message&&(this.params.preorder?i.appendChild(o):i.insertBefore(o,i.firstChild));e.fRunScripts(s.message),this.bind(t)}},bind:function(e){var t=BX("message"+e);if(t){this.mess["m"+e]={node:t,author:{id:t.getAttribute("bx-author-id"),name:t.getAttribute("bx-author-name")}};var s=BX.findChildren(t,{tagName:"A",className:"reviews-button-small"},!0),i=BX.delegate(function(){var t=BX.proxy_context;this.act(t.getAttribute("bx-act"),e)},this),a=BX.delegate(function(){this.act("reply",e)},this),r=BX.delegate(function(){this.act("quote",e)},this);if(s&&s.length>0)for(var o=0;o<s.length;o++)"moderate"==s[o].getAttribute("bx-act")||"del"==s[o].getAttribute("bx-act")?BX.adjust(s[o],{events:{click:i},attrs:{"bx-href":s[o].getAttribute("href"),href:"javascript:void(0);"}}):this.form&&("reply"==s[o].getAttribute("bx-act")?BX.bind(s[o],"click",a):"quote"==s[o].getAttribute("bx-act")&&BX.bind(s[o],"mousedown",r))}},act:function(t,s){if(s&&this.mess["m"+s])if("quote"==t){var i=e.GetSelection();if(document.getSelection&&(i=i.replace(/\r\n\r\n/gi,"_newstringhere_").replace(/\r\n/gi," "),i=i.replace(/  /gi,"").replace(/_newstringhere_/gi,"\r\n\r\n")),""===i&&s>0&&BX("message_text_"+s,!0)){var a=BX("message_text_"+s,!0);"object"==typeof a&&a&&(i=a.innerHTML)}i=i.replace(/[\n|\r]*<br(\s)*(\/)*>/gi,"\n");var r=function(e,t){var s=" ",i=/showWMVPlayer.*?bx_wmv_player.*?file:[\s'"]*([^"']*).*?width:[\s'"]*([^"']*).*?height:[\s'"]*([^'"]*).*?/gi,a=i.exec(t);if(a&&(s="[VIDEO WIDTH="+a[2]+" HEIGHT="+a[3]+"]"+a[1]+"[/VIDEO]")," "==s){a=/bxPlayerOnload[\s\S]*?[\s'"]*file[\s'"]*:[\s'"]*([^"']*)[\s\S]*?[\s'"]*height[\s'"]*:[\s'"]*([^"']*)[\s\S]*?[\s'"]*width[\s'"]*:[\s'"]*([^"']*)/gi.exec(t),a&&(s="[VIDEO WIDTH="+a[3]+" HEIGHT="+a[2]+"]"+a[1]+"[/VIDEO]")}return s};i=i.replace(/<script[^>]*>/gi,"").replace(/<\/script[^>]*>/gi,""),i=i.replace(/\001([^\002]*)\002/gi,r),i=i.replace(/<noscript[^>]*>/gi,"").replace(/<\/noscript[^>]*>/gi,""),i=i.replace(/\003([^\004]*)\004/gi," "),i=i.replace(/<table class\=[\"]*forum-quote[\"]*>[^<]*<thead>[^<]*<tr>[^<]*<th>([^<]+)<\/th><\/tr><\/thead>[^<]*<tbody>[^<]*<tr>[^<]*<td>/gi,""),i=i.replace(/<table class\=[\"]*forum-code[\"]*>[^<]*<thead>[^<]*<tr>[^<]*<th>([^<]+)<\/th><\/tr><\/thead>[^<]*<tbody>[^<]*<tr>[^<]*<td>/gi,""),i=i.replace(/<table class\=[\"]*data-table[\"]*>[^<]*<tbody>/gi,""),i=i.replace(/<\/td>[^<]*<\/tr>(<\/tbody>)*<\/table>/gi,""),i=i.replace(/[\r|\n]{2,}([\001|\002])/gi,"\n$1");for(var o=0;o++<50&&(i.search(/\002([^\002\003]*)\003/gi)>=0||i.search(/\001([^\001\003]*)\003/gi)>=0);)i=i.replace(/\002([^\002\003]*)\003/gi,"[CODE]$1[/CODE]").replace(/\001([^\001\003]*)\003/gi,"[QUOTE]$1[/QUOTE]");var n=function(e,t,s){for(var i=new RegExp("([^]*)("+t+")([^]*)","i"),a=new RegExp("((?:)(?:[^]*))("+t+")((?:[^]*)(?:))","i"),r=0;r++<300&&e.search(i)>=0;)e=e.replace(a,"$1"+s+"$3");return e};for(o=0;o++<10&&i.search(/\004([^\004\003]*)\003/gi)>=0;)i=n(i,"<tr>","[TR]"),i=n(i,"</tr>","[/TR]"),i=n(i,"<td>","[TD]"),i=n(i,"</td>","[/TD]"),i=i.replace(/\004([^\004\003]*)\003/gi,"[TABLE]$1[/TD][/TR][/TABLE]");i=BX.browser.IsIE()?i.replace(/<img(?:(?:\s+alt\s*=\s*\"?smile([^\"\s]+)\"?)|(?:\s+\w+\s*=\s*[^\s>]*))*>/gi,"$1"):i.replace(/<img.*?alt=[\"]*smile([^\"\s]+)[\"]*[^>]*>/gi,"$1"),i=i.replace(/<a[^>]+href=[\"]([^\"]+)\"[^>]+>([^<]+)<\/a>/gi,"[URL=$1]$2[/URL]"),i=i.replace(/<a[^>]+href=[\']([^\']+)\'[^>]+>([^<]+)<\/a>/gi,"[URL=$1]$2[/URL]"),i=i.replace(/<[^>]+>/gi," ").replace(/&lt;/gi,"<").replace(/&gt;/gi,">").replace(/&quot;/gi,'"'),i=i.replace(/(smile(?=[:;8]))/g,""),i=i.replace(/\&shy;/gi,""),i=i.replace(/\&nbsp;/gi," "),BX.onCustomEvent(this.form,"onQuote",[{author:this.mess["m"+s].author,id:s,text:i}])}else if("reply"==t)BX.onCustomEvent(this.form,"onReply",[{author:this.mess["m"+s].author,id:s}]);else if("del"!=t||confirm(BX.message("f_cdm"))){if("moderate"==t||"del"==t){var l=BX.proxy_context,d=l.getAttribute("bx-href").replace(/.AJAX_CALL=Y/g,"").replace(/.sessid=[^&]*/g,""),c=BX.findParent(l,{tag:"table"}),h=BX.create("a",{attrs:{className:"reply-action-note"}}),m=function(){BX.remove(h),BX.show(l.parentNode)};BX.hide(l.parentNode),h.innerHTML=BX.message("f_wait"),l.parentNode.parentNode.appendChild(h),BX.ajax.loadJSON(d,{AJAX_CALL:"Y",sessid:BX.bitrix_sessid()},BX.delegate(function(s){if(s.status&&c)if(BX.onCustomEvent(e,"onForumCommentAJAXAction",[t]),"del"==t){var i=e.curpage||top.window.location.href;BX.fx.hide(c,"scroll",{time:.15,callback_complete:BX.delegate(function(){BX.remove(c),m();var e=BX.findChild(BX(this.form.id+"container"),{class:"reviews-post-table"},!0,!0);(!e||e.length<1)&&this.params.pageNumber>1&&BX.reload(i)},this)})}else{var a=BX.hasClass(c,"reviews-post-hidden"),r=a?BX.message("f_hide"):BX.message("f_show"),o=BX.findChild(c,{className:"reviews-text"},!0);BX.fx.hide(o,"fade",{time:.1,callback_complete:function(){BX.toggleClass(c,"reviews-post-hidden"),l.innerHTML=r,d=d.replace(new RegExp("REVIEW_ACTION="+(a?"SHOW":"HIDE")),"REVIEW_ACTION="+(a?"HIDE":"SHOW")),l.setAttribute("bx-href",d),BX.fx.show(o,"fade",{time:.1}),m(),BX.style(o,"background-color",a?"#FFFFFF":"#E5F8E3")}})}else BX.addClass(h,"error"),h.innerHTML='<span class="errortext">'+s.message+"</span>"},this))}}else BX.DoNothing();else BX.DoNothing();return!1}};var s=function(){var t=function(e,t){if(this.id="FTRForm"+e.form.id,this.form=e.form,this.editor=t,this.windowEvents={},this.params={messageMax:64e3},this.onsuccess=BX.delegate(this.onsuccess,this),this.onfailure=BX.delegate(this.onfailure,this),this.submit=BX.delegate(this.submit,this),BX.bind(this.form,"submit",this.submit),this.isAjax="Y"==e.ajaxPost,"Y"==e.captcha){var s=new Captcha(this.form);BX.ready(function(){BX.bind(BX("forum-refresh-captcha"),"click",BX.proxy(s.Update,s))}),"Y"==e.bVarsFromForm&&s.Show()}BX.addCustomEvent(this.form,"onQuote",BX.delegate(function(e){this.show(),this.quote(e)},this)),BX.addCustomEvent(this.form,"onReply",BX.delegate(function(e){this.show(),this.paste(e)},this)),BX.addCustomEvent(this.form,"onTransverse",BX.delegate(this.transverse,this));var i=$(this.form).find(".js-stars > .rate__icon"),a=0;i.on("click",function(){var e=$(this),t=e.parent(),s=e.data("index");if(e.addClass("checked").siblings().removeClass("checked"),a==s)return t.removeClass("rating-"+a),a=0,!1;0!=a?t.removeClass("rating-"+a).addClass("is-checked"):t.removeClass("is-checked"),t.find("input").val(s),t.addClass("rating-"+s),a=s})};return t.prototype={submit:function(e){if(this.validate()){if(this.prepareForm(),this.disableButtons(!0),!this.isAjax)return!0;this.send()}return BX.PreventDefault(e)},prepareForm:function(){},disableButtons:function(e){for(var t=this.form.getElementsByTagName("input"),s=0;s<t.length;s++)"submit"==t[s].getAttribute("type")&&(t[s].disabled=!1!==e)},validate:function(){var e="",t=this.form.REVIEW_RATE.value+":SEPARATOR:"+this.form.REVIEW_TEXT_plus.value+":SEPARATOR:"+this.form.REVIEW_TEXT_minus.value+":SEPARATOR:"+this.form.REVIEW_TEXT_comment.value,s=this.form.REVIEW_TEXT_plus.value.length+this.form.REVIEW_TEXT_comment.value.length+this.form.REVIEW_TEXT_comment.value.length;return this.form.TITLE&&this.form.TITLE.value.length<=0&&(e+=BX.message("no_topic_name")),s<=0?e+=BX.message("no_message"):s>64e3&&(e+=BX.message("max_len").replace(/#MAX_LENGTH#/gi,64e3).replace(/#LENGTH#/gi,s)),""!==e?(alert(e),!1):(this.form.REVIEW_TEXT.value=t,!0)},busy:!1,send:function(){return!0!==this.busy&&(this.busy=!0,this.form.elements.dataType||this.form.appendChild(BX.create("input",{props:{type:"hidden",name:"dataType",value:"json"}})),BX.onCustomEvent(this.form,"onRequest",[this.form,this]),BX.ajax.submitAjax(this.form,{method:"POST",url:this.form.action,dataType:"json",onsuccess:this.onsuccess,onfailure:this.onfailure}),!0)},onsuccess:function(e){this.busy=!1,this.disableButtons(!1),BX.onCustomEvent(this.form,"onResponse",[this.form,this]),this.get(e)},onfailure:function(){BX.onCustomEvent(this.form,"onResponse",[this.form,this]),BX.reload()},get:function(t){if(e.curpage=e.curpage||top.window.location.href,BX.onCustomEvent(e,"onForumCommentAJAXPost",[t,this.form]),void 0===t||t.reload)return void BX.reload(e.curpage);if(t.status){if(t.allMessages||void 0!==t.message)BX.onCustomEvent(this.form,"onAdd",[t.messageID,t]),this.clear();else if(t.previewMessage){var s=BX.findChild(document,{className:"reviews-preview"},!0),i=BX.findChild(document,{className:/reviews-reply-form|reviews-collapse/},!0).parentNode,a=e.fTextToNode(t.previewMessage);e.fReplaceOrInsertNode(a,s,i,{className:/reviews-reply-form|reviews-collapse/}),e.PostFormAjaxStatus(""),e.fRunScripts(t.previewMessage)}var r=t.messageID?BX("message"+t.messageID):null;r&&BX.scrollToNode(r),e.appSLine.closePopup()}BX.closeWait(),t.statusMessage&&e.PostFormAjaxStatus(t.statusMessage)},clear:function(){var e=BX.findChild(document,{className:"reviews-preview"},!0);e&&BX.remove(e);for(var t,s,i,a=0;(t=BX("upload_files_"+a+++"_"+this.form.index.value))&&t;)(s=BX.findChild(t,{tagName:"input"},!0))&&BX(s)&&(i=BX.clone(s),i.value="",s.parentNode.insertBefore(i,s),s.parentNode.removeChild(s)),BX.hide(t);var r=BX.findChild(this.form,{className:"forum-upload-file-attach"},!0);r&&BX.show(r);var o=BX.findChild(this.form,{className:"reviews-upload-info"},!0);o&&BX.hide(o);var n=null,l=BX.findChild(this.form,{attr:{name:"captcha_code"}},!0),d=BX.findChild(this.form,{attr:{name:"captcha_word"}},!0),c=BX.findChild(this.form,{className:"reviews-reply-field-captcha-image"},!0);c&&(n=BX.findChild(c,{tag:"img"})),l&&d&&n&&(d.value="",BX.ajax.getCaptcha(function(e){l.value=e.captcha_sid,n.src="/bitrix/tools/captcha.php?captcha_code="+e.captcha_sid}))},show:function(){return BX.onCustomEvent(this.form,"onBeforeShow",[this]),BX.show(this.form.parentNode),BX.scrollToNode(BX.findChild(this.form,{attribute:{name:"send_button"}},!0)),BX.onCustomEvent(this.form,"onAfterShow",[this]),!1},hide:function(){return BX.onCustomEvent(this.form,"onBeforeHide",[this]),BX.hide(this.form.parentNode),BX.onCustomEvent(this.form,"onAfterHide",[this]),!1},transverse:function(e){return BX.hasClass(document.documentElement,"bx-no-touch")&&appSLine.pageWidth>=appSLine.grid.sm?$.fancybox.open(this.form.parentNode,$.extend({},appSLine.fancyOptions,{title:$(e.target).attr("title")})):"none"==this.form.parentNode.style.display?this.form.parentNode.style.display="inline-block":this.form.parentNode.style.display="none",!1},quote:function(e){BX.onCustomEvent(this.form,"onPaste",[e,"QUOTE",this]);e.author,e.text},paste:function(e){BX.onCustomEvent(this.form,"onPaste",[e,"REPLY",this]);e.author}},t}();BX.Forum.Init=function(e){e&&"object"==typeof e&&(new t(e),new s(e,!1))},BX.ready(function(){if(BX.browser.IsIE()){var e,t,s,i=BX.findChildren(document,{className:"reviews-post-table"},!0);if(!i)return;for(e=0;e<i.length;e++)for(t=i[e].getElementsByTagName("*"),s=t.length;s--;)t[s].scrollWidth>t[s].offsetWidth&&(t[s].style.paddingBottom="20px",t[s].style.overflowY="hidden")}}),e.fTextToNode=function(e){var t=BX.create("div");return t.innerHTML=e,t.childNodes.length>0?t:null},e.PostFormAjaxStatus=function(t){$.fancybox.getInstance()?formWrap=$.fancybox.getInstance().current.src:formWrap=document;var s,i=BX.findChild(formWrap,{className:"reviews-note-box"},!0,!0);if(i)for(s=0;s<=i.length;s++)BX.remove(i[s]);if(!(t.length<1)){var a=e.fTextToNode(t);if(a)if($.fancybox.getInstance()){var r=BX.findChild(formWrap,{className:"reviews-form"},!0);if(!r)return;r.parentNode.insertBefore(a,r)}else{var r=BX.findChild(document,{className:"reviews-block-container"},!0);if(!r)return;for(var o=["reviews-reply-form","reviews-collapse"],n=r;(n=n.nextSibling)&&n;)if(1==n.nodeType){var l=!1;for(s=0;s<o.length;s++)if(BX.hasClass(n,o[s])){l=!0;break}if(l){n.parentNode.insertBefore(a,n);break}}}}},e.SetReviewsAjaxPostTmp=function(t){e.forumAjaxPostTmp=t},e.fReplaceOrInsertNode=function(t,s,i,a){var r=null;return!!BX.type.isDomNode(i)&&(!(!BX.type.isDomNode(t)&&!BX.type.isArray(t)&&t.length>0&&!(t=e.fTextToNode(t)))&&(BX.type.isDomNode(s)&&(i=s.parentNode,r=s.nextSibling,i.removeChild(s)),r||(r=BX.findChild(i,a,!0)),r?r.parentNode.insertBefore(t,r):i.appendChild(t),!0))},e.fRunScripts=function(e){var t=BX.processHTML(e,!0);BX.ajax.processScripts(t.SCRIPT,!0)},e.ShowLastEditReason=function(e,t){t&&(t.style.display=e?"block":"none")},e.AttachFile=function(e,t,s,i){var a=null,r=!1;e=parseInt(e),t=parseInt(t),document.getElementById("upload_files_info_"+s).style.display="block";for(var o=e;o<e+t&&((a=document.getElementById("upload_files_"+o+"_"+s))&&null!==typeof a);o++)if("none"==a.style.display){r=!0,a.style.display="block";break}!0==(!r||o>=e+t-1)&&(i.style.display="none")},e.GetSelection=function(){var t,s="";return e.getSelection?(t=e.getSelection(),s=t.toString()):document.selection&&(t=document.selection,s=t.createRange().text),s}}(window);
/* End */
;
; /* Start:"a:4:{s:4:"full";s:111:"/bitrix/templates/ph_default/components/bitrix/main.userconsent.request/form/user_consent.min.js?16354170907138";s:6:"source";s:92:"/bitrix/templates/ph_default/components/bitrix/main.userconsent.request/form/user_consent.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
!function(){function t(t){this.caller=t.caller,this.formNode=t.formNode,this.controlNode=t.controlNode,this.inputNode=t.inputNode,this.config=t.config}t.prototype={},BX.UserConsent={msg:{title:"MAIN_USER_CONSENT_REQUEST_TITLE",btnAccept:"MAIN_USER_CONSENT_REQUEST_BTN_ACCEPT",btnReject:"MAIN_USER_CONSENT_REQUEST_BTN_REJECT",loading:"MAIN_USER_CONSENT_REQUEST_LOADING",errTextLoad:"MAIN_USER_CONSENT_REQUEST_ERR_TEXT_LOAD"},events:{save:"main-user-consent-request-save",refused:"main-user-consent-request-refused",accepted:"main-user-consent-request-accepted"},current:null,autoSave:!1,isFormSubmitted:!1,isConsentSaved:!1,attributeControl:"data-bx-user-consent",load:function(t){var e=this.find(t)[0];return e?(this.bind(e),e):null},loadAll:function(t,e){this.find(t,e).forEach(this.bind,this)},loadFromForms:function(){var t=document.getElementsByTagName("FORM");t=BX.convert.nodeListToArray(t),t.forEach(this.loadAll,this)},find:function(t){if(!t)return[];var e=t.querySelectorAll("["+this.attributeControl+"]");return e=BX.convert.nodeListToArray(e),e.map(this.createItem.bind(this,t)).filter(function(t){return!!t})},bind:function(t){t.config.submitEventName?BX.addCustomEvent(t.config.submitEventName,this.onSubmit.bind(this,t)):t.formNode&&BX.bind(t.formNode,"submit",this.onSubmit.bind(this,t)),BX.bind(t.controlNode,"click",this.onClick.bind(this,t))},createItem:function(e,n){var i=n.querySelector('input[type="checkbox"]');if(i)try{var o=JSON.parse(n.getAttribute(this.attributeControl)),s={formNode:null,controlNode:n,inputNode:i,config:o};return"FORM"==e.tagName?s.formNode=e:s.formNode=BX.findParent(i,{tagName:"FORM"}),s.caller=this,new t(s)}catch(t){return null}},onClick:function(t,e){this.requestForItem(t),e.preventDefault()},onSubmit:function(t,e){return this.isFormSubmitted=!0,!!this.check(t)||(e&&e.preventDefault(),!1)},check:function(t){return t.inputNode.checked?(this.saveConsent(t),!0):(this.requestForItem(t),!1)},requestForItem:function(t){this.setCurrent(t),this.requestConsent(t.config.id,{sec:t.config.sec,replace:t.config.replace},this.onAccepted,this.onRefused)},setCurrent:function(t){this.current=t,this.autoSave=t.config.autoSave,this.actionRequestUrl=t.config.actionUrl},onAccepted:function(){if(this.current){var t=this.current;this.saveConsent(this.current,function(){BX.onCustomEvent(t,this.events.accepted,[]),BX.onCustomEvent(this,this.events.accepted,[t]),this.isConsentSaved=!0,this.isFormSubmitted&&t.formNode&&!t.config.submitEventName&&BX.submit(t.formNode)}),this.current.inputNode.checked=!0,this.current=null}},onRefused:function(){BX.onCustomEvent(this.current,this.events.refused,[]),BX.onCustomEvent(this,this.events.refused,[this.current]),this.current.inputNode.checked=!1,this.current=null,this.isFormSubmitted=!1},initPopup:function(){this.popup||(this.popup={})},popup:{isInit:!1,caller:null,nodes:{container:null,shadow:null,head:null,loader:null,content:null,textarea:null,buttonAccept:null,buttonReject:null},onAccept:function(){this.hide(),BX.onCustomEvent(this,"accept",[])},onReject:function(){this.hide(),BX.onCustomEvent(this,"reject",[])},init:function(){if(this.isInit)return!0;var t=document.querySelector("script[data-bx-template]");if(!t)return!1;var e=document.createElement("DIV");return e.innerHTML=t.innerHTML,!!(e=e.children[0])&&(document.body.appendChild(e),this.isInit=!0,this.nodes.container=e,this.nodes.shadow=this.nodes.container.querySelector("[data-bx-shadow]"),this.nodes.head=this.nodes.container.querySelector("[data-bx-head]"),this.nodes.loader=this.nodes.container.querySelector("[data-bx-loader]"),this.nodes.content=this.nodes.container.querySelector("[data-bx-content]"),this.nodes.textarea=this.nodes.container.querySelector("[data-bx-textarea]"),this.nodes.buttonAccept=this.nodes.container.querySelector("[data-bx-btn-accept]"),this.nodes.buttonReject=this.nodes.container.querySelector("[data-bx-btn-reject]"),this.nodes.buttonAccept.textContent=BX.message(this.caller.msg.btnAccept),this.nodes.buttonReject.textContent=BX.message(this.caller.msg.btnReject),BX.bind(this.nodes.buttonAccept,"click",this.onAccept.bind(this)),BX.bind(this.nodes.buttonReject,"click",this.onReject.bind(this)),!0)},setTitle:function(t){this.nodes.head&&(this.nodes.head.textContent=t)},setContent:function(t){this.nodes.textarea&&(this.nodes.textarea.textContent=t)},show:function(t){"boolean"==typeof t&&(this.nodes.loader.style.display=t?"none":"",this.nodes.content.style.display=t?"":"none"),this.nodes.container.style.display="",document.body.appendChild(this.nodes.container)},hide:function(){this.nodes.container.style.display="none",document.body.removeChild(this.nodes.container)}},cache:{list:[],stringifyKey:function(t){return BX.type.isString(t)?t:JSON.stringify({key:t})},set:function(t,e){var n=this.get(t);n?n.data=e:this.list.push({key:this.stringifyKey(t),data:e})},getData:function(t){var e=this.get(t);return e?e.data:null},get:function(t){t=this.stringifyKey(t);var e=this.list.filter(function(e){return e.key==t});return e.length>0?e[0]:null},has:function(t){return!!this.get(t)}},requestConsent:function(t,e,n,i){e=e||{},e.id=t;var o=this.cache.stringifyKey(e);if(!this.popup.isInit){if(this.popup.caller=this,!this.popup.init())return;BX.addCustomEvent(this.popup,"accept",n.bind(this)),BX.addCustomEvent(this.popup,"reject",i.bind(this))}this.current&&this.current.config.text&&this.cache.set(o,this.current.config.text),this.cache.has(o)?this.setTextToPopup(this.cache.getData(o)):(this.popup.setTitle(BX.message(this.msg.loading)),this.popup.show(!1),this.sendActionRequest("getText",e,function(t){this.cache.set(o,t.text||""),this.setTextToPopup(this.cache.getData(o))},function(){this.popup.hide(),alert(BX.message(this.msg.errTextLoad))}))},setTextToPopup:function(t){var e="",n=t.indexOf("\n"),i=t.indexOf(".");n=n<i?n:i,n>=0&&n<=100&&(e=t.substr(0,n).trim(),e=e.split(".").map(Function.prototype.call,String.prototype.trim).filter(String)[0]),this.popup.setTitle(e||BX.message(this.msg.title)),this.popup.setContent(t),this.popup.show(!0)},saveConsent:function(t,e){this.setCurrent(t);var n={id:t.config.id,sec:t.config.sec,url:window.location.href};if(t.config.originId){var i=t.config.originId;if(t.formNode&&i.indexOf("%")>=0){var o=t.formNode.querySelectorAll('input[type="text"], input[type="hidden"]');o=BX.convert.nodeListToArray(o),o.forEach(function(t){t.name&&(i=i.replace("%"+t.name+"%",t.value?t.value:""))})}n.originId=i}t.config.originatorId&&(n.originatorId=t.config.originatorId),BX.onCustomEvent(t,this.events.save,[n]),BX.onCustomEvent(this,this.events.save,[t,n]),this.isConsentSaved||!t.config.autoSave?e&&e.apply(this,[]):this.sendActionRequest("saveConsent",n,e,e)},sendActionRequest:function(t,e,n,i){n=n||null,i=i||null,e.action=t,e.sessid=BX.bitrix_sessid(),e.action=t,BX.ajax({url:this.actionRequestUrl,method:"POST",data:e,timeout:10,dataType:"json",processData:!0,onsuccess:BX.proxy(function(t){t=t||{},t.error?i.apply(this,[t]):n&&n.apply(this,[t])},this),onfailure:BX.proxy(function(){var t={error:!0,text:""};i&&i.apply(this,[t])},this)})}},BX.ready(function(){BX.UserConsent.loadFromForms()})}();
/* End */
;
; /* Start:"a:4:{s:4:"full";s:103:"/bitrix/templates/ph_default/components/bitrix/catalog.bigdata.products/al/script.min.js?16354170902116";s:6:"source";s:84:"/bitrix/templates/ph_default/components/bitrix/catalog.bigdata.products/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function getCookie(t){var e=document.cookie.match(new RegExp("(?:^|; )"+t.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g,"\\$1")+"=([^;]*)"));return e?decodeURIComponent(e[1]):void 0}function bx_rcm_recommendation_event_attaching(t){return null}function bx_rcm_adaptive_recommendation_event_attaching(t,e){var a=function(e){var a,o=BX(this);for(a in t)if(t[a].productUrl==o.getAttribute("href")){window.JCCatalogBigdataProducts.prototype.RememberProductRecommendation(t[a].recommendationId,t[a].productId);break}},o=BX(e);o||(o=document.body);var i=BX.findChildren(o,{tag:"a"},!0);if(i){var r;for(r in i)BX.bind(i[r],"click",a)}}function bx_rcm_get_from_cloud(t,e,a){var o="https://analytics.bitrix.info/crecoms/v1_0/recoms.php",i=BX.ajax.prepareData(e);i&&(o+=(-1!==o.indexOf("?")?"&":"?")+i);var r=function(e){e.items||(e.items=[]),BX.ajax({url:"/bitrix/components/bitrix/catalog.bigdata.products/ajax.php?"+BX.ajax.prepareData({AJAX_ITEMS:e.items,RID:e.id}),method:"POST",data:a,dataType:"html",processData:!1,start:!0,onsuccess:function(e){var a=BX.processHTML(e);BX(t).innerHTML=a.HTML,BX.ajax.processScripts(a.SCRIPT),appSLine.gridInit()}})};BX.ajax({method:"GET",dataType:"json",url:o,timeout:3,onsuccess:r,onfailure:r})}!function(t){t.JCCatalogBigdataProducts||(t.JCCatalogBigdataProducts=function(t){},t.JCCatalogBigdataProducts.prototype.RememberRecommendation=function(t,e){var a=BX.findParent(t,{className:"bigdata_recommended_products_items"}),o=BX.findChild(a,{attr:{name:"bigdata_recommendation_id"}},!0).value;this.RememberProductRecommendation(o,e)},t.JCCatalogBigdataProducts.prototype.RememberProductRecommendation=function(t,e){var a,o=BX.cookie_prefix+"_RCM_PRODUCT_LOG",i=getCookie(o),r=!1,n=[];i&&(n=i.split("."));for(var c=n.length;c--;)a=n[c].split("-"),a[0]==e?(a=n[c].split("-"),a[1]=t,a[2]=BX.current_server_time,n[c]=a.join("-"),r=!0):BX.current_server_time-a[2]>2592e3&&n.splice(c,1);r||n.push([e,t,BX.current_server_time].join("-"));var d=n.join("."),m=new Date((new Date).getTime()+31536e7);document.cookie=o+"="+d+"; path=/; expires="+m.toUTCString()+"; domain="+BX.cookie_domain})}(window);
/* End */
;
; /* Start:"a:4:{s:4:"full";s:100:"/bitrix/templates/ph_default/components/bitrix/sale.recommended.products/al/script.js?16354170902547";s:6:"source";s:85:"/bitrix/templates/ph_default/components/bitrix/sale.recommended.products/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/

$(document).ready(function () {
  $(".itdelta__small-basket").on('click', function (e) {
  
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
                alert('???? ?????????????? ????????????!');
            } else {
                alert('?????????????????? ????????????!');
                console.log(data);
            }
        }
    });
});

});

/* End */
;; /* /bitrix/templates/ph_default/components/bitrix/catalog.element/catalog_custom/script.js?16354205096419*/
; /* /bitrix/templates/ph_default/components/bitrix/sale.prediction.product.detail/al/script.min.js?1635417090337*/
; /* /bitrix/templates/ph_default/components/bitrix/sale.gift.product/al/script.min.js?1635417090304*/
; /* /bitrix/templates/ph_default/template_ext/catalog.section/al/script.min.js?16354170903365*/
; /* /bitrix/templates/ph_default/components/bitrix/sale.gift.main.products/al/script.min.js?16354170902525*/
; /* /bitrix/templates/ph_default/components/bitrix/forum.topic.reviews/al/script.min.js?163541709015159*/
; /* /bitrix/templates/ph_default/components/bitrix/main.userconsent.request/form/user_consent.min.js?16354170907138*/
; /* /bitrix/templates/ph_default/components/bitrix/catalog.bigdata.products/al/script.min.js?16354170902116*/
; /* /bitrix/templates/ph_default/components/bitrix/sale.recommended.products/al/script.js?16354170902547*/
