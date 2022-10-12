@extends('admin.layout')

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('misc.payment_settings') }}

          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	 @if(Session::has('success_message'))
		    <div class="alert alert-success">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		       <i class="fa fa-check margin-separator"></i> {{ Session::get('success_message') }}
		    </div>
		@endif

        	<div class="content">

        		<div class="row">

        	<div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><strong>{{ trans('misc.payment_settings') }}</strong></h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/payments') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('errors.errors-forms')


                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.currency_code') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->currency_code }}" name="currency_code" class="form-control" placeholder="{{ trans('admin.currency_code') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.currency_symbol') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->currency_symbol }}" name="currency_symbol" class="form-control" placeholder="{{ trans('admin.currency_symbol') }}">
                        <p class="help-block">{{ trans('admin.notice_currency') }}</p>
                      </div>
                    </div>
                  </div><!-- /.box-body -->


                   <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.fee_donation') }}</label>
                      <div class="col-sm-10">
                      	<select name="fee_donation" class="form-control">
                      		<option @if( $settings->fee_donation == '1' ) selected="selected" @endif value="1">1%</option>
                      		<option @if( $settings->fee_donation == '2' ) selected="selected" @endif value="2">2%</option>
						  	<option @if( $settings->fee_donation == '3' ) selected="selected" @endif  value="3">3%</option>
						  	<option @if( $settings->fee_donation == '4' ) selected="selected" @endif value="4">4%</option>
						  	<option @if( $settings->fee_donation == '5' ) selected="selected" @endif value="5">5%</option>

						  	<option @if( $settings->fee_donation == '6' ) selected="selected" @endif value="6">6%</option>
						  	<option @if( $settings->fee_donation == '7' ) selected="selected" @endif value="7">7%</option>
						  	<option @if( $settings->fee_donation == '8' ) selected="selected" @endif value="8">8%</option>
						  	<option @if( $settings->fee_donation == '9' ) selected="selected" @endif value="9">9%</option>

						  	<option @if( $settings->fee_donation == '10' ) selected="selected" @endif value="10">10%</option>
						  	<option @if( $settings->fee_donation == '15' ) selected="selected" @endif value="15">15%</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                 <div class="box-body">
                   <div class="form-group">
                     <label class="col-sm-2 control-label">{{ trans('admin.currency_position') }}</label>
                     <div class="col-sm-10">
                       <select name="currency_position" class="form-control">
                         <option @if( $settings->currency_position == 'left' ) selected="selected" @endif value="left">{{$settings->currency_symbol}}99 - {{trans('admin.left')}}</option>
                         <option @if( $settings->currency_position == 'right' ) selected="selected" @endif value="right">99{{$settings->currency_symbol}} {{trans('admin.right')}}</option>
                         </select>
                     </div>
                   </div>
                 </div><!-- /.box-body -->

                 <!-- Start Box Body -->
                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('misc.decimal_format') }}</label>
                    <div class="col-sm-10">
                      <select name="decimal_format" class="form-control">
                        <option @if( $settings->decimal_format == 'dot' ) selected="selected" @endif value="dot">10,989.95</option>
                        <option @if( $settings->decimal_format == 'comma' ) selected="selected" @endif value="comma">10.989,95</option>
                        </select>
                    </div>
                  </div>
                </div><!-- /.box-body -->

               <div class="box-footer">
                 <button type="submit" class="btn btn-success">{{ trans('admin.save') }}</button>
               </div><!-- /.box-footer -->
               </form>

              </div><!-- /.row -->

        	</div><!-- /.content -->

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')

	<!-- icheck -->
	<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
		//Flat red color scheme for iCheck
        $('input[type="radio"]').iCheck({
          radioClass: 'iradio_flat-red'
        });

        $('input[type="checkbox"]').iCheck({
    	  	checkboxClass: 'icheckbox_square-red',
        	radioClass: 'iradio_square-red',
    	    increaseArea: '20%' // optional
	  });

	</script>


@endsection
