// module for Web Components
var $module = (function($B){

var _b_ = $B.builtins

function define(tag_name, cls){
    var $ = $B.args("define", 2, {tag_name: null, cls: null},
            ["tag_name", "cls"], arguments, {}, null, null),
        tag_name = $.tag_name,
        cls = $.cls
    if(typeof tag_name != "string"){
        throw _b_.TypeError.$factory("first argument of define() " +
            "must be a string, not '" + $B.class_name(tag_name) + "'")
    }else if(tag_name.indexOf("-") == -1){
        throw _b_.ValueError.$factory("custom tag name must " +
            "contain a hyphen (-)")
    }
    if(!_b_.isinstance(cls, _b_.type)){
        throw _b_.TypeError.$factory("second argument of define() " +
            "must be a class, not '" + $B.class_name(tag_name) + "'")
    }

    // Create the Javascript class used for the component. It must have
    // the same name as the Python class
    var src = String.raw`var WebComponent = class extends HTMLElement {
      constructor(){
        // Always call super first in constructor
        super()
        var init = $B.$getattr(cls, "__init__", _b_.None)
        if(init !== _b_.None){
            try{
                var _self = $B.DOMNode.$factory(this)
                _self.__class__ = cls
                $B.$call(init)(_self)
                if(WebComponent.initialized){
                    var nb_attrs = _self.attributes.length
                    for(var i = 0; i < nb_attrs; i++){
                        var item = _self.attributes.item(i)
                        throw _b_.TypeError.$factory("Custom element must not " +
                            "have attributes, found: " + item.name + '="' +
                            item.value + '"')
                    }
                }
            }catch(err){
                $B.handle_error(err)
            }
        }
      }
        static get observedAttributes(){
            try{
                var obs_attr = $B.$getattr(cls, "observedAttributes")
                return $B.$call(obs_attr)(cls)
            }catch(err){
                if(! $B.is_exc(err, [_b_.AttributeError])){
                    throw err
                }
                return []
            }
        }
    }
    `
    var name = cls.$infos.__name__
    eval(src.replace(/WebComponent/g, name))
    var webcomp = eval(name) // JS class for component
    webcomp.$cls = cls

    // Override __getattribute__ to handle DOMNode attributes such as
    // attachShadow
    cls.__getattribute__ = function(self, attr){
        try{
            return $B.DOMNode.__getattribute__(self, attr)
        }catch(err){
            if(err.__class__ === _b_.AttributeError){
                var ga = $B.$getattr(cls, "__getattribute__")
                return ga(self, attr)
            }else{
                throw err
            }
        }
    }

    var mro = [cls].concat(cls.__mro__)
    for(var i = 0, len = mro.length - 1; i < len; i++){
        var pcls = mro[i]
        for(var key in pcls){
            if(webcomp.prototype[key] === undefined &&
                    typeof pcls[key] == "function"){
                webcomp.prototype[key] = (function(attr, klass){
                    return function(){
                        return $B.pyobj2jsobj(klass[attr]).call(null,
                            $B.DOMNode.$factory(this), ...arguments)
                    }
                })(key, pcls)
            }
        }
    }

    // define WebComp as the class to use for the specified tag name
    customElements.define(tag_name, webcomp)
    webcomp.initialized = true
}

function get(name){
    var ce = customElements.get(name)
    if(ce && ce.$cls){return ce.$cls}
    return _b_.None
}

return {
    define: define,
    get: get
}

})(__BRYTHON__)