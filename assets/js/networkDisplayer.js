
class NetworkManager {
	constructor(experiment) {
		this.experiment = experiment;
		this.physicsToggled = true;
		this.currentIndex = 0; // First document of an experiment collection always related to the experiment information
		this.nodes = new vis.DataSet(this.getNodes(0));
		this.edges = new vis.DataSet(this.getEdges(0));
		var data = {nodes : this.nodes, edges : this.edges};
		this.network = new vis.Network(document.getElementById('network'), 
					data, 
					this.getNetworkOptions());
		this.selectionActivated = false;
		this.stepsMap = this.generateStepsMap();



		this.network.on( 'selectNode', function(params) {
			DataWriter.writeModalSelectedEntities(networkManager);
			if (params.edges.length == 0 && $('#modal-relation').hasClass('show')) {
				$('#modal-relation').modal('hide');
				}	
		});

		this.network.on( 'selectEdge', function(params) {		
			DataWriter.writeModalSelectedRelations(networkManager);
			if (params.nodes.length == 0 && $('#modal-entity').hasClass('show')) {
				$('#modal-entity').modal('hide');
			}
	});
	}

	getCustomizationOptions() {
		var experimentName = this.experiment.getExperimentName();
		if (_.has(customizationOptionsMap, experimentName)) {
			if (customizationOptionsMap[experimentName] == undefined || customizationOptionsMap[experimentName] == null) {
			        return defaultOptions;
			    }
			return customizationOptionsMap[experimentName];	
			}

		return defaultOptions;	
	}

	getNetworkOptions() {
		return 	({
		    physics:
		    {
		  		enabled: true,
			    stabilization : false,
		    	barnesHut: {
		      		gravitationalConstant: -2000,
		      		centralGravity: 0.3,
		      		springLength: 95,
		      		springConstant: 0.04,
		      		damping: 0.09,
		      		avoidOverlap: 0
		    	},
		    	forceAtlas2Based: {
		      		gravitationalConstant: -50,
		      		centralGravity: 0.01,
		      		springConstant: 0.08,
		      		springLength: 100,
		      		damping: 0.4,
		      		avoidOverlap: 0
		    	},
		    	repulsion: {
		      		centralGravity: 0.2,
		      		springLength: 200,
		      		springConstant: 0.05,
		      		nodeDistance: 100,
		      		damping: 0.09
		    	},
		    	hierarchicalRepulsion: {
		      		centralGravity: 0.0,
		      		springLength: 100,
		      		springConstant: 0.01,
		      		nodeDistance: 120,
		      		damping: 0.09
		    	},
		    	maxVelocity: 50,
		    	minVelocity: 0.1,
		    	solver: 'forceAtlas2Based',
		    	stabilization: {
		      		enabled: false,
		      		iterations: 1000,
		      		updateInterval: 100,
		      		onlyDynamicEdges: false,
		      		fit: true
		    	},
		    	timestep: 0.5,
		    	adaptiveTimestep: true
		    },
			interaction: 
			{
				selectConnectedEdges : false,
			    multiselect: true 
			}
		});
	}

	getNoPhysicsOptions() {
		return { physics : false, multiselect : true, selectConnectedEdges : false};
	}



	togglePhysics() {
		this.physicsToggled = ! this.physicsToggled;
		var options = this.physicsToggled ? this.getNetworkOptions() : this.getNoPhysicsOptions(); 
		this.network.setOptions(options);
	}
	
	loadSnapshot(snapshotNumber) {
		var selectedNodesIds = this.network.getSelectedNodes();
		var selectedEdgesIds = this.network.getSelectedEdges();
		//updates the currentIndex cursor
		if (! this.selectionActivated) {
			//create entirely new network elements (changes the disposition of the previous network elements)
			this.nodes.clear();	
			this.nodes.add(this.getNodes(snapshotNumber));
			
		} else {
			this.loadSnapshotWithSelection(this.currentIndex, snapshotNumber);
		}
		this.currentIndex = snapshotNumber;

		this.edges.clear();
		this.edges.add(this.getEdges(snapshotNumber));
		var experiment = this.experiment;
		_.remove(selectedNodesIds, function(id) {
			return experiment.getEntity(snapshotNumber, id) == undefined;
		});
		_.remove(selectedEdgesIds, function(id) {
			return experiment.getRelation(snapshotNumber, id) == undefined;
		});
		this.network.setSelection({nodes : selectedNodesIds, edges : selectedEdgesIds});
		DataWriter.writeModalSelectedEntities(this);
		DataWriter.writeModalSelectedRelations(this);
	}

	getAllNeighborsNodes(nodesIDArray) {
		var neighborsArray = [];
		for (var nodeID of nodesIDArray) {
			neighborsArray = _.concat(neighborsArray, this.network.getConnectedNodes(nodeID));
		}
		return neighborsArray;
	}
	//on click of 'Visualisation sélective' in the options panel
	//show only the selected nodes with CTRL+click and their neighbors in the current snapshot
	toggleSelection() {
		//toggle 'selectionActivated' boolean
		this.selectionActivated = ! this.selectionActivated;

		if (this.selectionActivated) {
			//get the nodes to keep according to what is selected
			this.selectedNodes = this.network.getSelectedNodes();
			this.selectedNodesNeighbors = this.getAllNeighborsNodes(this.selectedNodes);
			var elementsToKeepArray = _.concat(this.selectedNodes, this.selectedNodesNeighbors);
			//removes the nodes that aren't selected or aren't connected to them
			this.nodes.remove(_.difference(this.nodes.getIds(), elementsToKeepArray));
		} else {
			//reloads everything
			this.loadSnapshot(this.currentIndex);
			this.network.selectNodes(this.selectedNodes, true);
		}
	}

	getSelectedNeighbors(snapshotNumber) {
		var array = [];
		for (var id of this.selectedNodes) {
			array = _.concat(array, this.experiment.getRelatedEntities(snapshotNumber, id));
		}
		return array;
	}

	loadSnapshotWithSelection(oldSnapshotNumber, newSnapshotNumber) {
		for (var id of this.selectedNodes) {
			//if the selected node has disappeared, remove its neighbors too
			if (this.experiment.getEntity(newSnapshotNumber, id) == undefined) { 
				this.nodes.remove(id);
				this.nodes.remove(this.experiment.getRelatedEntities(oldSnapshotNumber, id));

			} //if the previously selected node that has disappeared during th simulation appears again or is updated (visual options...)
			else if (this.experiment.getEntity(newSnapshotNumber, id) != undefined) { 
				this.nodes.update(this.getNode(newSnapshotNumber, id));
				this.network.selectNodes(this.network.getSelectedNodes(), true);	
				var neighborsRemove = _.differenceWith(this.experiment.getRelatedEntities(oldSnapshotNumber, id), this.experiment.getRelatedEntities(newSnapshotNumber, id));
				this.nodes.remove(neighborsRemove);
				var nodesUpdateIdsArray = this.getNodesWithIds(newSnapshotNumber, this.experiment.getRelatedEntities(newSnapshotNumber, id));
				this.nodes.update(nodesUpdateIdsArray);
			}
		}
	}
 

	loadNext(player) {
		if (!this.isCurrentIndexMax()) {
			if (this.selectionActivated) {
				this.loadSnapshotWithSelection(this.currentIndex, this.currentIndex + 1);
			} else {
				//updates the data elements of the network(the View) that are displayed
				this.nodes.remove(this.stepsMap.forward[this.currentIndex].remove.nodes);
				this.nodes.update(this.stepsMap.forward[this.currentIndex].add.nodes);
				
			}
			this.edges.remove(this.stepsMap.forward[this.currentIndex].remove.edges);
			this.edges.update(this.stepsMap.forward[this.currentIndex].add.edges);
			//updates the cursor
			this.currentIndex++;
			player.moveCursor(this.currentIndex + 1);
			DataWriter.updateModals(this);
		}
	}

	loadPrevious(player) {
		if (this.currentIndex > 0) {
			//updates the data elements of the network(the View) that are displayed
			var index = this.getIndexMax() - this.currentIndex - 1;
			if (this.selectionActivated) {
				this.loadSnapshotWithSelection(this.currentIndex, this.currentIndex - 1);
			} else {
			 	this.nodes.remove(this.stepsMap.back[index].remove.nodes);
			 	this.nodes.update(this.stepsMap.back[index].add.nodes);
		 	}
			this.edges.remove(this.stepsMap.back[index].remove.edges);
			this.edges.update(this.stepsMap.back[index].add.edges);
			//updates the cursor
			this.currentIndex--;
			player.moveCursor(this.currentIndex + 1);
			DataWriter.updateModals(this);
		}
	} 	



	getNode(snapshotNumber, id) {
		var ent = this.experiment.getEntity(snapshotNumber, id);
		if (Customizer.isEntityFiltered(ent)) {
			// console.log(ent);
			var label = this.experiment.getName(ent);
			var title = DataWriter.writeObjectHTML(ent);
			var nodeOptions = Customizer.getVisualOptions(this, ent, 'nodes');
			var nodeData = {id : ent.entityID, label : label, title : title};
			return _.merge(nodeData, nodeOptions);
		}
	}

	getNodesWithIds(snapshotNumber, idArray) {
		var array = [];
		for(var id of idArray) {
			array.push(this.getNode(snapshotNumber, id));
		}
		return array;
	}

	getEdge(snapshotNumber, id) {
		var rel = this.experiment.getRelation(snapshotNumber, id);
		var title = DataWriter.writeObjectHTML(rel);
		var edgeOptions = Customizer.getVisualOptions(this, rel, 'relations');
		var edgeData = {id : rel.relationID, from : rel.nodeAid, to : rel.nodeBid, ...(rel.isOriented && {arrows : "to"}), color : {inherit : false}};
		return _.merge(edgeData,edgeOptions);
	}

	getNodes(snapshotNumber) {
		var array = [];
		for (var ent of	this.experiment.getEntities(snapshotNumber)) { 
			var node = this.getNode(snapshotNumber, ent.entityID);
			node ? array.push(node) : null;
		}
		return array;	
	}

	getEdges(snapshotNumber) {
		var array = [];
		for (var rel of this.experiment.getRelations(snapshotNumber)) {	
			var edge = this.getEdge(snapshotNumber, rel.relationID);
			array.push(edge);
		}
		return array;	
	}

	getIndexMax() {
		return this.experiment.getNbSnapshot();
	}

	generateStepsMap() {
		var map = { forward : [], back : [] };
		//all differential counting between the snapshots starting from the first to the last snapshot
		for (var i = 0; i < this.getIndexMax() - 1; i++) {
			map.forward.push(this.getNetworkDataDifferences(i, i + 1));
		} 
		//all differential counting between the snapshots starting from the last to the first snapshot
		for (var i = this.getIndexMax() - 1; i > 0; i--) {
			map.back.push(this.getNetworkDataDifferences(i, i - 1));
		}
		return map;	
	}

	isCurrentIndexMax() {
		return this.currentIndex === this.experiment.getNbSnapshot() - 1;
	}

	networkElementEquals(element1, element2) {
		return element1.id === element2.id && element1.type === element2.type; 
	}

	getNetworkDataDifferences(oldnapshotNumber, newSnapshotNumber) {
		var oldNodes = this.getNodes(oldnapshotNumber);
		var oldEdges = this.getEdges(oldnapshotNumber);
		var newNodes = this.getNodes(newSnapshotNumber);
		var newEdges = this.getEdges(newSnapshotNumber);
		return ({
				add : {
					nodes : newNodes,
					edges : _.differenceWith(newEdges, oldEdges, this.networkElementEquals)
				},
				remove : {
					nodes : _.differenceWith(oldNodes, newNodes, this.networkElementEquals),
					edges : _.differenceWith(oldEdges, newEdges, this.networkElementEquals)
				}
		});
	}

}






class Player {
	constructor(sliderID, outputID, networkManager) {
		//sets the attributes of the object
		this.slider = document.getElementById(sliderID);
		this.output = document.getElementById(outputID);
		this.networkManager = networkManager;
		this.moveCursor(1);
		this.setFPS(1);
		this.paused = true;
		this.intervalID = null;
		//sets the attributes of the slider
		this.slider.max = this.networkManager.getIndexMax();
		this.slider.value = this.networkManager.currentIndex;
	}

	setFPS(nb) {
		this.fps = nb;
	}

	//converts the fps (frame per second) variable into ms
	getInterval() {
		return 1 / this.fps * 1000;
	}

	//sets the output html element in the form " input / nbSnapshot" next to the slider
	//sets the new slider value
	moveCursor(input) {
			this.slider.value = input;
			this.output.innerHTML = (input  + '/' + this.networkManager.getIndexMax().toString());	
	}

	//changes the appearance of the icon according to the "paused" state
	//works with DOM Elements
	switchIcon() {
		var button = document.getElementById("playpause");
		var icon = document.getElementById("playpause-icn");
		button.removeChild(icon);
		var newIcon = document.createElement("i");
		newIcon.id = "playpause-icn";
	 	newIcon.className = this.paused ? "player-btn fas fa-play" : "player-btn fas fa-pause";
		button.appendChild(newIcon);
	}

	//starts the simulation
	toggle() {
		var player = this;
		//activates/deactivates the paused state
		this.paused = ! this.paused;
		this.switchIcon();	
		if (!this.paused) {
			this.intervalID = setInterval(function() {
								//if the last snapshot is reached on play, stops the simulation
								 if(this.networkManager.isCurrentIndexMax()) {
									player.toggle();
								} 
								//displays next snapshot
								this.networkManager.loadNext(player);
							}, this.getInterval());
		} else {		
			//when the simulation is paused
			clearInterval(this.intervalID);
		}
		
	}
	

}





class Experiment {
	constructor(experiment_collection) {
		this.collection = experiment_collection;
	}

	getNbSnapshot() {
		return _.size(this.collection) - 1;
	}

	isLastSnapshot(snapshotNumber) {
		return snapshotNumber === this.getNbSnapshot() - 2;
	}

	getSnapshot(snapshotNumber) {
		return this.collection[snapshotNumber + 1]; //First index of a collection is always related to the experiment itself
	}

	getExperimentInformation() {
		return _.omit(this.collection[0], '_id');
	}

	getExperimentName() {
		return this.collection[0].experimentName;
	}

	getEntities(snapshotNumber) {
		return this.getSnapshot(snapshotNumber).entities;
	}

	getRelations(snapshotNumber) {
		return this.getSnapshot(snapshotNumber).relations;
	}

	getEntity(snapshotNumber, id) {
		return this.getEntities(snapshotNumber).find(function(element) {
  			return element.entityID === id;
		});
	}

	getEntityIds(snapshotNumber) {
		var array = [];
		for (var ent of this.getEntities(snapshotNumber)) {
			array.push(ent.entityID);
		}
		return array;
	}

	getRelatedEntities(snapshotNumber, id) {
		var array = [];
		for (var rel of this.getRelations(snapshotNumber)) {
			if (rel.nodeAid === id) {
				array.push(rel.nodeBid);
			} else if (rel.nodeBid === id) {
				array.push(rel.nodeAid);
			}
		}
		return array;
	}

	getRelation(snapshotNumber, id) {
		return this.getRelations(snapshotNumber).find(function(element) {
  			return element.relationID === id;
		});
	}

	static getID(element) {
		if (_.has(element, "entityID")) {
			return element.entityID;
		} 
		else if (_.has(element, "relationID")) {
			return element.relationID;
		} 
		else if (_.has(element, "id")) {
			return element.id;
		}
	} 

	getName(element) {
		if(element.hasOwnProperty("attributeMap") && element.attributeMap.hasOwnProperty("name")) {
			return element.attributeMap.name;
		}	
	} 

	attributeExists(attribute) {
		for (var i = 0; i < this.getNbSnapshot(); i++) {
			for (var ent of this.getEntities(i)) {
				if(_.has(ent, 'attributeMap') && _.has(ent.attributeMap, attribute)) {
					return true;
				}
			}
		}
		return false;
	} 

	entityExists(entityID) {
		for (var i = 0; i < this.getNbSnapshot(); i++) {
			if (this.getEntity(i, entityID) != undefined) {
				return true;
			}
		}
		return false;
	}

	typeExists(type) {
		for (var i = 0; i < this.getNbSnapshot(); i++) {
			for (var ent of this.getEntities(i)) {
				if(ent.type === type) {
					return true;
				}
			}
		}
		return false;
	} 

}

class DataWriter {
	static isKeyForNumber(key) {
		return key.startsWith('$number');
	}

	static isKeyID(key) {
		var array = ['entityID', 'relationID', 'id'];
		return array.includes(key);
	}

	static getKeyString(key) {
		if (DataWriter.isKeyForNumber(key)) {
			return 'value'; //replaces mongodb default keys for numbers($numberInt, $numberDecimal...) by "value"
		} else if(DataWriter.isKeyID(key)) {
			return 'id';
		} else {
			return key;
		}
	}


	// Writes an object with each key in bold
	static writeObjectHTML(obj) {
		var result = '';
		Object.keys(obj).forEach(function(key,index) {
			result += "<div> <b>" + DataWriter.getKeyString(key) + '</b> : ';
			if(obj[key] instanceof Object) {
				// Recursive call to the method, if there's a nested object, with bootstrap class ml-3 for indentation
				result +=  "<div class='ml-3'>" +  DataWriter.writeObjectHTML(obj[key]) + "</div>";
			} else {
				result += obj[key];
			}
			result += '<br> </div>';
		});
		return result;
	}

	static showModal(divId) { // shows a modal in div of a specified divId
		$(divId).modal({
				backdrop: false,
				focus : false,
				show : true,
		});
		// Pouvoir déplacer le modal
		$('.modal-dialog').draggable({
			handle: ".modal-body"
		});
		// Modal resizable sans tout casser
		$('.modal-content').resizable({
			minHeight : 100,
			minWidth : 200,
		});
	}


	static writeModalSelectedEntities(networkManager) {
		DataWriter.showModal('#modal-entity');
		var selectedNodesIds = networkManager.network.getSelectedNodes();
		//empty the current information
		$("#entity-information").html('') 
		//if there are nodes selected AND the modal is active, refreshes the information in the modal, else we hide the modal
		if (selectedNodesIds.length && $('#modal-entity').hasClass('show')) {
			for (var id of selectedNodesIds) {
				var element = networkManager.getNode(networkManager.currentIndex, id)
				$("#entity-information").append(element.title);
			} 
		} else {
			$('#modal-entity').modal('hide');
		}
	}	

	static writeModalSelectedRelations(networkManager) {
		DataWriter.showModal('#modal-relation');
		var selectedEdgesIds = networkManager.network.getSelectedEdges();
		//empty the current information
		$("#relation-information").html('');
		//if there are nodes selected AND the modal is active, refreshes the information in the modal, else we hide the modal
		if (selectedEdgesIds.length && $('#modal-relation').hasClass('show')) {
			for (var id of selectedEdgesIds) {
				var element = networkManager.experiment.getRelation(networkManager.currentIndex, id);
				$("#relation-information").append(DataWriter.writeObjectHTML(element));
			}
		} else {
			$('#modal-relation').modal('hide');
		}
	}	

	static updateModals(networkManager) {
		DataWriter.writeModalSelectedEntities(networkManager);
		DataWriter.writeModalSelectedRelations(networkManager);
	}

}



class Customizer {
	//compares the value of "op1" to the value(s) of "op2" depending on the "operator" string value
	//return a boolean or a null 
	static isExpressionValid(operator, op1, op2) {
		switch(operator) {
			case  "lt" : 
				return op1 < op2;
			case "let" : 
				return op1 <= op2;
			case "gt" : 
				return op1 > op2;
			case "get" : 
				return op1 >= op2;
			case "eq" :
				return op1 === op2;
			case "neq" :
				return op1 !== op2;
			case "between":
				return op1 > op2[0] && op1 < op2[1]; // op2 (array) is an interval ]value1, value2[ so we work with an array of two compartments
			default : 
			return null;
		}
	}
 
	//get an 'entity' or 'relation' element
	//'object' is used in the next functions as the attributeMap parts of 'visual_options' in 'personnalisation.js'
	static isOptionAppliable(element, mapEntry) {
		// for the attributeMap part of 'visual_options'
		// if there is an id array property, the id must be included in the array for the option to be applied
		var elementID = Experiment.getID(element);
		return mapEntry.length == 2 || (mapEntry.length == 3 && mapEntry[2].includes(elementID)); 
	}


	//get the attributeMap Object of the 'nodes' or 'relations' part of 'visual_options' object in 'personnalisation.js'
	//get either a relation or an entity as 'element'
	//return an object containing options that are applied directly in the vis.js view elements
	static getAttributeMapOptions(optionsAttributeMap, element) {
		var resultObject = {};
		var elementAttributeMap = element.attributeMap;
		//loop through all the elements of the 'attributeMap' Object in 'visual_options'
		// which is directly put in parameter in 'getVisualOptions' function
		// each value consists of 'operators' properties
		Object.keys(optionsAttributeMap).forEach(function(key,index) {
			if (elementAttributeMap && elementAttributeMap.hasOwnProperty(key)) {
				//we accessed the attribute word compartment, now we have all the operator objects, with a value and an option object in them
				var attributeOperators = optionsAttributeMap[key];
				var op1 = elementAttributeMap[key];
				//loop through all the operators, and check if the option is appliable and if the value is correct
				Object.keys(attributeOperators).forEach(function(key,index) {
					// loop through all the map lines
					for (var mapEntry of attributeOperators[key].map) {
						var op2 = mapEntry[0];
						if (Customizer.isExpressionValid(key, op1, op2) && Customizer.isOptionAppliable(element, mapEntry)) {
							resultObject = _.defaults(resultObject, mapEntry[1]);
						}
					}
				});

			}
		});

		return resultObject;
	}



	//get either a relation or an entity as 'element'
	//get 'nodes' or 'relations' as type, depending on the type of the element
	//return an object containing options that are applied directly in the vis.js view elements
	static getVisualOptions(networkManager, element, type) {
		var resultObject = {};
		// get the type of element : "nodes" or "relations", and also the corresponding index of 'visual_options' in 'personnalisation.js'
		var optionsVar =  networkManager.getCustomizationOptions();
		var optionsOfType = optionsVar[type];


		// loop through all the keys of 'optionsOfType' : 'entityID' or 'relationID', 'type', 'attributeMap'
		// 'element' parameter has all of the 'optionsOfType' keys as properties 
		Object.keys(optionsOfType).forEach(function(key,index) {
			var optionOfType = optionsOfType[key];
			var elementPropertyValue = element[key];
			// example : if we find the the id of the element('id1') in the su-object optionsOfType.entityID
			if(optionOfType.hasOwnProperty(elementPropertyValue)) {
				resultObject = _.defaults(resultObject, optionOfType[elementPropertyValue]);
			}
			// we deal with the attributeMap property in a totally different way, see the technical documentation and the getAttributeMapOptions function for more information
			if (key === 'attributeMap') {
				resultObject = _.defaults(resultObject, Customizer.getAttributeMapOptions(optionOfType, element));
			}
		});		
		return resultObject;
	}

	static getFilteredEntitiesIDs() {
		var chosenAgentsIDs = [];
		for(var input of $('#inputsID').find('input')) {
			if (input.value && input.value != '') {
				chosenAgentsIDs.push(input.value);
			}
		}
		return chosenAgentsIDs;
	} 

	static getFilteredEntitiesTypes() {
		var chosenAgentsTypes = [];
		for(var input of $('#inputsType').find('input')) {
			if (input.value && input.value != '') {
				chosenAgentsTypes.push(input.value);
			}
		}
		return chosenAgentsTypes;
	}

	static isNetworkFiltered() {
		return Customizer.getFilteredEntitiesIDs().length != 0 || Customizer.getFilteredEntitiesTypes().length != 0 || Customizer.getFilteredEntitiesAttributes().length != 0;
	}

	static getValueAttributeInput(string) {
		if($.isNumeric(string)) {
			return parseInt(string,10);
		} else {
			return string.toString().replace(' ', '_');	
		}
	}


	static getFilteredEntitiesAttributes() {
		var chosenAgentsAttributes = [];
		for(var input of $('#inputsAttribute').find('input')) {
			if (input.value && input.value != '') {
				var expression = input.value.split(' ');
				if (expression.length == 3) {
					var value = Customizer.getValueAttributeInput(expression[2]);
					chosenAgentsAttributes.push({attribute : expression[0], operator : expression[1], value : value});
				} else {
					console.log('error in the expression');
				}
			}
		}
		return chosenAgentsAttributes;
	}

	static isEntityAttributeFiltered(entity) {
		for (var expression of Customizer.getFilteredEntitiesAttributes()) {
			if (_.has(entity, 'attributeMap') && _.has(entity.attributeMap, expression.attribute)) {
				var op1 = Customizer.getValueAttributeInput(entity.attributeMap[expression.attribute]);
				var op2 = Customizer.getValueAttributeInput(expression.value);
				return Customizer.isExpressionValid(expression.operator, op1, op2);
			}
		}
		return false;

	}
	static isEntityFiltered(entity) {
		if (Customizer.isNetworkFiltered()) {
			return Customizer.getFilteredEntitiesIDs().includes(entity.entityID) || Customizer.getFilteredEntitiesTypes().includes(entity.type) || Customizer.isEntityAttributeFiltered(entity);
		} 
		return true;
	}

	static checkAttributeInput(input, experiment) {
		var errorBox = $("#errorBox");
		if (input.val() != '') {
			var expression = input.val().split(" ");
			 if (expression.length != 3) {
				errorBox.append('Incorrect expression form <br>');
				return false;
			} else if (! experiment.attributeExists(expression[0]) ) {
				errorBox.append('Attribute not found <br>');
				return false;
			} else if (! ['lt', 'let', 'gt', 'get', 'neq', 'eq'].includes(expression[1])) {
				errorBox.append('Incorrect operator <br>');
				return false;
			} 
		}
		return true;
	}

	static checkIDInput(input, experiment) {
		var errorBox = $("#errorBox");
		if(! experiment.entityExists(input.val()) && input.val() != '') {
			console.log(input.val());
			errorBox.append('ID not found <br>');
			return false;
		}
		return true;
	}

	static checkTypeInput(input, experiment) {
		var errorBox = $("#errorBox");
		if (! experiment.typeExists(input.val()) && input.val() != '') {
			errorBox.append('Type not found <br>');
			return false;
		} 
		return true;
	}

}







