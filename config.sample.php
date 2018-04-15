<?php

if (!defined("MM_SESSION_NAME")) {
    /**
     * The name of the session
     */
    define("MM_SESSION_NAME", "mail-manager");
}

if (!defined("MM_PASSWORD")) {
    /**
     * The main password for this app
     */
    define("MM_PASSWORD", "mailserver");
}

if (!defined("MM_DATABASE_TYPE")) {
    /**
     * The database type
     */
    define("MM_DATABASE_TYPE", "mysql");
}

if (!defined("MM_DATABASE_HOST")) {
    /**
     * The database host
     */
    define("MM_DATABASE_HOST", "localhost");
}

if (!defined("MM_DATABASE_NAME")) {
    /**
     * The database name
     */
    define("MM_DATABASE_NAME", "mailserver");
}

if (!defined("MM_DATABASE_USER")) {
    /**
     * The database user
     */
    define("MM_DATABASE_USER", "mailserver");
}

if (!defined("MM_DATABASE_PASS")) {
    /**
     * The database password
     */
    define("MM_DATABASE_PASS", "mailserver");
}

if (!defined("MM_QUERY_DOMAIN")) {
    /**
     * The query to find the name of the domain
     * This query relies on the column the name is in to be named 'name'
     */
    define("MM_QUERY_DOMAIN", "SELECT virtual_domains.name AS name FROM virtual_domains WHERE id=1;");
}

if (!defined("MM_QUERY_NEW_USER")) {
    /**
     * The query to add a new email address to the database
     */
    define("MM_QUERY_NEW_USER", "INSERT INTO virtual_users (domain_id, password, email) VALUES (1, ENCRYPT(:password, CONCAT('$6$', SUBSTRING(SHA(RAND()), -16))), :email);");
}

if (!defined("MM_QUERY_CHANGE_PASSWORD")) {
    /**
     * The query to change a password
     */
    define("MM_QUERY_CHANGE_PASSWORD", "UPDATE virtual_users SET password = ENCRYPT(:password, CONCAT('$6$', SUBSTRING(SHA(RAND()), -16))) WHERE email=:email;");
}

if (!defined("MM_QUERY_FIND_ALIAS")) {
    /**
     * The find if a destination email address is in the database already
     */
    define("MM_QUERY_FIND_ALIAS", "SELECT virtual_users.email AS email, virtual_users.id AS id FROM virtual_users WHERE email = :dest;");
}

if (!defined("MM_QUERY_NEW_ALIAS")) {
    /**
     * The query to add a new alias to the database
     */
    define("MM_QUERY_NEW_ALIAS", "INSERT INTO virtual_aliases (domain_id, destination_id, source, destination) VALUES (1, :did, :source, :destination);");
}

if (!defined("MM_QUERY_DELETE_USER")) {
    /**
     * The query to delete an email address
     */
    define("MM_QUERY_DELETE_USER", "DELETE FROM virtual_users WHERE id = :id");
}

if (!defined("MM_QUERY_DELETE_ALIAS")) {
    /**
     * The query to delete an alias
     */
    define("MM_QUERY_DELETE_ALIAS", "DELETE FROM virtual_aliases WHERE id = :id");
}

if (!defined("MM_QUERY_SELECT_USERS")) {
    /**
     * The query to select all the email addresses for display
     */
    define("MM_QUERY_SELECT_USERS", "SELECT virtual_users.email AS email, virtual_users.id AS id FROM virtual_users ORDER BY email;");
}

if (!defined("MM_QUERY_SELECT_ALIAS")) {
    /**
     * The query to select all the email aliases for display
     */
    define("MM_QUERY_SELECT_ALIAS", "SELECT virtual_aliases.source AS source, virtual_aliases.destination AS destination, virtual_aliases.id AS id FROM virtual_aliases ORDER BY source, destination;");
}

