Hi the following instructions are written to work with windows as this was written on a windows machine

    ensure your windows machine has PHP 8.0.5 installed i use <a href="https://laragon.org">Laragon</a> for my web application environment so do check it out

    then to run it ensure your db has no password and the username is root and u have a database named realm_digital


    then CD into the app folder and type the command php artisan migrate:fresh

    then edit your .env file where its written NOTIFY_BIRTHDAY="" replace text inside quote and enter ur email to receive the wishes u may also edit the smtp settings to your own to send out emails in the real world

    then run php artisan serve  \\this will open a port on 8000 on your local machine ensure this port is open

    then on another console tab cd again into app folder and type php artisan queue:work \\this is important as we use queues to process background data.

    then fireup your browser or postman and do a get request to http://127.0.0.1:8000 this will return json results
