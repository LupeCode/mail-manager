# Mail Manager

This single page PHP app is designed to aid in the management of email servers set up with SQL virtual emails.

I designed this for a Postfix Dovecot MySQL setup.  If you are using something different, you may need to make modifications.

Think of this app like a _really_ ___really___ simple Postfix Admin.

This app only supports 1 domain.

## Requirements

* PHP >= 7.0.0
* A PDO connection to your database of choice

That's it.  You don't actually need to run this on an email server, this app just changes values in the database.

## Features

* Add email accounts
* Add email aliases
* Change email passwords
* Delete email accounts
* Delete email aliases

## Usage

You'll need to create a `config.php` file, use `config.sample.php` as a guide.  Or just rename that file and edit it.

## Screenshot

![screenshot](https://projects.lupecode.com/mail-manager/images/Screen%20Shot%202018-04-14%20at%2022.44.28-fullpage.png "Screenshot")

## Database Setup

The database this is set up for has these three tables.  As stated, yours are probably different.  These are included here to give you an idea of what you might have to change to get this to work on your system.

~~~
CREATE TABLE `virtual_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
~~~

~~~
CREATE TABLE `virtual_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `password` varchar(106) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `domain_id` (`domain_id`),
  CONSTRAINT `virtual_users_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `virtual_domains` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
~~~

~~~
CREATE TABLE `virtual_aliases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `source` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`),
  KEY `virtual_aliases_ibfk_2` (`destination_id`),
  CONSTRAINT `virtual_aliases_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `virtual_domains` (`id`) ON DELETE CASCADE,
  CONSTRAINT `virtual_aliases_ibfk_2` FOREIGN KEY (`destination_id`) REFERENCES `virtual_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
~~~

License: GNU GPL v3 or newer.
~~~
Copyright (C) Lupe Code, LLC.; Joshua Lopez

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see http://www.gnu.org/licenses/.
~~~
