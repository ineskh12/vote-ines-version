$(function() {
    window.allproduct = true;
    var handleBootstrapSwitch = function() {
        $('.tooltips').tooltip();
        $('.make-switch').bootstrapSwitch().on("switchChange.bootstrapSwitch", function (event, state) {
            var switcher = $(this);
            var pathname = window.location.pathname;
            var switch_for = $(this).data('for');
            var statut = switcher.is('checked');
            $.ajax({
                url: pathname+'/status',
                data: {
                    id: switcher.data('id'),
                    _token : $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                datatype: "json",
                success: function (data) {

                     if(switch_for == 'percentages'){

                        if (data.status == true) {
                            $.toast({
                                heading: 'Modification du statut',
                                text: 'A été faite avec succés.',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'success',
                                hideAfter: 3500,
                                stack: 6
                              });

                            if(data.somme < 100){
                                if($('#sum_percentage').length == 0){
                                    $('#href_percentage').append('<span class="label label-rouded label-danger pull-right" id="sum_percentage">'+data.somme+' %</span>');
                                }else{
                                    $('#sum_percentage').html(data.somme+' %');
                                }

                                if($('#sum_percentage_div').length == 0){
                                    $('#div_percentage').append('<div class="alert alert-danger" id="sum_percentage_div"><strong>Important!</strong> La somme des pourcentage est <b>'+data.somme+' %</b> , Il manque <b>'+ (100-data.somme) +' %</b> encore à ajouter !!</div>');
                                }else{
                                    $('#sum_percentage_div').html('<strong>Important!</strong> La somme des pourcentage est <b>'+data.somme+' %</b> , Il manque <b>'+ (100-data.somme) +' %</b> encore à ajouter !!');
                                }


                            }else{
                                $('#sum_percentage').remove();
                                $('#sum_percentage_div').remove();
                            }

                        }else if (data.status == false) {

                            switcher.bootstrapSwitch('state', statut , true);
                            $.toast({
                                heading: 'Modification du statut',
                                text: 'La valeur de pourcentage ne peut pas déppaser '+data.somme+'% .',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'warning',
                                hideAfter: 3500,
                                stack: 6
                            });

                        }else{
                            switcher.bootstrapSwitch('state', statut , true);
                            $.toast({
                                heading: 'Modification du statut',
                                text: 'n\'été pas faite avec succés.',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'error',
                                hideAfter: 3500

                              });
                        }

                     }else{
                        if (data == true) {
                            $.toast({
                                heading: 'Modification du statut',
                                text: 'A été faite avec succés.',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'success',
                                hideAfter: 3500,
                                stack: 6
                              });

                        }else{
                            switcher.bootstrapSwitch('state', statut , true);
                            $.toast({
                                heading: 'Modification du statut',
                                text: 'Une erreur est intervenue.',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'warning',
                                hideAfter: 3500,
                                stack: 6
                            });
                        }
                 }

                },
                error: function () {
                    switcher.bootstrapSwitch('state', statut , true);

                    $.toast({
                        heading: 'Modification du statut',
                        text: 'n\'été pas faite avec succés.',
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'error',
                        hideAfter: 3500

                      });
                }
            });
        });
    };
    /*** Begin adminstrateurs ***/
    if ($('#admins-table').length) {
        window.datatable = $('#admins-table').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 5, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "admins/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'country', name: 'country' },
                { data: 'avatar', name: 'avatar' , orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'prenom', name: 'prenom' },
                { data: 'email', name: 'email' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End adminstrateurs ***/

    /*** Begin events ***/
    if ($('#events-table').length) {
        window.datatable = $('#events-table').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 7, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "events/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'country', name: 'country' , orderable: false, searchable: false },
                { data: 'logo', name: 'logo' , orderable: false, searchable: false },
                { data: 'titre', name: 'titre' },
                { data: 'lang', name: 'lang' },
                { data: 'auth', name: 'auth' },
                { data: 'date_from', name: 'date_from' },
                { data: 'date_to', name: 'date_to' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'projects', name: 'projects' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End events ***/

    /*** Begin Judges ***/
    if ($('#judges-table').length) {
        var eventId = $("#event_id").val();
        window.datatable = $('#judges-table').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 1, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "judges/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content'),
                    "event_id" : eventId
                }
            },
            columns: [
                { data: 'country', name: 'country', orderable: false, searchable: false },
                { data: 'event', name: 'event' },
                { data: 'nom', name: 'nom' },
                { data: 'prenom', name: 'prenom' },
                { data: 'email', name: 'email' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End Judges ***/

    /*** Begin products ***/
    if ($('#projects-table').length) {
        $('#projects-table thead tr').clone(true).appendTo( '#projects-table thead' );
        $('#projects-table thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            $(this).html( '<input type="text" class="header-search" placeholder="Recherche" />' );

            $( 'input', this ).on( 'keyup change', function () {
                if ( window.datatable.column(i).search() !== this.value ) {
                    window.datatable
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        });

        var eventId = $("#event_id").val();

        window.datatable = $('#projects-table').DataTable({
            processing: true,
            serverSide: false,
            orderCellsTop: true,
            sfixedHeader: true,
            order: [[ 3, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "projects/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content'),
                    "event_id": eventId
                }
            },
            columns: [
                { data: 'event_id', name: 'event_id'},
                { data: 'logo', name: 'logo' , orderable: false, searchable: false },
                { data: 'titre', name: 'titre' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End products ***/

    /*** Begin questions ***/
    if ($('#questions-table').length) {
        window.datatable = $('#questions-table').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 50,
            order: [[ 3, "desc" ],[ 2, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "questions/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'title_fr', name: 'title_fr' , orderable: false, searchable: false },
                { data: 'question', name: 'question' , orderable: false, searchable: false },
                { data: 'status', name: 'status'},
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End questions ***/



    /*** Begin products ***/
    if ($('#projects-notebackoffice-table').length) {
        window.datatable = $('#projects-notebackoffice-table').DataTable({
            processing: true,
            serverSide: false,
            order: false,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "notebackoffice",
                "type": "POST",
                "data" : {
                    "percentage_id" : $('#percentage_id').val(),
                    "_token" : $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'event_id', name: 'event_id' },
                { data: 'logo', name: 'logo' , orderable: false, searchable: false },
                { data: 'titre', name: 'titre' },
                { data: 'note', name: 'note' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            // drawCallback: handleBootstrapSwitch
        });
    }
    /*** End products ***/





    if ($('#criterias-table').length) {
        var eventId = $("#event_id").val();
        window.datatable = $('#criterias-table').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 1, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "criterias/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content'),
                    "event_id" :eventId
                }
            },
            columns: [
                { data: 'event_id', name: 'event_id' },
                { data: 'category', name: 'category' },
                { data: 'titre', name: 'titre' },
                { data: 'percentage_id', name: 'percentage_id' },
                { data: 'coefficient', name: 'coefficient' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }

    if ($('#categories-table').length) {
        var eventId = $("#event_id").val();
        window.datatable = $('#categories-table').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 1, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "categories/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content'),
                    "event_id" :eventId
                }
            },
            columns: [
                { data: 'event_id', name: 'event_id' },
                { data: 'titre', name: 'titre' },
                { data: 'coefficient', name: 'coefficient' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at', orderable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });


    }



        /*** Begin products ***/
    if ($('#percentages-table').length) {
        var eventId = $("#event_id").val();
        window.datatable = $('#percentages-table').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 5, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "percentages/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content'),
                    "event_id": eventId
                }
            },
            columns: [
                { data: 'event_id', name: 'event_id' },
                { data: 'titre', name: 'titre' },
                { data: 'percentage', name: 'percentage' },
                { data: 'type', name: 'type' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End products ***/


        /*** Begin products ***/
    if ($('#products-table-accesoires').length) {
            var url = "accesoires";
        if ($("#product_id").length > 0) {
            url = "../accesoires";
        }
        window.datatable = $('#products-table-accesoires').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 50,
            order: [[ 5, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": url,
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                {
                    "mData": null,
                    "bSortable": false,
                    "mRender": function(data, type, full) {
                        if ($("#product_id").length > 0) {
                            var ids = [];
                            var cheked = "";
                            for(var i= 0; i < full.parents_accessoires.length; i++)
                            {
                                ids.push(full.parents_accessoires[i].id);
                            }

                            if(ids.includes($('#product_id').val())){
                                cheked = "checked";
                            }
                            return '<label class="mt-checkbox"><input type="checkbox" '+cheked+' value="'+full.id+'" name="products[]" class="prod"><span></span></label>';
                        }else{
                         return '<label class="mt-checkbox"><input type="checkbox" name="products[]"  value="'+full.id+'" class="prod"><span></span></label>';
                        }
                    }
                },
                { data: 'image', name: 'image' , orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'categorie_id', name: 'categorie_id' },
                { data: 'description', name: 'description' },
                { data: 'price', name: 'price' }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End products ***/
    /*** Begin users ***/
    if ($('#users-table').length) {
        window.datatable = $('#users-table').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 5, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "users/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'avatar', name: 'avatar' , orderable: false, searchable: false },
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'email', name: 'email' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End users ***/

    /*** Begin adminstrateurs ***/
    // if ($('#categories-table').length) {
    //     window.datatable = $('#categories-table').DataTable({
    //         processing: true,
    //         serverSide: false,
    //         order: [[ 5, "desc" ]],
    //         language: {
    //             url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
    //         },
    //         ajax: {
    //             "url": "categories/datatable",
    //             "type": "POST",
    //             "data" : {
    //                 "_token" : $('meta[name="csrf-token"]').attr('content')
    //             }
    //         },
    //         columns: [
    //             { data: 'logo', name: 'logo' , orderable: false, searchable: false },
    //             { data: 'title', name: 'title' },
    //             { data: 'description', name: 'description' },
    //             { data: 'status', name: 'status' },
    //             { data: 'accessoire', name: 'accessoire' },
    //             { data: 'created_at', name: 'created_at' },
    //             { data: 'actions', name: 'actions', orderable: false, searchable: false }
    //         ],
    //         drawCallback: handleBootstrapSwitch
    //     });
    // }
    /*** End adminstrateurs ***/

   /*** Begin orders ***/
    if ($('#orders-table').length) {
        window.datatable = $('#orders-table').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 5, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "orders/datatable",
                "type": "POST",
                "data" : {
                    "_token" : $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'id', name: 'id'},
                { data: 'user', name: 'user' },
                { data: 'nbr_products', name: 'nbr_products' },
                { data: 'total', name: 'total' },
                { data: 'status', name: 'status' , orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End orders ***/

   /*** Begin orders user ***/
    if ($('#orders-table-user').length) {
         window.order_datatable = $('#orders-table-user').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 4, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "../../users/orders/datatable",
                "type": "POST",
                "data" : {
                    "id" : $('#user_id').val(),
                    "_token" : $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'id', name: 'id', sWidth: '20%'},
                { data: 'nbr_products', name: 'nbr_products' },
                { data: 'total', name: 'total' },
                { data: 'status', name: 'status' , orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            autoWidth: false,
            columnDefs: [{
                targets: 0,
                width: '110px'
            },{
                targets: 4,
                width: '120px'
            }],
            drawCallback: function(){
                $(".select2-allow-clear").select2({
                    allowClear: true,
                    placeholder: $(this).data('placeholder'),
                    width: null
                });
            }
        });
    }
    /*** End orders user ***/

    /*** Begin adress user ***/
    if ($('#adress-table-user').length) {
        window.datatable = $('#adress-table-user').DataTable({
            processing: true,
            serverSide: false,
            order: [[ 6, "desc" ]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"
            },
            ajax: {
                "url": "../../users/adress/datatable",
                "type": "POST",
                "data" : {
                    "id" : $('#user_id').val(),
                    "_token" : $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'libele', name: 'libele'},
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'adress', name: 'adress' },
                { data: 'principal', name: 'principal' },
                { data: 'status', name: 'status' , orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            autoWidth: false,
            columnDefs: [{
                targets: 0,
                width: '110px'
            },{
                targets: 4,
                width: '120px'
            }],
            drawCallback: handleBootstrapSwitch
        });
    }
    /*** End adress user ***/

});
