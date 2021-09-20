@extends('layouts.app')
@section('pageTitle') Dashboard @stop

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/plugins/morrisjs/morris.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('assets/plugins/morrisjs/morris.css') }}" id="theme" rel="stylesheet">
    <style type="text/css">
        .res-img:hover{
            cursor: pointer;
        }
    </style>
@endsection


@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Dashboard </h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"></li>
            </ol>
        </div>
    </div>
    @include('includes.notif')
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                @foreach ($events as $event)
                    <div class="row">
                        <!-- column -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row" id="main_content">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-7">
                                            <img src="{{ asset('assets/uploads/events/'.$event->splash) }}" class="img-responsive">
                                            <center><a href="{{  route('resultats.index', ['event_id' => $event->id]) }}" target="_blank">
                                                <img src="{{ asset('assets/images/result.png') }}" class="img-responsive col-md-4 col-6 res-img"></a></center>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
            </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection


@section('script')
    <script src="{{ asset('assets/plugins/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/add-script.js') }}"></script>
    <script type="text/javascript">
        function load_main_content()
        {
            $('#main_content').load('admin/resultats/show_chart');
        }
    </script>
@endsection
