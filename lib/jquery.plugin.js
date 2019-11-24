;(function ($, window, document, undefined) {
	var pluginName = 'name', defaults = {};

	function Plugin (element, options) {
		this.element = element;
		this.settings = $.extend({}, defaults, options);

		this.init();
	}

	Plugin.prototype = {
		init: function () {
		}
	};

	$.fn[pluginName] = function (options) {
		if ((options === undefined) || (typeof options === 'object')) {
			return this.each(function () {
				if (!$.data(this, 'plugin_' + pluginName)) {
					$.data(this, 'plugin_' + pluginName, new Plugin(this, options));
				}
			});
		}

		if ((typeof options === 'string') && (options[0] !== '_') && (options !== 'init')) {
			var returns, args = arguments;

			this.each(function () {
				var instance = $.data(this, 'plugin_' + pluginName);

				if ((instance instanceof Plugin) && (typeof instance[options] === 'function')) {
					returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
				}

				if (options === 'destroy') {
					$.data(this, 'plugin_' + pluginName, null);
				}
			});

			return returns !== undefined ? returns : this;
		}
	};
})(jQuery, window, document);