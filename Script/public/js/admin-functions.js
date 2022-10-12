(function($) {
"use strict";

$(".select2").select2({
  tags: false,
  tokenSeparators: [','],
  theme: "bootstrap-5",
});

$(".actionDelete").on('click', function(e) {
   	e.preventDefault();

  var element = $(this);
	var id     = element.attr('data-url');
	var form   = $(element).parents('form');

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
		    	 	form.submit();
		    	 	}
		    	 });
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

	 $(".custom-file").on('change', function(){

     var element = $(this);

   	var loaded = false;
   	if(window.File && window.FileReader && window.FileList && window.Blob){
       // Check empty input filed
   		if($(this).val()) {

   			var oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
   			if($(this)[0].files.length === 0){return}

   			var oFile = $(this)[0].files[0];
         var fsize = $(this)[0].files[0].size; //get file size
   			var ftype = $(this)[0].files[0].type; // get file type

         // Validate formats
         if(!rFilter.test(oFile.type)) {
   				element.val('');
   				alert(formats_available);
   				return false;
   			}

         // Validate Size
         if(!rFilter.test(oFile.type)) {
   				element.val('');
   				alert(formats_available);
   				return false;
   			}
   		}// Check empty input filed
   	}// window File
   });
   // END UPLOAD PHOTO

	 $(".toggle-menu, .overlay").on('click', function() {
	$('.overlay').toggleClass('open');
});

})(jQuery);
