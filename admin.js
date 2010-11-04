(function($){
	$('table.form-table ul').sortable({
		handle:'span.handle',
		connectWith: 'ul',
		dropOnEmpty: false,
		update: function(event, ui) {
			var s = [];
			$('table.form-table li input').each(function(){
				s.push(this.value);
			});
			$('#order').val(s.toString());
		}
	});
})(jQuery);
