@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('misc.blog') }} ({{$data->total()}})
           <a href="{{ url('panel/admin/blog/create') }}" class="btn btn-sm btn-success no-shadow">
	        		<i class="glyphicon glyphicon-plus myicon-right"></i> {{ trans('misc.add_new') }}
	        		</a>
          </h4>
        </section>

        <!-- Main content -->
        <section class="content">

        	 @if(Session::has('info_message'))
		    <div class="alert alert-warning">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		      <i class="fa fa-warning margin-separator"></i>  {{ Session::get('info_message') }}
		    </div>
		@endif

		    @if(Session::has('success_message'))
		    <div class="alert alert-success">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		       <i class="fa fa-check margin-separator"></i>  {{ Session::get('success_message') }}
		    </div>
		@endif

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if( $data->total() !=  0 && $data->count() != 0 )
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ _('Post') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                      <th class="active">{{ trans('admin.actions') }}</th>
                    </tr>

                  @foreach( $data as $blog )
                    <tr>
                      <td>{{ $blog->id }}</td>
                      <td><a href="{{ url('blog/post', $blog->id).'/'.$blog->slug }}" title="{{$blog->title}}" target="_blank">{{ $blog->title }} <i class="fa fa-external-link-square"></i></a></td>
                      <td>{{ date('d M, Y', strtotime($blog->date)) }}</td>
                      <td>

                   <a href="{{ url('panel/admin/blog', $blog->id) }}" class="btn btn-success btn-sm padding-btn myicon-right">
                      		{{ trans('admin.edit') }}
                      	</a>

                        <a href="javascript:void(0);" data-url="{{ url('panel/admin/blog/delete', $blog->id) }}" class="btn btn-danger btn-sm padding-btn actionDeleteBlog">
                           		{{ trans('admin.delete') }}
                           	</a>

                      		</td>

                    </tr><!-- /.TR -->
                    @endforeach

                    @else
                    	<h3 class="text-center no-found">{{ trans('misc.no_results_found') }}</h3>
                  @endif

                  </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
          @if ($data->hasPages())
             {{ $data->links() }}
             @endif
            </div>
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
