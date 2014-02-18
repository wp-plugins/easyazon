<label class="setting">
	<span><?php _e('Amazon Associates Tracking ID'); ?></span>
	<select data-bind="options: localeTags, optionsCaption: '<?php _e('None'); ?>', value: shortcodeTag"></select>
	<p class="description easyazon-error" data-bind="visible: shortcodeTagEmpty"><?php printf(__('Warning: You will not receive commissions on sales through this link without a Tracking ID enabled. Add one via the <a href="%1$s" target="_blank">EasyAzon Settings</a> page.'), easyazon_get_settings_link()); ?></p>
</label>

<label class="setting">
	<span><?php _e('Open in New Window'); ?></span>
	<select data-easyazon-attribute="new_window" data-bind="value: shortcodeLinkNewWindow">
		<option value="default"><?php _e('Default'); ?></option>
		<option value="yes"><?php _e('Yes'); ?></option>
		<option value="no"><?php _e('No'); ?></option>
	</select>
</label>

<label class="setting">
	<span><?php _e('No Follow'); ?></span>
	<select data-bind="value: shortcodeLinkNofollow">
		<option value="default"><?php _e('Default'); ?></option>
		<option value="yes"><?php _e('Yes'); ?></option>
		<option value="no"><?php _e('No'); ?></option>
	</select>
</label>