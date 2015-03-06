var EasyAzonPopupStatesSearchVM = function(popup) {
	var _      = this,
		_popup = popup;

	_.querying = ko.observable(false);

	// For querying
	_.keywords = ko.observable('');
	_.locale   = ko.observable(EasyAzon_PopupStates_Search.locale);
	_.page     = ko.observable(1);
	_.pages    = ko.observable(1);

	_.pageNextEmpty  = ko.computed(function() { return _.page() >= _.page(); });
	_.pageNextExists = ko.computed(function() { return _.pages() > _.page(); });

	_.pagePrevEmpty  = ko.computed(function() { return _.page() == 1; });
	_.pagePrevExists = ko.computed(function() { return _.page() > 1; });

	_.pageNext = function() {
		if(_.pageNextExists()) {
			search(jQuery.extend(_.arguments(true), {
				page: (_.page() + 1)
			}));
		}
	};
	_.pagePrev = function() {
		if(_.pagePrevExists()) {
			search(jQuery.extend(_.arguments(true), {
				page: (_.page() - 1)
			}));
		}
	};

	// Results
	_.error         = ko.observable(false);
	_.message       = ko.observable('');
	_.response      = null;
	_.results       = ko.observableArray();
	_.resultsEmpty  = ko.computed(function() { return 0 == _.results().length; });
	_.resultsNumber = ko.computed(function() { return _.results().length; });
	_.searchDone    = ko.observable(false);

	_.arguments = function(previous) {
		if(previous) {
			return {
				keywords: _.response.keywords,
				locale:   _.response.locale,
				page:     _.response.page
			};
		} else {
			return {
				keywords: _.keywords(),
				locale:   _.locale(),
				page:     1
			};
		}
	};

	_.initiate = function() {
		search(_.arguments(false));
	};

	function search(arguments) {
		_.querying(true);

		jQuery.post(
			ajaxurl,
			jQuery.extend(arguments, {
				action: EasyAzon_PopupStates_Search.ajaxActionQueryProducts
			}),
			function(data, status) {
				_.searchDone(true);
				_.results.removeAll();
				_.response = data;

				for(var i = 0; i < _.response.items.length; i++) {
					_.results.push(new EasyAzonProductVM(_.response.items[i]));
				}

				_.error(_.response.error);
				_.message(_.response.message);
				_.pages(parseInt(_.response.pages));
				_.page(parseInt(_.response.page));
				_.querying(false);
			},
			'json'
		);
	};
}

window.EAPVM_CALLBACKS.push(function() {
	var _ = this;

	_.search = new EasyAzonPopupStatesSearchVM(_);
	_.searchActive = ko.computed(function() { return 'search' == _.state(); });
	_.searchActivate = function() { _.state('search'); };

	if(_.parent.easyAzonSelection) {
		_.search.keywords(_.parent.easyAzonSelection);
		_.search.initiate();
	}

	_.state('search');
});

window.EAPVM_RESET_CALLBACKS.push(function() {
	var _ = this;

	if(_.parent.easyAzonSelection && _.parent.easyAzonSelection != _.search.keywords()) {
		_.search.keywords(_.parent.easyAzonSelection);
		_.search.initiate();
	}
});
