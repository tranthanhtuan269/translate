// config
var $base_url 		= $('base').attr('href');
$('#edit_category_modal').on('show.bs.modal', function (event) {
  	var button 		= $(event.relatedTarget);
  	var data_id 	= button.attr('data-id');
  	var data_name 	= button.attr('data-name');
  	$('#categoryName_upd').val(data_name);
});

function updateCategory() {
	
}