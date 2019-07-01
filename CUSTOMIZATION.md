This section will explain how to customize the visual appearance of a network. 

* Do I have to know web programming to customize my networks ?
You don't have to know how to code in Javascript customize your network. You simply have to understand the concept of Javascript objects.
"An object is a collection of names or keys and values, represented in name:value pairs". It is basically like a HashMap in Java, but you can have any type of value. You can also have nested objects. 
Example : simple objet
```javascript 
    var house = {
    adress: "nowhere",
    storeys: 5,
};
```
Example 2 : nested object
```javascript 
    var house = {
    adress: "nowhere",
    storeys: 5,
    // nested object
    garden : {
      
    }
};
```
For more details about Javascript objects, see https://www.digitalocean.com/community/tutorials/understanding-objects-in-javascript .


* The `customizationVariables.js` configuration file 

Go to the `kajika` folder. Open `customizationVariables.js` with a text editor. 
You will already find the variable `defaultOptions` .

**Warning : You should always have `defaultOptions` in `customizationVariables.js` with this overall structure. Do not change the order of all the keys below.** 
```javascript 
var defaultOptions = {
	nodes  : { 	
		entityID : {},
		attributeMap : {},
		type : {}
	},
	relations :	{ 
		relationID : {}, 
		attributeMap : {},
		type : {}, 
	}
}; 
```
You have a "nodes" and a "relations" key. You can customize each type of network component in its corresponding object.

* Customization by entityID or relationID
 If you want to change the style of a specific agent or relation, go in the corresponding object.
 In this object, you will have nested objects. 
 Key : ID  
 Value : An object with the options 
 Example : the entity with ID `agent1` is entirely green.
 ```javascript 
var defaultOptions = {
	nodes  : { 	
		entityID : {
		       agent1 : { 
			 color : 'green'
			 }
  		},
		attributeMap : {},
		type : {}
	},
	relations : { 
		relationID : {}, 
		attributeMap : {},
		type : {}, 
	}
}; 
```
* Customization by type 
If you want to change the style of all the entities that have a certain type, the method is the same as it is for the ID.
Key : Type name
Value : An Object with the options 
Example : the relations with type `type1` are dashes.
 ```javascript 
var defaultOptions = {
	nodes  : { 	
		entityID : {},
		attributeMap : {},
		type : {}
	},
	relations : { 
		relationID : {}, 
		attributeMap : {},
		type : {
			type1 : {
				dashes : true
			}
		}, 
	}
}; 
```

* Customization by attribute 

If you want to change the style of all the entities that verify a certain expression dependi, the structure of the nested objects are different.
Key : Attribute name
Value : An Object with the following structure  
 ```javascript
 <attributeName> : {
	<operator1> : {
		map : [
			[ <value1> , <optionsObject1> ],
			[ <value2> , <optionsObject2> ]
		]
	}
}
```

When you want to apply the options only if specific elements verify the experiences, you can supply an array of IDs : 
 ```javascript
 <attributeName> : {
	<operator1> : {
		id : [ 'ID1', 'ID2' ],
		map : [
			[ <value1> , <optionsObject1> ],
			[ <value2> , <optionsObject2> ]
		]
	}
}
```
Warning : if you specify an empty id array, the option will never be applied. 

Example 1 : the entities with a `criticality >= 80 ` are squared-shaped.

 ```javascript 
var defaultOptions = {
	nodes  : { 	
		entityID : {},
		attributeMap : {
			criticality : {
				get : {
					map : [
						[ 80 , { shape : 'square' } ]
					]
				}
				
			}
		},
		type : {}
	},
	relations : { 
		relationID : {}, 
		attributeMap : {},
		type : {}, 
	}
}; 
```

Example 2 : If the entities with the ID `agent1`or `agent2` verify the expression `criticality == 0 `, then their size will be 50.

 ```javascript 
var defaultOptions = {
	nodes  : { 	
		entityID : {},
		attributeMap : {
			criticality : {
				eq : {
					id : ['agent1', 'agent2'],
					map : [
						[ 0 , { size : 50 } ]
					]
				}
				
			}
		},
		type : {}
	},
	relations : { 
		relationID : {}, 
		attributeMap : {},
		type : {}, 
	}
}; 
```

Example 3 : 
You can combine all the examples we have shown above. We add that all the agents with the attribute `error < 0.5` have a red border.
 ```javascript 
var defaultOptions = {
	nodes  : { 	
		entityID : {
			 agent1 : { 
				 color : 'green'
			 }
		},
		attributeMap : {
			criticality : {
				get : {
					map : [
						[ 80 , { shape : 'square' } ]
					]
				},
				eq : {
					id : ['agent1', 'agent2'],
					map : [
						[ 0 , { size : 50 } ]
					]
				}	
			},
			error : {
				lt : {
					map : [
						[ 0.5, color : { border : 'red'} ]
					]
				}
			}
		},
		type : {}
	},
	relations : { 
		relationID : {}, 
		attributeMap : {},
		type : {
			type1 : {
					dashes : true
				}
		}, 
	}
}; 
```
* What happens when many options overlap ?
There will be many cases when an element will verify many criteria. The options can overlap if you put the same keys in the options object. In this case, the **LAST** that have been added to the object will be applied.






Priority orders : 
In a "nodes" or "relations" value object, you can customize by ID (entityID or relationID), attribute value (attributeMap), or type.
If you specify the same keys in the options object, the priority order for applying the options is :
ID > attributeMap > type.

Example 4:
 ```javascript 
var defaultOptions = {
	nodes  : { 	
		entityID : {
			 agent1 : { 
				 color : 'green'
			 }
		},
		attributeMap : {
			criticality : {
				get : {
					map : [
						[ 80 , { color : 'blue' } ]
					]
				}
			},
			error : {
				lt : {
					map : [
						[ 0.5, color : { border : 'red'} ]
					]
				}
			}
		},
		type : {
			type2 : { 
				 color : 'yellow'
			 }
		}
	},
	relations : { 
		relationID : {}, 
		attributeMap : {},
		type : {}, 
	}
}; 
```
In the example 4, we will use the `color` key in the options objects. 
If agent1 verify all the criteria, it will be green because ID > attributeMap.
However, for the other agents, if they validate either of the criteria related to the attributeMap, they will have a red border because the error is the last specified within the attributeMap object.
Finally if an agent verify neither of the criteria except the one related to the type, it will be yellow.

* What about the options objects ? 

* Customizing many experiments 








* Customization CHEAT SHEET : 
