(function($) {
	"use strict";

	$(document).on('click','#updates .loadPaginator', function(r){
		r.preventDefault();
		 $(this).addClass('disabled').html('<span class="spinner-border"></span>');

				var page = $(this).attr('href').split('page=')[1];
				$.ajax({
					url: URL_BASE+'/ajax/campaign/updates?id='+campaignId+'&page=' + page
				}).done(function(data){
					if( data ) {
						$('#updates .loadPaginator').remove();

						$( data ).appendTo( "#listUpdates" );
						jQuery(".timeAgo").timeago();
					} else {
						alert(error);
					}
					//<**** - Tooltip
				});
		});

	$(document).on('click','#donations .loadPaginator', function(e){
				e.preventDefault();
			$(this).addClass('disabled').html('<span class="spinner-border"></span>');

				var page = $(this).attr('href').split('page=')[1];
				$.ajax({
					url: URL_BASE+'/ajax/donations?id='+campaignId+'&page=' + page
				}).done(function(data){
					if( data ) {
						$('#donations .loadPaginator, .wrap-paginator').remove();

						$( data ).appendTo( "#listDonations" );
						jQuery(".timeAgo").timeago();
					} else {
						alert(error);
					}
					//<**** - Tooltip
				});
			});

})(jQuery);
