$(function() {

	$(document).on('blur', '[name="TurnoverFilter[user_email]"]', function() {
		var $this = $(this);
		$this.val($this.data('value'));
	});

	$(document).on('autocompleteselect', '[name="TurnoverFilter[user_email]"]', function(e, ui) {
		$(this).data('value', ui.item.value);
		$('[name="TurnoverFilter[user_id]"]').val(ui.item.id);
	});

	$(document).on('click', '.turnover-filter-remove', function() {
		$('[name="TurnoverFilter[user_id]"]').val('');
		$('[name="TurnoverFilter[user_email]"]').val('').data('value', '');
	});

});
