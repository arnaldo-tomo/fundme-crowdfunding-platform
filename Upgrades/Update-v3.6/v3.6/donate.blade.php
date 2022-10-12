@extends('app')

@section('title'){{ trans('misc.donate').' - '.$response->title.' - ' }}@endsection

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />

<style>
/**
 * The CSS shown here will not be introduced in the Quickstart guide, but shows
 * how you can use CSS to style your Element's container.
 */
.StripeElement {
  box-sizing: border-box;

  height: 46px;

  padding: 14px 12px;

  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: white;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.StripeElement--focus {
	border-color: #f45302;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>

@endsection

@section('content')

<div class="jumbotron md header-donation jumbotron_set">
      <div class="container wrap-jumbotron position-relative">
      	<h2 class="title-site">{{ trans('misc.donate') }}</h2>
      	<p class="subtitle-site"><strong>{{$response->title}}</strong></p>
      </div>
    </div>

<div class="container margin-bottom-40 padding-top-40">

<!-- Col MD -->
<div class="col-md-8 margin-bottom-20">

	   <!-- form start -->
    <form method="POST" action="{{ url('donate', $response->id) }}" enctype="multipart/form-data" id="formDonation">

    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
    	<input type="hidden" name="_id" value="{{ $response->id }}">
			@if(isset($pledge))
				<input id="_pledge" type="hidden" name="_pledge" value="{{ $pledge->id }}">
			@endif

      @guest
        @captcha
      @endguest

			<div class="form-group">
				    <label>{{ trans('misc.enter_your_donation') }}</label>
				    <div class="input-group has-success">
				      <div class="input-group-addon addon-dollar">{{$settings->currency_symbol}}</div>
				      <input type="number" min="{{$settings->min_donation_amount}}"  autocomplete="off" id="onlyNumber" class="form-control input-lg" name="amount" @if( isset($pledge) )readonly='readonly'@endif value="@if( isset($pledge) ){{$pledge->amount}}@endif" placeholder="{{trans('misc.minimum_amount')}} @if($settings->currency_position == 'left') {{$settings->currency_symbol.$settings->min_donation_amount}} @else {{$settings->min_donation_amount.$settings->currency_symbol}} @endif {{$settings->currency_code}}">
				    </div>
				  </div>


                 <!-- Start -->
                    <div class="form-group">
                      <label>{{ trans('auth.full_name') }}</label>
                        <input type="text" id="cardholder-name" value="@if( Auth::check() ){{Auth::user()->name}}@endif" name="full_name" class="form-control input-lg" placeholder="{{ trans('misc.first_name_and_last_name') }}">
                    </div><!-- /. End-->

                    <!-- Start -->
                    <div class="form-group">
                      <label>{{ trans('auth.email') }}</label>
                        <input type="text" id="cardholder-email" value="@if( Auth::check() ){{Auth::user()->email}}@endif" name="email" class="form-control input-lg" placeholder="{{ trans('auth.email') }}">
                    </div><!-- /. End-->

              <div class="row form-group">
                  <!-- Start -->
                    <div class="col-xs-6">
                      <label>{{ trans('misc.country') }}</label>
                      	<select id="country" name="country" class="form-control input-lg" >
                      		<option value="">{{trans('misc.select_one')}}</option>
                      	@foreach(  App\Models\Countries::orderBy('country_name')->get() as $country )
                            <option @if( Auth::check() && Auth::user()->countries_id == $country->id ) selected="selected" @endif value="{{$country->country_name}}">{{ $country->country_name }}</option>
						@endforeach
                          </select>
                  </div><!-- /. End-->

                  <!-- Start -->
                    <div class="col-xs-6">
                      <label>{{ trans('misc.postal_code') }}</label>
                        <input type="text" id="postal_code" value="{{ old('postal_code') }}" name="postal_code" class="form-control input-lg" placeholder="{{ trans('misc.postal_code') }}">
                    </div><!-- /. End-->

              </div><!-- row form-control -->

                  <!-- Start -->
                    <div class="form-group">
                        <input type="text" id="comment" value="{{ old('comment') }}" name="comment" class="form-control input-lg" placeholder="{{ trans('misc.leave_comment') }}">
                    </div><!-- /. End-->

										<!-- Start -->
	                    <div class="form-group">
												<label>{{ trans('misc.payment_gateway') }}</label>
													<select name="payment_gateway" id="paymentGateway" class="form-control input-lg" >
															<option value="">{{trans('misc.select_one')}}</option>

															@foreach (PaymentGateways::where('enabled', '1')->orderBy('name')->get(); as $payment)

																@php

																if($payment->type == 'card' ) {
																	$paymentName = trans('misc.debit_credit_card') . ' ('.$payment->name.')';
																} elseif ($payment->type == 'bank') {
																	$paymentName = trans('misc.bank_transfer');
																} else {
																	$paymentName = trans('misc.pay_through').' '.$payment->name;
																}

																@endphp
																<option value="{{$payment->id}}">{{$paymentName}}</option>
															@endforeach

														</select>
	                    </div><!-- /. End-->

											@if($_bankTransfer)

											<div class="btn-block display-none" id="bankTransferBox">
												<div class="alert alert-info">
												<h4><strong><i class="fa fa-bank"></i> {{trans('misc.make_payment_bank')}}</strong></h4>
												<ul class="list-unstyled">
														<li>
															{!!nl2br($_bankTransfer->bank_info)!!}
														</li>
												</ul>
											</div>

											<div class="row form-group">
				                  <!-- Start -->
				                    <div class="col-sm-12">
															<label>{{ trans('admin.bank_transfer_details') }}</label>
				                        <textarea name="bank_transfer" rows="4" class="form-control input-lg" placeholder="{{ trans('admin.bank_transfer_info') }}"></textarea>
				                  </div><!-- /. End-->
				              </div><!-- row form-control -->

											</div><!-- Alert -->
											@endif

											<div id="stripeContainer" class="display-none">
												<div id="card-element" class="margin-bottom-10">
													<!-- A Stripe Element will be inserted here. -->
												</div>
												<!-- Used to display form errors. -->
												<div id="card-errors" class="alert alert-danger display-none" role="alert"></div>
											</div>


        <div class="form-group checkbox icheck">
				<label class="margin-zero">
					<input class="no-show" id="anonymous" name="anonymous" type="checkbox" value="1">
					<span class="margin-lft5 keep-login-title">{{ trans('misc.anonymous_donation') }}</span>
			</label>
		</div>
                    <!-- Alert -->
            <div class="alert alert-danger display-none" id="errorDonation">
							<ul class="list-unstyled" id="showErrorsDonation"></ul>
						</div><!-- Alert -->

                  <div class="box-footer text-center">

                    @guest
                      <p class="help-block">
                        <em>{{trans('misc.user_captcha')}} @if($settings->registration_active == 'on')- <a href="{{url('register')}}">{{trans('auth.sign_up')}}</a>@endif - <a href="{{url('login')}}">{{trans('auth.login')}}</a></em>
                      </p>
                    @endguest

                  	<hr />

                    <button type="submit" id="buttonDonation" class="btn-padding-custom btn btn-lg btn-main custom-rounded">{{ trans('misc.donate') }}</button>
                    <div class="btn-block text-center margin-top-20">
			           		<a href="{{url('campaign',$response->id)}}" class="text-muted">
			           		<i class="fa fa-long-arrow-left"></i>	{{trans('auth.back')}}</a>
			           </div>
                  </div><!-- /.box-footer -->

                </form>

 </div><!-- /COL MD -->

 <div class="col-md-4">

	@if( isset($pledge) )
	 <div class="panel panel-default">
  		<div class="panel-body">
				<h3 class="btn-block margin-zero" style="line-height: inherit;">
					{{trans('misc.seleted_pledge')}} <small><a href="{{url('donate',$response->id)}}">{{trans('misc.remove')}}</a></small>
				</h3>
 			<h3 class="btn-block margin-zero" style="line-height: inherit;">
 				<small>{{trans('misc.pledge')}}</small>
 				<strong class="font-default">{{App\Helper::amountFormat($pledge->amount)}}</strong>
 				</h3>
				<h4>{{ $pledge->title }}</h4>
 				<p class="wordBreak">
 					{{ $pledge->description }}
 				</p>

				<small class="btn-block text-muted">
					{{trans('misc.delivery')}}:
				</small>
				<strong>{{ date('F, Y', strtotime($pledge->delivery)) }}</strong>
 		</div><!-- panel-body -->
 	</div><!-- End Panel -->
@endif

	<!-- Start Panel -->
	<div class="panel panel-default">
		<div class="panel-body">
      <img class="img-responsive img-rounded margin-bottom-10" style="display: inline-block;" src="{{url('public/campaigns/small',$response->small_image)}}" />
			<h3 class="btn-block margin-zero" style="line-height: inherit;">
				<strong class="font-default">{{App\Helper::amountFormat($response->donations()->sum('donation'))}}</strong>
				<small>{{trans('misc.of')}} {{App\Helper::amountFormat($response->goal)}} {{strtolower(trans('misc.goal'))}}</small>
				</h3>

				<span class="progress margin-top-10 margin-bottom-10">
					<span class="percentage" style="width: {{$percentage}}%" aria-valuemin="0" aria-valuemax="100" role="progressbar"></span>
				</span>

				<small class="btn-block margin-bottom-10 text-muted">
					{{$percentage }}% {{trans('misc.raised')}} {{trans('misc.by')}} {{number_format($response->donations()->count())}} {{trans_choice('misc.donation_plural',$response->donations()->count())}}
				</small>
		</div>
	</div><!-- End Panel -->

	<!-- Start Panel -->
	 	<div class="panel panel-default">
		  <div class="panel-body">
		    <div class="media none-overflow">

		    	<span class="btn-block text-center margin-bottom-10 text-muted"><strong>{{trans('misc.organizer')}}</strong></span>

				  <div class="media-center margin-bottom-5">
				      <img class="img-circle center-block" src="{{url('public/avatar/',$response->user()->avatar)}}" width="60" height="60" >
				  </div>

				  <div class="media-body text-center">

				    	<h4 class="media-heading">
				    		{{$response->user()->name}}

				    	@if( Auth::guest()  || Auth::check() && Auth::user()->id != $response->user()->id )
				    		<a href="#" title="{{trans('misc.contact_organizer')}}" data-toggle="modal" data-target="#sendEmail">
				    				<i class="fa fa-envelope myicon-right"></i>
				    		</a>
				    		@endif
				    		</h4>

				    <small class="media-heading text-muted btn-block margin-zero">{{trans('misc.created')}} {{ date($settings->date_format, strtotime($response->date) ) }}</small>
				    @if( $response->location != '' )
				    <small class="media-heading text-muted btn-block"><i class="fa fa-map-marker myicon-right"></i> {{$response->location}}</small>
				    @endif
				  </div>
				</div>
		  </div>
		</div><!-- End Panel -->

	<div class="modal fade" id="sendEmail" tabindex="-1" role="dialog" aria-hidden="true">
	     		<div class="modal-dialog modalContactOrganizer">
	     			<div class="modal-content">
	     				<div class="modal-header headerModal headerModalOverlay position-relative" style="background-image: url('{{url('public/campaigns/large',$response->large_image)}}')">
					        <button type="button" class="close closeLight position-relative" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

					        <span class="btn-block margin-top-15 margin-bottom-15 text-center position-relative">
								      <img class="img-circle" src="{{url('public/avatar/',$response->user()->avatar)}}" width="80" height="80" >
								    </span>

							<h5 class="modal-title text-center font-default position-relative" id="myModalLabel">
					        	{{ $response->user()->name }}
					        	</h5>

					        <h4 class="modal-title text-center font-default position-relative" id="myModalLabel">
					        	{{ trans('misc.contact_organizer') }}
					        	</h4>
					     </div><!-- Modal header -->

					      <div class="modal-body listWrap text-center center-block modalForm">

					    <!-- form start -->
				    <form method="POST" class="margin-bottom-15" action="{{ url('contact/organizer') }}" enctype="multipart/form-data" id="formContactOrganizer">
				    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
				    	<input type="hidden" name="id" value="{{ $response->user()->id }}">

					    <!-- Start Form Group -->
	                    <div class="form-group">
	                    	<input type="text" required="" name="name" class="form-control" placeholder="{{ trans('users.name') }}">
	                    </div><!-- /.form-group-->

	                    <!-- Start Form Group -->
	                    <div class="form-group">
	                    	<input type="text" required="" name="email" class="form-control" placeholder="{{ trans('auth.email') }}">
	                    </div><!-- /.form-group-->

	                    <!-- Start Form Group -->
	                    <div class="form-group">
	                    	<textarea name="message" rows="4" class="form-control" placeholder="{{ trans('misc.message') }}"></textarea>
	                    </div><!-- /.form-group-->

	                    <!-- Alert -->
	                    <div class="alert alert-danger display-none" id="dangerAlert">
								<ul class="list-unstyled text-left" id="showErrors"></ul>
							</div><!-- Alert -->

	                   <button type="submit" class="btn btn-lg btn-main custom-rounded" id="buttonFormSubmit">{{ trans('misc.send_message') }}</button>
	                    </form>

	               <!-- Alert -->
	             <div class="alert alert-success display-none" id="successAlert">
								<ul class="list-unstyled" id="showSuccess"></ul>
							</div><!-- Alert -->

					      </div><!-- Modal body -->
	     				</div><!-- Modal content -->
	     			</div><!-- Modal dialog -->
	     		</div><!-- Modal -->

 </div><!-- /COL MD -->

 </div><!-- container wrap-ui -->

 <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modalStripe">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">{{trans('misc.debit_credit_card')}}</h4>
      </div>
      <div class="modal-body">
				<form action="{{url('charge')}}" method="post" id="payment-form">
					@csrf
					<div class="form-row text-center">


						<button id="card-button" class="btn btn-lg btn-main custom-rounded">{{trans('misc.donate')}}</button>
					</div>

				</form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')
<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src='https://js.paystack.co/v1/inline.js'></script>

<script type="text/javascript">

//$('#modalStripe').modal('show')

$('#onlyNumber').focus();

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


    $('input').iCheck({
	  	checkboxClass: 'icheckbox_square-red',
    	radioClass: 'iradio_square-red',
	    increaseArea: '20%' // optional
	  });
});

$('#paymentGateway').on('change', function(){

    if($(this).val() == '3') {
			$('#bankTransferBox').slideDown();
		} else {
				$('#bankTransferBox').slideUp();
		}

		if($(this).val() == '2') {
			$('#stripeContainer').slideDown();
		} else {
			$('#stripeContainer').slideUp();
		}

});


@if(isset($_stripe->key))
// Create a Stripe client.
var stripe = Stripe('{{$_stripe->key}}');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
  base: {
    color: '#32325d',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element.
var cardElement = elements.create('card', {style: style, hidePostalCode: true});

// Add an instance of the card Element into the `card-element` <div>.
cardElement.mount('#card-element');

// Handle real-time validation errors from the card Element.
cardElement.addEventListener('change', function(event) {
  var displayError = document.getElementById('card-errors');
  var payment = $('#paymentGateway').val();

  if(payment == 2) {
    if (event.error) {
  		displayError.classList.remove('display-none');
      displayError.textContent = event.error.message;
      $('#buttonDonation').removeAttr('disabled');
    } else {
  		displayError.classList.add('display-none');
      displayError.textContent = '';
    }
  }

});

var cardholderName = document.getElementById('cardholder-name');
var cardholderEmail = document.getElementById('cardholder-email');
var cardButton = document.getElementById('buttonDonation');

function chargeDonation() {

	//ev.preventDefault();

  var payment = $('#paymentGateway').val();

  if(payment == 2) {

  stripe.createPaymentMethod('card', cardElement, {
    billing_details: {name: cardholderName.value, email: cardholderEmail.value}
  }).then(function(result) {
    if (result.error) {

      if(result.error.type == 'invalid_request_error') {

          if(result.error.code == 'parameter_invalid_empty') {
            $('.popout').addClass('popout-error').html('{{trans('admin.card_required_name_email')}}').fadeIn('500').delay('5000').fadeOut('500');
          } else {
            $('.popout').addClass('popout-error').html(result.error.message).fadeIn('500').delay('5000').fadeOut('500');
          }
      }
      $('#buttonDonation').removeAttr('disabled');

    } else {

      $('#buttonDonation').attr({'disabled' : 'true'});

      // Otherwise send paymentMethod.id to your server
      $('input[name=payment_method_id]').remove();

			var $input = $('<input id=payment_method_id type=hidden name=payment_method_id />').val(result.paymentMethod.id);
			$('#formDonation').append($input);

			$.ajax({
 		 	headers: {
         	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     		},
 		   type: "POST",
			 dataType: 'json',
 		   url:"{{url('payment/stripe/charge')}}",
 		   data: $('#formDonation').serialize(),
 		   success: function(result) {
           handleServerResponse(result);

           if(result.success == false) {
             $('#buttonDonation').removeAttr('disabled');
           }
 		 }//<-- RESULT
 	   })

    }//ELSE
  });
}//PAYMENT STRIPE
}

function handleServerResponse(response) {
  if (response.error) {
    $('.popout').addClass('popout-error').html(response.error).fadeIn('500').delay('5000').fadeOut('500');
    $('#buttonDonation').removeAttr('disabled');

  } else if (response.requires_action) {
    // Use Stripe.js to handle required card action
    stripe.handleCardAction(
      response.payment_intent_client_secret
    ).then(function(result) {
      if (result.error) {
        $('.popout').addClass('popout-error').html('{{trans('misc.error_payment_stripe_3d')}}').fadeIn('500').delay('10000').fadeOut('500');
        $('#buttonDonation').removeAttr('disabled');

      } else {
        // The card action has been handled
        // The PaymentIntent can be confirmed again on the server

				var $input = $('<input type=hidden name=payment_intent_id />').val(result.paymentIntent.id);
				$('#formDonation').append($input);

        $('input[name=payment_method_id]').remove();

				$.ajax({
	 		 	headers: {
	         	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	     		},
	 		   type: "POST",
				 dataType: 'json',
	 		   url:"{{url('payment/stripe/charge')}}",
	 		   data: $('#formDonation').serialize(),
	 		   success: function(result){

					 if(result.success) {
             $('#buttonDonation').attr({'disabled' : 'true'});
             $url = '{{url('paypal/donation/success', $response->id)}}';
         		  window.location.href = $url;
					 } else {
						 $('.popout').addClass('popout-error').html(result.error).fadeIn('500').delay('5000').fadeOut('500');
             $('#buttonDonation').removeAttr('disabled');
					 }
	 		 }//<-- RESULT
	 	   })
      }// ELSE
    });
  } else {
    // Show success message
    if(response.success) {
      $('#buttonDonation').attr({'disabled' : 'true'});
      $url = '{{url('paypal/donation/success', $response->id)}}';
  		window.location.href = $url;
    }

  }
}
@endif

//<---------------- Donate ----------->>>>
@guest
_submitEvent = function() {
  sendDonation();

@if(isset($_stripe->key))
  chargeDonation();
  @endif

};
  @else
  $(document).on('click','#buttonDonation', function(s) {
    s.preventDefault();
    sendDonation();

    @if(isset($_stripe->key))
      chargeDonation();
      @endif

    });//<<<-------- * END FUNCTION CLICK * ---->>>>
@endguest


function sendDonation() {

  var element = $(this);
  var payment = $('#paymentGateway').val();
  //element.attr({'disabled' : 'true'});
  //$('.wrap-loader').remove();

  $.ajax({
        type: "POST",
        dataType: 'json',
        url:"{{url('donate', $response->id)}}",
        data: $('#formDonation').serialize(),
        success: function(result) {
            // success
            if(result.success == true && result.insertBody) {

              $('#bodyContainer').html('');

             $(result.insertBody).appendTo("#bodyContainer");

             if (payment != 1 && payment != 2) {
               element.removeAttr('disabled');
             }

               $('.wrap-loader').hide();
              //element.removeAttr('disabled');
              $('#errorDonation').fadeOut();

            } else if(result.success == true && result.url) {
              window.location.href = result.url;
            } else {

              var error = '';

              for($key in result.errors) {
                error += '<li><i class="glyphicon glyphicon-remove myicon-right"></i> ' + result.errors[$key] + '</li>';
              }

              $('#showErrorsDonation').html(error);
              $('#errorDonation').fadeIn(500);
              $('.wrap-loader').hide();
              element.removeAttr('disabled');

            }
        },
        error: function(responseText, statusText, xhr, $form) {
            // error
            element.removeAttr('disabled');
            $('.wrap-loader').hide();
            $('.popout').addClass('popout-error').html('Error ('+xhr+')').fadeIn('500').delay('5000').fadeOut('500');
        }
    });
}
//<---------------- End Donate ----------->>>>


</script>
@endsection
