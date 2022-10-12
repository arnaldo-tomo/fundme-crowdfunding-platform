(function($) {
	"use strict";

	//Date picker
		$('#datepicker').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy',
			startDate: '+7d',
			language: 'en'
		});

		$(document).ready(function() {

		$("#onlyNumber").keydown(function (e) {
				// Allow: backspace, delete, tab, escape, enter and .
				if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
						 // Allow: Ctrl+A, Command+A
						(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
						 // Allow: home, end, left, right, down, up
						(e.keyCode >= 35 && e.keyCode <= 40)) {
								 // let it happen, don't do anything
								 return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
						e.preventDefault();
				}
		});

	});

	$('#removePhoto').on('click', function(){
		$('#filePhoto').val('');
		$('.previewPhoto').css({backgroundImage: 'none'}).hide();
		$('.filer-input-dragDrop').removeClass('hoverClass');
	 });

	//================== START FILE IMAGE FILE READER
	$("#filePhoto").on('change', function(){

	var loaded = false;
	if(window.File && window.FileReader && window.FileList && window.Blob){
		if($(this).val()){ //check empty input filed
			var oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
			if($(this)[0].files.length === 0){return}


			var oFile = $(this)[0].files[0];
			var fsize = $(this)[0].files[0].size; //get file size
			var ftype = $(this)[0].files[0].type; // get file type


			if(!rFilter.test(oFile.type)) {
				$('#filePhoto').val('');
				$('.popout').addClass('popout-error').html(formats_available).fadeIn(500).delay(5000).fadeOut();
				return false;
			}

			var allowed_file_size = file_size_allowed;

			if(fsize>allowed_file_size){
				$('#filePhoto').val('');
				$('.popout').addClass('popout-error').html(max_size).fadeIn(500).delay(5000).fadeOut();
				return false;
			}

			oFReader.onload = function (e) {

				var image = new Image();
					image.src = oFReader.result;

				image.onload = function() {

						if( image.width < min_width) {
							$('#filePhoto').val('');
							$('.popout').addClass('popout-error').html(width_min_alert).fadeIn(500).delay(5000).fadeOut();
							return false;
						}

						if( image.height < min_height) {
							$('#filePhoto').val('');
							$('.popout').addClass('popout-error').html(height_min_alert).fadeIn(500).delay(5000).fadeOut();
							return false;
						}

						$('.previewPhoto').css({backgroundImage: 'url('+e.target.result+')'}).show();
						$('.filer-input-dragDrop').addClass('hoverClass');
						var _filname =  oFile.name;
					var fileName = _filname.substr(0, _filname.lastIndexOf('.'));
					};// <<--- image.onload


					 }

					 oFReader.readAsDataURL($(this)[0].files[0]);

		}
	} else{
		$('.popout').html('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.').fadeIn(500).delay(5000).fadeOut();
		return false;
	}
	});

		$('input[type="file"]').attr('title', window.URL ? ' ' : '');

		CKEDITOR.replace('description', {
					// Define the toolbar groups as it is a more accessible solution.

					extraPlugins: 'autogrow,image2,embed,youtube',
					removePlugins: 'resize',
					embed_provider : '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
					enterMode: CKEDITOR.ENTER_BR,

					// Toolbar adjustments to simplify the editor.
		 toolbar: [{
				 name: 'document',
				 items: ['Undo', 'Redo']
			 },
			 {
				 name: 'basicstyles',
				 items: ['Bold', 'Italic', 'Strike', 'Underline', '-', 'RemoveFormat']
			 },
			 {
				 name: 'links',
				 items: ['Link', 'Unlink', 'Anchor']
			 },
			 {
				 name: 'paragraph',
				 items: ['BulletedList', 'NumberedList']
			 },
			 {
				 name: 'insert',
				 items: ['Image', 'Youtube', 'Embed']
			 },
			 {
				 name: 'tools',
				 items: ['Maximize', 'ShowBlocks']
			 }
		 ],

			// Upload dropped or pasted images to the CKFinder connector (note that the response type is set to JSON).
			filebrowserImageUploadUrl : urlImageEditor,
			filebrowserUploadMethod: 'xhr',

					// Remove the redundant buttons from toolbar groups defined above.
					removeButtons: 'Subscript,Superscript,Anchor,Styles,Specialchar',
				});

				var data = CKEDITOR.instances.description.getData();

})(jQuery);
