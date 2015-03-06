window.EAPVM_CALLBACKS = window.EAPVM_CALLBACKS || [];
window.EAPVM_RESET_CALLBACKS = window.EAPVM_RESET_CALLBACKS || [];

var EasyAzonPopupVM = function() {
	var _      = this;

	_.unCamel = function(word) { return jQuery.trim(word.replace(/([a-z])([A-Z])/g, '$1 $2').replace(/^./, function(s) { return s.toUpperCase(); })); }

	// Store the state to decide what to show
	_.state = ko.observable(null);

	// Store the locale that is currently being operated on
	_.locale = ko.observable('');

	// Store tags that can be fetched
	_.tags = ko.computed(function() {
		var locale = _.locale(),
			tags   = [{ name: EasyAzon_Popup.tagNone, value: EasyAzon_Popup.tagNoneValue }];

		if(EasyAzon_Popup.tags[locale]) {
			for(var i = 0; i < EasyAzon_Popup.tags[locale].length; i++) {
				tags.push({
					name:  EasyAzon_Popup.tags[locale][i],
					value: EasyAzon_Popup.tags[locale][i]
				});
			}
		}

		return tags;
	});

	// Store the parent window so we can access data from it
	_.parent = (window.dialogArguments || opener || parent || top);

	// Store the currently operated product
	_.product = ko.observable(new EasyAzonProductVM({}));

	// Resets the VM to it's original state
	_.reset = function() {
		_.product(new EasyAzonProductVM({}));

		if(_.searchActivate) {
			_.searchActivate();
		}
	};

	// Produce a shortcode and insert it into the editor
	_.generateShortcode = function(shortcode, attributes, content) {
		var shortcodeText = '',
			attributesString = jQuery.map(attributes, function(value, name) {
				return value ? (' ' + name + '="' + value.replace(/"/g, '&quot;') + '"') : '';
			}).join('');


		shortcodeText += '[' + shortcode + attributesString + ']';
		shortcodeText += ('' === jQuery.trim(content) ? '' : (content + '[/' + shortcode + ']'));

		return shortcodeText;
	};

	_.shortcode = function(shortcode, attributes, content) {
		_.parent.send_to_editor(_.generateShortcode(shortcode, attributes, content));
	};
}

var EasyAzonProductVM = function(data) {
	var _ = this;

	_.data       = data;

	_.identifier = _.data.identifier ? _.data.identifier : '';
	_.images     = _.data.images && _.data.images.length ? _.data.images : [];
	_.title      = _.data.title ? _.data.title : '';
	_.url        = _.data.url ? _.data.url : '';

	_.image      = (function() {
		var placeholder = 'http://placehold.it/90/ffffff/ffffff.jpg&text=%20';

		for(var i = 2; i >= 0; i--) {
			if(_.images[i] && _.images[i].url) {
				return _.images[i];
			}
		}

		return {
			height: 90,
			width: 90,
			url: placeholder
		};
	}());
}

EasyAzon_Popup.reset = function() {
	var func, index;

	window.EAPVM.reset();

	for(index = 0; index < window.EAPVM_RESET_CALLBACKS.length; index++) {
		func = window.EAPVM_RESET_CALLBACKS[index];

		func.apply(window.EAPVM);
	}
};

jQuery(document).ready(function($) {
	var $popup, func, index;

	if(($popup = jQuery('#easyazon-popup')).size()) {
		$popup.hide();

		window.EAPVM = new EasyAzonPopupVM();

		for(index = 0; index < window.EAPVM_CALLBACKS.length; index++) {
			func = window.EAPVM_CALLBACKS[index];

			func.apply(window.EAPVM);
		}

		ko.applyBindings(window.EAPVM, $popup.get(0));

		$popup.show();
	}
});
