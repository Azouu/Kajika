<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php 
    			require_once('lib.php');
    			$col = $_GET['col'];
				echo $database->$col->findOne()->experimentName; 
			?> 
	</title>
	<?php require_once('dependencies.html'); ?>
	<script type="text/javascript" src="personnalisation.js"></script> 
</head>

<body>
<div class="wrapper">

	<?php require_once('menu.html'); ?>


	    <div class="container-fluid" id="main">
	    	<div class="wrapper">
	    		<!-- Bouton pour activer/désactiver la sidebar -->
		    	<a id="sidebarCollapse" class="btn my-auto">
					<i class="fas fa-align-left"></i>
				</a>
 
				<h1 class="mx-auto p-2" id="experiment-name">
					<?php
						echo $database->$col->findOne()->experimentName; 			
						$cursor = $database->$col->find();
						$document = $cursor->toArray();			
						$bson = MongoDB\BSON\fromPHP($document);			
						$json = MongoDB\BSON\toJSON($bson);
					?>
				</h1>

				<a id="graphBtn" class="btn my-auto" href="graph.php?col=<?php echo $col;?>" >
					<i class="fas fa-chart-bar"></i> Graphiques
				</a>

				<a id="optionsCollapse" class="btn my-auto">
					<i class="fas fa-cog"></i> Options
				</a>
			</div>
	
			<!-- Affichage du snapshot et de l'interface d'edit-->
			<div class="wrapper">

				<div class="row container-fluid">
		   		<div id="network" class="container-fluid col"></div> 
				    <div id="options-panel" class="container col active overflow-auto border ml-2 p-3"> 
				    	<h3> Options </h3>
				    	<h6> Graphe </h6> 
				    	<p onclick="networkManager.togglePhysics()"> Physique <input id="toggle-one" checked type="checkbox" data-onstyle="success" data-offstyle="danger" > </p>
				    	<p onclick="networkManager.toggleSelection()"> Sélection <input id="toggle-two" type="checkbox" data-onstyle="success" data-offstyle="danger"> </p>
				    	<h6 class="mt-4"> Lecteur </h6> 
				    	<!-- resets fps and restarts the player with new fps if the player is not paused -->
				    	<p class="d-inline"> FPS </p><input id="fps" class='form-control d-inline w-75 ml-1' type="number" value="1" min="0" onchange="player.setFPS(this.value); player.toggle(); player.toggle();">  

				    	<!--Tout ce qui concerne les input dynamiques avec les critères pour les entités à afficher sur le graphe -->
				    	<h6 class="mt-4"> Filtrage des entités </h6>

				    	 <label> Par ID </label>
				    	 <div class="controls" id="inputsID"> 
				    	 	<form onsubmit="return false;" autocomplete="off">
				                    <div class="entry input-group col-xs-3">
				                        <input class="form-control" name="fields[]" type="text" onchange="Customizer.checkIDInput(this, experiment)"/>
				                    	<span class="input-group-btn">
				                            <button class="btn btn-success btn-add" type="button">
				                                <i class="fas fa-plus"></i>
				                            </button>
				                        </span>
				                    </div>
				   			</form>
				        </div>
				        <div class="text-danger inputError  mb-2" id="alarmID"> </div>

				    	 <p> Par type </p>
				    	 <div class="controls" id="inputsType"> 
				    	 	<form onsubmit="return false;" autocomplete="off">
				                    <div class="entry input-group col-xs-3">
				                        <input class="form-control" name="fields[]" type="text" onchange="Customizer.checkTypeInput(this, experiment)"/>
				                    	<span class="input-group-btn">
				                            <button class="btn btn-success btn-add" type="button">
				                                <i class="fas fa-plus"></i>
				                            </button>
				                        </span>		                  
				                </div>
				            </form>
				        </div>
				        <div class="text-danger inputError  mb-2" id="alarmType"> </div>

				    	 <p> Par attribut </p>
				    	 <div class="controls" id="inputsAttribute" > 
				    	 		<form onsubmit="return false;" autocomplete="off">
				                    <div class="entry input-group col-xs-3">
				                        <input class="form-control" name="fields[]" type="text" onchange="Customizer.checkAttributeInput(this, experiment)"/>
				                    	<span class="input-group-btn">
				                            <button class="btn btn-success btn-add" type="button">
				                                <i class="fas fa-plus"></i>
				                            </button>
				                        </span> 
				                    </div>
				                </form>  
				        </div>
				        <div class="text-danger inputError mb-2" id="alarmAttribute"> </div>

				    	 <button type="button" class="btn btn-primary" onclick="reloadNetworkWithFilters(networkManager, experiment)">Appliquer</button>
				    </div>
				</div>
			</div>
		

		    <!-- Le "lecteur" qui permet de lire automatiquement les snapshots -->
		    <div class="mx-auto container" id="player">
		    	<!-- Le slider avec l'affichage dynamique du snapshot courant et de son nombre -->
		    	<div class="mx-auto row">
			    	<input type="range" name="pot" id="myRange" min='1' class="slider col-sm"> 
			    	<p class="col-"><span id="rangeValue"></span></p>
		  		 </div>

				 <!-- Le bouton play/pause avec les flèches pour avancer/reculer d'un pas -->
		  		 <div class="row align-items-center">
		  		 	<a class="col  arrow left text-center"><i class="fas fa-chevron-left player-btn"  onClick="networkManager.loadPrevious(player)" ></i></a>
		  		 	<div class="col text-center">
		  		 		<div onClick="player.toggle()" id="playpause">
							   <i class="fas fa-play player-btn" id="playpause-icn"></i>
		  		 		</div>
		  		 	</div>	
		  		 	<a class="col arrow right text-center"><i class="fas fa-chevron-right player-btn" onClick="networkManager.loadNext(player)"></i></a>
		  		</div>
			</div>

			<!-- Popup affichage expérience -->	
			<div class="modal" id="modal-experiment" tabindex="-1" role="dialog" aria-labelledby="experiment"> 
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-body">
							<button class="close" data-dismiss="modal" aria-label="Close">
								<span>&times;</span>
							</button>
							<h5 class="modal-title">Informations sur l'expérience </h5>
							<span id="experiment-information"></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Popup affichage entité -->	
			<div class="modal" id="modal-entity" tabindex="-1" role="dialog" aria-labelledby="experiment"> 
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-body">
							<button class="close" data-dismiss="modal" aria-label="Close">
								<span>&times;</span>
							</button>
							<span id="entity-information"></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Popup affichage relation -->	
			<div class="modal" id="modal-relation" tabindex="-1" role="dialog" aria-labelledby="experiment"> 
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-body">
							<button class="close" data-dismiss="modal" aria-label="Close">
								<span>&times;</span>
							</button>
							<span id="relation-information"></span>
						</div>
					</div>
				</div>
			</div>


		</div>
</div>

<script type="text/javascript">

	$('#optionsCollapse').on('click', function () {
	    $('#options-panel').toggleClass('active');
	});
	$(function() {
    	$('#toggle-one').bootstrapToggle();	
    	$('#toggle-two').bootstrapToggle();	
	});


	//Loads experiment and information 
	 json_experiment = <?php echo $json?>;
	var experiment = new Experiment(json_experiment);
	var networkManager = new NetworkManager(experiment);
	var sliderID = 'myRange';
	var player = new Player('myRange', 'rangeValue', networkManager);	
	document.getElementById(sliderID).oninput = function() {
	  		player.moveCursor(this.value);
	  		networkManager.loadSnapshot(parseInt(this.value, 10) - 1); 		
  	};
	
	$("#experiment-name").click(function() {
		DataWriter.showModal("#modal-experiment");
		$("#experiment-information").html(DataWriter.writeObjectHTML(experiment.getExperimentInformation()));
	}); 

	networkManager.network.on( 'selectNode', function(params) {
			DataWriter.showModal('#modal-entity');
			DataWriter.writeModalSelectedEntities(networkManager);
	});

	networkManager.network.on( 'selectEdge', function(params) {	
		if(params.nodes.length === 0) { 
			DataWriter.showModal('#modal-relation');
			DataWriter.writeModalSelectedRelations(networkManager);
		}
	});


	$(function()
	{
	    $(document).on('click', '.btn-add', function(e)
	    {
	        e.preventDefault();

	        //get the id of the 'control' class parent, which is either 'inputsID' or 'inputsType' or 'inputsAttribute'
	        var idParent = $(this).parents('.controls').attr('id');
	        var controlForm = $('#' + idParent + '.controls form:first'),
	            currentEntry = $(this).parents('.entry:first'),
	            newEntry = $(currentEntry.clone()).appendTo(controlForm);

	        switch(idParent) {
	        	case 'inputsID':
	        		newEntry.on('change', Customizer.checkIDInput(newEntry, experiment));
	        	break;
	        	case 'inputType':
	        		newEntry.on('change', Customizer.checkTypeInput(newEntry, experiment));
	        	break;
	        	case 'inputsAttribute' :
	        		newEntry.on('change', Customizer.checkAttributeInput(newEntry, experiment));
	        	break;
	        }

	        newEntry.find('input').val('');
	        controlForm.find('.entry:not(:last) .btn-add')
	            .removeClass('btn-add').addClass('btn-remove')
	            .removeClass('btn-success').addClass('btn-danger')
	            .html('<i class="fas fa-minus"></i>');
	   			 }).on('click', '.btn-remove', function(e)
				    {
						$(this).parents('.entry:first').remove();

						e.preventDefault();
						return false;
					});
	});


	function reloadNetworkWithFilters(networkManager, experiment) {
		var currentIndex = networkManager.currentIndex;
		networkManager = new NetworkManager(experiment);
		networkManager.currentIndex = currentIndex;
		networkManager.loadSnapshot(currentIndex);
	}

</script>
</body>
</html>