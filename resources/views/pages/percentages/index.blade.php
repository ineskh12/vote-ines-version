@extends('layouts.app')
@section('pageTitle') List Pourcentages @stop


@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-switch/bootstrap-switch.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <style type="text/css">
        .bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-retroorange{
          color: #fff;
          background: #03A9F4;
        }

        .bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-retroorange{
            color: #fff;
            background: #7460ee;
        }
    </style>
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Liste pourcentages</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Liste pourcentages</li>
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
                        <h4 class="card-title">Liste pourcentages</h4>
                        <input type='hidden' id='event_id' value="{{ request()->get('event_id') }}"/>
                        <a href="{{ route('percentages.create') }}" class="waves-effect waves-light ribbon ribbon-success ribbon-right">Nouvelle pourcentage</a>
                        <h6 class="card-subtitle">Gérer les pourcentages de vote </h6>
                        <div class="table-responsive m-t-40">
                            <table id="percentages-table" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Evènement</th>
                                        <th>Titre</th>
                                        <th>Pourcentage</th>
                                        <th width="60">Type</th>
                                        <th width="60">Statut</th>
                                        <th width="60">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Evènement</th>
                                        <th>Titre </th>
                                        <th>Pourcentage</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
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

    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>


    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/jquery.sweet-alert.custom.js') }}"></script>

    
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('js/data-table.js') }}"></script>
    <script src="{{ asset('js/add-script.js') }}"></script>
@endsection