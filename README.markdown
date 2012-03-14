README FIRST
============
<p>
  <strong>
  This is the project we took on for college as the final project in our career. It is originally intended for our college's administration, therefore it's still very specific to the particular problem we wanted to solve.
  It's also only in spanish (even though some code and comments are in english), but I plan on making everything english and with appropiate documentation.  
  I plan to make a generic system that can be used to manange different types of educational organizations, but this is in VERY EARLY development yet. It's functional but has many bugs and it's kind of messy to use.
  
  Please feel free to suggest changes or report any issues you find here or to jereman_gnr@hotmail.com
  </strong>
</p>


Basic Setup
-----------

There are 3 basic steps to get SGTi up and running:

  - Setup database connection parameters.
  - Generate DB from entity mapping
  - Configure virtual host.

**1) Setup database connection parameters**

--------------------------------------------------------------------------

SGTi uses Zend Framework 1.11 with Doctrine 2.1 as ORM, glueing them together with [Bisna][bisna]. Bisna is already setup and configured, but you have to specify the connection parameters and the DBMS you will use.

This is done in the `application.ini` configuration file under the section called *Doctrine DBAL Configuration*.  

Available database drivers can be found [here][doctrine].  
  

**2) Generate DB from entity mapping**

--------------------------------------------------------------------------

After setting up your DB params go to the `scripts` folder and run:

        php doctrine.php orm:schema-tool:create

This will create the corresponding tables. Then you need to generate the corresponding proxies for the entities, Doctrine uses these proxies mainly for lazy-loading, and to keep track of changes to entities:

        php doctrine.php orm:generate-proxies

Proxies are generated in the `library/SGTi/Entity/Proxy`. **It's important that all the proxy files generated have read, write and execute permissions**. Take this into account if you receive a "permission denied" or "can't open file" error when running for the first time (or a blank screen if you have php errors disabled).

You can also use this tool to review any changes you made to entity mappings and see if they are valid, just type `php doctrine.php` for a full list of commands.

**3) Configure virtual host**

--------------------------------------------------------------------------

The last step is to setup a virtual host for the project. If you located the project in, lets say, `/var/www/html/SGTi` you would setup your virtual host to look like this (I use httpd with Fedora 16)

        <VirtualHost 127.0.0.1>
           DocumentRoot "/var/www/html/SGTi/public"
           ServerName sgti.local

           # Set the environment to development mode. It can also be done in bootstrap.php, index.php or .htaccess in public folder
           SetEnv APPLICATION_ENV development

           <Directory "/var/www/html/SGTi/public">
               Options Indexes MultiViews FollowSymLinks
               AllowOverride All
               Order allow,deny
               Allow from all
           </Directory>
        </VirtualHost>

**BE SURE TO HAVE mod_rewrite ACTIVATED ON YOUR SERVER.** Zend Framework depends on it to convert it's url's.  


**EDIT 1:** For the first login the user and password are *root* and *root*.


[bisna]: https://github.com/guilhermeblanco/zendFramework1-Doctrine2
[doctrine]: http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connection-details