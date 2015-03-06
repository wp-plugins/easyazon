<?php if(is_wp_error($items)) { ?>
<div class="error">
	<p><strong><?php _e('Warning!'); ?></strong> <?php _e('You must provide your Amazon credentials and ensure they are valid. Requests cannot be made at this time.'); ?></p>
</div>
<?php } ?>

<p><?php printf(__('<a href="%s" target="_blank">Watch this video</a> showing how to set up your Access Key ID and Secret Access Key from Amazon. If you\'ve already watched the video, please visit your <a href="%s" target="_blank">AWS Account Management page</a> to retrieve your keys. These keys are required in order to send requests to Amazon and retrieve data about products and listings.'), 'http://boostwp.com/easyazon-pro-install/', 'https://console.aws.amazon.com/iam/home?#security_credential'); ?></p>
