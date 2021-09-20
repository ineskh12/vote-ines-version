@extends('layouts.app')
@section('pageTitle') Ajouter Projet @stop


@section('style')
    <link href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" id="theme" rel="stylesheet">
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Ajouter Projet</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Liste projects</a></li>
                <li class="breadcrumb-item active">Ajouter project</li>
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
                        <h4 class="card-title">Ajouter Projet</h4>
                        <h6 class="card-subtitle">Ajouter un nouveau project au compétition</h6>
                        <form method="post" action="{{ route('projects.store') }}" id="project_create" enctype="multipart/form-data">
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
                                                    Logo
                                                </label>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                        <img  src="{{ asset('assets/uploads/no_image.jpg') }}"/>
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                    <div>
                                                        <span class="btn red btn-outline btn-file">
                                                            <span class="fileinput-new btn waves-light btn-primary"> Choisir image </span>
                                                            <span class="fileinput-exists btn waves-light btn-outline-warning"> Changer </span>
                                                            <input type="file" name="logo" accept="image/png,image/jpg,image/jpeg"> </span>
                                                        <a href="javascript:;" class="btn waves-light btn-danger fileinput-exists" data-dismiss="fileinput"> Supprimer </a>
                                                    </div>
                                                </div>
                                                <div class="clearfix margin-top-10">
                                                    <span class="label label-success">NOTE!</span> JPG, PNG seulement.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="control-label">Evènement
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <select class="select2 form-control" data-placeholder='Choisir évènement' name="event_id" id="event_id" style="width: 100%; height:36px;">
                                                        @foreach($events as $event)
                                                            @if ($event->lang == 'fr')
                                                                <option value="{{ $event->id }}" data-lang="fr">{{ $event->title_fr}}</option>
                                                            @elseif($event->lang == 'en')
                                                                <option value="{{ $event->id }}" data-lang="en">{{ $event->title_en}}</option>
                                                            @elseif($event->lang == 'ar')
                                                                <option value="{{ $event->id }}" data-lang="ar">{{ $event->title_ar}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Titre EN
                                                        <span class="text-danger" id="req-tit-en"></span>
                                                    </label>
                                                    <input type="text" name="titre_en" id="titre_en" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Titre FR
                                                        <span class="text-danger" id="req-tit-fr"></span>
                                                    </label>
                                                    <input type="text" name="titre_fr" id="titre_fr" class="form-control"/>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Titre AR
                                                        <span class="text-danger" id="req-tit-ar"></span>
                                                    </label>
                                                    <input type="text" name="titre_ar" id="titre_ar" class="form-control"/>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-4">
                                        <a href="{{ route('projects.index') }}" class="btn btn-outline-info">Annuler</a>
                                        <button type="submit" class="btn waves-effect waves-light btn-success">Ajouter</button>
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

    <!-- Color Picker Plugin JavaScript -->
    <script src="{{ asset('assets/plugins/jscolor/jscolor.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var lang = $("#event_id").find(':selected').attr('data-lang');
            $('#req-tit-'+lang).text('*');
            $('#titre_'+lang).attr('data-required', 1);
            $("#event_id").change(function(){
                var lang = $(this).find(':selected').attr('data-lang');
                $('#req-tit-'+lang).text('*');
                $('#titre_'+lang).attr('data-required', 1);
            });
        });
    </script>
@endsection
