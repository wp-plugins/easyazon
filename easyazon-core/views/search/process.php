<div class="media-embed">
	<div class="easyazon-process">
		<?php do_action('easyazon_before_process'); ?>

		<?php
		include('_inc/search.php');

		include('_inc/shortcode-text.php');
		?>

		<?php do_action('easyazon_after_process'); ?>

		<div class="easyazon-process-clear"></div>
	</div>
</div>