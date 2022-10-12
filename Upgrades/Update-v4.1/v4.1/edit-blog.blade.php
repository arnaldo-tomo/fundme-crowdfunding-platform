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
            		{{ trans('misc.blog') }}
            			<i class="fa fa-angle-right margin-separator"></i>
            				{{ trans('admin.edit') }}
                  </h4>
                </section>

        <!-- Main content -->
        <section class="content">

        	<div class="content">

            @if(Session::has('success_message'))
    		    <div class="alert alert-success">
    		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    								<span aria-hidden="true">×</span>
    								</button>
    		       <i class="fa fa-check margin-separator"></i>  {{ Session::get('success_message') }}
    		    </div>
    		@endif

        		<div class="row">

        	<div class="box p-top-20">
                <!-- form start -->
                <form class="form-horizontal" method="post" action="{{ url('panel/admin/blog/update') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="id" value="{{ $data->id }}">

					@include('errors.errors-forms')

          <!-- Start Box Body -->
           <div class="box-body">
             <div class="form-group">
               <label class="col-sm-2 control-label">{{ trans('misc.title') }}</label>
               <div class="col-sm-10">
                 <input type="text" value="{{ $data->title }}" name="title" class="form-control" placeholder="Title">
               </div>
             </div>
           </div><!-- /.box-body -->

               <!-- Start Box Body -->
                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('misc.tags') }} (SEO)</label>
                    <div class="col-sm-10">
                      <input type="text" value="{{ $data->tags }}" name="tags" class="form-control" placeholder="Tags">
                    </div>
                  </div>
                </div><!-- /.box-body -->

                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('admin.thumbnail') }}</label>
                    <div class="col-sm-10">
                    	<div class="btn btn-info box-file">
                    		<input type="file" accept="image/*" name="thumbnail">
                    		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i> {{ trans('misc.upload') }}
                    		</div>

                    <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
    					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
    					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-attach-file-2 pull-right" title="Delete"></i>
    					     </div>
                    <p class="help-block margin-bottom-zero">{{ trans('misc.minimum_width_img_blog') }}</p>
                    </div>
                  </div>
                </div>

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.content') }}</label>
                      <div class="col-sm-10">

                      	<textarea name="content"rows="5" cols="40" id="content" class="form-control" placeholder="{{ _('Content') }}">{{ $data->content }}</textarea>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <a href="{{ url('panel/admin/blog') }}" class="btn btn-default">{{ trans('admin.cancel') }}</a>
                    <button type="submit" class="btn btn-success pull-right">{{ trans('admin.save') }}</button>
                  </div><!-- /.box-footer -->
                </form>
              </div>
        		</div><!-- /.row -->
        	</div><!-- /.content -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')
<script src="{{ asset('public/js/ckeditor-init.js')}}"></script>
@endsection
