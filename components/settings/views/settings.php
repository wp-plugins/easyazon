<div class="wrap" id="easyazon-settings-wrap">
	<h2><?php _e('EasyAzon'); ?></h2>

	<?php settings_errors(); ?>

	<form action="options.php" method="post">
		<p><strong><?php printf(__('Having trouble using EasyAzon? <a href="%s" target="_blank">Watch step-by-step video tutorials.</a>'), 'http://boostwp.com/easyazon-pro-install/'); ?></strong></p>

		<?php do_action('easyazon_settings_before_sections'); ?>

		<?php do_settings_sections(EASYAZON_SETTINGS_PAGE); ?>

		<?php do_action('easyazon_settings_after_sections'); ?>

		<?php do_action('easyazon_settings_before_save_button'); ?>

		<p class="submit submit-easyazon-settings">
			<?php settings_fields(EASYAZON_SETTINGS_PAGE); ?>
			<input type="submit" class="button button-primary" value="<?php _e('Save Changes'); ?>" />
		</p>

		<?php do_action('easyazon_settings_after_save_button'); ?>
	</form>
</div>
