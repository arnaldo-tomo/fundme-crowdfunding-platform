//<--------- waiting -------//>
(function($){
	"use strict";
$.fn.waiting = function( p_delay ){
	var $_this = this.first();
	var _return = $.Deferred();
	var _handle = null;

	if ( $_this.data('waiting') != undefined ) {
		$_this.data('waiting').rejectWith( $_this );
		$_this.removeData('waiting');
	}
	$_this.data('waiting', _return);

	_handle = setTimeout(function(){
		_return.resolveWith( $_this );
	}, p_delay );

	_return.fail(function(){
		clearTimeout(_handle);
	});

	return _return.promise();
};

})(jQuery);

// pagination
function pagination($url, $msgerror) {
	$(document).on('click','.loadPaginator', function(r) {
		r.preventDefault();
	  $(this).addClass('disabled').html('<span class="spinner-border"></span>');

				var page = $(this).attr('href').split('page=')[1];
				$.ajax({
					url: $url + page,
				}).done(function(data){
					if( data ) {
						$('.loadPaginator').remove();

						$( data ).appendTo( "#campaigns" );
					} else {
						alert( $msgerror );
					}
					//<**** - Tooltip
				});
		});
}

(function($) {
"use strict";

//************* READMORE
(function(c){function g(b,a){this.element=b;this.options=c.extend({},h,a);c(this.element).data("max-height",this.options.maxHeight);c(this.element).data("height-margin",this.options.heightMargin);delete this.options.maxHeight;if(this.options.embedCSS&&!k){var d=".readmore-js-toggle, .readmore-js-section { "+this.options.sectionCSS+" } .readmore-js-section { overflow: hidden; }",e=document.createElement("style");e.type="text/css";e.styleSheet?e.styleSheet.cssText=d:e.appendChild(document.createTextNode(d));
document.getElementsByTagName("head")[0].appendChild(e);k=!0}this._defaults=h;this._name=f;this.init()}var f="readmore",h={speed:100,maxHeight:200,heightMargin:16,moreLink:'<a href="#">Read More</a>',lessLink:'<a href="#">Close</a>',embedCSS:!0,sectionCSS:"display: block; width: 100%;",startOpen:!1,expandedClass:"readmore-js-expanded",collapsedClass:"readmore-js-collapsed",beforeToggle:function(){},afterToggle:function(){}},k=!1;g.prototype={init:function(){var b=this;c(this.element).each(function(){var a=
c(this),d=a.css("max-height").replace(/[^-\d\.]/g,"")>a.data("max-height")?a.css("max-height").replace(/[^-\d\.]/g,""):a.data("max-height"),e=a.data("height-margin");"none"!=a.css("max-height")&&a.css("max-height","none");b.setBoxHeight(a);if(a.outerHeight(!0)<=d+e)return!0;a.addClass("readmore-js-section "+b.options.collapsedClass).data("collapsedHeight",d);a.after(c(b.options.startOpen?b.options.lessLink:b.options.moreLink).on("click",function(c){b.toggleSlider(this,a,c)}).addClass("readmore-js-toggle"));
b.options.startOpen||a.css({height:d})});c(window).on("resize",function(a){b.resizeBoxes()})},toggleSlider:function(b,a,d){d.preventDefault();var e=this;d=newLink=sectionClass="";var f=!1;d=c(a).data("collapsedHeight");c(a).height()<=d?(d=c(a).data("expandedHeight")+"px",newLink="lessLink",f=!0,sectionClass=e.options.expandedClass):(newLink="moreLink",sectionClass=e.options.collapsedClass);e.options.beforeToggle(b,a,f);c(a).animate({height:d},{duration:e.options.speed,complete:function(){e.options.afterToggle(b,
a,f);c(b).replaceWith(c(e.options[newLink]).on("click",function(b){e.toggleSlider(this,a,b)}).addClass("readmore-js-toggle"));c(this).removeClass(e.options.collapsedClass+" "+e.options.expandedClass).addClass(sectionClass)}})},setBoxHeight:function(b){var a=b.clone().css({height:"auto",width:b.width(),overflow:"hidden"}).insertAfter(b),c=a.outerHeight(!0);a.remove();b.data("expandedHeight",c)},resizeBoxes:function(){var b=this;c(".readmore-js-section").each(function(){var a=c(this);b.setBoxHeight(a);
(a.height()>a.data("expandedHeight")||a.hasClass(b.options.expandedClass)&&a.height()<a.data("expandedHeight"))&&a.css("height",a.data("expandedHeight"))})},destroy:function(){var b=this;c(this.element).each(function(){var a=c(this);a.removeClass("readmore-js-section "+b.options.collapsedClass+" "+b.options.expandedClass).css({"max-height":"",height:"auto"}).next(".readmore-js-toggle").remove();a.removeData()})}};c.fn[f]=function(b){var a=arguments;if(void 0===b||"object"===typeof b)return this.each(function(){if(c.data(this,
"plugin_"+f)){var a=c.data(this,"plugin_"+f);a.destroy.apply(a)}c.data(this,"plugin_"+f,new g(this,b))});if("string"===typeof b&&"_"!==b[0]&&"init"!==b)return this.each(function(){var d=c.data(this,"plugin_"+f);d instanceof g&&"function"===typeof d[b]&&d[b].apply(d,Array.prototype.slice.call(a,1))})}})(jQuery);


jQuery.fn.reset = function () {
	$(this).each (function() { this.reset(); });
}

function scrollElement( element ){
	var offset = $(element).offset().top;
	$('html, body').animate({scrollTop:offset}, 500);
};

	// Owl Carousel
	$('.owl-carousel').owlCarousel({
		margin:10,
		items : categoriesCount,
		responsive: {
			0:{
						items:1
				},
				600:{
						items:2
				},
				1000:{
						items:4
				}
		}
	});

function escapeHtml( unsafe ) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

//<-------- * TRIM * ----------->
function trim ( string ) {
	return string.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

//<--------- * Search * ----------------->
$('#buttonSearch, #btnSearch').on('click',function(e){
		var search    = $('#btnItems').val();
		if( trim( search ).length < 2  || trim( search ).length == 0 || trim( search ).length > 100 ) {
			return false;
		} else {
			return true;
		}
	});//<---

	$('#btnSearch_2').on('click',function(e) {
		var search    = $('#btnItems_2').val();
		if( trim( search ).length < 2  || trim( search ).length == 0 || trim( search ).length > 100 ) {
			return false;
		} else {
			return true;
		}
	});//<---

$(document).ready(function() {

	jQuery(".timeAgo").timeago();

//================= * Input Click * ===================//
$(document).on('click','#avatar_file',function () {
		var _this = $(this);
	    $("#uploadAvatar").trigger('click');
	     _this.blur();
	});

	$('#cover_file').on('click',function () {
		var _this = $(this);
	    $("#uploadCover").trigger('click');
	     _this.blur();
	});

//======== INPUT CLICK ATTACH MESSAGES =====//
$(document).on('click','#upload_image',function () {
		var _this = $(this);
	    $("#uploadImage").trigger('click');
	     _this.blur();
	});

	$(document).on('click','#upload_file',function () {
		var _this = $(this);
	    $("#uploadFile").trigger('click');
	     _this.blur();
	});

	$(document).on('click','#shotPreview',function () {
		var _this = $(this);
	    $("#fileShot").not('.edit_post').trigger('click');
	     _this.blur();
	});

	$(document).on('click','#attachFile',function () {
		var _this = $(this);
	    $("#attach_file").trigger('click');
	     _this.blur();
	});

$(document).on('mouseenter','.deletePhoto, .deleteCover, .deleteBg', function(){

   	 var _this   = $(this);
   	 $(_this).html('<div class="photo-delete"></div>');
 });

 $(document).on('mouseleave','.deletePhoto, .deleteCover, .deleteBg', function(){

   	 var _this   = $(this);
   	 $(_this).html('');
 });


/*---------
 *
 * Credit : http://stackoverflow.com/questions/4459379/preview-an-image-before-it-is-uploaded
 * --------
 **/

//<---------- * Avatar * ------------>>
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#upload-avatar').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }//<------ End Function ---->

    //<---- * Avatar * ----->
    $("#file-avatar").change(function(){
        readURL(this);
    });

    //<---------- * Cover * ------------>>
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#upload-cover').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }//<------ End Function ---->

    //<---- * Avatar * ----->
    $("#file-cover").change(function(){
        readURL2(this);
    });

	//<**** - Tooltip
    $('.showTooltip').tooltip();

    $('.delete-attach-image').on('click',function(){
    	$('.imageContainer').fadeOut(100);
    	$('#previewImage').css({ backgroundImage : 'none'});
    	$('.file-name').html('');
    	$('#uploadImage').val('');

    });

    $('.delete-attach-file').on('click',function(){
    	$('.fileContainer').fadeOut(100);
    	$('#previewFile').css({ backgroundImage : 'none'});
    	$('.file-name-file').html('');
    	$('#uploadFile').val('');
    });

    $('.delete-attach-file-2').on('click',function(){
    	$('.fileContainer').fadeOut(100);
    	$('.file-name-file').html('');
    	$('#attach_file').val('');
    });

    $("#saveUpdate").on('click',function(){
    	$(this).css({'display': 'none'})
    });

    $("#paypalPay").on('click',function(){
    	$(this).css({'display': 'none'})
    });

  // Miscellaneous Functions

  /*= Like =*/
	$(".likeButton").on('click',function(e){
	var element     = $(this);
	var id          = element.attr("data-id");
	var like        = element.attr('data-like');
	var like_active = element.attr('data-unlike');
	var data        = 'id=' + id;

	e.preventDefault();

	element.blur();

	element.html('<i class="spinner-border spinner-border-sm align-baseline text-muted"></i>');

		 $.ajax({
		 	headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
		   type: "POST",
		   url: URL_BASE+"/ajax/like",
		   data: data,
		   success: function( result ){

		   	if( result == '') {
			   	  window.location.reload();
			   	  element.removeClass('likeButton');
			   	  element.removeClass('active');
		   	} else {

					if( element.hasClass( 'active' ) ) {
						   	  element.removeClass('active');
									element.html('<i class="far fa-heart align-baseline text-success"></i>');

						} else {
							element.addClass('active');
							element.html('<i class="fa fa-heart align-baseline text-success"></i>');
						}
		   		$('#countLikes').html(result);
		   	}
		 }//<-- RESULT
	   });//<--- AJAX


});//<----- CLICK

    // ====== FOLLOW HOVER ============
    $(document).on('mouseenter', '.activeFollow' ,function(){

	var following = $(this).attr('data-following');

	// Unfollow
	$(this).html( '<i class="far fa-times-circle mr-2"></i> ' + following);
	 })

	$(document).on('mouseleave', '.activeFollow' ,function() {
		var following = $(this).attr('data-following');
	 	$(this).html( '<i class="glyphicon glyphicon-ok myicon-right"></i> ' + following);
	 });

	 /*========= FOLLOW =============*/
	$(document).on('click',".followBtn",function(){
	var element    = $(this);
	var id         = element.attr("data-id");
	var _follow    = element.attr("data-follow");
	var _following = element.attr("data-following");
	var info       = 'id=' + id;

	element.removeClass( 'followBtn' );

	if( element.hasClass( 'follow_active activeFollow' ) ) {
		element.addClass( 'followBtn' );
		   	element.removeClass( 'follow_active activeFollow' );
		   element.html( '<i class="glyphicon glyphicon-plus myicon-right"></i> ' + _follow );
		   element.blur();

		}
		else {

			element.addClass( 'followBtn' );
		   	  element.removeClass( 'follow_active activeFollow' );
		   	    element.addClass( 'followBtn' );
		   	   element.addClass( 'follow_active activeFollow' );
		   	  element.html( '<i class="glyphicon glyphicon-ok myicon-right"></i> ' + _following );
		   	  element.blur();
		}

		 $.ajax({
		   type: "POST",
		   url: URL_BASE+"/ajax/follow",
		   dataType: 'json',
		   data: info,
		   success: function( result ){

		   	if( result.status == false ) {
		   		element.addClass( 'followBtn' );
			   	  element.removeClass( 'follow_active followBtn activeFollow' );
			   	   element.html( '<i class="glyphicon glyphicon-plus myicon-right"></i> ' + _follow );
			   	  element.html( type );
			   	  window.location.reload();
			   	  element.blur();
		   	}
		 }//<-- RESULT
	   });//<--- AJAX


});//<----- CLICK

// ====== FOLLOW HOVER BUTTONS SMALL ============
    $(document).on('mouseenter', '.btnFollowActive' ,function(){

	var following = $(this).attr('data-following');

	// Unfollow
	$(this).html( '<i class="far fa-times-circle mr-2"></i> ' + following);
	$(this).addClass('btn-danger').removeClass('btn-info');
	 })

	$(document).on('mouseleave', '.btnFollowActive' ,function() {
		var following = $(this).attr('data-following');
	 	$(this).html( '<i class="glyphicon glyphicon-ok myicon-right"></i> ' + following);
	 });

/*========= FOLLOW BUTTONS SMALL =============*/
	$(document).on('click',".btnFollow",function(){
	var element    = $(this);
	var id         = element.attr("data-id");
	var _follow    = element.attr("data-follow");
	var _following = element.attr("data-following");
	var info       = 'id=' + id;

	element.removeClass( 'btnFollow' );

	if( element.hasClass( 'btnFollowActive' ) ) {
		element.addClass( 'btnFollow' );
		   	element.removeClass( 'btnFollowActive' );
		   element.html( '<i class="glyphicon glyphicon-plus myicon-right"></i> ' + _follow );
		   element.blur();

		}
		else {

			element.addClass( 'btnFollow' );
		   	  element.removeClass( 'btnFollowActive' );
		   	    element.addClass( 'btnFollow' );
		   	   element.addClass( 'btnFollowActive' );
		   	  element.html( '<i class="glyphicon glyphicon-ok myicon-right"></i> ' + _following );
		   	  element.blur();
		}


		 $.ajax({
		   type: "POST",
		   url: URL_BASE+"/ajax/follow",
		   dataType: 'json',
		   data: info,
		   success: function( result ){

		   	if( result.status == false ) {
		   		element.addClass( 'btnFollow' );
			   	  element.removeClass( 'btnFollowActive followBtn' );
			   	   element.html( '<i class="glyphicon glyphicon-plus myicon-right"></i> ' + _follow );
			   	  element.html( type );
			   	  bootbox.alert( result.error  );
			   	  window.location.reload();
			   	  element.blur();
		   	}
		 }//<-- RESULT
	   });//<--- AJAX
});//<----- CLICK


			//<---------- * Remove Reply * ---------->
	  	 $(document).on('click','.removeMsg',function(){

	  	 	var element   = $(this);
	  	 	var data      = element.attr('data');
	  	 	var deleteMsg = element.attr('data-delete');
	  	 	var query     = 'message_id='+data;

	  	bootbox.confirm(deleteMsg, function(r) {

	  		if( r == true ) {

	  	 	element.parents('li').fadeTo( 200,0.00, function(){
   		             element.parents('li').slideUp( 200, function(){
   		  	           element.parents('li').remove();
   		              });
   		           });

	  	 	$.ajax({
	  	 		type : 'POST',
	  	 		url  : URL_BASE+'/message/delete',
	  	 		dataType: 'json',
	  	 		data : query,

	  	 	}).done(function( data ){

	  	 		if( data.total == 0 ) {
	  	 			var location = URL_BASE+"/messages";
   					window.location.href = location;
	  	 		}

	  	 		if( data.status != true ) {
	  	 			bootbox.alert(data.error);
	  	 			return false;
	  	 		}

	  	 		if( data.session_null ) {
					window.location.reload();
				}
	  	 	});//<--- Done
	  	 	}//END IF R TRUE
	  }); //Jconfirm
	 });//<---- * End click * ---->

	 $('button[type=submit], input[type=submit]').not('.btn_search,.actionDelete, .btn-auth').on('click',function(){
	 	$('.wrap-loader').show();
	 });

	 $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  e.target // newly activated tab
  e.relatedTarget // previous active tab
});

//<---------------- Create Campaign ----------->>>>
			$(document).on('click','#buttonFormSubmit',function(s) {

				for (var instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
			}

				s.preventDefault();
				var element = $(this);
				var spinner = '<span class="spinner-border spinner-border-sm align-baseline mr-2"></span>';
				var create = element.attr('data-create');
				var send = spinner + ' ' +element.attr('data-send');
				var $error = element.attr('data-error');

				element.attr({'disabled' : 'true'});
				element.html(send);


				(function(){
					 $("#formUpload").ajaxForm({
					 dataType : 'json',
					 error: function(responseText, statusText, xhr, $form) {
					 	element.html(create);
					 	element.removeAttr('disabled');

						if(!xhr) {
							$errorMsg = $error;
						} else {
							$errorMsg = xhr;
						}
					 	$('.popout').addClass('popout-error').html($errorMsg).fadeIn('500').delay('5000').fadeOut('500');
					 },
					 success:  function(result){

					 //===== SUCCESS =====//
					 if( result.success != false ) {
					 	window.location.href = result.target;
						}//<-- e
					else {
						var error = '';
              for(var $key in result.errors ) {
              	error += '<li><i class="far fa-times-circle mr-2"></i> ' + result.errors[$key] + '</li>';
              }
							$('#showErrors').html(error);
							$('#dangerAlert').fadeIn(500)
							$('.wrap-loader').hide();
							element.html(create);
							element.removeAttr('disabled');
							}
						}//<----- SUCCESS
					}).submit();
				})(); //<--- FUNCTION %
			});//<<<-------- * END FUNCTION CLICK * ---->>>>
	//<---------------- End Create Campaign ----------->>>>


	     //<<<<<---------------- Contact Organizer ----------->>>>
			$(document).on('click','#buttonFormSubmitContact',function(s) {

				s.preventDefault();
				var element = $(this);
				element.attr({'disabled' : 'true'});
				element.find('i').addClass('spinner-border spinner-border-sm mr-1');

				(function(){
					 $("#formContactOrganizer").ajaxForm({
					 dataType : 'json',
					 success:  function(result){

					 	if( result.error_fatal ) {
					 		$('#showErrors').html('<li><i class="far fa-times-circle mr-2"></i> ' + error + '</li>');
							$('#dangerAlert').fadeIn(500)
							element.find('i').removeClass('spinner-border spinner-border-sm mr-1');
							element.removeAttr('disabled');
							return false;
					 	}

					 //===== SUCCESS =====//
					 if( result.success != false ){
					 	$('#formContactOrganizer').remove();
					 	$('#showSuccess').html('<i class="fa fa-check myicon-right"></i>  ' + result.msg);
						$('#successAlert').fadeIn(500)
						element.find('i').removeClass('spinner-border spinner-border-sm mr-1');

							setTimeout(function() {
								$('#sendEmail').modal('hide');
							}, 3000);

						}//<-- e
					else {
						var error = '';
              for(var $key in result.errors ){
              	error += '<li><i class="far fa-times-circle mr-2"></i> ' + result.errors[$key] + '</li>';
              }
						$('#showErrors').html(error);
						$('#dangerAlert').fadeIn(500)
						element.find('i').removeClass('spinner-border spinner-border-sm mr-1');
						element.removeAttr('disabled');
						}
						}//<----- SUCCESS
					}).submit();
				})(); //<--- FUNCTION %
			});//<<<-------- * END FUNCTION CLICK * ---->>>>

	//<---------------- Edit Campaign ----------->>>>
			$(document).on('click','#buttonFormUpdate',function(s){

				for (var instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
			}

				s.preventDefault();
				var element = $(this);
				var spinner = '<span class="spinner-border spinner-border-sm align-baseline mr-2"></span>';
				var create = element.attr('data-create');
				var send = spinner + ' ' +element.attr('data-send');

				element.attr({'disabled' : 'true'});
				element.html(send);

				(function(){
					 $("#formUpdate").ajaxForm({
					 dataType : 'json',
					 success:  function(result){

					 	if( result.fatalError == true ) {
					 		window.location.href = result.target;
					 		return false;
					 	}

					 //===== SUCCESS =====//
					 if( result.success != false ){

					 	if( result.finish_campaign == true ) {
					 		window.location.href = result.target;
					 	} else {
					 		element.html(create);
					 		$('#dangerAlert').fadeOut();
						 	element.removeAttr('disabled');
						 	$('#successAlert').fadeIn(500);
					 	}

						}//<-- e
					else {
						var error = '';
                        for(var $key in result.errors ){
                        	error += '<li><i class="far fa-times-circle mr-2"></i> ' + result.errors[$key] + '</li>';
                        }
						$('#showErrors').html(error);
						$('#successAlert').fadeOut();
						$('#dangerAlert').fadeIn(500)
						element.html(create);
						element.removeAttr('disabled');
						}
						}//<----- SUCCESS
					}).submit();
				})(); //<--- FUNCTION %
			});//<<<-------- * END FUNCTION CLICK * ---->>>>
	//<---------------- End Edit Campaign ----------->>>>

	//<<<-------- * FUNCTION CLICK * ---->>>>
			$(document).on('click','#buttonUpdateForm',function(s){

				s.preventDefault();
				var element = $(this);
				var spinner = '<span class="spinner-border spinner-border-sm align-baseline mr-2"></span>';
				var create = element.attr('data-create');
				var send = spinner + ' ' +element.attr('data-send');

				element.attr({'disabled' : 'true'});
				element.html(send);

				(function(){
					 $("#formUpdateCampaign").ajaxForm({
					 dataType : 'json',
					 success:  function(result){

					 //===== SUCCESS =====//
					 if( result.success != false ){

						 if( result.rewards == true ) {
							 $('#successAlert').fadeIn();
							 $('#dangerAlert').fadeOut();
							 $("#formUpdateCampaign").reset();
	 						element.removeAttr('disabled');
							element.html(create);
						} else {
							window.location.href = result.target;
						}

				}//<-- e
					else {
						var error = '';
                        for(var $key in result.errors ){
                        	error += '<li><i class="far fa-times-circle mr-2"></i> ' + result.errors[$key] + '</li>';
                        }
						$('#showErrors').html(error);
						$('#successAlert').fadeOut();
						$('#dangerAlert').fadeIn(500)
						element.html(create);
						element.removeAttr('disabled');
						}
						}//<----- SUCCESS
					}).submit();
				})(); //<--- FUNCTION %
			});//<<<-------- * END FUNCTION CLICK * ---->>>>

}); //*************** End DOM ***************************//

$(function () {
    $('a[href="#search"]').on('click', function(event) {
        event.preventDefault();
        $('#search').addClass('open');
        $('#search > form > input[type="text"]').focus();
        $('body').css({overflow:'hidden'})
    });

    $('#search, a.close').on('click keyup', function(event) {
        if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
            $('#search ').removeClass('open');
            $('body').css({overflow:'auto'});
            $('#search > form > input[type="search"]').blur();
        }
    });

});

function textTruncate( element, text ){
	var descHeight = $(element).outerHeight();

	 if( descHeight > 1000 ) {
	 	$(element).addClass('truncate').append('<span class="btn-block text-center color-default font-default readmoreBtn"><strong>'+text+'</strong></span>');
	 }

	 $(document).on('click','.readmoreBtn', function(){
	 	$(element).removeClass('truncate');
	 	$(this).remove();
 	});
}//<<--- End

textTruncate('#desc', ' '+ReadMore);

		$(".navbar-toggler").on('click', function() {
			$('.collapsing').toggleClass('show');
			$('body').addClass("sidebar-overlay overflow-hidden");
		});

		// Close Menu
		$(".close-menu").on('click', function() {
			$('body').removeClass("sidebar-overlay overflow-hidden");
		});

			// Counter
			$('.counter').counterUp({
				delay: 10,
				time: 1000
			});

			// Parallax
			$('.parallax-cover').parallax("50%", 0.3);

/* Scroll Header */
$(function () {
  $(document).scroll(function () {
    var $nav = $(".scroll");
    $nav.toggleClass('shadow-sm bg-dark', $(this).scrollTop() > $nav.height());
  });
});


// Cookies
$(document).ready(function() {
	if (Cookies.get('cookieBanner'));
	else {
		$('.showBanner').fadeIn();
			$("#close-banner").on('click', function() {
					$(".showBanner").slideUp(50);
					Cookies.set('cookieBanner', true, { expires: 365 });
				});
			}
		});

	$( ".selectReward" ).on('mouseenter', function() {
     $(this).find('.cardSelectRewardBox').fadeIn();
  })
  .on('mouseleave', function() {
    $(this).find('.cardSelectRewardBox').fadeOut();
  });

	// Copy Code Embed
	$(document).on('click','#btn_copy_code', function(){
					copyToClipboard('#embedCode',this);
			});
			// Copy Link Campaign
			$('#btn_campaign_url').click(function(){
							copyToClipboard('#url_campaign',this);
					});

			function copyToClipboard(element,btn) {
					var $temp = $('<input>');
					$("body").append($temp);
					$temp.val($(element).val()).select();
					$(element).select().focus();
					document.execCommand("copy");
					$(btn).html('<i class="fa fa-check"></i> '+copied).removeClass('btn-primary').addClass('btn-success');
					$temp.remove();
					}

					// Select text copy url
					$("#embedCode,#url_campaign").on('click', function() {
						var $this = $(this);
					    $this.select();
							});

							// Delete campaign
							$("#deleteCampaign").on('click', function(e) {
							   	e.preventDefault();

							  var element = $(this);
								var url     = element.attr('data-url');

								element.blur();

								swal(
									{   title: delete_confirm,
									 text: confirm_delete_campaign,
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

									 $(".onlyNumber").keypress(function(event) {
											 return /\d/.test(String.fromCharCode(event.keyCode));
									 });

									 // Date picker Rewards
								     $('#datepickerRewards').datepicker({
								       autoclose: true,
								       format: 'yyyy-mm',
								       startView: "months",
								       minViewMode: "months",
								       startDate: '+1m',
								       language: 'en'
								     });

										 // Delete Reward
								      $(".actionDelete").on('click', function(e) {

								       	e.preventDefault();

								       	var element = $(this);
								    	   var url     = element.attr('href');
								    	    var form    = $(element).parents('form');

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

})(jQuery);
