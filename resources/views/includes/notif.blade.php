<div class="col-md-12" id="div_percentage">
	@if($data_sidebar['somme'] < 100)
    <div class="alert alert-danger" id="sum_percentage_div">
        <strong>Important!</strong> La somme des pourcentage pour l'évènement <b>{{ $data_sidebar['somme'] }}</b> est <b>{{ $data_sidebar['somme'] }} %</b> , Il manque <b>{{ 100 - $data_sidebar['somme'] }} %</b> encore à ajouter !! cliquer <a href ="{{route('percentages.create') }}">ici </a>
    </div>
    @endif

	@if(isset($data_sidebar['setting_note']))
    <div class="alert alert-danger" id="sum_percentage_div">
        <strong>Important!</strong> Il faut il configurer le module <b>Somme des votes</b>!! cliquer <a href ="{{route('settings.note') }}">ici </a>
    </div>
    @endif


</div>


