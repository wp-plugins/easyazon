jQuery(document).ready(function($) {
	$(document.body).on('click', '.insert-easyazon', function(event) {
		event.preventDefault();

		var $this = $(this),
			editor = EasyAzon.stateName,
			workflow = wp.media.editor.add(editor, { frame: 'post', state: EasyAzon.stateName, title: EasyAzon.stateTitle });

		workflow.once('open', function() {
			jQuery('.media-frame').addClass('hide-menu');
		});

		workflow.once('close', function() {
			jQuery('.media-frame').removeClass('hide-menu');
		});

		wp.media.editor.open(editor, { title: EasyAzon.stateTitle });
	});

	var $easyAzonSearch = $('.easyazon-process');

	if($easyAzonSearch.size() > 0) {
		var EAVM = new EasyAzonSearchVM();

		window.EAVM = EAVM;

		for(var i = 0; i < window.EAVM_CALLBACKS.length; i++) {
			window.EAVM_CALLBACKS[i](EAVM);
		}

		ko.applyBindings(EAVM, $easyAzonSearch.get(0));
	}
});

window.EAVM_CALLBACKS = window.EAVM_CALLBACKS || [];

var EasyAzonSearchVM = function() {
	var self = this;

	// Errors
	self.errorMessage = ko.observable('');
	self.hasErrorMessage = ko.computed(function() { return '' !== self.errorMessage(); });

	// Locale
	self.locales = EasyAzon.locales;
	self.locale = ko.observable(EasyAzon.locale);
	self.locale.subscribe(function(value) {
		self.searchResults.removeAll();
	});

	// Tags
	self.localeTags = ko.computed(function() {
		return jQuery.grep(('undefined' !== typeof EasyAzon.tags[self.locale()]) ? EasyAzon.tags[self.locale()] : [], function(element, index) {
			return '' !== element;
		});
	});
	self.hasLocaleTags = ko.computed(function() { return self.localeTags().length > 0; });
	self.chooseLocaleTag = function() {
		var localeTags = self.localeTags();
		if(self.shortcodeTagEmpty() && localeTags.length > 0) {
			self.shortcodeTag(localeTags[0]);
		}
	};

	// Search terms
	self.lastSearchTerms = ko.observable(false);
	self.searchTerms = ko.observable('');
	self.hasSearchTerms = ko.computed(function() { return '' !== jQuery.trim(self.searchTerms()); });

	// Search results
	self.searchResults = ko.observableArray();

	// Pagination
	self.page = ko.observable(1);
	self.numberPages = ko.observable(1);

	// Flags
	self.searchActive = ko.observable(false);
	self.canSearch = ko.computed(function() { return self.hasSearchTerms() && !self.searchActive(); });
	self.hasNextPage = ko.computed(function() { return self.canSearch() && self.page() < self.numberPages() && false !== self.lastSearchTerms(); });
	self.hasPreviousPage = ko.computed(function() { return self.canSearch() && self.page() > 1 && false !== self.lastSearchTerms(); });
	self.hasSearchResults = ko.computed(function() { return self.searchResults().length > 0; });

	// State
	self.restoreSearchState = function() {
		self.shortcodeProduct(false);
		self.state('search');
	};
	self.state = ko.observable('search');
	self.searchStateActive = ko.computed(function() { return 'search' === self.state(); });
	self.searchStateInactive = ko.computed(function() { return !self.searchStateActive(); });

	// Shortcodes

	self.insertShortcode = function(shortcode, attributes, content) {
		var win = window.dialogArguments || opener || parent || top, html = '';

		html += '[' + shortcode;
		html += jQuery.map(attributes, function(value, name) { return (!value) ? ('') : (' ' + name + '="' + value + '"'); }).join('');
		html += ']';
		html += ('' === jQuery.trim(content) ? '' : (content + '[/' + shortcode + ']'));

		self.restoreSearchState();
		win.send_to_editor(html);
	};

	/// Data
	self.shortcodeContent = ko.observable('');
	self.shortcodeLinkNewWindow = ko.observable('default');
	self.shortcodeLinkNofollow = ko.observable('default');
	self.shortcodeProduct = ko.observable(false);
	self.shortcodeSearchTerms = ko.observable('');
	self.shortcodeTag = ko.observable('');
	self.shortcodeTagEmpty = ko.computed(function() { return !self.shortcodeTag(); });
	self.shortcodeTagFormatted = ko.computed(function() { return self.shortcodeTagEmpty() ? 'NONE' : self.shortcodeTag(); });

	/// Text
	self.gatherShortcodeTextAttributes = function() {
		return {
			asin: self.shortcodeProduct().original.ASIN,
			locale: self.locale(),
			new_window: self.shortcodeLinkNewWindow(),
			nofollow: self.shortcodeLinkNofollow(),
			tag: self.shortcodeTagFormatted()
		};
	};
	self.insertShortcodeText = function() {
		self.insertShortcode(EasyAzon.shortcodeText, self.gatherShortcodeTextAttributes(), self.shortcodeContent());
	};
	self.shortcodeText = function(searchResult) {
		self.chooseLocaleTag();
		self.shortcodeContent(searchResult.title);
		self.shortcodeProduct(searchResult);

		self.state('shortcodeText');
	};
	self.shortcodeTextStateActive = ko.computed(function() { return 'shortcodeText' === self.state(); });

	// Results retrieval
	function doSearch() {
		self.errorMessage('');
		self.lastSearchTerms(self.searchTerms());
		self.searchActive(true);

		jQuery.post(
			ajaxurl,
			{
				action: EasyAzon.ajaxAction,
				locale: self.locale(),
				page: self.page(),
				searchTerms: self.searchTerms()
			},
			function(data, status) {
				self.searchResults.removeAll();

				if(data.error) {
					self.errorMessage(data.error_message);
				} else {
					self.page(parseInt(data.page));
					self.numberPages(parseInt(data.pages));

					for(var i in data.items) {
						self.searchResults.push(new EasyAzonSearchResultVM(data.items[i]));
					}
				}

				self.searchActive(false);
			},
			'json'
		);
	}

	// Actions
	self.enterable = function(data, event) {
		var key = event.which ? event.which : event.keyCode;

		if(13 === key) {
			self.search();
			return false;
		} else {
			return true;
		}
	};

	self.nextPage = function() {
		if(self.canSearch() && self.hasNextPage()) {
			self.page(self.page() + 1);
			self.searchTerms(self.lastSearchTerms());
			doSearch();
		}
	};

	self.previousPage = function() {
		if(self.canSearch() && self.hasPreviousPage()) {
			self.page(self.page() - 1);
			self.searchTerms(self.lastSearchTerms());
			doSearch();
		}
	};

	self.search = function() {
		if(self.canSearch()) {
			self.page(1);
			doSearch();
		}
	};

	// Miscellaneous cleanup

	self.localeTags.subscribe(function(value) {
		/*
		 * This is disappointing to have to do, but I'm not really sure how exactly to
		 * tell this subscription to fire after the templates have assumed the value of
		 * localeTags. What is happening right now is:
		 *
		 * 1. User changes locale in dropdown
		 * 2. localeTags gets updated based on change in locale
		 * 3. This subscription fires
		 * 4. The value of the shortcodeTag observable gets reset to undefined because
		 *    it isn't present in the dropdown of localeTags
		 * 5. The dropdown with localeTags bound as the options gets filled in
		 * 6. The dropdown shows "None" because shortcodeTag is undefined
		 *
		 * So, as disappointing as this is, I'd rather have it work then not, thus the setTimeout
		 */
		// if(value.length > 0) {
		// 	self.shortcodeTag(value[0]);
		// } else {
		// 	self.shortcodeTag('');
		// }
	});

	self.localeTags.notifySubscribers(self.localeTags());
};

var EasyAzonSearchResultVM = function(result) {
	var self = this;

	function extractImage(result) {
		if('undefined' !== (typeof result.ImageSets) && 'undefined' !== typeof(result.ImageSets.ImageSet) && 'undefined' !== typeof(result.ImageSets.ImageSet.ThumbnailImage)) {
			return result.ImageSets.ImageSet.ThumbnailImage;
		} else {
			return false;
		}
	}

	function extractPriceActual(result) {
		if('undefined' !== (typeof result.OfferSummary) && 'undefined' !== (typeof result.OfferSummary.LowestNewPrice) && 'undefined' !== (typeof result.OfferSummary.LowestNewPrice.FormattedPrice)) {
			return result.OfferSummary.LowestNewPrice.FormattedPrice;
		} else {
			return EasyAzon.noPrice;
		}
	}

	function extractPriceList(result) {
		if('undefined' !== (typeof result.ItemAttributes) && 'undefined' !== (typeof result.ItemAttributes.ListPrice) && 'undefined' !== (typeof result.ItemAttributes.ListPrice.FormattedPrice)) {
			return result.ItemAttributes.ListPrice.FormattedPrice;
		} else {
			return EasyAzon.noPrice;
		}
	}

	function extractTitle(result) {
		if('undefined' !== (typeof result.ItemAttributes) && 'undefined' !== (typeof result.ItemAttributes.Title)) {
			return result.ItemAttributes.Title;
		} else {
			return '';
		}
	}

	function extractUrl(result) {
		if('undefined' !== (typeof result.DetailPageURL)) {
			return result.DetailPageURL;
		} else {
			return '';
		}
	}

	var image = extractImage(result);

	self.imageUrl = image && image.URL ? image.URL : EasyAzon.placeholderUrl;
	self.imageHeight = image && image.Height ? image.Height : EasyAzon.placeholderHeight;
	self.imageWidth = image && image.Width ? image.Width : EasyAzon.placeholderWidth;

	self.priceActual = extractPriceActual(result);
	self.priceList = extractPriceList(result);
	self.title = extractTitle(result);
	self.url = extractUrl(result);

	self.original = result;
};

if(('undefined' !== typeof wp) && ('undefined' !== typeof wp.media) && ('undefined' !== typeof wp.media.view) && ('undefined' !== typeof wp.media.view.MediaFrame)) {
	var easyAzonCreateIframeStates = wp.media.view.MediaFrame.prototype.createIframeStates;
	wp.media.view.MediaFrame.prototype.createIframeStates = function() {
		easyAzonCreateIframeStates.apply(this, arguments);

		this.on('menu:render:default', function(view) {
			view.unset(EasyAzon.stateName);
		}, this);
	};
}