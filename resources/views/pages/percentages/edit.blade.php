@extends('layouts.app')
@section('pageTitle') Modifier Pourcentage @stop


@section('style')
    <link href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" id="theme" rel="stylesheet">
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Modifier Pourcentage</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('percentages.index') }}">Liste pourcentages</a></li>
                <li class="breadcrumb-item active">Modifier pourcentage</li>
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
                        <h4 class="card-title">Modifier Pourcentage: <b>{{ $percentage->titre_fr }}</b></h4>
                        <h6 class="card-subtitle">Modifier les critére de vote au systéme </h6>
                        <form method="post" action="{{ route('percentages.update', array('id'=>$percentage->id)) }}" data-id='{{ $percentage->id }}' id="percentage_edit" enctype="multipart/form-data">
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
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">Evènement
                                                <span class="text-danger"> * </span>
                                            </label>
                                            <select class="select2 form-control" data-placeholder='Choisir évènement' name="event_id" style="width: 100%; height:36px;">
                                                @foreach($events as $event)
                                                    @if ($event->lang == 'fr')
                                                        <option value="{{ $event->id }}" data-lang="fr" @if($percentage->event_id ==0 or $event->id == $percentage->event_id) selected @endif>{{ $event->title_fr}}</option>
                                                    @elseif($event->lang == 'en')
                                                        <option value="{{ $event->id }}" data-lang="en" @if($event->id == $percentage->event_id) selected @endif>{{ $event->title_en}}</option>
                                                    @elseif($event->lang == 'ar')
                                                        <option value="{{ $event->id }}" data-lang="ar" @if($event->id == $percentage->event_id) selected @endif>{{ $event->title_ar}}</option>
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
                                                <span class="text-danger"> * </span>
                                            </label>
                                            <input type="text" name="titre_en" data-required="1" class="form-control" value="{{ old('titre_en', $percentage->titre_en) }}"/> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Titre FR
                                                <span class="text-danger"> * </span>
                                            </label>
                                            <input type="text" name="titre_fr" data-required="1" class="form-control" value="{{ old('titre_fr', $percentage->titre_fr) }}"/> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Titre AR
                                                <span class="text-danger"> * </span>
                                            </label>
                                                <input type="text" name="titre_ar" data-required="1" class="form-control" value="{{ old('titre_fr', $percentage->titre_ar) }}" />
                                        </div>
                                    </div>
<!--                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Type de Vote
                                                <span class="text-danger"> * </span>
                                            </label>
                                            <div class="demo-radio-button">
                                                <input type="radio" name="type" id="radio_30"  value='1' @if($percentage->type == 1) checked @endif class="with-gap radio-col-orange">
                                                <label for="radio_30">Mobile</label>
                                                <input type="radio" name="type" id="radio_31" value='0' @if($percentage->type == 0) checked @endif class="with-gap radio-col-orange">
                                                <label for="radio_31">Back office</label>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">Pourcentage
                                                <span class="text-danger"> * </span>
                                            </label>
                                            <input name="percentage" type="text" class="form-control" value="{{ old('coefficient', $percentage->percentage) }}" />
                                            <input type="hidden" name="event_id_req" value="{{ request()->route('event_id') }}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-4">
                                        <a href="{{ route('percentages.index', request()->route('event_id')) }}" class="btn btn-outline-info">Annuler</a>
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