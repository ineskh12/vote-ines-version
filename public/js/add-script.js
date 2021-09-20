function remove(table,id, id_percentage=  null){
     if(table == 'projectnote'){
        var pathname = window.location.pathname+'/..';
    }else{
        var pathname = window.location.pathname;  
    }
    
    swal({
            title: "Êtes-vous sûr?",
            text: "Vous êtes entrain de supprimer !",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Oui, supprimer !",
            cancelButtonText: "Non, annuler !",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: pathname+'/'+id,
                    data: {
                        id:id,
                        id_percentage:id_percentage,
                        _token : $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    datatype: "json",
                    success: function (data) {

                        if(table == 'percentages'){

                            if (data.status == true) {
                                swal("Supprimer!", "L'enregistrement a éte supprimé.", "success");
                                window.datatable.ajax.reload(); 

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

                            }else{
                                swal("Erreur!", "Une erreur est intervenue.", "error");
                                window.datatable.ajax.reload();
                            }

                        }else if(table == 'projectnote'){
                            swal.close();
                            setTimeout(location.reload.bind(location), 300);
                        }else{
                            if(data == true){
                                swal("Supprimer!", "L'enregistrement a éte supprimé.", "success");
                                window.datatable.ajax.reload();    
                            }else{
                                swal("Erreur!", "Une erreur est intervenue.", "error");
                                window.datatable.ajax.reload();
                            }
                        }
                        
                    },
                    error: function (data) {
                        swal("Annuler", "Une erreur est intervenue dans le script !", "error");
                    }
                });

            } else {
                swal("Annuler", " Votre est sécurisé :)", "error");
            }
        });
}




