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
	<script type="text/javascript" src="customizationVariables.js"></script> 
	<script type="text/javascript" src="config.js"> </script>
	<?php require_once('dependencies.html'); ?>
</head>

<body>
<div class="wrapper">

	<?php 
		require_once('menu.html'); 
		$filterEntryModel = '<form onsubmit="return false;" autocomplete="off">' .
				                    '<div class="entry input-group col-xs-3">' .
				                       '<input class="form-control" name="fields[]" type="text" onchange="Customizer.checkTypeInput(this, experiment)"/>' . 
				                    	'<span class="input-group-btn">' . 
				                            '<button class="btn btn-danger btn-remove" type="button">'.
				                                '<i class="fas fa-minus"></i>'.
				                            '</button>' .
				                        '</span>' .	                  
				                '</div>' . 
				            '</form>';
	?>


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
					<i class="fas fa-chart-bar"></i> Charts
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
				    	<h6> Network </h6> 
				    	<p> Physics <input id="toggle-one" checked type="checkbox" data-onstyle="success" data-offstyle="danger" onchange="networkManager.togglePhysics()"> </p>

				    	<button class="d-inline-block btn btn-light mb-2" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" 
					   	data-content="Select one or many entities holding CTRL while clicking, then toggle this button to show only the <b>selected entities and their neighbors</b>" > 
					    	 	<i class="fas fa-question-circle"></i> 
					    </button>	
				    	<p class="d-inline"> Selection <input id="toggle-two" type="checkbox" data-onstyle="success" data-offstyle="danger" class="d-inline" onchange="networkManager.toggleSelection()"> </p>
				    	<h6 class="mt-4"> Player </h6> 
				    	<!-- resets fps and restarts the player with new fps if the player is not paused -->
				    	<button class="d-inline-block btn btn-light mb-2" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" 
					   	data-content="<b> Frame Per Second </b> : set the speed of the network player" > 
					    	 	<i class="fas fa-question-circle"></i> 
					    </button>	
				    	<p class="d-inline"> FPS </p> 
				    	<input id="fps" class='form-control d-inline w-25 ml-1' type="number" value="1" min="0" onchange="player.setFPS(this.value); player.toggle(); player.toggle();">  

				    	<!--Tout ce qui concerne les input dynamiques avec les critères pour les entités à afficher sur le graphe -->
				    	<div>
				    		<h6 class="d-inline-block mt-4"> Entity filter </h6> 
				    		
				   		 </div>


				    	 <label> By ID </label>
				    	 <div class="controls" id="inputsID"> 
				    	 	<form onsubmit="return false;" autocomplete="off">
				                    <div class="entry input-group col-xs-3">
				                        <input class="form-control" name="fields[]" type="text"/>
				                    	<span class="input-group-btn">
				                            <button class="btn btn-danger btn-remove" type="button">
				                                <i class="fas fa-minus"></i>
				                            </button>
				                        </span>
				                    </div>
				   			</form>
				   			<button class="btn btn-success btn-add" type="button">
				            	<i class="fas fa-plus"></i> Add input
				        	</button>
				        </div>

				        <br>

				    	 <label> By type </label> 
				    	 <div class="controls" id="inputsType"> 
				    	 	<form onsubmit="return false;" autocomplete="off">
				                    <div class="entry input-group col-xs-3">
				                        <input class="form-control" name="fields[]" type="text"/>
				                    	<span class="input-group-btn">
				                            <button class="btn btn-danger btn-remove" type="button">
				                                <i class="fas fa-minus"></i>
				                            </button>
				                        </span>		                  
				                </div>
				            </form>
				            <button class="btn btn-success btn-add" type="button">
				            	<i class="fas fa-plus"></i> Add input
				      	    </button>
				        </div>
				         
						<br>

					    <label class="d-inline-block"> By attribute </label>
					    <button class="d-inline-block btn btn-light" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" title="Operators" 
					   	data-content="[attribute name] [operator] [value] <br>
					   		<b> Operators  </b>: <br>
					   		lt : &lt; <br>
					   		let : &lt;= <br>
					   		gt : &gt;<br>
					   		get : &gt;= <br>
					   		eq : == <br>
					   		neq : !=" > 
					    	 	<i class="fas fa-question-circle"></i> 
					    </button>	
				    	 <div class="controls" id="inputsAttribute" > 
				    	 		<form onsubmit="return false;" autocomplete="off">
				                    <div class="entry input-group col-xs-3">
				                        <input class="form-control" name="fields[]" type="text"/>
				                    	<span class="input-group-btn">
				                            <button class="btn btn-danger btn-remove" type="button">
				                                <i class="fas fa-minus"></i>
				                            </button>
				                        </span> 
				                    </div>
				                </form>  
				            <button class="btn btn-success btn-add" type="button">
				            	<i class="fas fa-plus"></i> Add input
				        	 </button>
				        </div>
				      <br>

				        <div class="" id="errorBox">
				    		
				        </div>
				        <!-- Boutons pour appliquer ou enlever les options de filtrage -->
				    	 <button type="button" class="d-inline-block btn btn-primary" onclick="reloadNetworkWithFilters()">
				    		 Apply filters
				    	</button>
				    	 <button type="button" class="d-inline-block btn btn-outline-primary " onclick="resetFilters()">
				    		<i class="fas fa-sync"></i> Reset
				    	</button>

				    </div>
				</div>
			</div>
		

		    <!-- Le "lecteur" qui permet de lire automatiquement les snapshots -->
		    <div class="mx-auto container" id="player">
		    	<!-- Le slider avec l'affichage dynamique du snapshot courant et de son nombre -->
		    	<div class="mx-auto row">
					<div class="col-sm">
			    		<input type="range" name="pot" id="myRange" min='1' class="slider"> 
					</div>
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
							<h5 class="modal-title">Experiment information </h5>
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
							<h5 class="modal-title">Entity information</h5>
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
							<h5 class="modal-title">Relation information </h5>
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
    	$('[data-toggle="popover"]').popover();
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

	

	// onclick events of the buttons in the "Entity filter" compartment of the options panel
	$(function() {
	    $(document).on('click', '.btn-add', function(e) {
	        var idParent = $(this).parents('.controls').attr('id');  //get the id of the 'control' class parent, which is either 'inputsID' or 'inputsType' or 'inputsAttribute'
	        var controlForm = $('#' + idParent + ' form');
	        var entries = $('#' + idParent + ' form .entry');
	        var currentEntry = $(entries[entries.length - 1]);
	        if (currentEntry.children('input').val() != '') {
	        	var newEntry = currentEntry.clone().appendTo(controlForm);
	        	$(newEntry).children('input').val('');
	  		}
	  	});

	   	$(document).on('click', '.btn-remove', function(e) {	
			var idParentControls = $(this).parents('.controls').attr('id');
	        var nbEntries = $('#' + idParentControls + ' form .entry').length;
	        var currentEntry = $(this).parents('.entry');
			// don't allow to remove an entry if there is only one in the form
			if (nbEntries > 1) {
				$(currentEntry).remove();
			} else { //If there is only one entry, we empty its input
				$(currentEntry).children('input').val('');
			} 
		});
	});



	function checkFilters() {
		var refreshOK = true;
		for (var entry of $('.entry')) {
			var idParent = $(entry).parents('.controls').attr('id');
			var input = $(entry).find('input');
			switch (idParent) {
				case 'inputsID' :
				 	if (! Customizer.checkIDInput(input, experiment)) {
				 		return false;		
				 	}
				break;
				case 'inputsType' :
					if (! Customizer.checkTypeInput(input, experiment)) {
						return false;
					}
				break;
				case 'inputsAttribute' :
					if (! Customizer.checkAttributeInput(input, experiment)) {
						return false;
					}
				break;
			} 
		}
		return true;
	}


	function reloadNetworkWithFilters() {
		if (checkFilters()) {
			$("#errorBox").removeClass('alert alert-danger');
			$("#errorBox").html('');
			var currentIndex = networkManager.currentIndex;
			networkManager = new NetworkManager(experiment);
			networkManager.currentIndex = currentIndex;
			networkManager.loadSnapshot(currentIndex);
		}
	}

	function resetFilters() {
		var inputsIDsArray = ['#inputsID', '#inputsType', '#inputsAttribute'];
		for (var inputsID of inputsIDsArray) {
			var inputsGroupArray = inputsID + ' form';
			$(inputsGroupArray).remove();
			$(inputsID).prepend('<?php echo $filterEntryModel ?>');
		}
		reloadNetworkWithFilters(networkManager, experiment);
	}

</script>
</body>
</html>