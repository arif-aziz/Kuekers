void function(e,t){if("function"==typeof define&&define.amd)define(["jquery","underscore","backbone","exports"],function(i,n,s,o){e.B=t(e,o,i,n,s)});else if("undefined"!=typeof exports){var i=require("jquery"),n=require("underscore"),s=require("backbone");t(e,exports,i,n,s)}else e.B=t(e,e.B||{},e.jQuery,e._,e.Backbone)}(this,function(e,t,i,n,s){var o,r,a;n.mixin({isPrototypeOf:function(e,t){if(!e||!t)return!1;for(var i=!1,n=e.prototype;n;){if(n==t.prototype){i=!0;break}n=n.__proto__}return i},setPrototypeOf:function(e,t){return n.isFunction(Object.setPrototypeOf)?Object.setPrototypeOf(e.prototype||e,t):(e.prototype||e).__proto__=t,e},extendPrototype:function(e,t){return n.extend(e.prototype,t),e}});var l=s.Model,h=s.Collection,d=s.Events,c=s.View,u=c.prototype._ensureElement,p=i("<div>");return o=t.View=function(){function e(t){if(!(this instanceof e))return new e(t);var t=t||{};if(this.__ready=n.once(this.__ready),this.subViews=[],n.extend(this,n.pick(t,"template","views","bindings","events","modelEvents","superView")),this.template)switch(typeof this.template){case"string":this.template=this.template.trim(),this.template.match("<")||this.template.match(/\s/)||(this.template=i(this.template).html())}Object.defineProperties(this,{rootView:{get:function(){for(var e=this,t=this.superView;t;)e=t,t=t.superView;return e}}}),c.call(this,t),this.el&&this.el.parentNode&&this.__ready()}return n.extend(e.prototype,{bindings:!1,initialize:n.noop,ready:n.noop,_ensureElement:function(){u.call(this),this.render()},setModel:function(e){e&&(this.modelEvents&&n(this.modelEvents).each(function(t,i){i=n(["event","selector"]).object(n(i.match(/(.*)\s(.*)/)||[0,i]).rest(1)),t=n.isString(t)?this[t]:t;var s=this.model&&i.selector?this.model.get(i.selector):this.model,o=i.selector&&this.model?this.model.get(i.selector):e;s&&this.stopListening(s,i.event),this.listenTo(o,i.event,t)},this),this.model=e,this.setBindingModel(this.model),this.trigger("set:model",this.model))},setBindingModel:function(e){return!this.__dataBinding&&console.warn("Data-binding hasn't been initialized for this view."),this.__dataBinding&&this.__dataBinding.setModel(e),this.trigger("set:binding:models",this.__dataBinding.models),this},__ready:function(){this.initSubViews(),this.initDataBinding(),this.setModel(this.model),this.ready()},freeze:function(){return this._frozen=!0,this.trigger("freeze"),this},unfreeze:function(){return this._frozen=!1,this.trigger("unfreeze"),this},preventDefault:function(e){e.preventDefault()},stopPropagation:function(e){e.stopPropagation()},defer:function(e,t){return n.defer(n.bind(e,t||this)),this},delay:function(e,t,i){return n.delay(n.bind(e,i||this),t),this},render:function(){return this.__render.apply(this,arguments)},__render:function(){return this.trigger("before:render"),this.template&&this.$el.html(this.template),this.trigger("render"),this},initDataBinding:function(){this.__dataBinding||(this.__dataBinding=new a(this,this.model,{bindings:n.result(this,"bindings")||[]}))},initSubViews:function(){var e=/^(\w+)(?: (collection|model):([$\w.]+))?\s*>\s*(.*)$/;n.each(this.views,function(t,i){var n=i.match(e);if(!n)throw"View definition syntax error: '"+i+"'";var s=n[1],o=n[2],r=n[3],a=n[4],l={};o&&r&&(l[o]="$model"==r?this.model:"$collection"==r?this.collection:this.model.get(r)),this[s]=this.attachView(t,a,l)},this)},hasView:function(e){return this.subViews&&this.subViews.indexOf(e)>-1},attachView:function(e,t,i){var s=this.$(t).get(0);if(!s)throw'No element found for selector "'+t+'"';return n.isFunction(e)?(i||(i={}),i.el=s,i.superView=this,e=new e(i)):(e.setElement(s),e.superView=this,this.hasView(e)||this.subViews.push(e)),e.__ready(),e},appendView:function(e){return this.hasView(e)||this.subViews.push(e),e.superView=this,e.$el.appendTo(this.el),e.__ready(),e},prependView:function(e){return this.hasView(e)||this.subViews.push(e),e.superView=this,e.$el.prependTo(this.el),e.__ready(),e},remove:function(){return this.trigger("before:remove"),c.prototype.remove.call(this),this.superView&&this.superView.subViews&&n(this.superView.subViews).each(function(e,t){e&&e.cid==this.cid&&this.superView.subViews.splice(t,1)},this),this.trigger("remove"),this}}),n.setPrototypeOf(e,c.prototype),e.extend=c.extend,e}(),r=t.CollectionView=function(){function e(t){return this instanceof e?(this.previousSubViews={},this.options=t||{},this._debounceReset=n.debounce(this.reset),t.collection||(t.collection=new h),void o.call(this,t)):new e(t)}return n.extend(e.prototype,{itemView:o,itemViews:{},_reverseOrder:!1,_ensureElement:function(){u.call(this),this.__render(),this.getItemTemplate()},__ready:function(){this.setCollection(this.collection),this.ready()},getItemTemplate:function(){if(n.isEmpty(this.itemViews)){var e=this.el.children[0];if(e){var t=e.innerHTML.trim();this.itemView.prototype==o.prototype&&(this.itemView=this.itemView.extend()),n.extend(this.itemView.prototype,{template:t,tagName:e.tagName,className:e.className,attributes:n(e.attributes).chain().values().map(function(e){return e.name}).object([]).mapObject(function(t,i){return e.getAttribute(i)}).value()})}}else n.each(this.el.children,function(e){var t=e.getAttribute("data-relation");if(t&&this.itemViews[t]){var i=e.innerHTML.trim();this.itemViews[t].prototype==o.prototype&&(this.itemViews[t]=this.itemViews[t].extend()),n.extend(this.itemViews[t].prototype,{template:i,tagName:e.tagName,className:e.className,attributes:n(e.attributes).chain().values().map(function(e){return e.name}).object([]).mapObject(function(t,i){return e.getAttribute(i)}).value()})}},this);this.$el.empty()},setCollection:function(e){e&&(this.collection&&(this.stopListening(this.collection,"add",this.reset),this.stopListening(this.collection,"reset",this.reset),this.stopListening(this.collection,"sort",this.reset),this.trigger("unset:collection",this.collection)),this.collection=e,this.listenTo(this.collection,"add",this.reset),this.listenTo(this.collection,"reset",this.reset),this.listenTo(this.collection,"sort",this.reset),this.trigger("set:collection",this.collection),this.reset())},add:function(e){if(this.previousSubViews[e.cid]){var t=this.previousSubViews[e.cid];delete this.previousSubViews[e.cid]}else{var i=this.itemView;e.get("_rel")&&this.itemViews[e.get("_rel")]&&(i=this.itemViews[e.get("_rel")]);var t=new i({model:e})}return t.mid=e.cid,this._reverseOrder?this.prependView(t):this.appendView(t),t},reset:function(e){for(;this.subViews.length;){var t=this.subViews.pop();this.previousSubViews[t.mid]=t}this.collection.each(this.add,this);for(var i in this.previousSubViews)this.previousSubViews[i].$el.appendTo(p);return this}}),n.setPrototypeOf(e,o.prototype),e.extend=o.extend,e}(),a=t.DataBind=function(){function e(t,i,s){return this instanceof e==!1?new e(t,i,s):(s||(s={}),this.options=n(s).defaults({bindings:[]}),this.view=null,this.models=[],this.bindings=[],n.bindAll(this,"inputEventHandler","changeEventHandler"),this.setupView(t),this.setModel(i),this)}var t=/(?:(\w+):)?({.*}|[^;]+);?/gi;return n.setPrototypeOf(e,d),n.extend(e.prototype,{setModel:function(e){var t=this;return n(t.models).each(function(e){t.stopListening(e)},t),t.models=n.filter(n.isArray(e)?e:[e],function(e){return e instanceof l}),n(t.models).each(function(e){n.each(t.bindingsIndex,function(i,n){t.listenTo(e,"change:"+n,function(e,i,s){t.updateView(n)})})},t),t.updateView(),t.trigger("set:models",t.models),this},setupView:function(e){function a(s){var a=i(s),h=(a.attr("data-bind")||"").replace(/\s+/g,"");for(t.lastIndex=0,a.removeAttr("data-bind");d=t.exec(h);)c=d[1],u=d[2],c||(c=a.is('input[type="checkbox"]')?"checked":a.is('input[type="radio"]')?"radio":a.is("input,select,textarea")?"value":a.is("[contenteditable]")?"html":"text"),"model"!=c?"collection"!=c?(nested=u.match(/{(.*)}/),u=nested?nested[1]:u,n(nested?nested[1].split(","):[u]).each(function(e){split=e.split(":"),l={$el:a,el:s,type:c,attr:1==split.length?split[0]:split[1]},2==split.length&&(l.key=split[0]),a.data("bind-events")&&(l.events=a.data("bind-events")),p.bindings.push(l),boundAttributes=[l.attr],n(boundAttributes).each(function(e){p.bindingsIndex[e]||(p.bindingsIndex[e]=[]),-1==p.bindingsIndex[e].indexOf(l)&&p.bindingsIndex[e].push(l)})})):(n.defer(function(e,t,i){var n=e[t+"View"]=new r({el:s,template:i});"this"==t||"$collection"==t?(e.on("set:collection",function(){n.setCollection(e.collection)}),e.collection&&e.trigger("set:collection",e.collection)):(e.on("set:model",function(){n.setCollection(e.model.get(t))}),e.model&&e.trigger("set:model",e.model))},e,u,s.innerHTML),s.innerHTML=""):(n.defer(function(e,t,i){var n=e[t+"View"]=new o({el:s,template:i});e.on("set:model",function(){var i="this"==t||"$model"==t?e.model:e.model.get(t);n.setModel(i)}),e.model&&e.trigger("set:model",e.model)},e,u,s.innerHTML),s.innerHTML="")}if(e instanceof s.View==0)return!1;n(this.bindings).each(function(e){e.$el.off(e.events||"change",this.changeEventHandler)},this);var l,h,d,c,u,p=this;this.view=e,this.bindings=[],this.bindingsIndex={},n(this.options.bindings).each(function(t){h=e.$(t.selector),(h.length?h:e.$el).each(function(e,s){n(n.isString(t.attr)?[t.attr]:t.attr).each(function(e,o){l=n({$el:i(s),el:s,type:t.type,attr:e}).defaults(t),n.isString(o)&&(l.key=o),p.bindings.push(l),boundAttributes=[l.attr],n(boundAttributes).each(function(e){p.bindingsIndex[e]||(p.bindingsIndex[e]=[]),-1==p.bindingsIndex[e].indexOf(l)&&p.bindingsIndex[e].push(l)})})})});var f;for(e.$el.attr("data-bind")&&a(e.el);f=e.el.querySelector("[data-bind]");)a(f);n(this.bindings).each(function(e){e.$el.on(e.events||"change",{binding:e},this.changeEventHandler),e.$el.removeAttr("data-bind").removeAttr("data-bind-events")},this)},updateView:function(e){var t,i,s,o=this;n(e?o.bindingsIndex[e]:o.bindings).each(function(e){t=o.getData(e.attr),(t instanceof l||t instanceof h)&&(i=t,t=t.toJSON()),s=e.set||o.setters[e.type]||null,s&&(n.isFunction(e.parse)&&(t=e.parse.call(e,t,e.key,o.view)),s(e.$el,t,e.key))})},setters:{text:function(e,t){e.text()===t||e.text(n.isUndefined(t)?" ":t)},html:function(e,t){e.html()===t||e.html(n.isUndefined(t)?" ":t)},value:function(e,t){e.val()===t||e.val(n.isUndefined(t)?" ":t)},attr:function(e,t,i){i&&e.attr(i,t)},prop:function(e,t,i){i&&e.prop(i,t)},style:function(e,t,i){i?e.css(i,t):e.css(t||{})},"class":function(e,t,i){e[t?"addClass":"removeClass"](i)},checked:function(e,t){e.attr("checked",t)},radio:function(e,t){e.attr("checked",e.val()==t?!0:!1)},enabled:function(e,t){e.attr("disabled",!t)},disabled:function(e,t){e.attr("disabled",t)},toggle:function(e,t){e.toggle(t)},visible:function(e,t){e.toggle(!!t)},hidden:function(e,t){e.toggle(!t)}},getters:{text:function(e){return e.text()},html:function(e){return e.html()},value:function(e){var t=e.val();return e.is('input[type="number"],input[type="range"]')&&(t=parseFloat(t),n.isNaN(t)&&(t="")),t},checked:function(e){return e.prop("checked")},radio:function(e){return e.prop("checked")?e.val():void 0}},getData:function(e){var t=[];return n.each(this.models,function(i){var n=i.get(e);-1==t.indexOf(n)&&t.push(n)}),t.length<2?t[0]:"-"},setData:function(e,t){n.invoke(this.models,"set",e,t)},inputEventHandler:function(e){return},changeEventHandler:function(e){if(e.data&&e.data.binding&&e.data.binding.$el.is(e.currentTarget)){var t=e.data.binding;if(this.getters[t.type]){var i=this.getters[t.type](t.$el,e);void 0===i||this.setData(t.attr,i)}}}}),e}(),t});