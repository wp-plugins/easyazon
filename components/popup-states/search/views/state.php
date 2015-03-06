<div class="easyazon-popup-state" data-bind="visible: searchActive, with: search">
	<h3><?php _e('Search Options'); ?></h3>

	<?php do_action('easyazon_search_form_before'); ?>

	<form action="admin-ajax.php" id="easyazon-popup-form" method="post">
		<table class="form-table">
			<tbody>
				<?php do_action('easyazon_search_fields_before'); ?>

				<tr>
					<th scope="row"><label for="easyazon-search-keywords"><?php _e('Search Keywords or ASIN'); ?></label></th>
					<td>
						<input type="text" class="large-text" id="easyazon-search-keywords" data-bind="textInput: keywords" />
						<p class="description easyazon-search-result-error" data-bind="text: message, visible: error"></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="easyazon-search-locale"><?php _e('Search Locale'); ?></label></th>
					<td>
						<select id="easyazon-search-locale" data-bind="value: locale">
							<?php foreach(easyazon_get_locales() as $locale => $locale_name) { ?>
							<option value="<?php echo esc_attr($locale); ?>"><?php echo esc_html($locale_name); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>

				<?php do_action('easyazon_search_fields_after'); ?>
			</tbody>
		</table>

		<?php do_action('easyazon_search_buttons_before'); ?>

		<p>

			<button class="button button-primary button-large" id="easyazon-search-submit" data-bind="click: initiate"><?php _e('Search'); ?></button>

			<?php do_action('easyazon_search_buttons'); ?>

			<span class="spinner easyazon-search-spinner" data-bind="css: { 'easyazon-search-spinner-active': querying }"></span>

		</p>

		<?php do_action('easyazon_search_buttons_after'); ?>
	</form>

	<?php do_action('easyazon_search_form_after'); ?>

	<div class="easyazon-search-results" data-bind="visible: searchDone">
		<?php do_action('easyazon_search_results_before'); ?>

		<div class="tablenav top">
			<div class="tablenav-pages">
				<span class="displaying-num"><span data-bind="text: resultsNumber"></span> <?php _e('products'); ?></span>
				<span class="pagination-links">
					<a class="prev-page" title="<?php _e('Go to the previous page'); ?>" href="#" data-bind="click: pagePrev, css: { disabled: pagePrevEmpty }">&lsaquo;</a>
					<span class="paging-input">
						<span data-bind="text: page"></span> <?php _e('of'); ?> <span class="total-pages" data-bind="text: pages"></span>
					</span>
					<a class="next-page" title="<?php _e('Go to the next page'); ?>" href="#" data-bind="click: pageNext, css: { disabled: pageNextEmpty }">&rsaquo;</a>
				</span>
			</div>
		</div>

		<table class="widefat fixed">
			<thead>
				<tr>
					<?php
					$search_result_columns = apply_filters('easyazon_search_results_columns', array());
					foreach($search_result_columns as $search_result_column => $search_result_column_name) {
						printf('<th class="easyazon-search-result-column-%s" scope="col">%s</th>', esc_attr($search_result_column), esc_html($search_result_column_name));
					}
					?>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<?php
					foreach($search_result_columns as $search_result_column => $search_result_column_name) {
						printf('<th class="easyazon-search-result-column-%s" scope="col">%s</th>', esc_attr($search_result_column), esc_html($search_result_column_name));
					}
					?>
				</tr>
			</tfoot>

			<tbody>
				<tr data-bind="visible: resultsEmpty">
					<td colspan="<?php echo count($search_result_columns); ?>"><?php _e('No products were found'); ?></td>
				</tr>

				<!-- ko template: { name: 'template-search-result', foreach: results } --><!-- /ko -->
			</tbody>
		</table>

		<div class="tablenav bottom">
			<div class="tablenav-pages">
				<span class="displaying-num"><span data-bind="text: resultsNumber"></span> <?php _e('products'); ?></span>
				<span class="pagination-links">
					<a class="prev-page" title="<?php _e('Go to the previous page'); ?>" href="#" data-bind="click: pagePrev, css: { disabled: pagePrevEmpty }">&lsaquo;</a>
					<span class="paging-input">
						<span data-bind="text: page"></span> <?php _e('of'); ?> <span class="total-pages" data-bind="text: pages"></span>
					</span>
					<a class="next-page" title="<?php _e('Go to the next page'); ?>" href="#" data-bind="click: pageNext, css: { disabled: pageNextEmpty }">&rsaquo;</a>
				</span>
			</div>
		</div>

		<?php do_action('easyazon_search_results_after'); ?>
	</div>
</div>

<script type="text/html" id="template-search-result">
<tr><?php foreach($search_result_columns as $search_result_column => $search_result_column_name) { printf('<td class="easyazon-search-result-column-%s">%s</td>', esc_attr($search_result_column), apply_filters("easyazon_search_result_column_{$search_result_column}", '')); } ?></tr>
</script>
