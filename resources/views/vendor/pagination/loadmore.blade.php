@if ($paginator->hasMorePages())
  <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-primary p-2 px-5 btn-lg rounded loadPaginator" id="paginator">
    {{trans('misc.loadmore')}} <small class="pl-1"><i class="far fa-arrow-alt-circle-down"></i></small>
  </a>
@endif
