(window.webpackJsonp=window.webpackJsonp||[]).push([["price-history"],{VdHh:function(t,e,n){"use strict";n.r(e);var o=n("k8s9");function i(t){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function c(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}function u(t,e){return!e||"object"!==i(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function s(t){return(s=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}function a(t,e){return(a=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}var l=function(t){function e(){return r(this,e),u(this,s(e).apply(this,arguments))}var n,i,l;return function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&a(t,e)}(e,t),n=e,(i=[{key:"init",value:function(){console.log("History Plugin loaded"),this._client=new o.a,this.button=this.el.children["ajax-button"],this.textdiv=this.el.children["ajax-display"],this.id=this.el.children["ajax-button"].value,this._registerEvents()}},{key:"_registerEvents",value:function(){this.button.onclick=this._fetch.bind(this)}},{key:"_fetch",value:function(){this._client.get("/price_history/price_change/"+this.id,this._setContent.bind(this),"application/json",!0)}},{key:"_setContent",value:function(t){console.log(t);var e=JSON.parse(t),n="<ul>";e.forEach((function(t,e){n+="<li>"+t.changeDate+": "+t.oldNetPrice+" to "+t.newNetPrice+"</li>"})),n+="</ul>",this.textdiv.innerHTML=n}}])&&c(n.prototype,i),l&&c(n,l),e}(n("FGIj").a);window.PluginManager.register("PriceHistoryPlugin",l,"[data-price-history-plugin]")}},[["VdHh","runtime","vendor-node","vendor-shared"]]]);