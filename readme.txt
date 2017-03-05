GENERAL INFORMATION
===================

Active Agenda is a web-technology-based data management application designed
to help employers manage many types of risk and mitigate "loss" in the workplace.
Active Agenda helps employers manage risks such as: human injury, environmental
releases, employment practices, product safety, property loss...and a long list
of other pre and post loss risks.  It provides tools for pro-active, pre-incident,
risk prevention, as well as post-accident fact gathering, documentation, and
claims management.

Philosophically, we believe in open collaboration and sharing rather than 
"command and control."  Our design interfaces, and business processes, were
built with "accountable openness" in mind.  Users can be allowed to find and
compare data by themselves - helping themselves and each other do a better job,
but all edits are logged, and (in the future) all transactions can be easily
reverted.  Of course, certain types of data must be kept confidential, and there
IS a system for assigning granular permissions to data.  In fact, it is possible
to run an Active Agenda installation with the polar opposite philosophy, where
users only see the specific data they need.

Technically, Active Agenda is a web database application that works with PHP and
MySQL. Its many sub-applications (called "modules") are defined via an XML
format that consolidates metadata from data structure to screen layouts.  The
PHP layer does a whole lot of SQL generating, and builds the complicated joins
between tables automatically.  Most of the difficult code is pre-generated
beforehand to simpler, static PHP files that are executed on the web server.  To
that end, there is a command-line utility that munches through all the XML
definitions and applies any changes to tables or user interface.

Active Agenda is also a work in progress.  It was developed by two lone
developers with a vision, and supported by wives with looooots of patience, as
well as children wearing very old shoes.  There are rough edges in the
application.  We believe the application is actually quite usable already, but
it's not finished.

If you find this interesting, definitely try downloading Active Agenda,
installing it, and send us a bug report :-)

Typed by: Mattias Thorslund

mthorslund@activeagenda.net
http://www.activeagenda.net


INSTALLATION
============

See the instructions at s2a/install/installation.txt in this package.

UPGRADING
=========

See the instructions at s2a/install/upgrade.txt in this package.

PREREQUISITES
=============

Web Server: Any Web server that runs PHP should work in theory. We've used 
Apache 1.3.x and 2.0.x.

PHP: Version 4.x, not 5, yet. A migration will be necessary at some point.

MySQL: Written for 4.1, testing with 5.0 - seems to work fine.
