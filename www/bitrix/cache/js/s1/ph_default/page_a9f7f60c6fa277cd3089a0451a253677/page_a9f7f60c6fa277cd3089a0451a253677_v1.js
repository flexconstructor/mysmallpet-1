
; /* Start:"a:4:{s:4:"full";s:102:"/bitrix/templates/ph_default/components/bitrix/sale.basket.basket/basket/script.min.js?163541709022734";s:6:"source";s:82:"/bitrix/templates/ph_default/components/bitrix/sale.basket.basket/basket/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function updateBasketTable(e,t){var a,s,i,r,o,n,l,_,u,c,d,p,T,A,E,b,f,B,m,P,y,v,I,X,C,S,h,k,N,O,R,D,g=BX("basket_items"),U=!1,L=!1,M=!1,Q=!1,Y=!1,F=!1,w=BX("basket_form");if(g&&"object"==typeof t){if(a=g.rows,r=a[a.length-1],l="Y"===t.PARAMS.QUANTITY_FLOAT,null!==e&&t.BASKET_DATA){s=t.BASKET_ID,i=t.BASKET_DATA.GRID.ROWS[s],n=t.COLUMNS.split(","),o=document.createElement("tr"),_=BX(e),o.setAttribute("id",t.BASKET_ID),o.setAttribute("data-item-name",i.NAME),o.setAttribute("data-item-brand",i[basketJSParams.BRAND_PROPERTY+"_VALUE"]),o.setAttribute("data-item-price",i.PRICE),o.setAttribute("data-item-currency",i.CURRENCY),o.setAttribute("class","table_item product"),r.parentNode.insertBefore(o,_.nextSibling),"Y"===t.DELETE_ORIGINAL&&_.parentNode.removeChild(_);var H=["TYPE","PROPS","DELAY","DELETE","DISCOUNT","WEIGHT"];for(u=0;u<n.length;u++)if("TYPE"===n[u])!0;else if("PROPS"===n[u])F=!0;else if("DELAY"===n[u])L=!0;else if("DELETE"===n[u])U=!0;else if("DISCOUNT"===n[u])Q=!0;else if("WEIGHT"===n[u])Y=!0;else if(-1!=n[u].indexOf("PROPERTY_")&&basketJSParams.ARTICLE_PROP&&Object.keys(basketJSParams.ARTICLE_PROP).length>0)for(var x in basketJSParams.ARTICLE_PROP)if(n[u]=="PROPERTY_"+basketJSParams.ARTICLE_PROP[x]){H.push(n[u]),M=u;break}e:for(u=0;u<n.length;u++){for(x in H)if(H[x]==n[u])continue e;switch(n[u]){case"PROPS":case"DELAY":case"DELETE":case"TYPE":case"DISCOUNT":case"WEIGHT":break;case"NAME":if(d=o.insertCell(-1),p="",d.setAttribute("class","table_item__item"),c=i.PREVIEW_PICTURE_SRC.length>0?i.PREVIEW_PICTURE_SRC:i.DETAIL_PICTURE_SRC.length>0?i.DETAIL_PICTURE_SRC:basketJSParams.TEMPLATE_FOLDER+"/assets/img/noimg.png",p+='<div class="table_item__pic">',i.DETAIL_PAGE_URL.length>0?p+='<a href="'+i.DETAIL_PAGE_URL+'">              <img class="table_item__img" src="'+c+'" alt="'+i.NAME+'" title="'+i.NAME+'">            </a>':p+='<img class="table_item__img" src="'+c+'" alt="'+i.NAME+'" title="'+i.NAME+'">',p+="</div>",p+='<div class="table_item__head">            <h4 class="table_item__name">',i.DETAIL_PAGE_URL.length>0?p+='<a href="'+i.DETAIL_PAGE_URL+'">'+i.NAME+"</a>":p+=i.NAME,p+="</h4>",!1!==M)for(var x in basketJSParams.ARTICLE_PROP)if(""!=basketJSParams.ARTICLE_PROP[x]){p+='<div class="product__article">'+basketJSParams.HEADERS[M]+": "+i["PROPERTY_"+basketJSParams.ARTICLE_PROP[x]+"_VALUE"]+"</div>";break}p+='</div>            <dl class="table_item__props dl-list">';t:for(A=0;A<n.length;A++){for(x in H)if(H[x]==n[A])continue t;switch(n[A]){case"TYPE":case"PROPS":case"DELAY":case"DELETE":case"DISCOUNT":case"WEIGHT":case"NAME":case"QUANTITY":case"PRICE":case"SUM":break;default:var J;J=-1!=n[A].indexOf("PROPERTY_")?i[n[A]+"_VALUE"]:i[n[A]],void 0!=J&&(p+="<dt>"+basketJSParams.HEADERS[A]+":</dt>                     <dd>"+J+"</dd>")}}if(Y&&i.WEIGHT_FORMATED>0&&(cellTotalHTML+="<dt>"+basketJSParams.SALE_WEIGHT+":</dt> <dd>"+i.WEIGHT_FORMATED+"</dd>"),F)for(var u in i.PROPS){var T=!1;for(var A in i.SKU_DATA)if(i.SKU_DATA[A].CODE==i.PROPS[u].CODE){T=!0;break}T||(p+="<dt>"+i.PROPS[u].NAME+":</dt>              <dd>"+i.PROPS[u].VALUE+"</dd>")}if(p+="</dl>",i.SKU_DATA){p+='<div class="table_item__offer_props">';for(E in i.SKU_DATA)if(i.SKU_DATA.hasOwnProperty(E)){b=i.SKU_DATA[E];var G=!1,K=!1,V="offer_prop";if(BX.util.in_array(b.CODE,basketJSParams.OFFER_TREE_COLOR_PROPS))G=!0,V+=" offer_prop-color";else if(BX.util.in_array(b.CODE,basketJSParams.OFFER_TREE_BTN_PROPS))K=!0,V+=" offer_prop-btn";else for(B in b.VALUES)if(f=b.VALUES[B],!1!==f.PICT){G=!0;break}if(G||K){p+='<div class="'+V+' js-offer_prop" data-code="'+b.CODE+'">                    <div class="offer_prop-name">'+b.NAME+':</div>                  <ul class="offer_prop__values clearfix" id="prop_'+b.CODE+"_"+i.ID+'">';var j=!1;for(P in b.VALUES){m=b.VALUES[P];var W="offer_prop__value";for(y=0;y<i.PROPS.length;y++)v=i.PROPS[y],v.CODE===b.CODE&&(v.VALUE!==m.NAME&&v.VALUE!==m.XML_ID||(W+=" checked",j=m));if(p+='<li class="'+W+'"                        data-value-id="'+("S"==b.TYPE&&"directory"==b.USER_TYPE?m.XML_ID:m.NAME)+'"                        data-element="'+i.ID+'"                        data-property="'+b.CODE+'"                      >',G){var q="";m.PICT?q="background-image:url("+m.PICT.SRC+")":basketJSParams.COLORS_TABLE[m.NAME.toLowerCase()]&&(q="background-color:"+basketJSParams.COLORS_TABLE[m.NAME.toLowerCase()].RGB),p+='<span class="offer_prop__icon">                          <span class="offer_prop__img" title="'+m.NAME+'" style="'+q+'"></span>                        </span>'}else p+=m.NAME;p+="</li>"}p+="</ul>                    </div>"}else{var z="cart_item_"+b.CODE+"_"+i.ID;p+='<div class="offer_prop js-offer_prop" data-code="'+b.CODE+'">                      <div class="offer_prop-name">'+b.NAME+':</div>                      <div class="dropdown select">                          <ul class="offer_prop__values dropdown-menu" aria-labelledby="'+z+'" id="prop_'+b.CODE+"_"+i.ID+'">';var j=!1;for(P in b.VALUES){m=b.VALUES[P];var W="offer_prop__value";for(y=0;y<i.PROPS.length;y++)v=i.PROPS[y],v.CODE===b.CODE&&(v.VALUE!==m.NAME&&v.VALUE!==m.XML_ID||(W+=" checked",j=m));p+='<li class="'+W+'"                                   data-value-id="'+("S"==b.TYPE&&"directory"==b.USER_TYPE?m.XML_ID:m.NAME)+'"                                    data-element="'+i.ID+'"                                    data-property="'+b.CODE+'"                                  >                                <a>'+m.NAME+"</a>                              </li>",p+="</li>"}p+='</ul>                      <div class="dropdown-toggle select__btn" id="'+z+'" data-toggle="dropdown" aria-expanded="true" aria-haspopup="true" role="button">                        <svg class="select__icon icon-svg"><use xlink:href="#svg-down-round"></use></svg>'+j.NAME+"</div>                    </div>                  </div>"}}p+="</div>"}d.innerHTML=p;break;case"QUANTITY":I=o.insertCell(-1),X="",C=parseFloat(i.MEASURE_RATIO)>0?i.MEASURE_RATIO:1,parseFloat(i.AVAILABLE_QUANTITY)>0?'max="'+i.AVAILABLE_QUANTITY+'"':"",S=!1,0!=C&&""!=C&&(h=i.QUANTITY,i.QUANTITY=getCorrectRatioQuantity(i.QUANTITY,C,l),h!=i.QUANTITY&&(S=!0)),I.setAttribute("class","table_item__quantity"),X+='<span class="quantity">',0!=C&&""!=C&&(X+='<i class="quantity__minus js-basket-minus"></i>'),X+='<input type="number" size="3"\t\t\t\t\t\tclass="quantity__input js-quantity"\t\t\t\t\t\tid="QUANTITY_INPUT_'+i.ID+'"\t\t\t\t\t\tname="QUANTITY_INPUT_'+i.ID+'"\t\t\t\t\t\tsize="2" step="'+C+'"\t\t\t\t\t\tvalue="'+i.QUANTITY+'"\t\t\t\t\t\tonchange="updateQuantity(\'QUANTITY_INPUT_'+i.ID+"','"+i.ID+"', "+C+","+l+')"\t\t\t\t\t/>',0!=C&&""!=C&&(X+='<i class="quantity__plus js-basket-plus"></i>'),X+='<input type="hidden" id="QUANTITY_'+i.ID+'" name="QUANTITY_'+i.ID+'" value="'+i.QUANTITY+'" />\t\t\t\t\t\t</span>',i.hasOwnProperty("MEASURE_TEXT")&&i.MEASURE_TEXT.length>0&&(X+=' <span class="js-measure">'+i.MEASURE_TEXT+"</span>"),I.innerHTML=X,S&&updateQuantity("QUANTITY_INPUT_"+i.ID,i.ID,C,l);break;case"PRICE":k=o.insertCell(-1),oCellPriceHTML="",k.setAttribute("class","table_item__price price"),N=i.FULL_PRICE_FORMATED!=i.PRICE_FORMATED?i.FULL_PRICE_FORMATED:"",oCellPriceHTML+=' <div class="price__pdv'+(parseFloat(i.DISCOUNT_PRICE_PERCENT>0)?" disc":"")+'" id="current_price_'+i.ID+'">'+i.PRICE_FORMATED+'</div>\t\t\t\t\t\t<div class="price__pv" id="old_price_'+i.ID+'">'+N+"</div>",Q&&0<parseFloat(i.DISCOUNT_PRICE_PERCENT)&&(oCellPriceHTML+='<div class="price__pdd">'+basketJSParams.SALE_PRICE_DIFF+': \t\t\t\t\t\t\t<span id="discount_value_'+i.ID+'">'+i.DISCOUNT_PRICE_PERCENT_FORMATED+"</span>\t\t\t\t\t\t</div>"),k.innerHTML=oCellPriceHTML;break;case"SUM":R=o.insertCell(-1),D="",k.setAttribute("class","table_item__sum price"),D+='<div class="price__pdv" id="sum_'+i.ID+'">',void 0!=typeof i[n[u]]&&(D+=i[n[u]]),D+="</div>",R.innerHTML+=D}}oCellControl=o.insertCell(-1),oCellControl.setAttribute("class",""),oCellControlHTML="",U&&(oCellControlHTML+='<a href="'+basketJSParams.DELETE_URL.replace("#ID#",i.ID)+'">        <svg class="btn__icon icon-close icon-svg"><use xlink:href="#svg-close"></use></svg>'+basketJSParams.SALE_DELETE+"</a><br/>"),L&&(oCellControlHTML+='<a href="'+basketJSParams.DELAY_URL.replace("#ID#",i.ID)+'">        <svg class="btn__icon icon-lock icon-svg"><use xlink:href="#svg-lock"></use></svg>'+basketJSParams.SALE_DELAY+"</a>"),oCellControl.innerHTML=oCellControlHTML;var Z=BX.findChildren(o,{className:"offer_prop__value"},!0);if(Z&&Z.length>0)for(u=0;Z.length>u;u++)BX.bind(Z[u],"click",BX.delegate(function(e){skuPropClickHandler(e)},this))}if(t.BASKET_DATA)for(O in t.BASKET_DATA.GRID.ROWS)if(t.BASKET_DATA.GRID.ROWS.hasOwnProperty(O)){var $=t.BASKET_DATA.GRID.ROWS[O];BX("discount_value_"+O)&&(BX("discount_value_"+O).innerHTML=basketJSParams.SALE_DISCOUNT+": "+$.DISCOUNT_PRICE_PERCENT_FORMATED),BX("current_price_"+O)&&(BX("current_price_"+O).innerHTML=$.PRICE_FORMATED),BX("old_price_"+O)&&(BX("old_price_"+O).innerHTML=$.FULL_PRICE_FORMATED!=$.PRICE_FORMATED?$.FULL_PRICE_FORMATED:""),BX("sum_"+O)&&(BX("sum_"+O).innerHTML=$.SUM),BX("QUANTITY_"+O)&&(BX("QUANTITY_INPUT_"+O).value=$.QUANTITY,BX("QUANTITY_INPUT_"+O).defaultValue=$.QUANTITY,BX("QUANTITY_"+O).value=$.QUANTITY)}if(t.BASKET_DATA&&couponListUpdate(t.BASKET_DATA),t.hasOwnProperty("WARNING_MESSAGE")){var ee="";for(u=t.WARNING_MESSAGE.length-1;u>=0;u--)ee+='<font class="errortext">'+t.WARNING_MESSAGE[u]+"</font><br/>";var te=BX("warning_message");""==ee?BX.hide(te):BX.show(te),te.innerHTML=ee}if(t.BASKET_DATA){var ae=BX.findChildren(w,{class:"allWeight_FORMATED"},!0);if(ae&&ae.length>0)for(var u in ae)ae[u].innerHTML=t.BASKET_DATA.allWeight_FORMATED.replace(/\s/g,"&nbsp;");var se=BX.findChildren(w,{class:"allSum_wVAT_FORMATED"},!0);if(se&&se.length>0)for(var u in se)se[u].innerHTML=t.BASKET_DATA.allSum_wVAT_FORMATED.replace(/\s/g,"&nbsp;");var ie=BX.findChildren(w,{class:"allVATSum_FORMATED"},!0);if(ie&&ie.length>0)for(var u in ie)ie[u].innerHTML=t.BASKET_DATA.allVATSum_FORMATED.replace(/\s/g,"&nbsp;");var re=BX.findChildren(w,{class:"allSum_FORMATED"},!0);if(re&&re.length>0)for(var u in re)re[u].innerHTML=t.BASKET_DATA.allSum_FORMATED.replace(/\s/g,"&nbsp;");var oe=BX.findChildren(w,{class:"PRICE_WITHOUT_DISCOUNT"},!0);if(oe&&oe.length>0)for(var u in oe)oe[u].innerHTML=t.BASKET_DATA.PRICE_WITHOUT_DISCOUNT!=t.BASKET_DATA.allSum_FORMATED?t.BASKET_DATA.PRICE_WITHOUT_DISCOUNT.replace(/\s/g,"&nbsp;"):"";BX.onCustomEvent("OnBasketChange")}}}function couponCreate(e,t){var a="disabled";BX.type.isElementNode(e)&&("BAD"===t.JS_STATUS?a="bad":"APPLYED"===t.JS_STATUS&&(a="good"),e.appendChild(BX.create("div",{props:{className:"coupon"},children:[BX.create("span",{props:{className:"coupon__del "+a},attrs:{"data-coupon":t.COUPON}}),BX.create("div",{props:{className:"l-context"},children:[BX.create("input",{props:{className:"form-control coupon__input "+a,type:"text",value:t.COUPON,name:"OLD_COUPON[]"},attrs:{disabled:!0,readonly:!0}})]}),BX.create("div",{props:{className:"coupon__note"},html:t.JS_CHECK_CODE})]})))}function couponListUpdate(e){var t,a,s,i,r,o,n,l,_=BX("basket_form");if(!e||"object"==typeof e){if((t=BX.findChildren(_,{class:"cart__coupons"},!0))&&t.length>0&&e.COUPON_LIST&&BX.type.isArray(e.COUPON_LIST))for(var u in t)if(s=BX.findChildren(t[u],{class:"coupon__input"},!0),s&&(s.value=""),i=BX.findChildren(t[u],{tagName:"input",property:{name:"OLD_COUPON[]"}},!0)){for(BX.type.isElementNode(i)&&(i=[i]),o=0;o<e.COUPON_LIST.length;o++){for(r=!1,l=-1,n=0;n<i.length;n++)if(i[n].value===e.COUPON_LIST[o].COUPON){r=!0,l=n,i[n].couponUpdate=!0;break}r?(a="disabled","BAD"===e.COUPON_LIST[o].JS_STATUS?a="bad":"APPLYED"===e.COUPON_LIST[o].JS_STATUS&&(a="good"),BX.adjust(i[l],{props:{className:"form-control coupon__input "+a}}),BX.adjust(i[l].parentNode.previousSibling,{props:{className:"coupon__del "+a}}),BX.adjust(i[l].parentNode.nextSibling,{html:e.COUPON_LIST[o].JS_CHECK_CODE})):couponCreate(t[u],e.COUPON_LIST[o])}for(n=0;n<i.length;n++)void 0!==i[n].couponUpdate&&i[n].couponUpdate?i[n].couponUpdate=null:(BX.remove(i[n].parentNode.parentNode),i[n]=null)}else for(o=0;o<e.COUPON_LIST.length;o++)couponCreate(t[u],e.COUPON_LIST[o]);t=null}}function skuPropClickHandler(e){e||(e=window.event);var t,a,s,i,r,o,n,l=BX.proxy_context,_={},u={};if(l&&l.hasAttribute("data-value-id")){if(BX.showWait(),t=l.getAttribute("data-element"),a=l.getAttribute("data-property"),s=BX("action_var").value,_[a]=BX.util.htmlspecialcharsback(l.getAttribute("data-value-id")),BX.hasClass(l,"checked"))return void BX.closeWait();if((i=BX.findChildren(BX(t),{tagName:"ul",className:"offer_prop__values"},!0))&&i.length>0)for(r=0;i.length>r;r++)if(i[r].id!=="prop_"+a+"_"+t&&(o=BX.findChildren(BX(i[r].id),{tagName:"li",className:"checked"},!0))&&o.length>0)for(n=0;o.length>n;n++)o[n].hasAttribute("data-value-id")&&(_[o[n].getAttribute("data-property")]=BX.util.htmlspecialcharsback(o[n].getAttribute("data-value-id")));u={basketItemId:t,sessid:BX.bitrix_sessid(),site_id:BX.message("SITE_ID"),props:_,action_var:s,select_props:BX("column_headers").value,offers_props:BX("offers_props").value,quantity_float:BX("quantity_float").value,price_vat_show_value:BX("price_vat_show_value").value,hide_coupon:BX("hide_coupon").value,use_prepayment:BX("use_prepayment").value},u[s]="select_item",BX.ajax({url:"/bitrix/components/bitrix/sale.basket.basket/ajax.php",method:"POST",data:u,dataType:"json",onsuccess:function(e){BX.closeWait(),updateBasketTable(t,e)}})}}function getColumnName(e,t){return BX("col_"+t)?BX.util.trim(BX("col_"+t).innerHTML):""}function leftScroll(e,t,a){a=parseInt(a,10);var s=BX("prop_"+e+"_"+t);if(s){var i=parseInt(s.style.marginLeft,10);i<=20*(6-a)&&(s.style.marginLeft=i+20+"%")}}function rightScroll(e,t,a){a=parseInt(a,10);var s=BX("prop_"+e+"_"+t);if(s){var i=parseInt(s.style.marginLeft,10);i>20*(5-a)&&(s.style.marginLeft=i-20+"%")}}function checkOut(){return BX("coupon")&&(BX("coupon").disabled=!0),BX("basket_form").submit(),!0}function updateBasket(){recalcBasketAjax({})}function enterCoupon(e){var t=e;t&&t.value&&recalcBasketAjax({coupon:t.value})}function updateQuantity(e,t,a,s){var i=BX(e).defaultValue,r=parseFloat(BX(e).value)||0,o=!1,n=BX("auto_calculation")&&"Y"==BX("auto_calculation").value||!BX("auto_calculation");if(0===a||1==a)o=!0;else{var l=1e4*r,_=1e4*a,u=l%_;parseInt(r);0===u&&(o=!0)}var c=!1;parseInt(r)!=parseFloat(r)&&(c=!0),r=!1===s&&!1===c?parseInt(r):parseFloat(r).toFixed(4),r=correctQuantity(r),o?(BX(e).defaultValue=r,BX("QUANTITY_INPUT_"+t).value=r,BX("QUANTITY_"+t).value=r,n&&basketPoolQuantity.changeQuantity(t)):(r=getCorrectRatioQuantity(r,a,s),r=correctQuantity(r),r!=i?(BX("QUANTITY_INPUT_"+t).value=r,BX("QUANTITY_"+t).value=r,n&&basketPoolQuantity.changeQuantity(t)):BX(e).value=i)}function setQuantity(e,t,a,s){var i,r=parseFloat(BX("QUANTITY_INPUT_"+e).value);i="up"==a?r+t:r-t,i<0&&(i=0),s&&(i=parseFloat(i).toFixed(4)),i=correctQuantity(i),t>0&&i<t&&(i=t),s||i==i.toFixed(4)||(i=parseFloat(i).toFixed(4)),i=getCorrectRatioQuantity(i,t,s),i=correctQuantity(i),BX("QUANTITY_INPUT_"+e).value=i,BX("QUANTITY_INPUT_"+e).defaultValue=i,updateQuantity("QUANTITY_INPUT_"+e,e,t,s)}function getCorrectRatioQuantity(e,t,a){var s,i=(e/t-(e/t).toFixed(0)).toFixed(6),r=e,o=!1;if(t=parseFloat(t),0==i)return r;if(0!==t&&1!=t)for(s=t,max=parseFloat(e)+parseFloat(t);s<=max;s=parseFloat(parseFloat(s)+parseFloat(t)).toFixed(4))r=s;else 1===t&&(r=0|e);return parseInt(r,10)!=parseFloat(r)&&(o=!0),r=!1===a&&!1===o?parseInt(r,10):parseFloat(r).toFixed(4),r=correctQuantity(r)}function correctQuantity(e){return parseFloat((1*e).toString())}function recalcBasketAjax(e){if(basketPoolQuantity.isProcessing())return!1;BX.showWait();var t,a,s={},i=BX("action_var").value,r=BX("basket_items"),o=BX("delayed_items");if(t={sessid:BX.bitrix_sessid(),site_id:BX.message("SITE_ID"),props:s,action_var:i,select_props:BX("column_headers").value,offers_props:BX("offers_props").value,quantity_float:BX("quantity_float").value,price_vat_show_value:BX("price_vat_show_value").value,hide_coupon:BX("hide_coupon").value,use_prepayment:BX("use_prepayment").value},t[i]="recalculate",e&&"object"==typeof e)for(a in e)e.hasOwnProperty(a)&&(t[a]=e[a]);if(r&&r.rows.length>0)for(a=0;r.rows.length>a;a++)t["QUANTITY_"+r.rows[a].id]=BX("QUANTITY_"+r.rows[a].id).value;if(o&&o.rows.length>0)for(a=0;o.rows.length>a;a++)t["DELAY_"+o.rows[a].id]="Y";basketPoolQuantity.setProcessing(!0),basketPoolQuantity.clearPool(),BX.ajax({url:"/bitrix/components/bitrix/sale.basket.basket/ajax.php",method:"POST",data:t,dataType:"json",onsuccess:function(t){BX.closeWait(),basketPoolQuantity.setProcessing(!1),e.coupon&&t&&t.BASKET_DATA&&t.BASKET_DATA.NEED_TO_RELOAD_FOR_GETTING_GIFTS&&BX.reload(),basketPoolQuantity.isPoolEmpty()?(updateBasketTable(null,t),basketPoolQuantity.updateQuantity()):basketPoolQuantity.enableTimer(!0)}})}function showBasketItemsList(e){BX.removeClass(BX("basket_toolbar_button"),"current"),BX.removeClass(BX("basket_toolbar_button_delayed"),"current"),BX.removeClass(BX("basket_toolbar_button_subscribed"),"current"),BX.removeClass(BX("basket_toolbar_button_not_available"),"current"),BX("normal_count").style.display="inline-block",BX("delay_count").style.display="inline-block",BX("subscribe_count").style.display="inline-block",BX("not_available_count").style.display="inline-block",2==e?(BX("basket_items_list")&&(BX("basket_items_list").style.display="none"),BX("basket_items_delayed")&&(BX("basket_items_delayed").style.display="block",BX.addClass(BX("basket_toolbar_button_delayed"),"current"),BX("delay_count").style.display="none"),BX("basket_items_subscribed")&&(BX("basket_items_subscribed").style.display="none"),BX("basket_items_not_available")&&(BX("basket_items_not_available").style.display="none")):3==e?(BX("basket_items_list")&&(BX("basket_items_list").style.display="none"),BX("basket_items_delayed")&&(BX("basket_items_delayed").style.display="none"),BX("basket_items_subscribed")&&(BX("basket_items_subscribed").style.display="block",BX.addClass(BX("basket_toolbar_button_subscribed"),"current"),BX("subscribe_count").style.display="none"),BX("basket_items_not_available")&&(BX("basket_items_not_available").style.display="none")):4==e?(BX("basket_items_list")&&(BX("basket_items_list").style.display="none"),BX("basket_items_delayed")&&(BX("basket_items_delayed").style.display="none"),BX("basket_items_subscribed")&&(BX("basket_items_subscribed").style.display="none"),BX("basket_items_not_available")&&(BX("basket_items_not_available").style.display="block",BX.addClass(BX("basket_toolbar_button_not_available"),"current"),BX("not_available_count").style.display="none")):(BX("basket_items_list")&&(BX("basket_items_list").style.display="block",BX.addClass(BX("basket_toolbar_button"),"current"),BX("normal_count").style.display="none"),BX("basket_items_delayed")&&(BX("basket_items_delayed").style.display="none"),BX("basket_items_subscribed")&&(BX("basket_items_subscribed").style.display="none"),BX("basket_items_not_available")&&(BX("basket_items_not_available").style.display="none"))}function deleteCoupon(e){var t,a=BX.proxy_context;a&&a.hasAttribute("data-coupon")&&(t=a.getAttribute("data-coupon"))&&t.length>0&&recalcBasketAjax({delete_coupon:t})}function deleteProductRow(e){var t,a,s=BX.findParent(e,{tagName:"TR"});return s&&(t=BX("QUANTITY_"+s.id))&&(a=getCurrentItemAnalyticsInfo(s,t.value)),setAnalyticsDataLayer([],[a]),document.location.href=e.href,!1}function checkAnalytics(e,t){if(e&&t&&0!==BX.util.array_values(e).length){var a,s,i,r={},o=[],n=[];if(t&&t.rows.length)for(i=1;t.rows.length>i;i++)a=t.rows[i].id,0!=(s=BX("QUANTITY_"+a).value-e[a])&&(r=getCurrentItemAnalyticsInfo(t.rows[i],s),s>0?o.push(r):n.push(r));(o.length||n.length)&&setAnalyticsDataLayer(o,n)}}function getCurrentItemAnalyticsInfo(e,t){if(e){var a,s,i=[],r={name:e.getAttribute("data-item-name")||"",id:e.id,price:e.getAttribute("data-item-price")||0,brand:(e.getAttribute("data-item-brand")||"").split(",  ").join("/"),variant:"",quantity:Math.abs(t)};for(a=e.querySelectorAll(".bx_active[data-sku-name]"),s=0;s<a.length;s++)i.push(a[s].getAttribute("data-sku-name"));return r.variant=i.join("/"),r}}function setAnalyticsDataLayer(e,t){window[basketJSParams.DATA_LAYER_NAME]=window[basketJSParams.DATA_LAYER_NAME]||[],e&&e.length&&window[basketJSParams.DATA_LAYER_NAME].push({event:"addToCart",ecommerce:{currencyCode:getCurrencyCode(),add:{products:e}}}),t&&t.length&&window[basketJSParams.DATA_LAYER_NAME].push({event:"removeFromCart",ecommerce:{currencyCode:getCurrencyCode(),remove:{products:t}}})}function getCurrencyCode(){var e,t=BX("basket_items"),a="";return t&&(e=t.querySelector("[data-item-currency"))&&(a=e.getAttribute("data-item-currency")),a}BasketPoolQuantity=function(){this.processing=!1,this.poolQuantity={},this.updateTimer=null,this.currentQuantity={},this.lastStableQuantities={},this.updateQuantity()},BasketPoolQuantity.prototype.updateQuantity=function(){var e=BX("basket_items");if("Y"===basketJSParams.USE_ENHANCED_ECOMMERCE&&checkAnalytics(this.lastStableQuantities,e),e&&e.rows.length>0)for(var t=0;e.rows.length>t;t++){var a=e.rows[t].id;this.currentQuantity[a]=BX("QUANTITY_"+a).value}this.lastStableQuantities=BX.clone(this.currentQuantity,!0)},BasketPoolQuantity.prototype.changeQuantity=function(e){var t=BX("QUANTITY_"+e).value,a=this.isPoolEmpty();this.currentQuantity[e]&&this.currentQuantity[e]!=t&&(this.poolQuantity[e]=this.currentQuantity[e]=t),a?this.trySendPool():this.enableTimer(!0)},BasketPoolQuantity.prototype.trySendPool=function(){this.isPoolEmpty()||this.isProcessing()||(this.enableTimer(!1),recalcBasketAjax({}))},BasketPoolQuantity.prototype.isPoolEmpty=function(){return 0==Object.keys(this.poolQuantity).length},BasketPoolQuantity.prototype.clearPool=function(){this.poolQuantity={}},BasketPoolQuantity.prototype.isProcessing=function(){return!0===this.processing},BasketPoolQuantity.prototype.setProcessing=function(e){this.processing=!0===e},BasketPoolQuantity.prototype.enableTimer=function(e){clearTimeout(this.updateTimer),!1!==e&&(this.updateTimer=setTimeout(function(){basketPoolQuantity.trySendPool()},1500))},BX.ready(function(){basketPoolQuantity=new BasketPoolQuantity;var e,t,a=BX.findChildren(BX("basket_items"),{className:"offer_prop__value"},!0),s=BX("basket_form");if(a&&a.length>0)for(e=0;a.length>e;e++)BX.bind(a[e],"click",BX.delegate(function(e){skuPropClickHandler(e)},this));if(t=BX.findChildren(s,{class:"cart__coupons"},!0))for(var i in t)BX.bindDelegate(t[i],"click",{attribute:"data-coupon"},BX.delegate(function(e){deleteCoupon(e)},this));basketJSParams.EVENT_ONCHANGE_ON_START&&"Y"==basketJSParams.EVENT_ONCHANGE_ON_START&&BX.onCustomEvent("OnBasketChange")});
/* End */
;
; /* Start:"a:4:{s:4:"full";s:89:"/bitrix/templates/ph_default/template_ext/catalog.section/al/script.min.js?16354170903365";s:6:"source";s:70:"/bitrix/templates/ph_default/template_ext/catalog.section/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function rsCatalogSectionAjaxRefresh(e,a){var t=$("#"+e.ajaxId),o={type:"POST",url:e.url,data:{},success:function(o){var r=BX.parseJSON(o);if(e.historyPush&&"replaceState"in window.history)if("popstate"!=e.type){var i=null;if(i={isAjax:!0,url:e.url,id:e.ajaxId},e.url=e.url.indexOf("#")<0?e.url+"#"+e.ajaxId:e.url,window.history.replaceState(i,null,e.url),t.length>0){var s=$(window).scrollTop(),n=s+$(window).height(),l=t.offset().top;(l<s||l>n)&&$("html, body").animate({scrollTop:l},1e3)}}else window.location.hash=e.ajaxId;if(null==r)t.html(o);else for(var c in r)$("#"+c).html(r[c]);$(a.target).parent().addClass("active").siblings().removeClass("active"),appSLine.setProductItems()},error:function(){console.warn("sorter - change template -> error responsed")},complete:function(){appSLine.ajaxExec=!1,t.rsToggleDark()}};appSLine.ajaxExec||"#"==o.url||void 0==o.url||(t.rsToggleDark({progress:!0,progressTop:"100px"}),appSLine.ajaxExec=!0,o.url+=(o.url.indexOf("?")<0?"?":"&"!=o.url.slice(-1)?"&":"")+"rs_ajax=Y",void 0!=e.ajaxId&&(o.url+="&ajax_id="+e.ajaxId),$.ajax(o))}$(document).ready(function(){$(".js_popup_detail").fancybox({width:1170,wrapCSS:"popup_detail",fitToView:!0,autoSize:!0,openEffect:"fade",closeEffect:"fade",padding:[25,20,25,20],helpers:{title:null},ajax:{dataType:"html",headers:{popup_detail:"Y"}},beforeLoad:function(){this.href=this.href+(0<this.href.indexOf("?")?"&":"?")+"popup_detail=Y"},beforeShow:function(){appSLine.setProductItems()},afterClose:function(){appSLine.setProductItems()},afterShow:function(){$(".fancybox-inner").css("overflow","visible")}})}),$(document).on("mouseenter",".catalog_item",function(){$(this).addClass("is-hover")}),$(document).on("mouseleave",".catalog_item",function(){$(this).removeClass("is-hover").find(".div_select.opened").removeClass("opened").addClass("closed")}),$(window).on("scroll",function(){$(".js-ajaxpages_auto").each(function(){var e=$(this);200>e.offset().top-window.pageYOffset-$(window).height()&&!appSLine.ajaxExec&&e.find("a").trigger("click")})}),$(document).on("click",".js-catalog_refresh a",function(e){var a=$(this),t=a.attr("href").split("+").join("%2B"),o=a.closest(".js-catalog_refresh"),r=o.data("ajax-id"),i={url:t,ajaxId:r};void 0!=o.data("history-push")&&(i.historyPush=!0),rsCatalogSectionAjaxRefresh(i,e),e.preventDefault()}),$(document).on("click",".js-ajaxpages a",function(e){var a=$(this),t=a.attr("href"),o=a.closest(".js-ajaxpages"),r=o.data("ajax-id"),i={type:"POST",url:t,success:function(e){var a=BX.parseJSON(e);if("replaceState"in window.history){var i=null;i={url:t,id:r},t=t.indexOf("#")<0?t+"#"+r:t,window.history.replaceState(i,null,t)}if(null==a)o.replaceWith(e);else for(var s in a)$("#"+s).html(a[s])},error:function(){console.warn("ajaxpages -> error responsed")},complete:function(){appSLine.ajaxExec=!1,o.rsToggleDark()}};i.url+=(i.url.indexOf("?")<1?"?":"&"!=i.url.slice(-1)?"&":"")+"rs_ajax=Y&ajax_type=pages",o.length>0&&!appSLine.ajaxExec&&(appSLine.ajaxExec=!0,o.rsToggleDark(),$.ajax(i)),e.preventDefault()}),window.addEventListener("popstate",function(e){null!==e&&(null===e.state||$.isEmptyObject(e.state)||(e.state.isAjax?e.state.id&&e.state.url?rsCatalogSectionAjaxRefresh({url:e.state.url,ajaxId:e.state.id,type:"popstate"},e):e.state.url?window.location.href=e.state.url:window.location.reload():window.location.reload()))},!1);

/* End */
;; /* /bitrix/templates/ph_default/components/bitrix/sale.basket.basket/basket/script.min.js?163541709022734*/
; /* /bitrix/templates/ph_default/template_ext/catalog.section/al/script.min.js?16354170903365*/