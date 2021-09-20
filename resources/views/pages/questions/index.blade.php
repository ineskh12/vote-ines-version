@extends('layouts.app')
@section('pageTitle') List Questions @stop


@section('style')
<head>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

 </head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-switch/bootstrap-switch.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Liste questions</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Liste questions</li>
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
                        <h4 class="card-title">Liste de questions</h4>
                        <h6 class="card-subtitle">Gérer les questions du public </h6>
                        <div class="row">

                        <div class="col-3">
                        <input id="searchit" onkeyup="myFunction()" type="text" class="form-control rounded" placeholder="Search" aria-label="Search" />
                            </span>
</div></div>

                        <div class="table-responsive m-t-40">

                            <table id="results" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="60">Projet</th>
                                        <th width="200">Question</th>
                                        <th width="60">Status</th>
                                        <th width="60" onclick="sortByDate()" > <i onclick="sortByDate()" class="fa fa-sort" aria-hidden="true"></i> Date de création</th>
                                        <th width="60">Action</th>
                                    </tr>
                                </thead>

                                @php
                                    $ques = DB::table('questions')->get();
                                    @endphp


                                     @foreach ($ques as $ques)
                                     <tr>



<td> @foreach(\App\Models\Project::where('id',$ques->project_id)->get() as $name)
{{$name->titre_fr}} </br> @endforeach</td>
            <td>{{ $ques->question}}</td>
            @if($ques->status==0)
            <td>
            <label class="label label-danger">non valide</label>
            </td>
            @endif
            @if($ques->status==1)

            <td>
            <label class="label label-info">valide</label>
            </td>
            @endif

            <td> {{ date("d-m-Y H:i", strtotime($ques->created_at))}}</td>

            @if($ques->status==0)
            <td>
            <a href="{{ route('questions.validate',$ques->id) }}">
                                <button type="button" class="tooltips btn btn-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="valider cette question">
                                   <i class="fa fa-check"></i>
                                </button>
                            </a>
            </td>
            @endif
            @if($ques->status==1)

            <td>
                        <a href="{{ route('questions.invalidate',$ques->id) }}" >
                            <button type="button" class="tooltips btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="invalider cette question">
                              <i class="fa fa-times"></i>
                            </button>
                        </a>
            </td>
            @endif


        </tr>
           @endforeach


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
    <script>


function myFunction() {
  // Declare variables
  console.log("ok");
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchit");
  filter = input.value.toUpperCase();
  table = document.getElementById("results");
  tr = table.getElementsByTagName("tr");
  console.log("ddddddd");
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
  if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

          function sortByDate() {
            console.log("sorted");

            var tbody = document.querySelector("#results tbody");
            // get trs as array for ease of use
            var rows = [].slice.call(tbody.querySelectorAll("tr"));

            rows.sort(function(a,b) {
              return new Date(b.cells[3].innerHTML) - new Date(a.cells[3].innerHTML)  ;
            });

            rows.forEach(function(v) {
              tbody.appendChild(v); // note that .appendChild() *moves* elements
            });
          }





          </script>

@endsection
