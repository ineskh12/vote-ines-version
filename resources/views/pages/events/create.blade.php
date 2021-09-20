@extends('layouts.app')
@section('pageTitle') Ajouter Evènement @stop


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
            <h3 class="text-themecolor">Ajouter Evènement</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Liste des évènements</a></li>
                <li class="breadcrumb-item active">Ajouter un évènement</li>
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
                        <h4 class="card-title">Ajouter un évènement</h4>
                        <h6 class="card-subtitle">Ajouter un nouveau évènement de compétition</h6>
                        <form method="post" action="{{ route('events.store') }}" id="event_create" enctype="multipart/form-data">
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
                                        <strong>Erreur!</strong> <br>
                                        {!! $message !!}
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
                                                        <option data-content='<span class="flag-icon flag-icon-gb"></span> Anglais' value="en"></option>
                                                        <!-- <option data-content='<span class="flag-icon flag-icon-tn"></span> Arabe' value="ar"></option> -->
                                                        <option data-content='<span class="flag-icon flag-icon-fr"></span> Français' value="fr"></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Authentification
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <select name="auth" data-required="1" class="form-control">
                                                        <option value="mail">Login/Pwd</option>
                                                        <option value="code">QR Code</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Titre :
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">EN</span>
                                                        <input type="text" class="form-control" name="titre_en" id="titre_en"  data-required="1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">&nbsp;
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">FR</span>
                                                        <input type="text" class="form-control" name="titre_fr" id="titre_fr" data-required="1">
                                                    </div>
                                                </div>
                                            </div>

                                             <!-- // titre en AR
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">&nbsp;
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"> AR</span>
                                                        <input type="text" class="form-control" name="titre_ar" id="titre_ar" data-required="1">
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
                                                    <input type="text" name="date_from" id="date_from" data-required="1" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">A :
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <input type="text" name="date_to" id="date_to" data-required="1" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Barème
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <input type="number" name="scale" data-required="1" min="0" value="0" class="form-control"/>
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
                                                                <img  src="{{ asset('assets/uploads/no_image.jpg') }}"/>
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
                                                                <img  src="{{ asset('assets/uploads/no_image.jpg') }}"/>
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
                                                            <span class="label label-success">NOTE!</span> JPG, JPEG et PNG seulement.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-4">
                                        <a href="{{ route('events.index') }}" class="btn btn-outline-info">Annuler</a>
                                        <button onClick="goin()" type="submit" class="btn waves-effect waves-light btn-success">Ajouter</button>
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

function goin() {

var e = document.getElementById("lang");
var strUser = e.value;
if(strUser === "fr"){

    document.getElementById("titre_fr").required = true;

}
else if(strUser === "en"){

document.getElementById("titre_en").required = true;

}
}


        $(document).ready(function(){
            $('.selectpicker').selectpicker();
            $('#date_from').datepicker({
                format: 'dd-mm-yyyy',
                language: 'fr'
            }).datepicker("setDate",'now');
            $('#date_to').datepicker({
                format: 'dd-mm-yyyy',
                language: 'fr'
            }).datepicker("setDate",'now');
            $('#required_title').text('Titre '+$("#lang").val().toUpperCase()+' est obligatoire!');
            $('#titre_'+$("#lang").val()).focus();
            $("#lang").change(function(){
                $('#required_title').text('Titre '+$(this).val().toUpperCase()+' est obligatoire!');
                $('#titre_'+$(this).val()).focus();
            });
        });
    </script>
@endsection
