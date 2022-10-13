@if (count($errors) > 0)
  <div class="alert alert-danger alert-dismissible fade show" role="alert" id="dangerAlert">
    <ul class="list-unstyled mb-0">
    @foreach ($errors->all() as $error)
      <li><i class="far fa-times-circle mr-2"></i> {{$error}}</li>
    @endforeach
    </ul>
  </div>
@endif
