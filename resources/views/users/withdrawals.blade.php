@extends('app')

@section('title') {{ trans('misc.withdrawals') }} - @endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
      <div class="container position-relative">
        <h1>{{ trans('misc.withdrawals') }}</h1>
      </div>
    </div>

<div class="container py-5">

  <div class="row">

    <div class="col-md-3">
      @include('users.navbar-settings')
    </div>

		<!-- Col MD -->
		<div class="col-md-9">
			@if (session('success_message'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            		<i class="bi-check2 mr-2"></i> {{ session('success_message') }}
            		</div>
            	@endif

			@include('errors.errors-forms')

          @if ($withdrawals->count() != 0)
          <div class="table-responsive mt-5 border">
            <table class="table m-0">
              <thead>
                <tr>
                  <th class="active">{{ trans('misc.campaign') }}</th>
  			          <th class="active">{{ trans('admin.amount') }}</th>
  			          <th class="active">{{ trans('misc.method') }}</th>
  			          <th class="active">{{ trans('admin.status') }}</th>
  			          <th class="active">{{ trans('admin.date') }}</th>
  			          <th class="active">{{ trans('admin.actions') }}</th>
                </tr>
              </thead>

              <tbody>

              @foreach ($withdrawals as $withdrawal)
                <tr>
                  <td>{{ $withdrawal->id }}</td>
                  <td>
                    <a title="{{$withdrawal->title}}" href="{{ url('campaign',$withdrawal->campaigns()->id) }}" target="_blank">{{ str_limit($withdrawal->campaigns()->title,20,'...') }} <i class="fa fa-external-link-square"></i></a>
                    </td>
                  <td>@if($settings->currency_position == 'left'){{ $settings->currency_symbol.$withdrawal->amount }}@else{{$withdrawal->amount.$settings->currency_symbol}}@endif</td>
                  <td>{{ $withdrawal->gateway }}</td>
                  <td>
                    @if( $withdrawal->status == 'paid' )
                    <span class="badge bg-success">{{trans('misc.paid')}}</span>
                    @else
                    <span class="badge bg-warning text-dark">{{trans('misc.pending_to_pay')}}</span>
                    @endif
                  </td>
                  <td>{{ date($settings->date_format, strtotime($withdrawal->date)) }}</td>
                  <td>

                    @if( $withdrawal->status != 'paid' )
                            {!! Form::open([
                      'method' => 'POST',
                      'url' => "delete/withdrawal/$withdrawal->id",
                      'class' => 'displayInline'
                    ]) !!}

                    {!! Form::button(trans('misc.delete'), ['class' => 'btn btn-danger btn-sm deleteW']) !!}
                {!! Form::close() !!}

                @else

                - {{trans('misc.paid')}} -

                @endif

                    </td>

                </tr><!-- /.TR -->
                @endforeach

              </tbody>
            </table>
          </div>

          @if ($withdrawals->hasPages())
            {{ $withdrawals->links() }}
          @endif

        @else
          <h4 class="mt-0 fw-light">
            {{ trans('misc.no_results_found') }}
          </h4>
        @endif

		</div><!-- /COL MD -->
    </div><!-- / Wrap -->
 </div><!-- container -->

 <!-- container wrap-ui -->
@endsection

@section('javascript')
<script type="text/javascript">

$(".deleteW").click(function(e) {
   	e.preventDefault();

   	var element = $(this);
    var form    = $(element).parents('form');
    element.blur();

	swal(
		{   title: "{{trans('misc.delete_confirm')}}",
		 text: "{{trans('misc.confirm_delete_withdrawal')}}",
		  type: "warning",
		  showLoaderOnConfirm: true,
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		   confirmButtonText: "{{trans('misc.yes_confirm')}}",
		   cancelButtonText: "{{trans('misc.cancel_confirm')}}",
		    closeOnConfirm: false,
		    },
		    function(isConfirm){
		    	 if (isConfirm) {
		    	 	form.submit();
		    	 	}
		    	 });


		 });
</script>
@endsection
