@extends('layouts.app')
@section('pageTitle') Modifier Jury @stop


@section('style')
    <link href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" id="theme" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css" id="select" rel="stylesheet">
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Modifier Jury</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('events.index', request()->route('event_id')) }}">Evènement</a></li>
                <li class="breadcrumb-item"><a href="{{ route('judges.index', request()->route('event_id')) }}">Liste des Jury</a></li>
                <li class="breadcrumb-item active">Modifier Jury</li>
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
                        <h4 class="card-title">Modifier Jury: <b>{{ $judge->nom }} {{ $judge->prenom }}</b></h4>
                        <h6 class="card-subtitle">Modifier les information de compte jury </h6>
                        <form method="post" action="{{ route('judges.update', array('id'=>$judge->id)) }}" id="judge_edit"  data-id="{{ $judge->id }}" enctype="multipart/form-data">
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
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label class="control-label">Evènement
                                                <span class="text-danger"> * </span>
                                            </label>
                                            <select class="select2 form-control" data-placeholder='Choisir évènement' name="event_id" style="width: 100%; height:36px;">
                                                @foreach($events as $oneEvent)
                                                    @if ($oneEvent->lang == 'fr')
                                                        <option value="{{ $oneEvent->id }}" data-lang="fr" @if($oneEvent->id == $judge->event_id) selected @endif>{{ $oneEvent->title_fr}}</option>
                                                    @elseif($oneEvent->lang == 'en')
                                                        <option value="{{ $oneEvent->id }}" data-lang="en" @if($oneEvent->id == $judge->event_id) selected @endif>{{ $oneEvent->title_en}}</option>
                                                    @elseif($oneEvent->lang == 'ar')
                                                        <option value="{{ $oneEvent->id }}" data-lang="ar" @if($oneEvent->id == $judge->event_id) selected @endif>{{ $oneEvent->title_ar}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nom
                                                <span class="text-danger"> * </span>
                                            </label>
                                            <input type="text" name="nom" data-required="1" class="form-control" value="{{ old('nom', $judge->nom) }}"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Prénom
                                                <span class="text-danger"> * </span>
                                            </label>
                                                <input type="text" name="prenom" data-required="1" class="form-control" value="{{ old('prenom', $judge->prenom) }}"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Email
                                                <span class="text-danger"> * </span>
                                            </label>
                                                <input name="email" type="text" class="form-control" value="{{ old('email', $judge->email) }}"/>
                                        </div>
                                    </div>
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
                                        <div class="form-group">
                                            <label class="control-label">Mot de passe
                                            </label>
                                                <input name="password" id="password" type="password" class="form-control" />
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
                                        <a href="{{ route('judges.index', request()->route('event_id')) }}" class="btn btn-outline-info">Annuler</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js" type="text/javascript"></script>

    <script type="text/javascript">
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
    });
    </script>
@endsection
