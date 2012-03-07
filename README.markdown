README
======

Basic Setup
-----------

There are some 2 basic steps to get SGTi up and running and they are:

  - Setup database parameters.
  - Generate DB from entity mapping
  - Configure virtual host.

**1) Setup database connection**

--------------------------------------------------------------------------

SGTi uses Zend Framework 1.11 with Doctrine 2.1 as ORM, glueing them together with [Bisna]. Bisna is already setup and configured, but you have to specify the connection parameters and the DBMS you will use.

This is done in the `application.ini` configuration file under the section called *Doctrine DBAL Configuration*.  

Available database drivers can be found [here].  
  

**2) Generate DB from entity mapping**

--------------------------------------------------------------------------

After setting up your DB params go to the `scripts` folder and run:

        php doctrine.php orm:schema-tool:create

This will create the corresponding tables. Then you need to generate the corresponding proxies for the entities, Doctrine uses these proxies mainly for lazy-loading, and to keep track of changes to entities:

        php doctrine.php orm:generate-proxies

Proxies are generated in the `library/SGTi/Entity/Proxy`. **It's important that all the proxy files generated have read, write and execute permissions**. Take this into account if you receive a "permission denied" error when running for the first time (or a blank screen if you have php errors disabled).

