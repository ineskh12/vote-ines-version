@extends('layouts.app')
@section('pageTitle') Somme des votes @stop


@section('style')
    <link href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Somme des votes</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Somme des votes</li>
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
                        <h4 class="card-title">Somme des votes</h4>
                        <h6 class="card-subtitle">Configurer la somme des votes pour avoir un calcul correct</h6>
                        <form method="post" action="{{ route('settings.note.submit') }}" id="note_settings" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide" style="display: none">
                                    <button class="close" data-close="alert"></button> Vous avez des erreurs de formulaire. Veuillez v??rifier ci-dessous. 
                                </div>
                                <div class="alert alert-success display-hide" style="display: none">
                                    <button class="close" data-close="alert"></button> La validation de votre formulaire est r??ussie! 
                                </div>
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alertadd">
                                        <strong>Succ??s!</strong> {!! $message !!}
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
                                    <div class="col-md-4"></div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Somme des votes
                                                <span class="text-danger"> * </span>
                                            </label>
                                            <input type="text" name="somme" data-required="1" class="form-control" value="{{ old('somme', $settings->somme)}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-5"></div>
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
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/form-validation.js') }}" type="text/javascript"></script>
    <script>
    jQuery(document).ready(function() {
        $(".select2").select2({
            allowClear: true,
            val:null,
            placeholder: $(this).data('placeholder'),
        });
    });
    </script>
@endsection