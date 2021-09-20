@extends('layouts.app')
@section('pageTitle') Noter un projet @stop


@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Noter un projet</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Noter un projet</li>
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
                        <h4 class="card-title">Noter le projet: <b> @if($event->lang == 'fr') {{ $project->titre_fr }} @elseif($event->lang == 'en') {{ $project->titre_en }} @else {{ $project->titre_ar }} @endif</b></h4>
                        <h6 class="card-subtitle">Note réservée aux administrateurs </h6>
                        @if(count($percentages) == 0)
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <div class="alert alert-info" role="alert">
                                        <h4 class="alert-heading">La note Back-Office n'est pas configurée!</h4>
                                        <p>Vous n'avez pas configuré une note back-office pour l'évenement <b>@if($event->lang == 'fr') {{ $event->title_fr }} @elseif($event->lang == 'en') {{ $event->title_en }} @else {{ $event->title_ar }} @endif</b></p>
                                        <hr>
                                        <p class="mb-0">Vous pouvez configurer une <a href="{{ route('percentages.create') }}">ici</a>.</p>
                                    </div>
                                </div>
                                <div class="col-4"></div>
                            </div>
                        @else                 
                            <form method="post" action="{{ route('notes.backoffice.store') }}" id="note_create" enctype="multipart/form-data">
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
                                    
                                   
                                    @foreach ($percentages as $percentage)
                                        <div class="row">
                                            <div class="col-md-4 pl-5">@if($event->lang == 'fr') {{ $percentage->titre_fr }} @elseif($event->lang == 'en') {{ $percentage->titre_en }} @else {{ $percentage->titre_ar }} @endif</div>
                                            <div class="col-md-4 pr-5"><input type="number" min="0" max="{{ $event->scale }}" name="note{{ $percentage->id }}" value="@if(isset($currentNotes[$percentage->id])){{ $currentNotes[$percentage->id] }}@else{{0}}@endif" class="form-control" /> </div>
                                            <div class="col-md-4 pr-5" style="font-size:xx-large;">/ {{ $event->scale }}</div>
                                        </div>
                                        <hr>
                                    @endforeach                                    
                                </div>
                                <div class="form-actions mt-5">
                                    <div class="row">
                                        <div class="col-md-5"></div>
                                        <div class="col-md-4">
                                            <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
                                            <a href="{{ route('projects.index') }}" class="btn btn-outline-info">Annuler</a>
                                            <button type="submit" class="btn waves-effect waves-light btn-success">Ajouter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
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
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/form-validation.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/data-table.js') }}"></script>
    <script src="{{ asset('js/add-script.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/form-validation.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>


    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
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