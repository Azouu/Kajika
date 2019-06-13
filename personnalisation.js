	var visual_options = {
		nodes  : { 	entityID : 
			{ 
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

		attributeMap : 
			{
				"criticality" : { 

					lt : {
						value : 40,
						id : ["VAC_E1", "VAC_E3"],
						options : {
							color : "black"
						}
					},
					gt : {
						value : 70,
						options : {
							
						}
					},
					between : {
						value : [40,100],
						id : ['VAC_E3'],
						options : {
							color : 'grey'
						}
					}

				},
				"error" : {

						gt : {
							value : 7,	
							options : {
								color : "yellow"
							}
						}

					}
			},
			type : 
			{ 
				"Wind Turbine Agent" :
				  { color : 'red' } 
			},		
	},


	relations :
	{ 
		relationID : {}, 
		type : {}, 
		attributeMap : {}
	}			
};	
