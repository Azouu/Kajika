# Kajika
Kajika is an open source web application that can be used to visualize networks and charts. 

## Getting started
  ### Prerequisites
Kajika currently has the following dependencies :
* MongoDB 4.0
* MongoDB Compass
* XAMPP 7.3.6 (Compatibility with Windows, Linux and Mac OS)

 ### Installation 
 
 #### 1. Install MongoDB 4.0 and MongoDB Compass
 Go to https://www.mongodb.com/download-center/community .  
 Choose the 4.0 version and the MSI package, along with the OS that you use.
 Download the file and execute it. Select the checkbox "Install MongoDB Compass" when you are asked to.
 
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
 _**Note :** You can save this command in a ```.cmd``` file to launch MongoDB faster._
  
 * **For Linux users :**  
 Go to https://docs.mongodb.com/v3.2/administration/install-on-linux/ and choose one of the distributions.
 
For more information on how to install MongoDB, go to https://docs.mongodb.com/v3.2/administration/install-community/ .
 
 #### 2. Install XAMPP 7.3.6
 Go to https://www.apachefriends.org/download.html and get the installer for **PHP 7.3.6**.  
 Launch the installer and execute the instructions.
 
 #### 3. Get the MongoDB driver for XAMPP 
 When you are done with the XAMPP installation, go to https://pecl.php.net/package/mongodb/1.5.5/windows and download the PHP 7.3 Thread Safe package.  
 Unzip it and put the ``` php_mongodb.dll ``` file in the xampp installation folder. The relative path is ``` xampp/php/ext ```.  
 Then, register the ``` php_mongodb.dll ``` file in ```php.ini ```.  It's in  ``` xampp/php/ ``` (php folder).  
 Add the following line : ``` extension=php_mongodb.dll ```  
 Restart XAMPP.
For complementary information on how to install MongoDB on PHP for Xampp, go to https://learnedia.com/install-mongodb-configure-php-xampp-windows/ .
 
 
 
 
 
 
 



