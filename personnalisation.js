var amawindOptions = {
		nodes  : { 	
			entityID : 	{ 
				"VAC_E1" :
				 	 {	color :
				 	 	{ 
				 	 		background : 'green',
				 		}
				 	},
			 	"VAC_E2" : 
					 { shape : "triangle",
					 	color : 'yellow',
					 	fixed : true,
					 	x : -1000,
					 	y : 600}
			},

			attributeMap : {
				"criticality" : { 
					between : {
							map : [
								[ [20,40], { color : "black"} ],
								[ [40, 50], { color : "grey"}]
							]
			
						}		
					},
			},
			type : { 
				"Wind Turbine Agent" :
				  { color : 'red' } 
			}		
	},


	relations : { 
		relationID : {}, 
		type : {}, 
		attributeMap : {}
	}			
};	

var defaultOptions = {
	nodes  : { 	
		entityID : {},
		attributeMap : {},
		type : {}
	},

	relations :	{ 
		relationID : {}, 
		type : {}, 
		attributeMap : {}
	}
};