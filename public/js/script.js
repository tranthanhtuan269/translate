// config
var $base_url 		= $('base').attr('href');

var $listLanguageInGroup = [];

$('#edit_category_modal').on('show.bs.modal', function (event) {
  	var button 		= $(event.relatedTarget);
  	var data_id 	= button.attr('data-id');
  	var data_name 	= button.attr('data-name');
  	$('#categoryName_upd').val(data_name);
});

$('.input-browse-btn').click(function(){
	$('input[name=browse-file]').click();
});

$('input[name=browse-file]').change(function(event){
    var _self = $(this);
    files = event.target.files;
    var fileExtension = ['json', 'xml'];
    var checkFileUpload = 0;
    var html = '';
    
    var formData = new FormData();

    $('.input-files>.text-content').html();
    $.each(files, function( key, value ) {
        if ($.inArray($(this)[0].name.split('.').pop().toLowerCase(), fileExtension) == -1) {
            $().toastmessage('showErrorToast', "The File " + $(this)[0].name + " is not formatted correctly.");
        }else{
        	formData.append(key, value);
        	checkFileUpload++;
        	html += "<div data-name=" + value.name + "> - " + value.name + "</div>";
        }
    });
    $('.input-files>.text-content').html(html);
});

$('select[name=translateGroup]').change(function(event){
    var id      = $(this).val();
    if(id != ""){
	    $.ajax({
	        url: baseURL+"/groups/"+id+"/getLanguages",
	        method: "GET",
	        dataType:'json',
	        success: function (response) {
	            $listLanguageInGroup = response.languages;
	        }
	    });
    }
});

$('.translate-btn').click(function(){
	if($('.input-files>.text-content').find('div').length == 0){
		$.ajsrConfirm({
			message: "Please upload a file to translate!",
			confirmButton: "OK",
			cancelButton : "Cancel",
			showCancel: false,
			nineCorners: false,
		});
		return;
	}

	if($('select[name=category]').val() == ''){
		$.ajsrConfirm({
			message: "Please select a category!",
			confirmButton: "OK",
			cancelButton : "Cancel",
			showCancel: false,
			nineCorners: false,
		});
		return;
	}

	if($('select[name=translateGroup]').val() == ''){
		$.ajsrConfirm({
			message: "Please select a translate group!",
			confirmButton: "OK",
			cancelButton : "Cancel",
			showCancel: false,
			nineCorners: false,
		});
		return;
	}
	
    var fileExtension = ['json', 'xml'];
    
	$.each($('input[name=browse-file]').prop('files'), function( key, value ) {
		var $fileName = $(this)[0].name.split('.');
        if ($.inArray($fileName.pop().toLowerCase(), fileExtension) != -1) {
        	var form_data = new FormData();
        	form_data.append(key, value);
        	form_data.append('category', $('select[name=category]').val());
			form_data.append('translateGroup', $('select[name=translateGroup]').val());
			requestTranslate(form_data, $(this)[0].name, $fileName[0]);
        }
    });
});

function requestTranslate(formData, filenameFull, filenameShort){
	$.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: $base_url + "/translate",
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function( xhr ) {
        	if($('#' + filenameShort).length == 0){
	        	var html = "";
			    html += "<tr id='"+filenameShort+"'>";
	            html += "<td class='input-file-list'>"+filenameFull+"</td>";
	            html += "<td class='progress-list'>";
	            $.each($listLanguageInGroup, function( index, value ) {
				  	html += "<div class='link-download "+value.name+"'> - Translate to "+value.name+"<span class='image-loading'><img src='"+$base_url+"/images/loading.gif' style='float:right;' width='20' height='20'></span></div>";
				});
	            html += "</td>";
	            html += "</tr>";

			  	$('#translate-return-body').append(html);
		  	}
		},
        complete: function(data) {
            if(data.status == 200){
				$('#' + filenameShort + ' .progress-list .image-loading img').attr('src', $base_url+'/images/check-mark.svg');
				$('#' + filenameShort + ' .download-btn').removeClass('d-none');
            }
        }
    });
}

function deleteGroup($id){
	$.ajsrConfirm({
		message: "Are you sure you want to delete this item?",
		confirmButton: "OK",
		cancelButton : "Cancel",
		onConfirm: function() {
			var data = {
				id : $id,
				_method : "DELETE"
			}
			$.ajaxSetup({
		        headers: {
		          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        }
		    });

		    $.ajax({
		        type: "POST",
		        url: $base_url + "/groups/" + $id,
		        data: data,
		        dataType: 'json',
		        complete: function(data) {
		            if(data.status == 200){
		            	$('#group-' + $id).hide('slow');
		            }
		        }
		    });
		},
		nineCorners: false,
	});
}