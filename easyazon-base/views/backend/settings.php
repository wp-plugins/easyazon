<div class="wrap">
	<form action="options.php" enctype="multipart/form-data" method="post">
		<?php screen_icon(); ?>

		<h2><?php _e('EasyAzon Settings'); ?></h2>

		<?php settings_errors(); ?>

		<div class="easyazon-settings-sections">
			<?php do_settings_sections(self::SETTINGS_PAGE); ?>
		</div>

		<p class="submit">
			<?php settings_fields(self::SETTINGS_NAME); ?>
			<input type="submit" class="button button-primary" value="<?php _e('Save Changes'); ?>" />
		</p>
	</form>
</div>