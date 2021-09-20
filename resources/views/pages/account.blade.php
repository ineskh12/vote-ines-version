@extends('layouts.app')
@section('pageTitle') Account @stop


@section('style')
    <link href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" id="theme" rel="stylesheet">
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Mon compte</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Mon compte</li>
            </ol>
        </div>
    </div>
    @include('includes.notif')
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Mon compte</h4>
                        <h6 class="card-subtitle">Modifier les information de vote compte </h6>
                        <form method="post" action="{{ route('admins.update', array('id'=>$admin->id)) }}"  id="admin_account"  data-id="{{ $admin->id }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide" style="display: none">
                                    <button class="close" data-close="alert"></button> Vous avez des erreurs de formulaire. Veuillez vérifier ci-dessous. 
                                </div>
                                <div class="alert alert-success display-hide" style="display: none">
                                    <button class="close" data-close="alert"></button> La validation de votre formulaire est réussie! 
                                </div>
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alertadd">
                                        <strong>Succès!</strong> {!! $message !!}
                                    </div>
                                @endif

                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger alertadd">
                                        <strong>Erreur!</strong> {!! $message !!}
                                    </div>
                                @endif

                                @if(count($errors))
                                    <div class="alert alert-danger alertadd">
                                        <strong>Erreur!</strong>
                                        @foreach ($errors->all() as $error)
                                           {{ $error }}<br>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="control-label">
                                                    Avatar
                                                </label>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">

                                                        @if(Auth::user()->avatar != '' && File::exists(public_path().'/assets/uploads/admins/'.Auth::user()->avatar) )
                                                             <img src="{{ asset('/assets/uploads/admins/'.Auth::user()->avatar) }}"/> 
                                                        @else
                                                             <img  src="{{ asset('assets/uploads/avatar.png') }}"/> 
                                                        @endif

                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                    <div>
                                                        <span class="btn red btn-outline btn-file">
                                                            <span class="fileinput-new btn waves-light btn-primary"> Choisir image </span>
                                                            <span class="fileinput-exists btn waves-light btn-outline-warning"> Changer </span>
                                                            <input type="file" name="avatar" accept="image/png,image/jpg,image/jpeg"> </span>
                                                        <a href="javascript:;" class="btn waves-light btn-danger fileinput-exists" data-dismiss="fileinput"> Supprimer </a>
                                                    </div>
                                                </div>
                                                <div class="clearfix margin-top-10">
                                                    <span class="label label-success">NOTE!</span> JPG, PNG seulement. 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nom
                                                <span class="text-danger"> * </span>
                                            </label>
                                            <input type="text" name="nom" data-required="1" class="form-control"  value="{{ old('nom', $admin->nom) }}" /> 
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Prénom
                                                <span class="text-danger"> * </span>
                                            </label>
                                                <input type="text" name="prenom" value="{{ old('prenom', $admin->prenom) }}" data-required="1" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Email
                                                <span class="text-danger"> * </span>
                                            </label>
                                                <input name="email" type="text" value="{{ old('email', $admin->email) }}" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h3 class="form-section">Optionnel</h3>
                                        <div class="form-group">
                                            <label class="control-label">Mot de passe
                                            </label>
                                                <input name="password" type="password" id="password" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Confirmer mot de passe
                                            </label>
                                                <input name="password_confirmation" type="password" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-4">
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-info">Annuler</a>
                                        <button type="submit" class="btn waves-effect waves-light btn-warning">Modifier</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection


@section('script')

    <script src="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/form-validation.js') }}" type="text/javascript"></script>
@endsection