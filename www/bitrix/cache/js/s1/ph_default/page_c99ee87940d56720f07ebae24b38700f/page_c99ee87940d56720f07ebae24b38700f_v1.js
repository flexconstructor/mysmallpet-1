
; /* Start:"a:4:{s:4:"full";s:98:"/bitrix/templates/ph_default/components/bitrix/catalog.section.list/al/script.min.js?1635417090315";s:6:"source";s:80:"/bitrix/templates/ph_default/components/bitrix/catalog.section.list/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
$(document).ready(function(){$(".menu_vml, .menu_vml__sub").each(function(){$(this).menuAim({activate:function(e){$(e).addClass("is-hover")},deactivate:function(e){$(e).removeClass("is-hover")},exitMenu:function(e){$(e.activeRow).add($(e.activeRow).find("is-hover")).removeClass("is-hover"),e.activeRow=null}})})});
/* End */
;
; /* Start:"a:4:{s:4:"full";s:93:"/bitrix/templates/ph_default/assets/lib/jquery.menu-aim/jquery.menu-aim.min.js?16354170903310";s:6:"source";s:74:"/bitrix/templates/ph_default/assets/lib/jquery.menu-aim/jquery.menu-aim.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
!function(t){"use strict";var e=function(t,e){this.$menu=this.activeRow=this.mouseLocs=this.lastDelayLoc=this.timeoutId=this.options=null,this.init(t,e)};e.DEFAULTS={rowSelector:"> li",submenuSelector:"*",submenuDirection:"right",tolerance:75,enter:t.noop,exit:t.noop,activate:t.noop,deactivate:t.noop,exitMenu:t.noop},e.prototype.getDefaults=function(){return e.DEFAULTS},e.prototype.mousemoveDocument=function(t){this.mouseLocs.push({x:t.pageX,y:t.pageY}),this.mouseLocs.length>this.MOUSE_LOCS_TRACKED&&this.mouseLocs.shift()},e.prototype.mouseleaveMenu=function(){this.timeoutId&&clearTimeout(this.timeoutId),this.options.exitMenu(this)&&(this.activeRow&&this.options.deactivate(this.activeRow),this.activeRow=null)},e.prototype.mouseenterRow=function(t){this.timeoutId&&clearTimeout(this.timeoutId),this.options.enter(t.currentTarget),this.possiblyActivate(t.currentTarget)},e.prototype.mouseleaveRow=function(t){this.options.exit(t.currentTarget)},e.prototype.clickRow=function(t){this.activate(t.currentTarget)},e.prototype.activate=function(t){t!=this.activeRow&&(this.activeRow&&this.options.deactivate(this.activeRow),this.options.activate(t),this.activeRow=t)},e.prototype.possiblyActivate=function(t){var e=this.activationDelay(),i=this;e?this.timeoutId=setTimeout(function(){i.possiblyActivate(t)},e):this.activate(t)},e.prototype.activationDelay=function(){function e(t,e){return(e.y-t.y)/(e.x-t.x)}if(!this.activeRow||!t(this.activeRow).is(this.options.submenuSelector))return 0;var i=this.$menu.offset(),o={x:i.left,y:i.top-this.options.tolerance},s={x:i.left+this.$menu.outerWidth(),y:o.y},n={x:i.left,y:i.top+this.$menu.outerHeight()+this.options.tolerance},u={x:i.left+this.$menu.outerWidth(),y:n.y},a=this.mouseLocs[this.mouseLocs.length-1],h=this.mouseLocs[0];if(!a)return 0;if(h||(h=a),h.x<i.left||h.x>u.x||h.y<i.top||h.y>u.y)return 0;if(this.lastDelayLoc&&a.x==this.lastDelayLoc.x&&a.y==this.lastDelayLoc.y)return 0;var c=s,r=u;"left"==this.options.submenuDirection?(c=n,r=o):"below"==this.options.submenuDirection?(c=u,r=n):"above"==this.options.submenuDirection&&(c=o,r=s);var m=e(a,c),l=e(a,r),p=e(h,c),y=e(h,r);return m<p&&l>y?(this.lastDelayLoc=a,this.DELAY):(this.lastDelayLoc=null,0)},e.prototype.destroy=function(){this.$menu.removeData("jquery.menu-aim"),this.$menu.off(".menu-aim").find(this.options.rowSelector).off(".menu-aim"),t(document).off(".menu-aim")},e.prototype.reset=function(t){this.timeoutId&&clearTimeout(this.timeoutId),this.activeRow&&t&&this.options.deactivate(this.activeRow),this.activeRow=null},e.prototype.init=function(e,i){this.$menu=t(e),this.activeRow=null,this.mouseLocs=[],this.lastDelayLoc=null,this.timeoutId=null,this.options=t.extend({},this.getDefaults(),i),this.MOUSE_LOCS_TRACKED=3,this.DELAY=300,this.$menu.on("mouseleave.menu-aim",t.proxy(this.mouseleaveMenu,this)).find(this.options.rowSelector).on("mouseenter.menu-aim",t.proxy(this.mouseenterRow,this)).on("mouseleave.menu-aim",t.proxy(this.mouseleaveRow,this)).on("click.menu-aim",t.proxy(this.clickRow,this)),t(document).on("mousemove.menu-aim",t.proxy(this.mousemoveDocument,this))},t.fn.menuAim=function(i){return this.each(function(){var o=t(this),s=o.data("jquery.menu-aim"),n="object"==typeof i&&i;s||o.data("jquery.menu-aim",s=new e(this,n)),"string"==typeof i&&s[i]()})}}(jQuery);
/* End */
;
; /* Start:"a:4:{s:4:"full";s:100:"/bitrix/templates/ph_default/components/bitrix/catalog.smart.filter/al/script.min.js?163541709016628";s:6:"source";s:80:"/bitrix/templates/ph_default/components/bitrix/catalog.smart.filter/al/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function JCSmartFilter(t,e,i){this.ajaxURL=t,this.form=null,this.timer=null,this.cacheKey="",this.cache=[],this.popups=[],this.viewMode=e,i&&i.SEF_SET_FILTER_URL&&(this.bindUrlToButton("set_filter",i.SEF_SET_FILTER_URL),this.sef=!0),i&&i.SEF_DEL_FILTER_URL&&this.bindUrlToButton("del_filter",i.SEF_DEL_FILTER_URL)}JCSmartFilter.prototype.submit=function(t){var e=[];this.gatherInputsValues(e,BX.findChildren(t,{tag:new RegExp("^(input|select)$","i")},!0))},JCSmartFilter.prototype.keyup=function(t){this.timer&&clearTimeout(this.timer),this.timer=setTimeout(BX.delegate(function(){this.reload(t)},this),500)},JCSmartFilter.prototype.uncheck=function(t,e){if(window["trackBar"+e]){var i=window["trackBar"+e];if(-1!=t.getAttribute("for").indexOf("MAX")){var r=i.maxInput;r.value=r.max}else if(-1!=t.getAttribute("for").indexOf("MIN")){var r=i.minInput;r.value=r.min}i.onInputChange()}else{var r=BX(t.getAttribute("for"));r.removeAttribute("checked"),r.checked=!0,"radio"==r.type&&(r=BX.findChild(BX.findParent(r,{class:"rs_filter-options"}),{tag:"input",attribute:{value:""}},!0))}this.keyup(r)},JCSmartFilter.prototype.click=function(t){this.timer&&clearTimeout(this.timer),this.timer=setTimeout(BX.delegate(function(){this.reload(t)},this),500)},JCSmartFilter.prototype.reload=function(t){if(""!==this.cacheKey)return this.timer&&clearTimeout(this.timer),void(this.timer=setTimeout(BX.delegate(function(){this.reload(t)},this),1e3));if(this.cacheKey="|",this.position=BX.pos(t,!0),this.form=BX.findParent(t,{tag:"form"}),this.form){var e=[];e[0]={name:"ajax",value:"y"},this.gatherInputsValues(e,BX.findChildren(this.form,{tag:new RegExp("^(input|select)$","i")},!0));for(var i=0;i<e.length;i++)this.cacheKey+=e[i].name+":"+e[i].value+"|";if(this.cache[this.cacheKey])this.curFilterinput=t,this.postHandler(this.cache[this.cacheKey],!0);else{if(this.sef){BX("set_filter").disabled=!0}this.curFilterinput=t,BX.ajax.loadJSON(this.ajaxURL,this.values2post(e),BX.delegate(this.postHandler,this))}}},JCSmartFilter.prototype.updateItem=function(t,e){if("N"===e.PROPERTY_TYPE||e.PRICE){var i=window["trackBar"+t];!i&&e.ENCODED_ID&&(i=window["trackBar"+e.ENCODED_ID]),i&&e.VALUES&&(e.VALUES.MIN&&(e.VALUES.MIN.FILTERED_VALUE?i.setMinFilteredValue(e.VALUES.MIN.FILTERED_VALUE):i.setMinFilteredValue(e.VALUES.MIN.VALUE)),e.VALUES.MAX&&(e.VALUES.MAX.FILTERED_VALUE?i.setMaxFilteredValue(e.VALUES.MAX.FILTERED_VALUE):i.setMaxFilteredValue(e.VALUES.MAX.VALUE)))}else if(e.VALUES)for(var r in e.VALUES)if(e.VALUES.hasOwnProperty(r)){var s=e.VALUES[r],n=BX(s.CONTROL_ID);if(n){var o=document.querySelector('[data-role="label_'+s.CONTROL_ID+'"]');if(s.DISABLED){switch(n.type.toLowerCase()){case"radio":case"checkbox":n.disabled=!0}o?BX.addClass(o,"disabled"):BX.addClass(n.parentNode,"disabled")}else{switch(n.type.toLowerCase()){case"radio":case"checkbox":n.removeAttribute("disabled")}o?BX.removeClass(o,"disabled"):BX.removeClass(n.parentNode,"disabled")}s.hasOwnProperty("ELEMENT_COUNT")&&(o=document.querySelector('[data-role="count_'+s.CONTROL_ID+'"]'))&&(o.innerHTML=s.ELEMENT_COUNT)}}},JCSmartFilter.prototype.postHandler=function(t,e){var i,r,s,n=BX("modef"),o=BX("modef_num");if(t&&t.ITEMS){for(var a in this.popups)this.popups.hasOwnProperty(a)&&this.popups[a].destroy();this.popups=[],t.SEF_SET_FILTER_URL&&this.bindUrlToButton("set_filter",t.SEF_SET_FILTER_URL);for(var l in t.ITEMS)t.ITEMS.hasOwnProperty(l)&&this.updateItem(l,t.ITEMS[l]);if(n&&o&&(o.innerHTML=t.ELEMENT_COUNT,i=BX.findChildren(n,{tag:"A"},!0),t.FILTER_URL&&i&&(i[0].href=BX.util.htmlspecialcharsback(t.FILTER_URL)),t.FILTER_AJAX_URL&&t.COMPONENT_CONTAINER_ID&&(BX.unbindAll(i[0]),BX.bind(i[0],"click",function(e){r=BX.util.htmlspecialcharsback(t.FILTER_AJAX_URL);var i=BX(t.COMPONENT_CONTAINER_ID);return $(i).rsToggleDark({progress:!0,progressTop:"100px"}),BX.ajax({url:r,method:"POST",data:{rs_ajax:"Y",ajax_id:t.COMPONENT_CONTAINER_ID},onsuccess:BX.delegate(function(e){e&&e.JS&&BX.ajax.processScripts(BX.processHTML(e.JS).SCRIPT,!1,BX.delegate(function(){this.onLoadSuccess(t,e)},this))},this),onfailure:BX.delegate(function(){this.onLoadFailure(t)},this)}),BX.PreventDefault(e)})),s=BX.findChild(BX.findParent(this.curFilterinput,{class:"bx-filter-parameters-box"}),{class:"bx-filter-container-modef"},!0,!1),s&&(n.style.display="inline-block",clearTimeout(this.iTimeoutModef),s.appendChild(n),this.iTimeoutModef=setTimeout(function(){n.style.display="none"},4e3)),t.INSTANT_RELOAD&&t.COMPONENT_CONTAINER_ID)){var c=BX(t.COMPONENT_CONTAINER_ID);r=void 0==t.SEF_SET_FILTER_URL?BX.util.htmlspecialcharsback(t.FILTER_AJAX_URL):t.SEF_SET_FILTER_URL,BX.hide(BX.findChild(s,{class:"modef__link"},!0,!1)),$(c).rsToggleDark({progress:!0,progressTop:"100px"}),BX.ajax({url:void 0==t.SEF_SET_FILTER_URL?BX.util.htmlspecialcharsback(t.FILTER_AJAX_URL):t.SEF_SET_FILTER_URL,method:"POST",data:{rs_ajax:"Y",ajax_id:t.COMPONENT_CONTAINER_ID},dataType:"json",onsuccess:BX.delegate(function(e){console.log("ajax onsuccess"),e&&e.JS&&(console.log("ajax onsuccess 1"),BX.ajax.processScripts(BX.processHTML(e.JS).SCRIPT,!1,BX.delegate(function(){console.log("ajax onsuccess 3"),this.onLoadSuccess(t,e)},this)))},this),onfailure:BX.delegate(function(){this.onLoadFailure(t)},this)})}}if(this.sef){BX("set_filter").disabled=!1}e||""===this.cacheKey||(this.cache[this.cacheKey]=t),this.cacheKey=""},JCSmartFilter.prototype.bindUrlToButton=function(t,e){var i=BX(t);if(i){"submit"==i.type&&(i.type="button"),BX.bind(i,"click",function(t,e){return function(){return e(t)}}(e,function(t){return window.location.href=t,!1}))}},JCSmartFilter.prototype.gatherInputsValues=function(t,e){if(e)for(var i=0;i<e.length;i++){var r=e[i];if(!r.disabled&&r.type)switch(r.type.toLowerCase()){case"text":case"number":case"textarea":case"password":case"hidden":case"select-one":r.value.length&&(t[t.length]={name:r.name,value:r.value});break;case"radio":case"checkbox":r.checked&&(t[t.length]={name:r.name,value:r.value});break;case"select-multiple":for(var s=0;s<r.options.length;s++)r.options[s].selected&&(t[t.length]={name:r.name,value:r.options[s].value})}}},JCSmartFilter.prototype.values2post=function(t){for(var e=[],i=e,r=0;r<t.length;){var s=t[r].name.indexOf("[");if(-1==s)i[t[r].name]=t[r].value,i=e,r++;else{var n=t[r].name.substring(0,s),o=t[r].name.substring(s+1);i[n]||(i[n]=[]);var a=o.indexOf("]");-1==a?(i=e,r++):0==a?(i=i[n],t[r].name=""+i.length):(i=i[n],t[r].name=o.substring(0,a)+o.substring(a+1))}}return e},JCSmartFilter.prototype.hideFilterProps=function(t){var e=t.parentNode,i=e.querySelector("[data-role='bx_filter_block']");e.querySelector("[data-role='prop_angle']");if(BX.hasClass(e,"bx-active"))i.style.overflow="hidden",new BX.easing({duration:300,start:{opacity:1,height:i.offsetHeight},finish:{opacity:0,height:0},transition:BX.easing.transitions.quart,step:function(t){i.style.opacity=t.opacity,i.style.height=t.height+"px"},complete:function(){i.setAttribute("style",""),BX.removeClass(e,"bx-active")}}).animate();else{i.style.display="block",i.style.opacity=0,i.style.height="auto",i.style.overflow="hidden";var r=i.offsetHeight;i.style.height=0,new BX.easing({duration:300,start:{opacity:0,height:0},finish:{opacity:1,height:r},transition:BX.easing.transitions.quart,step:function(t){i.style.opacity=t.opacity,i.style.height=t.height+"px"},complete:function(){BX.addClass(e,"bx-active"),i.setAttribute("style","")}}).animate()}},JCSmartFilter.prototype.selectDropDownItem=function(t,e){this.keyup(BX(e)),BX.findParent(BX(e),{className:"bx-filter-select-container"},!1).querySelector('[data-role="currentOption"]').innerHTML=t.innerHTML},JCSmartFilter.prototype.onLoadSuccess=function(t,e){history.pushState(null,null,void 0==t.SEF_SET_FILTER_URL?BX.util.htmlspecialcharsback(t.FILTER_URL):t.SEF_SET_FILTER_URL);var i,r=BX(t.COMPONENT_CONTAINER_ID),s=BX.parseJSON(e);if(null==s)i=BX.processHTML(e,!1),r.innerHTML=i.HTML,BX.ajax.processScripts(i.SCRIPT);else for(var n in s){var o=BX(n);o&&(i=BX.processHTML(s[n],!1),o.innerHTML=i.HTML,BX.ajax.processScripts(i.SCRIPT))}$(r).rsToggleDark()},JCSmartFilter.prototype.onLoadFailure=function(t){$(BX(t.COMPONENT_CONTAINER_ID)).rsToggleDark()},BX.namespace("BX.Iblock.SmartFilter"),BX.Iblock.SmartFilter=function(){var t=function(t){"object"==typeof t&&(this.leftSlider=BX(t.leftSlider),this.rightSlider=BX(t.rightSlider),this.tracker=BX(t.tracker),this.trackerWrap=BX(t.trackerWrap),this.minInput=BX(t.minInputId),this.maxInput=BX(t.maxInputId),this.minPrice=parseFloat(t.minPrice),this.maxPrice=parseFloat(t.maxPrice),this.curMinPrice=parseFloat(t.curMinPrice),this.curMaxPrice=parseFloat(t.curMaxPrice),this.fltMinPrice=t.fltMinPrice?parseFloat(t.fltMinPrice):parseFloat(t.curMinPrice),this.fltMaxPrice=t.fltMaxPrice?parseFloat(t.fltMaxPrice):parseFloat(t.curMaxPrice),this.precision=t.precision||0,this.priceDiff=this.maxPrice-this.minPrice,this.leftPercent=0,this.rightPercent=0,this.fltMinPercent=0,this.fltMaxPercent=0,this.colorUnavailableActive=BX(t.colorUnavailableActive),this.colorAvailableActive=BX(t.colorAvailableActive),this.colorAvailableInactive=BX(t.colorAvailableInactive),this.isTouch=!1,this.init(),"ontouchstart"in document.documentElement?(this.isTouch=!0,BX.bind(this.leftSlider,"touchstart",BX.proxy(function(t){this.onMoveLeftSlider(t)},this)),BX.bind(this.rightSlider,"touchstart",BX.proxy(function(t){this.onMoveRightSlider(t)},this))):(BX.bind(this.leftSlider,"mousedown",BX.proxy(function(t){this.onMoveLeftSlider(t)},this)),BX.bind(this.rightSlider,"mousedown",BX.proxy(function(t){this.onMoveRightSlider(t)},this))),BX.bind(this.minInput,"keyup",BX.proxy(function(t){this.onInputChange()},this)),BX.bind(this.maxInput,"keyup",BX.proxy(function(t){this.onInputChange()},this)))};return t.prototype.init=function(){var t;this.curMinPrice>this.minPrice&&(t=this.curMinPrice-this.minPrice,this.leftPercent=100*t/this.priceDiff,this.leftSlider.style.left=this.leftPercent+"%",this.colorUnavailableActive.style.left=this.leftPercent+"%"),this.setMinFilteredValue(this.fltMinPrice),this.curMaxPrice<this.maxPrice&&(t=this.maxPrice-this.curMaxPrice,this.rightPercent=100*t/this.priceDiff,this.rightSlider.style.right=this.rightPercent+"%",this.colorUnavailableActive.style.right=this.rightPercent+"%"),this.setMaxFilteredValue(this.fltMaxPrice)},t.prototype.setMinFilteredValue=function(t){if(this.fltMinPrice=parseFloat(t),this.fltMinPrice>=this.minPrice){var e=this.fltMinPrice-this.minPrice;this.fltMinPercent=100*e/this.priceDiff,this.leftPercent>this.fltMinPercent?this.colorAvailableActive.style.left=this.leftPercent+"%":this.colorAvailableActive.style.left=this.fltMinPercent+"%",this.colorAvailableInactive.style.left=this.fltMinPercent+"%"}else this.colorAvailableActive.style.left="0%",this.colorAvailableInactive.style.left="0%"},t.prototype.setMaxFilteredValue=function(t){if(this.fltMaxPrice=parseFloat(t),this.fltMaxPrice<=this.maxPrice){var e=this.maxPrice-this.fltMaxPrice;this.fltMaxPercent=100*e/this.priceDiff,this.rightPercent>this.fltMaxPercent?this.colorAvailableActive.style.right=this.rightPercent+"%":this.colorAvailableActive.style.right=this.fltMaxPercent+"%",this.colorAvailableInactive.style.right=this.fltMaxPercent+"%"}else this.colorAvailableActive.style.right="0%",this.colorAvailableInactive.style.right="0%"},t.prototype.getXCoord=function(t){var e=t.getBoundingClientRect(),i=document.body,r=document.documentElement,s=window.pageXOffset||r.scrollLeft||i.scrollLeft,n=r.clientLeft||i.clientLeft||0,o=e.left+s-n;return Math.round(o)},t.prototype.getPageX=function(t){t=t||window.event;var e=null;if(this.isTouch&&null!=event.targetTouches[0])e=t.targetTouches[0].pageX;else if(null!=t.pageX)e=t.pageX;else if(null!=t.clientX){var i=document.documentElement,r=document.body;e=t.clientX+(i.scrollLeft||r&&r.scrollLeft||0),e-=i.clientLeft||0}return e},t.prototype.recountMinPrice=function(){var t=this.priceDiff*this.leftPercent/100;t=(this.minPrice+t).toFixed(this.precision),t!=this.minPrice?this.minInput.value=t:this.minInput.value="",smartFilter.keyup(this.minInput)},t.prototype.recountMaxPrice=function(){var t=this.priceDiff*this.rightPercent/100;t=(this.maxPrice-t).toFixed(this.precision),t!=this.maxPrice?this.maxInput.value=t:this.maxInput.value="",smartFilter.keyup(this.maxInput)},t.prototype.onInputChange=function(){var t;if(this.minInput.value){var e=this.minInput.value;e<this.minPrice&&(e=this.minPrice),e>this.maxPrice&&(e=this.maxPrice),t=e-this.minPrice,this.leftPercent=100*t/this.priceDiff,this.makeLeftSliderMove(!1)}if(this.maxInput.value){var i=this.maxInput.value;i<this.minPrice&&(i=this.minPrice),i>this.maxPrice&&(i=this.maxPrice),t=this.maxPrice-i,this.rightPercent=100*t/this.priceDiff,this.makeRightSliderMove(!1)}},t.prototype.makeLeftSliderMove=function(t){t=!1!==t,this.leftSlider.style.left=this.leftPercent+"%",this.colorUnavailableActive.style.left=this.leftPercent+"%";var e=!1;this.leftPercent+this.rightPercent>=100&&(e=!0,this.rightPercent=100-this.leftPercent,this.rightSlider.style.right=this.rightPercent+"%",this.colorUnavailableActive.style.right=this.rightPercent+"%"),this.leftPercent>=this.fltMinPercent&&this.leftPercent<=100-this.fltMaxPercent?(this.colorAvailableActive.style.left=this.leftPercent+"%",e&&(this.colorAvailableActive.style.right=100-this.leftPercent+"%")):this.leftPercent<=this.fltMinPercent?(this.colorAvailableActive.style.left=this.fltMinPercent+"%",e&&(this.colorAvailableActive.style.right=100-this.fltMinPercent+"%")):this.leftPercent>=this.fltMaxPercent&&(this.colorAvailableActive.style.left=100-this.fltMaxPercent+"%",e&&(this.colorAvailableActive.style.right=this.fltMaxPercent+"%")),t&&(this.recountMinPrice(),e&&this.recountMaxPrice())},t.prototype.countNewLeft=function(t){var e=this.getPageX(t),i=this.getXCoord(this.trackerWrap),r=this.trackerWrap.offsetWidth,s=e-i;return s<0?s=0:s>r&&(s=r),s},t.prototype.onMoveLeftSlider=function(t){return this.isTouch||(this.leftSlider.ondragstart=function(){return!1}),this.isTouch?(document.ontouchmove=BX.proxy(function(t){this.leftPercent=100*this.countNewLeft(t)/this.trackerWrap.offsetWidth,this.makeLeftSliderMove()},this),document.ontouchend=function(){document.ontouchmove=document.touchend=null}):(document.onmousemove=BX.proxy(function(t){this.leftPercent=100*this.countNewLeft(t)/this.trackerWrap.offsetWidth,this.makeLeftSliderMove()},this),document.onmouseup=function(){document.onmousemove=document.onmouseup=null}),!1},t.prototype.makeRightSliderMove=function(t){t=!1!==t,this.rightSlider.style.right=this.rightPercent+"%",this.colorUnavailableActive.style.right=this.rightPercent+"%";var e=!1;this.leftPercent+this.rightPercent>=100&&(e=!0,this.leftPercent=100-this.rightPercent,this.leftSlider.style.left=this.leftPercent+"%",this.colorUnavailableActive.style.left=this.leftPercent+"%"),100-this.rightPercent>=this.fltMinPercent&&this.rightPercent>=this.fltMaxPercent?(this.colorAvailableActive.style.right=this.rightPercent+"%",e&&(this.colorAvailableActive.style.left=100-this.rightPercent+"%")):this.rightPercent<=this.fltMaxPercent?(this.colorAvailableActive.style.right=this.fltMaxPercent+"%",e&&(this.colorAvailableActive.style.left=100-this.fltMaxPercent+"%")):100-this.rightPercent<=this.fltMinPercent&&(this.colorAvailableActive.style.right=100-this.fltMinPercent+"%",e&&(this.colorAvailableActive.style.left=this.fltMinPercent+"%")),t&&(this.recountMaxPrice(),e&&this.recountMinPrice())},t.prototype.onMoveRightSlider=function(t){return this.isTouch||(this.rightSlider.ondragstart=function(){return!1}),this.isTouch?(document.ontouchmove=BX.proxy(function(t){this.rightPercent=100-100*this.countNewLeft(t)/this.trackerWrap.offsetWidth,this.makeRightSliderMove()},this),document.ontouchend=function(){document.ontouchmove=document.ontouchend=null}):(document.onmousemove=BX.proxy(function(t){this.rightPercent=100-100*this.countNewLeft(t)/this.trackerWrap.offsetWidth,this.makeRightSliderMove()},this),document.onmouseup=function(){document.onmousemove=document.onmouseup=null}),!1},t}(),$(document).ready(function(){var t=$(".bx-filter");t.find(".bx-filter-scroll").each(function(){$(this).scrollbar({})}),t.find(".bx-filter-search > input").on("keyup",function(){var t=$(this).val().toLowerCase(),e=$(this).closest(".bx-filter-block").find(".bx-filter-parameters-box-container").children();t.length<1?e.css("display","block"):e.each(function(){$(this).hasClass("bx-color-sl")&&$(this).find(".bx-filter-btn-color-icon").attr("title").toLowerCase().indexOf(t)>=0||$(this).find(".bx-filter-param-text").text().toLowerCase().indexOf(t)>=0?$(this).css("display","block"):$(this).css("display","none")})})});
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
;; /* /bitrix/templates/ph_default/components/bitrix/catalog.section.list/al/script.min.js?1635417090315*/
; /* /bitrix/templates/ph_default/assets/lib/jquery.menu-aim/jquery.menu-aim.min.js?16354170903310*/
; /* /bitrix/templates/ph_default/components/bitrix/catalog.smart.filter/al/script.min.js?163541709016628*/
; /* /bitrix/templates/ph_default/components/bitrix/catalog.section/catalog_custom/script.js?16354170902569*/