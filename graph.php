<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Visualisation graphique </title>
    <?php require_once("dependencies.html") ?>

</head>


<body>
	
<div class="wrapper">

	<?php 
			require_once("menu.html");
			require_once("lib.php");
			$col = $_GET['col'];		
			// Get all distinct agent types that are of numeric values (all agents of same type have same attributeMap structure)
			$types = $database->$col->distinct("entities.type");
			$arr = [];
			foreach($types as $type) {
				$typeQuery = array('entities.type' => $type);
				$entity = $database->$col->findOne($typeQuery, ['projection' => ['entities.$' => 1, '_id' => 0]]);
				if (property_exists($entity->entities[0], "attributeMap")) { //If an entity does or doesn't have an attributeMap
					$attributeMap = $entity->entities[0]->attributeMap;
					foreach(array_keys((array) $attributeMap) as $attributeName) {
						$attributeValue = $attributeMap[$attributeName];
						if(is_numeric($attributeValue)) {
							$arr[] = $attributeName;
						}
					}
				}
			}
			$arr = array_unique($arr);

			$cursor = $database->$col->find(array(), ['projection' => ['snapshotNumber' => 1, 'entities' => 1, '_id' => 0]]);
			$document = $cursor->toArray();			
			$bson = MongoDB\BSON\fromPHP($document);			
			$json = MongoDB\BSON\toJSON($bson);
	?>	

	<div class="container-fluid" id="main">
	    <div class="wrapper">
	    	<!-- Bouton pour activer/désactiver la sidebar -->
		    <a id="sidebarCollapse" class="btn my-auto">
				<i class="fas fa-align-left"></i>
			</a>
 
			<h1 class="mx-auto mt-5">Visualisation des graphiques</h1>
		</div>

		<div class="container-fluid">
			<div class="row ">
				<div id="graph-container" class="col-9"></div>

				<div class="col pl-3">
					<div class="checkbox-container border m-2 p-3">
						<h5> Attributs </h5>
						<input type="text" id="searchAttribute"  class="form-control" onkeyup="filterCheckboxes(this.id,'a-g-checkboxes', 'attribute')" placeholder="Attribut à rechercher">
						<div id="a-g-checkboxes">
							<?php
								foreach($arr as $item) {
									echo "<div class='attribute'>";
										echo "<input type='checkbox' value='$item' onchange='handleChange(this,map,length)'  class='attributeCheckbox'>";
										echo "<label for='$item'> $item </label> <br>" ;
									echo "</div>";
								}
							?>
						</div>
					</div>

					<div class="checkbox-container border m-2 p-3	">
						<h5> Agents </h5>
						<input type="text" id="searchEntity" class="form-control" onkeyup="filterCheckboxes(this.id,'e-g-checkboxes', 'entity')" placeholder="Entité à rechercher">
						<div id="e-g-checkboxes">

						</div>
					</div>
				</div>
			</div>
		</div>


		<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="ml-5"> <!-- Goes to the origin page 'network.php?col=[name of experiment]' -->
			<button class="btn btn-primary"> <i class="fas fa-arrow-left"></i> Retour </button>	
		</a>
	</div>


</div>

<script type="text/javascript">
	function filterCheckboxes(inputID, containerID, className) {
		  // Declare variables 
		  var input = document.getElementById(inputID);
		  var filter = input.value.toUpperCase();
		  var container = document.getElementById(containerID);
		  var checkbox = container.getElementsByClassName(className);

		  // Loop through all table rows, and hide those who don't match the search query
		  for (i = 0; i < checkbox.length; i++) {
		    label = checkbox[i].getElementsByTagName("label")[0];
		    if (label) {
		      txtValue = label.textContent || label.innerText;
		      if (txtValue.toUpperCase().indexOf(filter) > -1) {
		        checkbox[i].style.display = "";
		      } else {
		        checkbox[i].style.display = "none";
		      }
		    } 
		  }
		}
		// Returns an object : for each entityID, the attributes and their evolution if they are numeric 
		function getMapEntities(snapshots) {
			var map = {};
			for(var s = 1; s < Object.keys(snapshots).length; s++) { // Index 0 is empty
				for(e in snapshots[s].entities) {
					var entity = snapshots[s].entities[e];
					// Adds the entity to the map if it doesn't exist yet
					if (!map.hasOwnProperty(entity.entityID)) {
						map[entity.entityID] = {};
						map[entity.entityID].type = entity.type;
					}
					for(att in entity.attributeMap) {
							//Creates an array if it doesn't exist yet, then adds the current number to it
							if(!map[entity.entityID].hasOwnProperty(att) && $.isNumeric(entity.attributeMap[att])) {
								map[entity.entityID][att] = [];
							} 
							if ($.isNumeric(entity.attributeMap[att])) {
								map[entity.entityID][att][s - 1] = entity.attributeMap[att]; // We don't push in case we don't get that attributes in some snapshots
							}
						}
				}
			}
			return map;
		}

		
		function writeEntityCheckbox(item) {
			return "<div class='entity'> <input type='checkbox' class='entityCheckbox' onclick='handleChangeEntity(this, map, length)' value='"+ item + "' > <label for='" + item + "'>" + item + "</label> <br> </div>";
		}


		//Returns an array with the indexes of all plots of an "att" type, in the graph_container div that contains the plotly object
		function searchPlotType(graph_container, att, nb) {
			var arr = []; 
			for (d in graph_container.data) {
				//Get the name of the trace, then return the type by taking what's after the ";" character
				var trace = graph_container.data[d]
				if(trace.name.split(";")[nb] == att) {
					// push the index of the element
					arr.push(parseInt(d));
				}
			}
			return arr;
		}


			function uncheck(className) {
				var indice = className == "entityCheckbox" ? 0 : 1;
				for (checkbox of document.getElementsByClassName(className)){
					if (checkbox.checked == true) {
						checkbox.checked = false;
						Plotly.deleteTraces(graph_container, searchPlotType(graph_container, checkbox.value, indice));
					}
				}
			}

		// plots the value of "checkbox" according to the information on "map" or deletes it
		function handleChange(checkbox, map, nbSnapshot) {
			if(checkbox.checked == true){
				uncheck("entityCheckbox");
				for(m in map) {
					Plotly.addTraces(graph_container, {x :	[...Array(nbSnapshot).keys()],  y: map[m][checkbox.value], name : m + ";" + checkbox.value});
				}
			} else {
				Plotly.deleteTraces(graph_container, searchPlotType(graph_container, checkbox.value, 1));
			}
		}	



		function handleChangeEntity(checkbox, map, nbSnapshot) {
			if(checkbox.checked == true) {
				uncheck("attributeCheckbox");
				var entityName = checkbox.value;
				for(m in map[entityName]) {
					if (Array.isArray(map[entityName][m])) { // means that if they are numerical values
						Plotly.addTraces(graph_container, {x :	[...Array(nbSnapshot).keys()],  y: map[entityName][m], name : entityName + ";" + m});
					}
				} 
			} else {
					Plotly.deleteTraces(graph_container, searchPlotType(graph_container, checkbox.value, 0));
				}
			
		}

		function replaceNumberproperty(obj) {
			var number;
			if (obj instanceof Object) {
				Object.keys(obj).forEach(function(key,index) {
					if (key.startsWith('$number')) {
						number = parseFloat(obj[key]);
					}
				});
			return number;
			}
		}

		function hasNumberProperty(obj) {
			return Object.keys(obj).forEach(function(key,index) {
				return (key.startsWith('$number'));
			});
		}
		function cleanJSON(json) {
			Object.keys(json).forEach(function(key,index) {
				for (ent in json[index]) {
					var entities = json[key][ent];
					for (ent in entities) {
						for (att in entities[ent].attributeMap) {
							var attribute = entities[ent].attributeMap[att];
							if (attribute instanceof Object && hasNumberProperty(attribute)) {
								attribute = replaceNumberproperty(attribute);
							} 
						}
					}
				}
			});
			return json;	
		}

		// Get the php query results for all the entities
		var snapshots = <?php echo $json ?>;
		
		var length = Object.keys(snapshots).length;

		var graph_container = document.getElementById("graph-container");
		var layout = {
			xaxis : { title : "numéro de snapshot"} ,
			margin : { t : 10 }
		};

		var config = {
		  toImageButtonOptions: {
		    format: 'png', // one of png, svg, jpeg, webp
		    filename: 'agent_plot',
		    height: 1000,
		    width: 1400,
		    scale: 1 // Multiply title/legend/axis/canvas sizes by this factor
		  },
		  responsive : true, 
		  scrollZoom : true,
		  displaylogo : false
		};


		Plotly.plot(graph_container,  [], layout, config);

		map = getMapEntities(cleanJSON(snapshots));
	//	snapshots = null; //"frees" the snapshots variable that can be heavy	

		//displays the name of all the agents in the corresponding div
		for (entity of _.keys(map)) { 
			document.getElementById("e-g-checkboxes").innerHTML += writeEntityCheckbox(entity);
		}

	</script>

</body>
</html>