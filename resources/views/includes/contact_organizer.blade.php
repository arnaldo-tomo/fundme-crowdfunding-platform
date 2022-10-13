<div class="modal fade" id="sendEmail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modalContactOrganizer">
          <div class="modal-content">
            <div class="modal-header headerModal headerModalOverlay position-relative" style="background-image: url('{{url('public/campaigns/large',$response->large_image)}}'); background-size: cover;">

            <h5 class="modal-title text-center text-white position-relative btn-block" id="myModalLabel">

              <span class="btn-block margin-bottom-15 text-center position-relative">
                  <img class="rounded-circle" src="{{url('public/avatar/',$response->user()->avatar)}}" width="80" height="80" >
                </span>
                  {{ $response->user()->name }}
                  <small class="btn-block m-0">{{ trans('misc.contact_organizer') }}</small>
                  </h5>
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
                    <div class="alert alert-danger d-none-custom" id="dangerAlert">
              <ul class="list-unstyled text-left" id="showErrors"></ul>
            </div><!-- Alert -->

            <button type="button" class="btn btn-sm btn-secondary px-4 py-2 no-hover custom-rounded" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>

              <button type="submit" class="btn btn-sm btn-primary px-4 py-2 custom-rounded no-hover" id="buttonFormSubmitContact">
                <i></i>
                {{ trans('misc.send_message') }}
              </button>
             </form>

             <!-- Alert -->
             <div class="alert alert-success d-none-custom" id="successAlert">
              <ul class="list-unstyled" id="showSuccess"></ul>
            </div><!-- Alert -->

              </div><!-- Modal body -->
            </div><!-- Modal content -->
          </div><!-- Modal dialog -->
        </div><!-- Modal -->
