<div id="easyazon-localization-calculator">
	<h4><?php _e('Can link localization pay for EasyAzon Pro?'); ?></h4>

	<p><label for="easyazon-localization-calculator-earnings"><?php _e('What are your average monthly earnings from Amazon?'); ?></label></p>
	<code>$</code><input type="text" class="code regular-text easyazon-localization-calculator-field" id="easyazon-localization-calculator-earnings" value="250" />

	<p><label for="easyazon-localization-calculator-percentage"><?php _e('What percentage of traffic comes from your dominant country?'); ?></label></p>
	<select class="code easyazon-localization-calculator-field" id="easyazon-localization-calculator-percentage">
		<?php foreach(range(5, 100, 5) as $percentage) { ?>
		<option <?php selected($percentage, 50); ?> value="<?php echo esc_attr($percentage); ?>"><?php printf('%d%%', $percentage); ?></option>
		<?php } ?>
	</select>

	<p><?php _e('You\'re losing <strong>$<span id="easyazon-localization-calculator-lost">0</span></strong> every month from international traffic. EasyAzon Pro will pay for itself in only <strong><span id="easyazon-localization-calculator-period">1</span> <span id="easyazon-localization-calculator-unit">month</span></strong>.'); ?></p>
</div>

<h4><?php printf(__('Unlock these extra link options with EasyAzon Pro - <a href="%s" target="_blank">Upgrade Today!</a></p>'), esc_attr(esc_url('http://easyazon.com/why-pro/?utm_source=easyazonplugin&utm_medium=link&utm_campaign=easyazonsettings'))); ?></h4>

<ul class="easyazon-bullet-list">
	<li><?php _e('Automated link cloaking'); ?></li>
	<li><?php _e('Add to cart functionality (increase cookie length - more time to earn commissions)'); ?></li>
	<li><?php _e('Link localization (earn commissions from previously wasted global traffic by automatically converting affiliate links to match the location your website is being visited from: e.g. UK visitors see Amazon.co.uk links)'); ?></li>
	<li><?php _e('Support for multiple affiliate tracking IDs'); ?></li>
</ul>

<h4><?php printf(__('Unlock these extra link types with EasyAzon Pro - <a href="%s" target="_blank">Upgrade Today!</a></p>'), esc_attr(esc_url('http://easyazon.com/why-pro/?utm_source=easyazonplugin&utm_medium=link&utm_campaign=easyazonsettings'))); ?></h4>

<ul class="easyazon-bullet-list">
	<li><?php _e('Product images'); ?></li>
	<li><?php _e('Product information block - includes product title, thumbnail, pricing, and a call to action'); ?></li>
	<li><?php _e('Buy on Amazon buttons - locale specific'); ?></li>
	<li><?php _e('Link to search results for a specific term'); ?></li>
</ul>
