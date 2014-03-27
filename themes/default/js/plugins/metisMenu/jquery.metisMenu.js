;(function (jQuery, window, document, undefined) {

    var pluginName = "metisMenu",
        defaults = {
            toggle: true
        };
        
    function Plugin(element, options) {
        this.element = element;
        this.settings = jQuery.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {
        init: function () {

            var jQuerythis = jQuery(this.element),
                jQuerytoggle = this.settings.toggle;

            jQuerythis.find('li.active').has('ul').children('ul').addClass('collapse in');
            jQuerythis.find('li').not('.active').has('ul').children('ul').addClass('collapse');

            jQuerythis.find('li').has('ul').children('a').on('click', function (e) {
                e.preventDefault();

                jQuery(this).parent('li').toggleClass('active').children('ul').collapse('toggle');

                if (jQuerytoggle) {
                    jQuery(this).parent('li').siblings().removeClass('active').children('ul.in').collapse('hide');
                }
            });
        }
    };

    jQuery.fn[ pluginName ] = function (options) {
        return this.each(function () {
            if (!jQuery.data(this, "plugin_" + pluginName)) {
                jQuery.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery.noConflict(), window, document);
