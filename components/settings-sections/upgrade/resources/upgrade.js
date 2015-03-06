jQuery(document).ready(function($) {
	$('.easyazon-localization-calculator-field').change(function(event) {
		var $lost      = $('#easyazon-localization-calculator-lost'),
			$period    = $('#easyazon-localization-calculator-period'),
			$unit      = $('#easyazon-localization-calculator-unit'),
			earnings   = parseInt($('#easyazon-localization-calculator-earnings').val()),
			percentage = parseInt($('#easyazon-localization-calculator-percentage').val()),
			price = 47,
			lost = 0,
			period = 0,
			unit = EasyAzon_Settings_Upgrade.months;

		if(isNaN(earnings)) {
			earnings = 100;

			$('#easyazon-localization-calculator-earnings').val(earnings);
		}

		if(isNaN(percentage)) {
			percentage = 50;

			$('#easyazon-localization-calculator-percentage').val(percentage);
		}

		lost  = earnings * (1.0 - (percentage / 100.0));
		lost  = 0 == lost ? 1 : lost;
		period = price / lost;

		if(period < 1.0) {
			period = Math.ceil(period * 30);
			unit   = 1 == period ? EasyAzon_Settings_Upgrade.day : EasyAzon_Settings_Upgrade.days;
		} else {
			period = Math.ceil(period);
			unit   = 1 == period ? EasyAzon_Settings_Upgrade.month : EasyAzon_Settings_Upgrade.months;
		}

		$lost.text(lost.toFixed(2));
		$period.text(period);
		$unit.text(unit);
	}).filter(':first').trigger('change');

	$('#easyazon-localization-calculator').show();
});
