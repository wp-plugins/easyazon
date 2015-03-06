var EasyAzonPopupStatesLinkVM = function(popup) {
	var _      = this;

	_.popup = popup;

	_.product = ko.observable(new EasyAzonProductVM({}));

	_.text    = ko.observable('');
	_.nw      = ko.observable('');
	_.nf      = ko.observable('');
	_.tag     = ko.observable('');

	_.attributes = function() {
		return {
			identifier: _.product().identifier,
			locale:     _.popup.locale(),
			nw:         _.nw(),
			nf:         _.nf(),
			tag:        _.tag()
		}
	};

	_.cancel = function() {
		_.popup.searchActivate();
	};

	_.insert = function() {
		_.popup.searchActivate();
		_.popup.shortcode(EasyAzon_PopupStates_Link.shortcode, _.attributes(), _.text());
	};
}

window.EAPVM_CALLBACKS.push(function() {
	var _ = this;

	_.link = new EasyAzonPopupStatesLinkVM(_);
	_.linkActive = ko.computed(function() { return 'link' == _.state(); });
	_.linkActivate = function(product) {
		var tags = [], tag = '';

		_.locale(_.search.response.locale);
		_.link.product(product);
		_.link.text(_.parent.easyAzonSelection || product.title);

		tags = _.tags();
		tag  = tags.length > 1 ? tags[1].value : '';
		_.link.tag(tag);

		_.state('link');
	};
});

window.EAPVM_RESET_CALLBACKS.push(function() {
	var _ = this;
});
