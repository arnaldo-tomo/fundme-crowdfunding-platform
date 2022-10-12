<link href="{{ asset('public/css/core.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/styles.css') }}" rel="stylesheet">

<script type="text/javascript">
    // URL BASE
    var URL_BASE = "{{ url('/') }}";
    // ReadMore
    var ReadMore = "{{ trans('misc.view_more') }}";
    var ReadLess = "{{ trans('misc.view_less') }}";
 </script>

 @if($settings->color_default <> '')
 <style>
 ::selection{ background-color: {{$settings->color_default}}; color: white; }
 ::moz-selection{ background-color: {{$settings->color_default}}; color: white; }
 ::webkit-selection{ background-color: {{$settings->color_default}}; color: white; }

 body a,
 a:hover,
 a:focus,
 a.page-link,
 .btn-outline-primary {
     color: {{$settings->color_default}};
 }
 .btn-primary:not(:disabled):not(.disabled).active,
 .btn-primary:not(:disabled):not(.disabled):active,
 .show>.btn-primary.dropdown-toggle,
 .btn-primary:hover,
 .btn-primary:focus,
 .btn-primary:active,
 .btn-primary,
 .btn-primary.disabled,
 .btn-primary:disabled,
 .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before,
 .page-item.active .page-link,
 .page-link:hover {
     background-color: {{$settings->color_default}};
     border-color: {{$settings->color_default}};
 }
 .bg-primary,
 .dropdown-item:focus,
 .dropdown-item:hover,
 .dropdown-item.active,
 .dropdown-item:active,
 .owl-theme .owl-dots .owl-dot.active span,
 .owl-theme .owl-dots .owl-dot:hover span,
 #updates li:hover:before {
     background-color: {{$settings->color_default}}!important;
 }
 .owl-theme .owl-dots .owl-dot.active span::before,
 .owl-theme .owl-dots .owl-dot:hover span::before,
 .form-control:focus,
 .custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before,
 .custom-control-input:focus:not(:checked) ~ .custom-control-label::before,
 .custom-select:focus,
 .btn-outline-primary {
 	border-color: {{$settings->color_default}};
 }
 .custom-control-input:not(:disabled):active~.custom-control-label::before,
 .custom-control-input:checked~.custom-control-label::before,
 .btn-outline-primary:hover,
 .btn-outline-primary:focus,
 .btn-outline-primary:not(:disabled):not(.disabled):active {
     color: #fff;
     background-color: {{$settings->color_default}};
     border-color: {{$settings->color_default}};
 }
 .blog-post img {max-width: 100% !important; height: auto !important;}
 .ribbon-1 {z-index: 999;}
 </style>
@endif
