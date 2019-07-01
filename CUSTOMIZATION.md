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
       'agent1' : 
       { 
         color : 'green'
       }
    },
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
* Customization by type 
If you want to change the style of 

