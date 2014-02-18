<div class="easyazon-process-shortcode-text" data-bind="visible: shortcodeTextStateActive">
	<label class="setting">
		<input type="button" class="button-primary easyazon-button easyazon-input" value="<?php _e('Insert shortcode'); ?>" data-bind="click: insertShortcodeText" />
		<input type="button" class="button-secondary easyazon-button easyazon-input" value="<?php _e('Return to search'); ?>" data-bind="click: restoreSearchState" />
	</label>

	<label class="setting">
		<span><?php _e('Link Text'); ?></span>
		<input type="text" data-bind="value: shortcodeContent" />
	</label>

	<?php do_action('easyazon_shortcode_link_options', 'link'); ?>

	<label class="setting">
		<input type="button" class="button-primary easyazon-button easyazon-input" value="<?php _e('Insert shortcode'); ?>" data-bind="click: insertShortcodeText" />
		<input type="button" class="button-secondary easyazon-button easyazon-input" value="<?php _e('Return to search'); ?>" data-bind="click: restoreSearchState" />
	</label>

	<?php do_action('easyazon_shortcode_after_actions', 'link'); ?>
</div>