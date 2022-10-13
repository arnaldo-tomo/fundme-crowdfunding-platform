<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Installer</title>
    <link href="{{ asset('public/css/core.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/styles.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ url('public/img/favicon.png') }}" />

    <style>
    /**
    * Extracted from: SweetAlert
    * Modified by: Istiak Tridip
    */
   .success-checkmark {
     width: 80px;
     height: 115px;
     margin: 0 auto;
   }
   .success-checkmark .check-icon {
     width: 80px;
     height: 80px;
     position: relative;
     border-radius: 50%;
     box-sizing: content-box;
     border: 4px solid #4CAF50;
   }
   .success-checkmark .check-icon::before {
     top: 3px;
     left: -2px;
     width: 30px;
     transform-origin: 100% 50%;
     border-radius: 100px 0 0 100px;
   }
   .success-checkmark .check-icon::after {
     top: 0;
     left: 30px;
     width: 60px;
     transform-origin: 0 50%;
     border-radius: 0 100px 100px 0;
     animation: rotate-circle 4.25s ease-in;
   }
   .success-checkmark .check-icon::before, .success-checkmark .check-icon::after {
     content: "";
     height: 100px;
     position: absolute;
     background: #FFFFFF;
     transform: rotate(-45deg);
   }
   .success-checkmark .check-icon .icon-line {
     height: 5px;
     background-color: #4CAF50;
     display: block;
     border-radius: 2px;
     position: absolute;
     z-index: 10;
   }
   .success-checkmark .check-icon .icon-line.line-tip {
     top: 46px;
     left: 14px;
     width: 25px;
     transform: rotate(45deg);
     animation: icon-line-tip 0.75s;
   }
   .success-checkmark .check-icon .icon-line.line-long {
     top: 38px;
     right: 8px;
     width: 47px;
     transform: rotate(-45deg);
     animation: icon-line-long 0.75s;
   }
   .success-checkmark .check-icon .icon-circle {
     top: -4px;
     left: -4px;
     z-index: 10;
     width: 80px;
     height: 80px;
     border-radius: 50%;
     position: absolute;
     box-sizing: content-box;
     border: 4px solid rgba(76, 175, 80, 0.5);
   }
   .success-checkmark .check-icon .icon-fix {
     top: 8px;
     width: 5px;
     left: 26px;
     z-index: 1;
     height: 85px;
     position: absolute;
     transform: rotate(-45deg);
     background-color: #FFFFFF;
   }

   @keyframes rotate-circle {
     0% {
       transform: rotate(-45deg);
     }
     5% {
       transform: rotate(-45deg);
     }
     12% {
       transform: rotate(-405deg);
     }
     100% {
       transform: rotate(-405deg);
     }
   }
   @keyframes icon-line-tip {
     0% {
       width: 0;
       left: 1px;
       top: 19px;
     }
     54% {
       width: 0;
       left: 1px;
       top: 19px;
     }
     70% {
       width: 50px;
       left: -8px;
       top: 37px;
     }
     84% {
       width: 17px;
       left: 21px;
       top: 48px;
     }
     100% {
       width: 25px;
       left: 14px;
       top: 45px;
     }
   }
   @keyframes icon-line-long {
     0% {
       width: 0;
       right: 46px;
       top: 54px;
     }
     65% {
       width: 0;
       right: 46px;
       top: 54px;
     }
     84% {
       width: 55px;
       right: 0px;
       top: 35px;
     }
     100% {
       width: 47px;
       right: 8px;
       top: 38px;
     }
   }
    </style>
  </head>
  <body class="bg-primary">
  		<main role="main">
        <div class="jumbotron m-0 bg-primary" style="padding: 40px 0">
          <div class="container pt-lg-md">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="card bg-light shadow border-0">

                  <!-- SuccessInstaller -->
                  <div class="card-header bg-white d-none-custom py-4" id="SuccessInstaller">

                    <div class="success-checkmark">
                      <div class="check-icon">
                        <span class="icon-line line-tip"></span>
                        <span class="icon-line line-long"></span>
                        <div class="icon-circle"></div>
                        <div class="icon-fix"></div>
                      </div>
                    </div>

                    <h4 class="text-center mb-0 font-weight-bold">
                      Installation completed successfully!
                    </h4>
                    <small class="btn-block text-center mt-2">Login with these credentials.</small>

                    <div class="w-100 text-center mt-3">
                      <code class="d-block w-100 mb-3">
                        User: admin@example.com <br>
                        Password: 123456
                      </code>

                      <small class="btn-block text-center mt-2">* Remember to change the password and email.</small>

                    <form method="POST" action="{{ url('installer/script/user') }}" id="formCreateUser">
                      @csrf
                      <button type="submit" id="createUser" class="btn btn-primary mt-3 px-4 font-weight-light">
                        Go Panel Admin <i class="fa fa-long-arrow-alt-right ml-2"></i>
                      </button>
                    </form>

                    </div>
                  </div><!-- End SuccessInstaller -->

                <!-- headerRequirements -->
                <div class="card-header bg-white py-4" id="headerRequirements">
                  <h4 class="text-center mb-0 font-weight-bold">
                    Welcome to Installer
                  </h4>
                  <small class="btn-block text-center mt-2">Server Requirements</small>
                </div>

                <!-- headerSetupDatabase -->
                <div class="card-header bg-white py-4 d-none-custom" id="headerSetupDatabase">
                  <h4 class="text-center mb-0 font-weight-bold">
                    Database and App
                  </h4>
                  <small class="btn-block text-center mt-2">Setup Database and App</small>
                </div>

                  <div class="card-body px-lg-5 py-lg-5" id="containerRequirements">

                    <div class="card shadow-sm">
                  			<div class="list-group list-group-sm list-group-flush">

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>PHP Version: {{ phpversion() }}
                                    <small class="w-100 d-block">Version required: {{ $minVersionPHP }}</small>
                                  </span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $versionPHP ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>BCMath</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $BCMath ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Ctype</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $Ctype ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Fileinfo</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $Fileinfo ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Openssl</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $openssl ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Pdo</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $pdo ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Mbstring</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $mbstring ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Tokenizer</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $tokenizer ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>JSON</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $json ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>XML</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $xml ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>cURL</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $curl ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>GD</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $gd ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Exif</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $exif ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                        </div>
                      </div>

                      @if ($versionPHP
                          && $BCMath
                          && $Ctype
                          && $Fileinfo
                          && $openssl
                          && $pdo
                          && $mbstring
                          && $tokenizer
                          && $json
                          && $xml
                          && $curl
                          && $gd
                          && $exif
                          )
                          <a href="javascript:void(0);" id="nextDatabase" class="btn btn-primary my-4 w-100">Setup Database and App <i class="fa fa-long-arrow-alt-right ml-1"></i></a>
                        @else
                          <div class="alert alert-danger mt-3" role="alert">
                            <i class="fa fa-exclamation-triangle"></i> You must meet all the requirements to be able to install, enable or install the extensions marked in red or update your PHP version
                          </div>
                      @endif
                  </div><!-- card body requirements -->

                  <!-- Database setup -->
                  <div class="card-body px-lg-5 py-lg-5 d-none-custom" id="containerSetupDatabase">

                    <form method="POST" action="{{ url('installer/script/database') }}" id="formSetupDatabase">
                        @csrf

                    <div class="row">

                      <small class="w-100 d-block mb-2">-- Database</small>

                      <div class="col-md-6">
                      <div class="form-group mb-3">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-database"></i></span>
                          </div>
                          <input class="form-control" required value="{{old('database')}}" placeholder="Database" name="database" type="text">
                        </div>
                      </div>
                      </div>

                      <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                          </div>
                          <input name="username" required type="text" value="{{old('username')}}" class="form-control" placeholder="Username">
                        </div>
                      </div>
                      </div>

                      </div><!-- Row -->

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-database"></i></span>
                          </div>
                          <input name="host" required type="text" class="form-control" value="{{old('host')}}" placeholder="Host">
                        </div>
                        <small class="text-muted btn-block mb-4">For example: <em>127.0.0.1</em> or <em>localhost</em></small>
                      </div>

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                          </div>
                          <input name="password" required type="password" class="form-control" placeholder="Password">
                        </div>
                      </div>

                      <small class="w-100 d-block mb-2">-- App</small>

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-cogs"></i></span>
                          </div>
                          <input name="app_name" required type="text" value="{{old('app_name')}}" class="form-control" placeholder="App Name">
                        </div>
                        <small class="text-muted btn-block mb-4">For example: <em>Fudme</em></small>
                      </div>

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-link"></i></span>
                          </div>
                          <input name="app_url" required type="text" value="{{old('app_url')}}" class="form-control" placeholder="App URL">
                        </div>
                        <small class="text-muted btn-block mb-4">The App URL must be as follows: <em>{{url('/')}}</em></small>
                      </div>

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                          </div>
                          <input name="email_admin" required type="email" value="{{old('email_admin')}}" class="form-control" placeholder="Email Admin">
                        </div>
                        <small class="text-muted btn-block mb-4">For Example: <em>no-reply@yoursite.com</em></small>
                      </div>

                      <div class="alert alert-danger d-none-custom" id="errors">
                          <ul class="list-unstyled m-0" id="showErrors"></ul>
                        </div>

                      <div class="text-center">
                        <button type="submit" id="setupDatabase" class="btn btn-primary my-4 w-100">Install</button>
                      </div>
                    </form>
                  </div><!-- card-body Database setup -->

                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
  </body>
  <script src="{{ asset('public/js/core.min.js') }}"></script>
  <script src="{{ asset('public/js/installer.js') }}"></script>
</html>
