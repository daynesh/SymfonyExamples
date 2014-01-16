MyAlbum
=======

Introduction
------------
This is a simple application used to demonstrate the Symfony PHP web framework.  It essentially provides the user with access to a list of music albums stored in a local MySQL database.  

It supports HTTP Requests on the following URL paths:

* GET /
* GET /album
* GET /album/add
* GET /album/edit/:id
* GET /album/delete/:id
* POST /album/add
* POST /album/edit/:id
* POST /album/delete/:id


Web Server Setup
----------------

### Symfony CLI Server

The simplest way to get started is by running Symfony's built-in web server:

    php app/console server:run

This will start the cli-server on port 8080, and bind it to localhost.

