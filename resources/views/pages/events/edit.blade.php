@extends('layouts.app')
@section('pageTitle') Modifier Evènement @stop


@section('style')
    <link href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" id="picker" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" id="select" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css" id="select" rel="stylesheet">
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Modifier Evènement</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Liste des évènements</a></li>
                <li class="breadcrumb-item active">Modifier Evènement</li>
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
                        <h4 class="card-title">Modifier Evènement: <b>{{ $event->titre_fr }}</b></h4>
                        <h6 class="card-subtitle">Modifier les informations de l'évènement </h6>
                        <form method="post" action="{{ route('events.update', array('id'=>$event->id)) }}" id="event_edit" enctype="multipart/form-data">
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
                                    <div class="col-md-9 pl-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Pays :
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    @if((Auth::user()->is_super_admin == "0") && (Auth::user()->country == "ci")   )
                                                    <select name="country"  class="form-control selectpicker" >
                                                    <option data-content='<span class="flag-icon flag-icon-ci"></span> Côte d&#39ivoire' value="ci"></option>
                                                   </select>
                                                    @elseif ((Auth::user()->is_super_admin == "0") && (Auth::user()->country == "et")   )
                                                    <select name="country"  class="form-control selectpicker" >
                                                    <option data-content='<span class="flag-icon flag-icon-et"></span> Ethiopie' value="et"></option>
                                                   </select>
                                                   @elseif ((Auth::user()->is_super_admin == "0") && (Auth::user()->country == "sn")   )
                                                    <select name="country"  class="form-control selectpicker" >
                                                    <option data-content='<span class="flag-icon flag-icon-sn"></span> Sénégal' value="sn"></option>
                                                   </select>
                                                   @elseif ((Auth::user()->is_super_admin == "0") && (Auth::user()->country == "tn")   )
                                                    <select name="country"  class="form-control selectpicker" >
                                                    <option data-content='<span class="flag-icon flag-icon-tn"></span> Tunisie' value="tn"></option>
                                                   </select>
                                                   @elseif ((Auth::user()->is_super_admin == "0") && (Auth::user()->country == "cm")   )
                                                    <select name="country"  class="form-control selectpicker" >
                                                    <option data-content='<span class="flag-icon flag-icon-cm"></span> Cameroun' value="cm"></option>
                                                   </select>


                                                    @else
                        <select name="country"  class="form-control selectpicker" >

<option data-content='<span class="flag-icon flag-icon-tn"></span> Tunisie' value="tn"></option>
<option data-content='<span class="flag-icon flag-icon-sn"></span> Sénégal' value="sn"></option>
<option data-content='<span class="flag-icon flag-icon-et"></span> Ethiopie' value="et"></option>
<option data-content='<span class="flag-icon flag-icon-ci"></span>Côte d&#39ivoire' value="ci"></option>
<option data-content='<span class="flag-icon flag-icon-cm"></span> Cameroun' value="cm"></option>

</select>
                        @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Langue par défaut :
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <select name="lang" id="lang" data-required="1" class="form-control selectpicker" >
                                                        <option data-content='<span class="flag-icon flag-icon-gb"></span> Anglais' value="en" @if($event->lang == 'en') selected @endif></option>
                                                        <option data-content='<span class="flag-icon flag-icon-tn"></span> Arabe' value="ar" @if($event->lang == 'ar') selected @endif></option>
                                                        <option data-content='<span class="flag-icon flag-icon-fr"></span> Français' value="fr" @if($event->lang == 'fr') selected @endif></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Authentification
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <select name="auth" data-required="1" class="form-control">
                                                        <option value="mail"  @if($event->auth_type == 'mail') selected @endif>Login/Pwd</option>
                                                        <option value="code"  @if($event->auth_type == 'code') selected @endif>QR Code</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Titre : <span id="required_title" class="small"></span>
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">EN</span>
                                                        <input type="text" class="form-control" name="titre_en" id="titre_en" data-required="1" value="{{ old('title_en', $event->title_en) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">&nbsp;
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">FR</span>
                                                        <input type="text" class="form-control" name="titre_fr" id="titre_fr" data-required="1" value="{{ old('title_fr', $event->title_fr) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">&nbsp;
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">AR</span>
                                                        <input type="text" class="form-control" name="titre_ar" id="titre_ar" data-required="1" value="{{ old('title_ar', $event->title_ar) }}">
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">De :
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <input type="text" name="date_from" id="date_from" data-required="1" class="form-control" value="{{ old('date_from', $event->date_from->format('d-m-Y')) }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">A :
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <input type="text" name="date_to" id="date_to" data-required="1" class="form-control" value="{{ old('date_to', $event->date_to->format('d-m-Y')) }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Barème
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <input type="number" name="scale" data-required="1" min="0" value="{{ old('scale', $event->scale) }}" class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 text-center">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <label class="control-label">
                                                            Icone
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">

                                                                @if($event->icon != '' && File::exists(public_path().'/assets/uploads/events/'.$event->icon) )
                                                                     <img src="{{ asset('/assets/uploads/events/'.$event->icon) }}"/>
                                                                @else
                                                                     <img  src="{{ asset('assets/uploads/no_image.jpg') }}"/>
                                                                @endif

                                                            </div>
                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                            <div>
                                                                <span class="btn red btn-outline btn-file">
                                                                    <span class="fileinput-new btn waves-light btn-primary"> Choisir image </span>
                                                                    <span class="fileinput-exists btn waves-light btn-outline-warning"> Changer </span>
                                                                    <input type="file" name="icon" accept="image/png,image/jpg,image/jpeg"> </span>
                                                                <a href="javascript:;" class="btn waves-light btn-danger fileinput-exists" data-dismiss="fileinput"> Supprimer </a>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix margin-top-10">
                                                            <span class="label label-success">NOTE!</span> JPG, JPEG et PNG seulement.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <label class="control-label">
                                                            Splash Screen
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">

                                                                @if($event->splash != '' && File::exists(public_path().'/assets/uploads/events/'.$event->splash) )
                                                                     <img src="{{ asset('/assets/uploads/events/'.$event->splash) }}"/>
                                                                @else
                                                                     <img  src="{{ asset('assets/uploads/no_image.jpg') }}"/>
                                                                @endif

                                                            </div>
                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                            <div>
                                                                <span class="btn red btn-outline btn-file">
                                                                    <span class="fileinput-new btn waves-light btn-primary"> Choisir image </span>
                                                                    <span class="fileinput-exists btn waves-light btn-outline-warning"> Changer </span>
                                                                    <input type="file" name="splash" accept="image/png,image/jpg,image/jpeg"> </span>
                                                                <a href="javascript:;" class="btn waves-light btn-danger fileinput-exists" data-dismiss="fileinput"> Supprimer </a>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix margin-top-10">
                                                            <span class="label label-success">NOTE!</span> JPG, JPEG et PNG seulement.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <label class="control-label">
                                                            Logo
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">

                                                                @if($event->logo != '' && File::exists(public_path().'/assets/uploads/events/'.$event->logo) )
                                                                     <img src="{{ asset('/assets/uploads/events/'.$event->logo) }}"/>
                                                                @else
                                                                     <img  src="{{ asset('assets/uploads/no_image.jpg') }}"/>
                                                                @endif

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
                                                            <span class="label label-success">NOTE!</span> JPG, JPEG et PNG seulement.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-4">
                                        <a href="{{ route('events.index') }}" class="btn btn-outline-info">Annuler</a>
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

    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/form-validation.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js" type="text/javascript"></script>

    <!-- Color Picker Plugin JavaScript -->
    <script src="{{ asset('assets/plugins/jscolor/jscolor.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.selectpicker').selectpicker();
            $('#date_from').datepicker({
                format: 'dd-mm-yyyy',
                language: 'fr'
            }).datepicker("setDate",$('#date_from').val());
            $('#date_to').datepicker({
                format: 'dd-mm-yyyy',
                language: 'fr'
            });
            $('#required_title').text('Titre '+$("#lang").val().toUpperCase()+' est obligatoire!');
            $('#titre_'+$("#lang").val()).focus();
            $("#lang").change(function(){
                $('#required_title').text('Titre '+$(this).val().toUpperCase()+' est obligatoire!');
                $('#titre_'+$(this).val()).focus();
            });
        });
    </script>
@endsection
