<div class="easyazon-process-search" data-bind="visible: searchStateActive">
	<?php do_action('easyazon_before_search'); ?>

	<label class="setting">
		<span><?php _e('Search Keywords or ASIN'); ?></span>
		<input type="text" class="regular-text easyazon-input" id="easyazon-search-terms" value="" data-bind="event: { keypress: enterable }, value: searchTerms, valueUpdate: 'afterkeydown'" />
		<input type="button" class="button-primary easyazon-button easyazon-input" value="<?php _e('Search Amazon'); ?>" data-bind="click: search, enable: canSearch" />
		<img alt="" title="" class="ajax-feedback easyazon-ajax-feedback" src="<?php esc_attr_e(esc_url(admin_url('images/wpspin_light.gif'))); ?>" data-bind="style: { visibility: searchActive() ? 'visible' : 'hidden' }" />
		<?php do_action('easyazon_after_search_button'); ?>
		<p class="description easyazon-error" data-bind="text: errorMessage,visible: hasErrorMessage"></p>
	</label>

	<label class="setting">
		<span><?php _e('Search Locale'); ?></span>
		<select id="easyazon-search-locale" data-bind="options: locales, optionsText: 'name', optionsValue: 'key', value: locale"></select>
	</label>

	<?php do_action('easyazon_after_search'); ?>

	<?php do_action('easyazon_before_results'); ?>

	<div data-bind="visible: hasSearchResults">

		<div class="tablenav top">
			<div class="tablenav-pages">
				<span class="pagination-links">
					<a class="prev-page" title="<?php _e('Go to the previous page'); ?>" href="#" data-bind="click: previousPage, css: { disabled: !hasPreviousPage() }">&lsaquo; <?php _e('Previous'); ?></a>
					<span class="paging-input"><span data-bind="text: page"></span> <?php _e('of'); ?> <span class="total-pages" data-bind="text: numberPages"></span></span>
					<a class="next-page" title="<?php _e('Go to the next page'); ?>" href="#" data-bind="click: nextPage, css: { disabled: !hasNextPage() }"><?php _e('Next'); ?> &rsaquo;</a>
				</span>
			</div>
		</div>

		<table class="widefat fixed">
			<thead>
				<tr>
					<th scope="col" class="easyazon-image"><?php _e('Image'); ?></th>
					<th scope="col" class="easyazon-title"><?php _e('Title'); ?></th>
					<th scope="col" class="easyazon-links"><?php _e('Insert'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" class="easyazon-image"><?php _e('Image'); ?></th>
					<th scope="col" class="easyazon-title"><?php _e('Title'); ?></th>
					<th scope="col" class="easyazon-links"><?php _e('Insert'); ?></th>
				</tr>
			</tfoot>
			<tbody data-bind="template: { foreach: searchResults, name: 'easyazon-search-result-template' }"></tbody>
		</table>

		<div class="tablenav bottom">
			<div class="tablenav-pages">
				<span class="pagination-links">
					<a class="prev-page" title="<?php _e('Go to the previous page'); ?>" href="#" data-bind="click: previousPage, css: { disabled: !hasPreviousPage() }">&lsaquo; <?php _e('Previous'); ?></a>
					<span class="paging-input"><span data-bind="text: page"></span> <?php _e('of'); ?> <span class="total-pages" data-bind="text: numberPages"></span></span>
					<a class="next-page" title="<?php _e('Go to the next page'); ?>" href="#" data-bind="click: nextPage, css: { disabled: !hasNextPage() }"><?php _e('Next'); ?> &rsaquo;</a>
				</span>
			</div>
		</div>
	</div>

	<?php do_action('easyazon_after_results'); ?>

	<script type="text/html" id="easyazon-search-result-template">
	<tr>
		<td class="easyazon-image">
			<a target="_blank" data-bind="attr: { href: url }"><img alt="" src="<?php esc_attr_e(esc_url($placeholder)); ?>" data-bind="attr: { src: imageUrl, height: imageHeight, width: imageWidth }" /></a>
		</div>
		<td class="easyazon-product">
			<strong><a target="_blank" data-bind="attr: { href: url }, text: title"></a></strong><br />
			List Price: <span data-bind="text: priceList"></span><br />
			Best Price: <span data-bind="text: priceActual"></span>
		</td>
		<td class="easyazon-links">
			<?php echo '<span>' . implode('</span> | <span>', apply_filters('easyazon_get_search_result_actions', array())) . '</span>'; ?>
		</td>
	</tr>
	</script>
</div>