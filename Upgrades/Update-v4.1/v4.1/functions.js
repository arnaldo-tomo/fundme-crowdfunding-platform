(function($) {
"use strict";

$("#addSub").click(function(e){

	e.preventDefault();

	var saveHtml    = $('#addSub').html();
	$('#addSub').attr({'disabled' : 'true'}).html('<i class="fa fa-cog fa-spin fa-1x fa-fw fa-loader"></i>');

    $.ajax({
        url: URL_BASE + "/panel/admin/subcategories/add",
        type:"POST",
        data: $('#addSubForm').serialize(),
        success:function(data){
            window.location.reload();
        },error:function(data){
           var errors =data.responseJSON;

		     var errorsHtml = '';

		    $.each(errors['errors'], function(index, value) {
		        errorsHtml += '<li><i class="glyphicon glyphicon-remove myicon-right"></i> ' + value + '</li>';
		        });

					$('#errorsAlert').html(errorsHtml);
					$('#boxErrors').fadeIn();
					$('#addSub').removeAttr('disabled').html(saveHtml);
		        }
    }); //end of ajax
});

// Delete Blog
		 $(".actionDeleteBlog").on('click', function(e) {
		    	e.preventDefault();

		    var element = $(this);
		 	  var url     = element.attr('data-url');

		 	element.blur();

		 	swal(
		 		{   title: delete_confirm,
		 		  type: "warning",
		 		  showLoaderOnConfirm: true,
		 		  showCancelButton: true,
		 		  confirmButtonColor: "#DD6B55",
		 		   confirmButtonText: yes_confirm,
		 		   cancelButtonText: cancel_confirm,
		 		    closeOnConfirm: false,
		 		    },
		 		    function(isConfirm){
		 		    	 if (isConfirm) {
		 		    	 	window.location.href = url;
		 		    	 	}
		 		    });
		 		 });

})(jQuery);
