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

$('.input-browse-btn').click(function(){
	$('input[name=browse-file]').click();
});

$('input[name=browse-file]').change(function(event){
    var _self = $(this);
    files = event.target.files;
    var fileExtension = ['json', 'xml'];
    var checkFileUpload = 0;
    
    var formData = new FormData();

    $.each(files, function( key, value ) {
        if ($.inArray($(this)[0].name.split('.').pop().toLowerCase(), fileExtension) == -1) {
            $().toastmessage('showErrorToast', "The File " + $(this)[0].name + " is not formatted correctly.");
        }else{
        	formData.append(key, value);
        	checkFileUpload++;
        	$('.input-files>.text-content').html();
        }
    });

    if(checkFileUpload > 0){
	    $.ajaxSetup({
	        headers: {
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $.ajax({
	        type: "POST",
	        url: $base_url + "/uploadAjaxFile",
	        data: formData,
	        dataType: 'json',
	        contentType: false,
	        processData: false,
	        cache: false,
	        complete: function(data) {
	            if(data.status == 200){
	            	var html = "";
	                $.each(data.responseJSON.fileUploaded, function( index, value ) {
	                	html += "<div data-name=" + value.new_name + "> - " + value.filename + "</div>";
	                });
	                $('.input-files>.text-content').html(html);
	            }
	        }
	    });
    }
});