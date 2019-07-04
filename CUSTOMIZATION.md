This section will explain how to customize the visual appearance of a network. 
 
# Table of contents
1. [Do I have to know web programming to customize my networks ?](#do-i-have-to-know-web-programming-to-customize-my-networks-)
2. [The customization configuration file](#the-customization-configuration-file)
 	1. [Customization by entityID or relationID](#customization-by-entityid-or-relationid)
	2. [Customization by type](#customization-by-type)
	3. [Customization by attribute](#customization-by-attribute)
	4. [Operators to write expressions](#operators-to-write-expressions)
	5. [The options object](#the-options-object)
	6. [What happens when many options overlap ?](#what-happens-when-many-options-overlap-)
	7. [Customizing many experiments separately](#customizing-many-experiments-separately)
	8. [The "color" option](#the-color-option)

## Do I have to know web programming to customize my networks ?
You don't have to know how to code in Javascript customize your network. You simply have to understand the concept of Javascript objects. The options are based on the nodes and edges options of the vis.js library we use to create the network. 
_"An object is a collection of names or keys and values, represented in name:value pairs"_. It is basically like a **HashMap** in Java, but you can put any type of value. You can also have nested objects. 
### Example 1 : simple object
```javascript 
    var house = {
    adress: "nowhere",
    storeys: 5,
};
```
### Example 2 : nested object
```javascript 
    var house = {
    adress: "nowhere",
    storeys: 5,
    // nested objects
    garden : {
      plants : {
   	trees : ['apple', 'cherry'],
	flowers : ['hibiscus', 'rosebush']
	}
      hut : true,
      surface : 43
    }
};
```
For more details about Javascript objects, see https://www.digitalocean.com/community/tutorials/understanding-objects-in-javascript .


## The customization configuration file 

Go to the `kajika` folder. Open `customizationVariables.js` with a text editor.  
You will already find the variable `defaultOptions`.  

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
You have a **"nodes"** and a **"relations"** key. You can customize each type of network component in its corresponding value object. Here, you will find many nested objects. We will reference each nested object by its key and the type of its value.  

### Customization by entityID or relationID
 If you want to change the style of a specific agent or relation, go in **nodes -> entityID** or **relations -> relationID**.  
* **Key** : entityID or relationID.  
* **Value** : an object with the options.  
#### Example 3: the entity with ID `agent1` is entirely green.
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
### Customization by type 
If you want to change the style of all the entities that have a specific type, the method is the same as the ID. Go in **nodes -> type** or **relations -> type** .
* **Key** : type name.  
* **Value** : an object with the options.  
#### Example 4: the relations of type `type1` are dashes.
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

### Customization by attribute 
If you want to change the style of all the entities/relations that verify a certain expression depending on a specific attribute, the structure of the nested objects will be different. Go to**nodes -> attributeMap** or **relations -> attributeMap**
* **Key** : attribute name.
* **Value** : an object with the following structure :
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
* For the syntax of the operator, see the table below.   
* You should always have a `map` attributre in **nodes -> attributeMap -> attributeName -> operator1** or **relations -> attributeMap -> attributeName -> operator1** .  
* `map`is an 2D array. On each line there is an array. The first index is the value you set for your expression, and the second index is an object with the options.

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
_**Warning : if you specify an empty array of IDs, the options will never be applied.**_

### Operators to write expressions
In the "Customization by attribute" section, we have seen that we need operators to write expressions. We need them as **keys** in **nodes -> attributeMap -> "attributeName"** or **relations -> attributeMap -> "attributeName"**
Here is a table with all the operators, their corresponding sign and the type of value you must supply in **nodes -> attributeMap -> "attributeName" -> "operator" -> map[i][0]** .  

| Operator | Corresponding sign  | Value type |
| :---------------: |:---------------:|:---------------:| 
| lt  | < |  numeric value |
| let  | <= |  numeric value |
| gt  | > |  numeric value |
| get  | >= |  numeric value |
| eq | == |  numeric value |
| neq | != |  numeric value |
| between | ∈ |  array of 2 numeric values |

### The options object 
The options object are dependent on the vis.js library. You will find many more options if you see https://visjs.org/docs/network/nodes.html and https://visjs.org/docs/network/edges.html .
The **Name** column in the table references one of the many keys you can have in an options object. You can sometimes find nested objects (When the **Name** has a dropdown arrow).  
If you want to have concrete examples on how to use these options, see the **full options** section of the vis.js nodes and edges documentation (links above).

#### Example 5: the entities with an attribute `criticality >= 80 ` are squared-shaped.

 ```javascript 
var defaultOptions = {
	nodes  : { 	
		entityID : {},
		attributeMap : {
			criticality : {
				// get (greater or equal then) 
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

#### Example 6: If the entities with the ID `agent1`or `agent2` verify the expression `criticality == 0 `, then their size will be 50.
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

#### Example 7: Combination of all the examples
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
## What happens when many options overlap ?
There will be many cases when an element will verify many criteria. The options can overlap if you have similar keys in the options object.  

### Priority orders 
In a "nodes" or "relations" value object, you can customize by ID (entityID or relationID), attribute value (attributeMap), or type.
If you specify similar keys in the options object, the priority order for applying the options is :
**ID > attributeMap > type**.  
However, if 2 or more criteria are verified in the attributeMap sub-object, the **last** is proritary.

#### Example 8: Priority orders for applying the `color` option
 ```javascript 
var defaultOptions = {
	nodes  : { 	
		entityID : {
			 agent1 : { 
				 color : 'green'
			 }
		},
		attributeMap : {
			// the error criteria has the priority over criticality here
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
In the example 8, for all the criteria that are verified we apply a `color`option.  
If `agent1` verify all the criteria, it will be **green** because **ID > attributeMap.**  
However, for the other agents, if they validate either of the criteria related to the attributeMap, they will have a **red border** because the error is the last specified within the attributeMap object.
Finally if an agent verify neither of the criteria except the one related to the type, it will be **yellow**.

## Customizing many experiments separately
In the `kajika` folder, open `config.js`.  
In this file, you will find the `customizationOptionsMap` object.  
Each key is the name of the experiment. The corresponding value is the customization variable specified in `customizationVariables.js` before.
```javascript 
var customizationOptionsMap = {
	"ExperimentName1" : experimentOptions,
};
```
`experimentOptions` references the variable experimentOptions in `customizationVariables.js`.
Default customization variable is `defaultOptions`. If you supply a wrong variable in the map or if you don't specify any variable for that experiment name, `defaultOptions` will be applied.
> You mustn't remove the defaultOptions variable of `customizationVariables.js`.

## The color option
* **Default colors** 
Nodes : light blue (#D2E5FF)  
Highlighted (selected) nodes : dark blue (#2472f0)  
Edges : grey  
Highlighted (selected) edges : dark blue (#2472f0)  

* **Setting the color option 
If you want to set a color for the nodes/edges, be careful.
#### Example 9
```javascript 
	entityID : {
			 agent1 : { 
				 color : 'green'
			 }
		}
```
In the example 9, you overlap ALL color options. The node will be green even if it is highlighted. Instead you **should never set the color as a value of the color key, but rather supply an object**. If you want to change the color of the node/edge on highlight, see example 11.
#### Example 10 : set the `background` color of the agent of ID 'agent1' to green
```javascript 
	entityID : {
			 agent1 : { 
				 color : {
				 background : 'green'
			 }
		}
```
#### Example 11 : set the `highlight` color of the agent of ID 'agent1' to red
```javascript 
	entityID : {
			 agent1 : { 
				 color : {
				 background : 'green',
				 highlight : 'red'
			 }
		}
```

