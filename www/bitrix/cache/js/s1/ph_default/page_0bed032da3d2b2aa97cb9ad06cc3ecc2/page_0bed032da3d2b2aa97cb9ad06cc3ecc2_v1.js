
; /* Start:"a:4:{s:4:"full";s:103:"/bitrix/templates/ph_default/components/bitrix/news.list/new_super_banners/script.min.js?16354170901460";s:6:"source";s:84:"/bitrix/templates/ph_default/components/bitrix/news.list/new_super_banners/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
$(function(){var t=parseInt($(".js-mainbanners").data("timeout"),10),s={timeout:t-1100,backSpeed:1e3,startTime:void 0,stopTime:void 0,stopTimePassed:0,$el:$(".js-mainbanners-progressline"),start:function(t){t=$.isFunction(t)?t:function(){},this.$el.find(".js-progress").stop().animate({width:"100%"},this.timeout,"linear",t)},reset:function(t){t=$.isFunction(t)?t:function(){},this.stopTime=void 0,this.startTime=void 0,this.stopTimePassed=0,this.$el.find(".js-progress").stop().animate({width:"0"},this.backSpeed,"linear",t)},stop:function(){this.$el.find(".js-progress").stop(),this.stopTime=(new Date).getTime()},restart:function(){this.reset($.proxy(this.start,this))}};$(".js-mainbanners").owlCarousel({items:1,loop:!0,mouseDrag:!1,touchDrag:!1,animateIn:!BX.browser.IsIE9()&&"fadeIn",animateOut:!BX.browser.IsIE9()&&"fadeOut",autoplay:!0,autoplayTimeout:t,autoplaySpeed:2e3,smartSpeed:2e3,onInitialize:function(){setTimeout($.proxy(function(){this.$element.addClass("is-initialized"),s.start()},this),0),$(document).on("mouseenter",".js-additionals a",function(){s.stop(),$(".js-mainbanners").trigger("stop.owl.autoplay"),$(".js-additionals a").one("mouseleave",function(){s.start(),$(".js-mainbanners").trigger("play.owl.autoplay")})})},onTranslate:function(){s.restart()}}),$(window).blur(function(){s.stop(),$(".js-mainbanners").trigger("stop.owl.autoplay")}),$(window).focus(function(){s.start(),$(".js-mainbanners").trigger("play.owl.autoplay")})});
/* End */
;
; /* Start:"a:4:{s:4:"full";s:91:"/bitrix/templates/ph_default/components/bitrix/news.list/brands/script.min.js?1635417090331";s:6:"source";s:73:"/bitrix/templates/ph_default/components/bitrix/news.list/brands/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
$(document).ready(function(){var e={dots:!1,items:2,margin:8,stagePadding:16,responsive:{}};e.responsive[appSLine.grid.xs]={items:3},e.responsive[appSLine.grid.sm]={items:4},e.responsive[appSLine.grid.md]={items:5},e.responsive[appSLine.grid.lg]={items:6},$(".js-carousel_brands").owlCarousel($.extend({},appSLine.owlOptions,e))});
/* End */
;
; /* Start:"a:4:{s:4:"full";s:102:"/bitrix/templates/ph_default/components/bitrix/catalog.section/catalog_custom/script.js?16354170902569";s:6:"source";s:87:"/bitrix/templates/ph_default/components/bitrix/catalog.section/catalog_custom/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/

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

/* End */
;
; /* Start:"a:4:{s:4:"full";s:105:"/bitrix/templates/ph_default/components/bitrix/catalog.product.subscribe/al/script.min.js?163541709010617";s:6:"source";s:85:"/bitrix/templates/ph_default/components/bitrix/catalog.product.subscribe/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
!function(e){e.JCCatalogProductSubscribe||(e.JCCatalogProductSubscribe=function(e){this.buttonId=e.buttonId,this.buttonClass=e.buttonClass,this.jsObject=e.jsObject,this.ajaxUrl="/bitrix/components/bitrix/catalog.product.subscribe/ajax.php",this.alreadySubscribed=e.alreadySubscribed,this.urlListSubscriptions=e.urlListSubscriptions,this.listOldItemId={},this.elemButtonSubscribe=null,this.elemPopupWin=null,this.defaultButtonClass="bx-catalog-subscribe-button",this._elemButtonSubscribeClickHandler=BX.delegate(this.subscribe,this),this._elemHiddenClickHandler=BX.delegate(this.checkSubscribe,this),BX.ready(BX.delegate(this.init,this))},e.JCCatalogProductSubscribe.prototype.init=function(){this.buttonId&&(this.elemButtonSubscribe=BX(this.buttonId),this.elemHiddenSubscribe=BX(this.buttonId+"_hidden")),this.elemButtonSubscribe&&BX.bind(this.elemButtonSubscribe,"click",this._elemButtonSubscribeClickHandler),this.elemHiddenSubscribe&&BX.bind(this.elemHiddenSubscribe,"click",this._elemHiddenClickHandler),this.setButton(this.alreadySubscribed)},e.JCCatalogProductSubscribe.prototype.checkSubscribe=function(){this.elemHiddenSubscribe&&this.elemButtonSubscribe&&(this.listOldItemId.hasOwnProperty(this.elemButtonSubscribe.dataset.item)?this.setButton(!0):BX.ajax({method:"POST",dataType:"json",url:this.ajaxUrl,data:{sessid:BX.bitrix_sessid(),checkSubscribe:"Y",itemId:this.elemButtonSubscribe.dataset.item},onsuccess:BX.delegate(function(e){e.subscribe?(this.setButton(!0),this.listOldItemId[this.elemButtonSubscribe.dataset.item]=!0):this.setButton(!1)},this)}))},e.JCCatalogProductSubscribe.prototype.subscribe=function(){if(this.elemButtonSubscribe=BX.proxy_context,!this.elemButtonSubscribe)return!1;BX.ajax({method:"POST",dataType:"json",url:this.ajaxUrl,data:{sessid:BX.bitrix_sessid(),subscribe:"Y",itemId:this.elemButtonSubscribe.dataset.item,siteId:BX.message("SITE_ID")},onsuccess:BX.delegate(function(e){if(e.success)this.createSuccessPopup(e),this.setButton(!0),this.listOldItemId[this.elemButtonSubscribe.dataset.item]=!0;else if(e.contactFormSubmit){var t=this.createContentForPopup(e),s=BX.create("div",{props:{className:"form row"},children:[t,BX.create("div",{props:{className:"form-group"},children:[BX.create("br"),BX.create("button",{text:BX.message("CPST_SUBSCRIBE_BUTTON_NAME"),props:{className:"btn btn-primary"},events:{click:BX.delegate(function(){if(!this.validateContactField(e.contactTypeData))return!1;BX.ajax.submitAjax(t,{method:"POST",url:this.ajaxUrl,processData:!0,onsuccess:BX.delegate(function(e){if(e=BX.parseJSON(e,{}),e.success)this.createSuccessPopup(e),this.setButton(!0),this.listOldItemId[this.elemButtonSubscribe.dataset.item]=!0;else if(e.error){e.hasOwnProperty("setButton")&&(this.listOldItemId[this.elemButtonSubscribe.dataset.item]=!0,this.setButton(!0));var t=e.message;e.hasOwnProperty("typeName")&&(t=e.message.replace("USER_CONTACT",e.typeName)),BX.addClass(BX("bx-catalog-subscribe-form-notify"),"alert alert-danger"),BX("bx-catalog-subscribe-form-notify").innerHTML=t}},this)})},this)}})]})]});this.showPopup(s,{title:BX.message("CPST_SUBSCRIBE_POPUP_TITLE")})}else e.error&&(e.hasOwnProperty("setButton")&&(this.listOldItemId[this.elemButtonSubscribe.dataset.item]=!0,this.setButton(!0)),this.showWindowWithAnswer({status:"error",message:e.message}))},this)})},e.JCCatalogProductSubscribe.prototype.validateContactField=function(e){var t=BX.findChildren(BX("bx-catalog-subscribe-form"),{tag:"input",attribute:{id:"userContact"}},!0);if(!t.length||"object"!=typeof e)return BX.addClass(BX("bx-catalog-subscribe-form-notify"),"alert alert-danger"),BX("bx-catalog-subscribe-form-notify").innerHTML=BX.message("CPST_SUBSCRIBE_VALIDATE_UNKNOW_ERROR"),!1;for(var s,a,i,r=[],c=[],o=0;o<t.length;o++)s=t[o].getAttribute("data-id"),a=t[o].value,i=BX("bx-contact-use-"+s),i&&"N"==i.value?c.push(!0):a.length||r.push(BX.message("CPST_SUBSCRIBE_VALIDATE_ERROR_EMPTY_FIELD").replace("#FIELD#",e[s].contactLable));if(t.length==c.length)return BX.addClass(BX("bx-catalog-subscribe-form-notify"),"alert alert-danger"),BX("bx-catalog-subscribe-form-notify").innerHTML=BX.message("CPST_SUBSCRIBE_VALIDATE_ERROR"),!1;if(r.length){BX.addClass(BX("bx-catalog-subscribe-form-notify"),"alert alert-danger");for(var n=0;n<r.length;n++)BX("bx-catalog-subscribe-form-notify").innerHTML=r[n];return!1}return!0},e.JCCatalogProductSubscribe.prototype.reloadCaptcha=function(){BX.ajax.get(this.ajaxUrl+"?reloadCaptcha=Y","",function(e){BX("captcha_sid").value=e,BX("captcha_img").src="/bitrix/tools/captcha.php?captcha_sid="+e})},e.JCCatalogProductSubscribe.prototype.createContentForPopup=function(e){if(!e.hasOwnProperty("contactTypeData"))return null;var t=e.contactTypeData,s=Object.keys(t).length,a="",i="N",r=document.createDocumentFragment();s>1&&(i="Y",a="display:none;",r.appendChild(BX.create("p",{text:BX.message("CPST_SUBSCRIBE_MANY_CONTACT_NOTIFY")}))),r.appendChild(BX.create("p",{props:{id:"bx-catalog-subscribe-form-notify"}}));for(var c in t)s>1&&r.appendChild(BX.create("div",{props:{className:"bx-catalog-subscribe-form-container"},children:[BX.create("div",{props:{className:"checkbox"},children:[BX.create("lable",{props:{className:"bx-filter-param-label"},attrs:{onclick:this.jsObject+".selectContactType("+c+", event);"},children:[BX.create("input",{props:{type:"hidden",id:"bx-contact-use-"+c,name:"contact["+c+"][use]",value:"N"}}),BX.create("input",{props:{id:"bx-contact-checkbox-"+c,type:"checkbox"}}),BX.create("span",{props:{className:"bx-filter-param-text"},text:t[c].contactLable})]})]})]})),r.appendChild(BX.create("div",{props:{id:"bx-catalog-subscribe-form-container-"+c,className:"bx-catalog-subscribe-form-container",style:a},children:[BX.create("div",{props:{className:"bx-catalog-subscribe-form-container-input form-group"},children:[BX.create("input",{props:{id:"userContact",className:"form-control",type:"text",name:"contact["+c+"][user]",placeholder:BX.message("CPST_SUBSCRIBE_LABLE_CONTACT_INPUT").replace("#CONTACT#",t[c].contactLable)},attrs:{"data-id":c}})]})]}));e.hasOwnProperty("captchaCode")&&r.appendChild(BX.create("div",{props:{className:"bx-catalog-subscribe-form-container form-group"},children:[BX.create("div",{props:{className:"bx-captcha"},children:[BX.create("input",{props:{type:"hidden",id:"captcha_sid",name:"captcha_sid",value:e.captchaCode}}),BX.create("img",{props:{id:"captcha_img",className:"captcha-img pull-right",src:"/bitrix/tools/captcha.php?captcha_sid="+e.captchaCode},attrs:{width:"180",height:"40",alt:"captcha",onclick:this.jsObject+".reloadCaptcha();"}})]}),BX.create("div",{props:{className:"bx-catalog-subscribe-form-container-input l-overflow"},children:[BX.create("input",{props:{id:"captcha_word",className:"form-control",type:"text",name:"captcha_word",placeholder:BX.message("CPST_ENTER_WORD_PICTURE")+"*"},attrs:{maxlength:"50"}})]})]}));var o=BX.create("form",{props:{id:"bx-catalog-subscribe-form"},children:[BX.create("input",{props:{type:"hidden",name:"manyContact",value:i}}),BX.create("input",{props:{type:"hidden",name:"sessid",value:BX.bitrix_sessid()}}),BX.create("input",{props:{type:"hidden",name:"itemId",value:this.elemButtonSubscribe.dataset.item}}),BX.create("input",{props:{type:"hidden",name:"siteId",value:BX.message("SITE_ID")}}),BX.create("input",{props:{type:"hidden",name:"contactFormSubmit",value:"Y"}})]});return o.appendChild(r),o},e.JCCatalogProductSubscribe.prototype.selectContactType=function(t,s){var a=BX("bx-catalog-subscribe-form-container-"+t),i="",r=BX("bx-contact-checkbox-"+t);if(!a)return!1;if(r!=s.target&&(r.checked?r.checked=!1:r.checked=!0),a.currentStyle)i=a.currentStyle.display;else if(e.getComputedStyle){var c=e.getComputedStyle(a,null);i=c.getPropertyValue("display")}"none"===i?(BX("bx-contact-use-"+t).value="Y",BX.style(a,"display","")):(BX("bx-contact-use-"+t).value="N",BX.style(a,"display","none"))},e.JCCatalogProductSubscribe.prototype.createSuccessPopup=function(e){var t=BX.create("div",{props:{className:"bx-catalog-popup-content form row"},children:[BX.create("p",{props:{className:"bx-catalog-popup-message text-center"},children:[BX.create("img",{props:{src:appSLine.SITE_TEMPLATE_PATH+"/assets/img/like.png"}})]}),BX.create("p",{props:{className:"text-center"},text:e.message}),BX.create("div",{props:{className:"form-group"},children:[BX.create("button",{text:BX.message("CPST_SUBSCRIBE_BUTTON_CLOSE"),props:{className:"btn btn-primary"},events:{click:BX.delegate(function(){$.fancybox.close()},this)}})]})]});this.showPopup(t,{title:BX.message("CPST_SUBSCRIBE_POPUP_TITLE")})},e.JCCatalogProductSubscribe.prototype.initPopupWindow=function(){this.elemPopupWin=BX.PopupWindowManager.create("CatalogSubscribe_"+this.buttonId,null,{autoHide:!1,offsetLeft:0,offsetTop:0,overlay:!0,closeByEsc:!0,titleBar:!0,closeIcon:!0,contentColor:"white"})},e.JCCatalogProductSubscribe.prototype.setButton=function(e){this.alreadySubscribed=Boolean(e),this.alreadySubscribed?(this.elemButtonSubscribe.className=this.buttonClass+" "+this.defaultButtonClass+" disabled",this.elemButtonSubscribe.innerHTML="<span>"+BX.message("CPST_TITLE_ALREADY_SUBSCRIBED")+"</span>",BX.unbind(this.elemButtonSubscribe,"click",this._elemButtonSubscribeClickHandler)):(this.elemButtonSubscribe.className=this.buttonClass+" "+this.defaultButtonClass,this.elemButtonSubscribe.innerHTML="<span>"+BX.message("CPST_SUBSCRIBE_BUTTON_NAME")+"</span>",BX.bind(this.elemButtonSubscribe,"click",this._elemButtonSubscribeClickHandler))},e.JCCatalogProductSubscribe.prototype.showWindowWithAnswer=function(e){e=e||{},e.message||("success"==e.status?e.message=BX.message("CPST_STATUS_SUCCESS"):e.message=BX.message("CPST_STATUS_ERROR"));var t=BX.create("div",{props:{className:"bx-catalog-subscribe-alert text-center form row"},children:[BX.create("p",{props:{className:"text-center"},children:[BX.create("img",{props:{src:appSLine.SITE_TEMPLATE_PATH+"/assets/img/like.png"}})]}),BX.create("p",{props:{className:"text-center"},children:[BX.create("span",{props:{className:"bx-catalog-subscribe-aligner"}}),BX.create("span",{props:{className:"bx-catalog-subscribe-alert-text"},text:e.message})]}),BX.create("div",{props:{className:"form-group"},children:[BX.create("button",{text:BX.message("CPST_SUBSCRIBE_BUTTON_CLOSE"),props:{className:"btn btn-primary"},events:{click:BX.delegate(function(){$.fancybox.close()},this)}})]})]});this.showPopup(t,{title:BX.message("CPST_SUBSCRIBE_POPUP_TITLE")})},e.JCCatalogProductSubscribe.prototype.showPopup=function(e,t){var s=$.fancybox.getInstance();s?s.setContent(s.current,e):$.fancybox.open(e,$.extend({},appSLine.fancyOptions,t))})}(window);
/* End */
;
; /* Start:"a:4:{s:4:"full";s:89:"/bitrix/templates/ph_default/components/bitrix/news.line/main/script.min.js?1635417090496";s:6:"source";s:71:"/bitrix/templates/ph_default/components/bitrix/news.line/main/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
$(document).ready(function(){var e=function(e){var i={dots:!1,nav:!1,items:2,margin:8,stagePadding:18,responsive:{}};appSLine.pageWidth<appSLine.grid.md?(i.responsive[appSLine.grid.sm]={items:3},i.responsive[appSLine.grid.md]={items:4},i.responsive[appSLine.grid.lg]={items:5},$(".js-newsline").owlCarousel($.extend({},appSLine.owlOptions,i))):$(".js-newsline.owl-carousel").trigger("destroy.owl.carousel").removeClass("owl-carousel").removeAttr("style")};e(),$(window).resize(function(){e()})});
/* End */
;; /* /bitrix/templates/ph_default/components/bitrix/news.list/new_super_banners/script.min.js?16354170901460*/
; /* /bitrix/templates/ph_default/components/bitrix/news.list/brands/script.min.js?1635417090331*/
; /* /bitrix/templates/ph_default/components/bitrix/catalog.section/catalog_custom/script.js?16354170902569*/
; /* /bitrix/templates/ph_default/components/bitrix/catalog.product.subscribe/al/script.min.js?163541709010617*/
; /* /bitrix/templates/ph_default/components/bitrix/news.line/main/script.min.js?1635417090496*/
