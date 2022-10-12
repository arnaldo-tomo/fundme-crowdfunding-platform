(function($) {
	"use strict";

$(document).on('click','#nextDatabase',function(s) {
 $(this).hide();
 $('#headerRequirements, #containerRequirements').hide();
 $('#containerSetupDatabase, #headerSetupDatabase').show();
});

	$(document).on('click','#setupDatabase',function(s) {

	s.preventDefault();
	var element = $(this);
	element.attr({'disabled' : 'true'});
	element.html('<i class="spinner-border spinner-border-sm align-middle mr-1"></i> Installing...');

	(function(){
		 $('#formSetupDatabase').ajaxForm({
		 dataType : 'json',
		 success:  function(result) {

			 if (result.success) {

				 $('#containerSetupDatabase, #headerSetupDatabase').hide();
				 $('#SuccessInstaller').show();

			 } else {

				 if (result.errors) {

					 var error = '';
					 var $key = '';

					 for($key in result.errors) {
						 error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
					 }

					 $('#showErrors').html(error);
					 $('#errors').show();
					 element.removeAttr('disabled');
					 element.html('Install');
				 }
			 }

			},
			error: function(responseText, statusText, xhr, $form) {
					// error
					element.removeAttr('disabled');
					element.html('Install');
					swal({
							type: 'error',
							title: 'Oops',
							text: 'Error occurred ('+xhr+')',
						});
			}
		}).submit();
	})(); //<--- FUNCTION %
});//<<<-------- * END FUNCTION CLICK * ---->>>>

$(document).on('click','#createUser',function(s) {

s.preventDefault();
var element = $(this);
element.attr({'disabled' : 'true'});
element.html('<i class="spinner-border spinner-border-sm align-middle mr-1"></i> Please wait...');

(function(){
	 $('#formCreateUser').ajaxForm({
	 dataType : 'json',
	 success:  function(result) {

		 if (result.success) {
			 window.location.href = result.url;
		 }
		},
		error: function(responseText, statusText, xhr, $form) {
				// error
				element.removeAttr('disabled');
				element.html('Install');
				swal({
						type: 'error',
						title: 'Oops',
						text: 'Error occurred ('+xhr+')',
					});
		}
	}).submit();
})(); //<--- FUNCTION %
});//<<<-------- * END FUNCTION CLICK * ---->>>>

})(jQuery);
