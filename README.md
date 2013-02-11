lighthouse-fogbugz
==================

Lighthouse to Fogbugz integration to push issues reported in Lighthouse to Fogbugz via an email.

The application is built using Silex and has a composer.json file to get started. The data store is SQLlite so should not require any permissions to be set-up.

All commands are built using the Symfony Console component so to set-up the project you will need to have terminal or cygwin on your machine.

Installation:
==================

    cd /path/to/directory/
    php console.php setup:lighthouse https://acme.lighthouseapi.com a1b2c3d4e5f6g7h8i9
    php console.php setup:fogbugz support@domain.com

If you would like to start the synchronisation from a specific date then there is an option for that (20120201)

Running synchronisation:
==================

Run as a cron job as often as your please:

    php console.php push:tickets