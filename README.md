# Kajika
Kajika is an open source web application that can be used to visualize networks and charts. 

## Getting started
  ### Prerequisites
Kajika currently has the following dependencies :
* MongoDB 4.0 (NoSQL Database)
* XAMPP 7.3.6 (Compatibility with Windows, Linux and Mac OS)

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
 Launch the installer and execute the instructions.
 
 #### 3. Get the MongoDB driver for XAMPP 
 When you are done with the XAMPP installation, go to https://pecl.php.net/package/mongodb/1.5.5/windows and download the PHP 7.3 Thread Safe package.  
 For the next sections, we suppose that you are already located in the xampp installation folder (``` C:\xampp ``` by default in Windows). Unzip the driver and put the ``` php_mongodb.dll ``` file in the `` xampp/php/ext `` folder.  
Then, register the ``` php_mongodb.dll ``` file in ```php.ini ```.  It is located in  ``` xampp/php/ ```. 
Add the following line : ``` extension=php_mongodb.dll ```.  
Restart XAMPP (Apache).  
For complementary information on how to install MongoDB on PHP for Xampp, see https://learnedia.com/install-mongodb-configure-php-xampp-windows/ .

 #### 4. Setup Kajika within the XAMPP environment
 Download the repository and unzip it in  ``` xampp/php/htdocs ```.  
 Rename it ``` kajika ```.  
 Ensure that Apache is started on Xampp.  
 Open your browser, type in ``` http://localhost/kajika ```.  
 
#### 5. Import your Adaptative Multi-Agent System (AMAS) on the MongoDB database
If you use Java or Python for your AMAS, download one of the following drivers and follow the instructions.
* **Java driver** :  https://github.com/FlorentMouysset/Links2-javadriver .
* **Python driver** : https://github.com/tanguyesteoule/links_pydriver .   

#### 6. Configure the connexion with the database 
Within the Kajika folder, open `lib.php` with a text editor.  
```php
	require 'vendor/autoload.php'; 
	$client = new MongoDB\Client('mongodb://localhost:27017',[]);
	$database = $client->DBName; 
 ```
If you have not enabled authentication on MongoDB, leave the array on the first line empty.  
To enable authentication on MongoDB, see https://docs.mongodb.com/manual/tutorial/enable-authentication/ .  
When it is done, change `lib.php` and replace the fields with the appropriate strings.  
**Example :**
```php
	require 'vendor/autoload.php'; 
	$client = new MongoDB\Client('mongodb://localhost:27017',
	[ 
	   'username' => 'root',
	   'password' => '',
	   'connectTimeoutMS' => 60000	
	]);
	$database = $client->DBName; 
 ```
Don't forget to change the line `$database = $client->DBName;` by replacing *DBName* with the name of your defined MongoDB database.

#### 6. Start using Kajika !
First, Ensure that xampp and Apache are running.  
Start MongoDB. Default on Windows is :
  ```
  C:\Program Files\MongoDB\Server\4.0\bin\mongod.exe
  ```
 Finally, open the browser and type in ``` http://localhost/kajika ```. You can now use the app !

 
 
 
 
 
 
 



