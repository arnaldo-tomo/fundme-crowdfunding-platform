@extends('app')

@section('title'){{ trans('misc.edit_reward').' - ' }}@endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
    <div class="container wrap-jumbotron position-relative">
    	<h1>{{ trans('misc.edit_reward') }}</h1>
      <p class="mb-2">
        {{$campaign->title}}
      </p>

      @if ($campaign->donations()->where('rewards_id',$data->id)->count() == 0 )
              {!! Form::open([
                 'method' => 'POST',
                 'url' => 'delete/rewards',
                 'class' => 'text-center'
               ]) !!}
            {!! Form::hidden('id',$data->id ); !!}
         {!! Form::submit(trans('misc.delete_reward'), ['class' => 'btn btn-danger btn-xs padding-btn actionDelete']) !!}

           {!! Form::close() !!}
         @endif

    </div>
  </div>

<div class="container py-5">
	<div class="row">

    <div class="wrap-container-lg">
	<!-- col-md-8 -->
	<div class="col-md-12">

			@include('errors.errors-forms')

    <!-- form start -->
    <form method="POST" action="" id="formUpdateCampaign" enctype="multipart/form-data">

    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
    	<input type="hidden" name="id" value="{{ $data->id }}">

      <!-- Start Form Group -->
      <div class="form-group">
        <label>{{ trans('misc.title') }}</label>
          <input type="text" value="{{$data->title}}"  name="title" autocomplete="off" class="form-control" placeholder="{{ trans('misc.title') }}">
        <small class="text-muted">{{ trans('misc.title_reward_desc') }}</small>

      </div><!-- /.form-group-->

      <div class="form-group">
        <label>{{ trans('misc.amount') }}</label>
        <div class="input-group">
            <span class="input-group-text">{{$settings->currency_symbol}}</span>
          <input type="number" min="1" class="form-control onlyNumber" name="amount" autocomplete="off" value="{{ $data->amount }}" placeholder="{{ trans('misc.amount') }}">
        </div>
      </div>

        <div class="form-group">
            <label>{{ trans('misc.description') }}</label>
            	<textarea name="description" rows="4" id="description" class="form-control" placeholder="{{ trans('misc.description') }}">{{$data->description}}</textarea>
          </div>

          <div class="form-group">
            <label>{{ trans('misc.quantity') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-copy"></i></span>
              <input type="number" min="1" class="form-control onlyNumber" name="quantity" autocomplete="off" value="{{$data->quantity}}" placeholder="{{ trans('misc.quantity') }}">
            </div>
          </div>

          <!-- Start Form Group -->
          <div class="form-group">
            <label>{{ trans('misc.delivery') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              <input type="text" value="{{$data->delivery}}" id="datepickerRewards" name="delivery" autocomplete="off" class="form-control" placeholder="{{ trans('misc.delivery') }}">
            </div>
            <small class="text-muted">{{ trans('misc.delivery_desc') }}</small>

          </div><!-- /.form-group-->

            <!-- Alert -->
            <div class="alert alert-danger d-none-custom mt-2" id="dangerAlert">
							<ul class="list-unstyled mb-0" id="showErrors"></ul>
						</div><!-- Alert -->

            <div class="alert alert-success d-none-custom mt-2" id="successAlert">
                    <ul class="list-unstyled mb-0" id="success_update">
                      <li><i class="far fa-check-circle mr-1"></i> {{ trans('misc.success_add_rewards') }}
                        <a href="{{url('campaign',$data->id)}}" class="text-white text-underline">{{trans('misc.view_campaign')}}</a>
                      </li>
                    </ul>
                  </div><!-- Alert -->

            <div class="box-footer">
            	<hr />
              <button type="submit" id="buttonUpdateForm" class="btn btn-block btn-lg btn-primary no-hover" data-create="{{ trans('auth.send') }}" data-send="{{ trans('misc.send_wait') }}">{{ trans('auth.send') }}</button>
              <div class="btn-block text-center mt-2">
           		<a href="{{url('campaign',$campaign->id)}}" class="text-muted">
           		<i class="fa fa-long-arrow-alt-left"></i>	{{trans('auth.back')}}</a>
           </div>
            </div><!-- /.box-footer -->
          </form>
        </div><!-- wrap-center -->
		</div><!-- col-md-12-->

	</div><!-- row -->
</div><!-- container -->
@endsection
