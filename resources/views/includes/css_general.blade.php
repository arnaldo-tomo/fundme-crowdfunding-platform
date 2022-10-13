<link href="{{ asset('public/css/core.css') }}?v={{$settings->version}}" rel="stylesheet">
<link href="{{ asset('public/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/styles.css') }}?v={{$settings->version}}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/js/datepicker/datepicker3.css')}}" rel="stylesheet" type="text/css">

<script type="text/javascript">
    // Variables
    var URL_BASE = "{{ url('/') }}";
    var ReadMore = "{{ trans('misc.view_more') }}";
    var ReadLess = "{{ trans('misc.view_less') }}";
    var error = "{{ trans('misc.error') }}";
    var categoriesCount = {{ isset($categories) ? $categories->count() : 0 }};
    var copied = "{{ trans('misc.copied') }}";
    var delete_confirm = "{{trans('misc.delete_confirm')}}";
    var confirm_delete_campaign = "{{trans('misc.confirm_delete_campaign')}}";
    var yes_confirm = "{{trans('misc.yes_confirm')}}";
    var cancel_confirm = "{{trans('misc.cancel_confirm')}}";
    var formats_available = "{{ trans('misc.formats_available') }}";

    var file_size_allowed = {{$settings->file_size_allowed * 1024}};
    var max_size = "{{trans('misc.max_size').': '.App\Helper::formatBytes($settings->file_size_allowed * 1024)}}";
    @php
      $dimensions = explode('x',$settings->min_width_height_image);
    @endphp
    var min_width = {{ $dimensions[0] }};
    var min_height = {{ $dimensions[1] }};
    var width_min_alert = "{{trans('misc.width_min',['data' => $dimensions[0]])}}";
    var height_min_alert = "{{trans('misc.height_min',['data' => $dimensions[1]])}}";
    var urlImageEditor = "{{route('upload.image', ['_token' => csrf_token()])}}";
    var card_required_name_email = '{{trans('admin.card_required_name_email')}}';
    var error_payment_stripe_3d = '{{trans('misc.error_payment_stripe_3d')}}';
    var amount = '{{trans('misc.amount')}}';
    var donations = '{{trans('misc.donations')}}';
    var decimalFormat = '{{ $settings->decimal_format }}';
    var currencyPosition = '{{ $settings->currency_position }}';
    var currencySymbol = '{{ $settings->currency_symbol }}';
 </script>

 <style>
 :root {
   --color-default: {{ $settings->color_default }};
 }
 </style>
