<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.ico') }}">
    <title>OSC {{ date('Y') }}  - Orange Developer Center</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{ asset('css/colors/blue.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style type="text/css">
    .help-block{
        color: red;
    }
</style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 col-5 text-left"><img src="{{ asset('assets/login/logo.png') }}" class="img-responsive"></div>
                <div class="col-md-8 col-2"></div>
                <div class="col-md-2 col-5 text-right"><img src="{{ asset('assets/login/slogan.png') }}" class="img-responsive"></div>
            </div>
            <div class="login-register">
                <div class="login-box card">
                    <div class="card-body">
                    <img src="{{ asset('assets/login/cloud_left.png') }}"  class="img-left2"/>
                    <img src="{{ asset('assets/login/cloud_right.png') }}" class="img-right2"/>
                        <form class="form-horizontal form-material" id="reset" method="post" action="{{ url('/password/email') }}">
                             {{ csrf_field() }}
                            <h2 class="box-title m-b-20" style="text-align: center">Réinitialiser le mot de passe</h2>
                            <div class="alert alert-danger display-hide" style="display: none">
                                <button class="close" data-close="alert"></button> Vous avez des erreurs de formulaire. Veuillez vérifier ci-dessous. 
                            </div>
                            <div class="alert alert-success display-hide" style="display: none">
                                <button class="close" data-close="alert"></button> La validation de votre formulaire est réussie! 
                            </div>
                            @if (session('status'))
                                <div class="alert alert-success alertadd">
                                    {!! session('status') !!}
                                </div>
                            @endif
                            @if ($errors->has('email'))
                                <div class="alert alert-danger alertadd">
                                    {!! $errors->first('email') !!}
                                </div>
                            @endif
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="col-xs-12">
                                    <input class="form-control" type="text" placeholder="Email" name="email" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="form-group text-center m-t-20">
                                <div class="col-xs-12">
                                    <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Envoyer</button>
                                </div>
                            </div>
                            <div class="form-group m-b-0">
                                <div class="col-sm-12 text-center">
                                    <a href="{{ url('/login') }}" id="to-recover" class="text-dark pull-right">Retour</a> 
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="container-fluid w-100 bg-faded">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="row">
                    <div class="col-1 col-md-2 text-right">
                         <img src="{{ asset('assets/images/logo-icon.png') }}" class="img-responsive logo">
                    </div>
                    <div class="col-11 col-md-10 text-left">
                        <div class="row">
                            <div class="col-md-12">Powred By</div>
                            <div class="col-md-12 odc" >Orange Developer Center</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>
    <!--stickey kit -->
    <script src="{{ asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/form-validation.js') }}" type="text/javascript"></script>
</body>
</html>
