# Kajika
Kajika is an open source web application that can be used to visualize networks and charts. 
It is used to visualize Multi-Agents Systems using networks.  

## Getting started
  ### Prerequisites
Kajika has the following dependencies :
* MongoDB 4.0 (NoSQL Database)
* XAMPP 7.3.6 (Compatibility with Windows, Linux and Mac OS) or any Web Environment with a PHP interpreter.

 ### Installation 
 
 #### 1. Install MongoDB 4.0 and MongoDB Compass
 Go to https://www.mongodb.com/download-center/community .  
 Choose the 4.0 version and the MSI package, along with the OS that you use.
 Download the file and execute it. Select the checkbox "Install MongoDB Compass" when you are asked to.  
 MongoDB Compass is A GUI that helps you manage your MongoDB Database. It will be needed on **step 5**.
 
 * **For Windows users :**  
 Navigate to the C Drive on your computer and create a new folder called data here.  
 Inside the data folder you just created, create another folder called db.
  ```
  md C:\data\db
  ```
  To start a MongoDB service, navigate to your MongoDB installation folder, then go to the bin folder and lauch mongod.exe.
  If you have kept the default MongoDB path during the installation, you can execute this command in the command prompt 
  ```
  C:\Program Files\MongoDB\Server\4.0\bin\mongod.exe
  ```
>  You can save this command in a ```.cmd``` file to launch MongoDB faster.
  
 * **For Linux users :**  
 Go to https://docs.mongodb.com/v3.2/administration/install-on-linux/ and choose one of the distributions.
 
For more information on how to install MongoDB, go to https://docs.mongodb.com/v3.2/administration/install-community/ .
 
 #### 2. Install XAMPP 7.3.6
 Go to https://www.apachefriends.org/download.html and get the installer for **PHP 7.3.6**.  
Launch the installer and execute the instructions. If you get a warning message at the start of the installation about the User Account Control (UAC), just skip it. 
 
 #### 3. Get the MongoDB driver for XAMPP 
 When you are done with the XAMPP installation, go to https://pecl.php.net/package/mongodb/1.5.5/windows and download the PHP 7.3 Thread Safe package.  
Go to the xampp installation folder. Unzip the driver and put the ``` php_mongodb.dll ``` file in the `` xampp/php/ext `` folder.  
Then, go to ``` xampp/php/ ``` and open ```php.ini ``` (it is a configuration file). Add the following line : ``` extension=php_mongodb.dll ```   
Restart Apache on Xampp if it was already started.  
For complementary information on how to install MongoDB on PHP for Xampp, see https://learnedia.com/install-mongodb-configure-php-xampp-windows/ .

 #### 4. Setup Kajika within the XAMPP environment
 Download the repository and unzip it in  ``` xampp/htdocs ``` or simply clone the git repository. Rename the folder ``` kajika ```.  
 Ensure that Apache is started on Xampp.  
 Open your browser, type in ``` http://localhost/kajika ```.  
 
#### 5. Import your Adaptative Multi-Agent System (AMAS) on the MongoDB database
If you use Java or Python for your AMAS, download one of the following drivers and follow the instructions.
* **Java driver** :  https://github.com/FlorentMouysset/Links2-javadriver .
* **Python driver** : https://github.com/tanguyesteoule/links_pydriver .   

#### 6. Configure the connexion with the database 
Within the `` kajika `` folder, open `lib.php` with a text editor.  
Change the line  7 `$database = $client->DBName;` by replacing *DBName* with the name of your defined MongoDB database.
If you have not enabled authentication on MongoDB, leave the array on line 5 empty.

**Autentication enabled on mongoDB** :  
If you want to enable authentication on MongoDB, see https://docs.mongodb.com/manual/tutorial/enable-authentication/ .  
If it is done, change `lib.php` and replace the fields with the appropriate strings.  
**Example :**
```php
	require 'vendor/autoload.php'; 
	$client = new MongoDB\Client('mongodb://localhost:27017',
	[ 
	   'username' => 'a',
	   'password' => 'b',
	   'connectTimeoutMS' => 60000	
	]);
	$database = $client->DBName; 
 ```
 
#### 7. Start using Kajika !
First, ensure that Apache on Xampp is running then start the MongoDB service on the command prompt.  
**Reminder** default on Windows is :
  ```
  C:\"Program Files"\MongoDB\Server\4.0\bin\mongod.exe
  ```
 Finally, open the browser and type in ``` http://localhost/kajika ```. You can now use the app !


 ### How to use Kajika ?
 #### 1. Experiment selection
 Select "Experiments" on the left sidebar. 
 > We suggest that you give each experiment a different name when you fill the MongoDB DB with the driver. That way, you won't have two items with the same name on the experiment list.
 
 #### 2. Network visualization 
 **IMPORTANT : if your network doesn't show or there is an error, refresh the page with** <kbd>CTRL<kbd> + <kbd>F5<kbd>**
##### Information display 
Kajika is designed to focus on the visualization of the network. You can
**Experiment information** : Click on the title of the experiment to show a modal with the related information.
**Entity information** :  Click on a node. On the left bottom side of the page, a modal with the related information will pop.
**Relation information** :  Click on an edge. On the right bottom side of the page, a modal with the related information will pop. 
**Multi-selection information display** : You can display many entity/relation information at the same time. To do that, click on an entity while holding <kbd>CTRL</kbd>.

 ##### Network visual customization

See [CUSTOMIZATION.md](https://github.com/Azouu/Kajika/blob/master/CUSTOMIZATION.md) for more details.


 ##### Options panel
 Click on the "Options" button on the top right corner of the page to toggle the options panel.  
* Network :  
Physics : 
When you move a node on the network, it will pull all the other connected nodes thanks to gravity models (physics).
Physics are activated by default, but you can deactivate them. It can be useful to do this if you have too many entities and your network moves too much and doesn't get stabilized.

Selection : 
When toggling the "selection" button, you will only show the selected entities and their neighbors. You can select an entity or many by holding <kbd>CTRL</kbd> while clicking.

* Player
FPS (Frame Per Second) : Change the speed of the player. Default is 1 FPS.

* Entity filter  
You can filter the network to display only the entities that interest you. 
Important : for the "By attribute" filter.
The expression must be in the form <attribute name> <operator> <value>. 
You should have only 2 spaces in the expression, between each element.  
Warning : the input is case sensitive ! 

What are the operators ?  
Hover on the `?` button next to the "By attribute" title to see the correspondances. 

Example 1 : 
I want to show all the agents that have a `criticality` < 80.
In this case the expression would be : `criticality lt 80`.

Example 2 : 
Exceptionnal case : the value contains a space
I want to show the agent that has the `name` `Agent 1`. But there is a problem : there is a space in this value. 
In this case the expression would be : `name eq Agent_1`.
> Replace each space with `_`.

Example 3 :  
I want to show all the agents except the one that has the `name`attribute equaling `Agent 1`.
In this case the expression would be : `name neq Agent_1`.

#### 3. Charts
Here you can see the evolution of numeric attributes.  
When you check an attribute, you display its evolution for each agent that has it.  
When you check an agent, you display the evolution of all its numeric attributes.  
Click on an element of the legend to deactivate its corresponding trace, or double-click to isolate it.  
You can hold the left mouse button while moving it to zoom on a specific place of the chart.  
The charts are built with the plotly.js library. You can see https://plot.ly/javascript/ if you want more details.  

> When you check a box in one of the two sections ("Attributes" or "Agents"), you automatically uncheck the boxes in the other.

## Built with
* Vis.js 4.21.0
* JQuery 
* Bootsrap 4.3.1
* Lodash
* plotly.js 1.47.4
* Font Awesome 5.0.13

## Support 
If you encounter a difficulty with Kajika, feel free to open an issue on this repository.  
You can also send me an email on lhdj.ines@gmail.com  

## Contributing
If you want to contribute to the growth of Kajika, feel free to send a pull request and ideally an email specifying what you want to add to the app.
See [CONTRIBUTION.md](https://github.com/Azouu/Kajika/blob/master/CONTRIBUTION.md) to get more information on the structure of the app.  
## Authors
The app was made during the internship of Inès Louahadj under the supervision of Tanguy Esteoule and Carole Bernon, team members of SMAC (IRIT), between April and July 2019. 

## License

## Project status




 
 
 
 
 
 



