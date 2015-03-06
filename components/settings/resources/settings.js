jQuery(document).ready(function($) {
	(function() {
		var $wrap = $('#easyazon-settings-wrap'),
			$title = $wrap.find('h2'),
			$stitles = $wrap.find('h3');

		if($stitles.size()) {
			$title.addClass('nav-tab-wrapper').append('&nbsp;&nbsp;');

			$stitles.each(function(index, stitle) {
				var $stitle = $(stitle),
					$section = $stitle.nextUntil('h3,p.submit-easyazon-settings'),
					selector = 'easyazon-settings-section-' + index;

				if($section) {
					$section.add($stitle).wrapAll('<div class="easyazon-settings-section" id="' + selector + '"></div>');
					$title.append('<a class="nav-tab" href="#' + selector + '">' + $stitle.text() + '</a>');
				}
			});

			var $sections = $wrap.find('.easyazon-settings-section');
			var $tabs = $title.find('.nav-tab');

			$tabs.click(function(event) {
				event.preventDefault();

				var $this = $(this),
					$active = $tabs.filter('.nav-tab-active'),
					selected = $this.is('.nav-tab-active'),
					selector = $this.attr('href');

				if(!selected) {
					// Do it in this order otherwise the UI bounces oddly
					$this.addClass('nav-tab-active');
					$active.removeClass('nav-tab-active');

					$sections.filter(':visible').hide().end().filter(selector).show();
				}
			}).filter(':first').trigger('click');
		}
	}());
});
