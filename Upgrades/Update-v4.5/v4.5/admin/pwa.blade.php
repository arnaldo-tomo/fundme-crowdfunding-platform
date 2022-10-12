@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		Progressive Web Apps (PWA)
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

        	<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Progressive Web Apps (PWA)</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/pwa') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="callout callout-info">
                       <p><i class="icon fa fa-info-circle myicon-right"></i> {{ __('misc.alert_pwa_https') }}</p>
                     </div>

                     <div class="form-group">
                       <label class="col-sm-2 control-label">{{ __('misc.pwa_short_name') }}</label>
                       <div class="col-sm-10">
                         <input type="text" value="{{ env('PWA_SHORT_NAME') }}" name="PWA_SHORT_NAME" class="form-control" placeholder="">
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <div class="box-body">
                     <h4>PNG ICONS for the PWA (<a href="https://maskable.app/editor" target="_blank">https://maskable.app/editor</a>)</h4>
                   </div>

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">72x72 Maskable Icon</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_ICON_72]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">96x96 Icon</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_ICON_96]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">128x128 Icon</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_ICON_128]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">144x144 Icon</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_ICON_144]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">152x152 Icon</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_ICON_152]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">384x384 Icon</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_ICON_384]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">512x512 Icon</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_ICON_512]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <hr />

                   <div class="box-body">
                     <h4>PNG SPLASH screens for the PWA (<a href="https://appsco.pe/developer/splash-screens" target="_blank">https://appsco.pe/developer/splash-screens</a>)</h4>
                   </div>

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">640x1136</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_SPLASH_640]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">750x1334</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_SPLASH_750]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">1125x2436</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_SPLASH_1125]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">1242x2208</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_SPLASH_1242]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">1536x2048</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_SPLASH_1536]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">1668x2224</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_SPLASH_1668]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">2048x2732</label>
                       <div class="col-sm-10">
                       	<div class="btn btn-info box-file">
                       		<input type="file" accept="image/*" name="files[PWA_SPLASH_2048]" class="filePhoto" />
                       		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                           <span class="text-file">{{ trans('misc.choose_image') }}</span>
                       		</div>

                         <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
           					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
           					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle far fa-times-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
           					     </div>
                       </div>
                     </div>
                   </div><!-- /.box-body -->


                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">{{ trans('admin.save') }}</button>
                  </div><!-- /.box-footer -->
                </form>
              </div>
        		</div><!-- /.row -->
        	</div><!-- /.content -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')

	<script type="text/javascript">
  $(".filePhoto").on('change', function(){

    var element = $(this);

    element.parents('.box-file').find('.text-file').html('{{trans('misc.choose_image')}}');

  	var loaded = false;
  	if(window.File && window.FileReader && window.FileList && window.Blob){
      // Check empty input filed
  		if($(this).val()) {

  			oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
  			if($(this)[0].files.length === 0){return}

  			var oFile = $(this)[0].files[0];
        var fsize = $(this)[0].files[0].size; //get file size
  			var ftype = $(this)[0].files[0].type; // get file type

        // Validate formats
        if(!rFilter.test(oFile.type)) {
  				element.val('');
  				alert("{{ trans('misc.formats_available') }}");
  				return false;
  			}

        // Validate Size
        if(!rFilter.test(oFile.type)) {
  				element.val('');
  				alert("{{ trans('misc.formats_available') }}");
  				return false;
  			}

  			oFReader.onload = function (e) {

  				var image = new Image();
          image.src = oFReader.result;

  				image.onload = function() {

            element.parents('.box-body').find('.fileContainer').removeClass('display-none');
            element.parents('.box-body').find('.file-name-file').html(oFile.name);
          };// <<--- image.onload
        }
          oFReader.readAsDataURL($(this)[0].files[0]);
  		}// Check empty input filed
  	}// window File
  });
  // END UPLOAD PHOTO

  $('.delete-image').click(function(){
        var element = $(this);

        element.parents('.box-body').find('.fileContainer').addClass('display-none');
        element.parents('.box-body').find('.file-name-file').html('');
        element.parents('.box-body').find('.filePhoto').val('');
  });

	</script>


@endsection
