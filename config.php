<?php

// ----------------------------------------------
// Auto-load: production mode
// ----------------------------------------------

// The production mode should be used in the "production environment".
// In this environment, the application's code should not be changed.
// This implies that no new class will be added while the application is running (in the production environment).
// In this mode the application will not dynamically index the list of classes each time it boots.
// The application will just load a file (the "index file") that contains the class index.
//    o If the "index file" exists, it is loaded.
//    o Otherwise, it is created.
// 
// If you want to activate the production mode for the auto-load process,
// then you must uncomment the following line.
//
// WARNING:
// If you add a new class in your application which is running in production mode, you must remove the
// following file:
//          "data/classes.idx".

// AutoLoad::activateProductionMode();




// ----------------------------------------------
// Auto-load: classes' repository
// ----------------------------------------------

// If you want to add a new classes' repository for the auto-load process,
// then you must use the method Swing::addClassesRepository(path_to_the_repository).
// Ex: Swing::addClassesRepository('/tmp');
//
// WARNING: 
// If you add a new classes' repository, you must remove the following file:
//          "data/classes.idx";

// Swing::addClassesRepository($your_directory);




// ----------------------------------------------
// Your configuration below.
// ----------------------------------------------

// Here, you can use the "registry".
// For example: Registry::set('nextHop', '192.23.4.5');
// In your code, you can get the value: $nextHop = Registry::get('nextHop');




// ----------------------------------------------
// Sessions' configuration.
// ----------------------------------------------

// If you want to initialize session, you can do it there.
//
// If you want to use the default PHP sessions' storage:
// require_once 'Session.php';
// Session::init();
//
// __OR__
//
// If you want to use the Swinger' simple implementation for a MySql session's backend:
// require_once 'SessionMySql.php';
// Session::init(new SessionMySql($db_name, $dn_host, $db_user, $db_password));
//
// This class supposes that you use the following DB schema:
// CREATE TABLE sessions
// (
//    `id` VARCHAR(255) NOT NULL,
//    `data` TEXT NOT NULL,
//    `timestamp` DATETIME NOT NULL,
//    INDEX (`id`),
//    INDEX (`timestamp`),
//    UNIQUE (`id`)
// )  ENGINE=MyISAM;
//
// Please note that you can change the name of the table, if you want.
// require_once 'Session.php';
// Session::init(new SessionMySql($db_name, $dn_host, $db_user, $db_password, $table_name));

// require_once 'Session.php';
// Session::init();
//
// __OR__
//
// require_once 'Session.php';
// require_once 'SessionMySql.php';
// Session::init(new SessionMySql($db_name, $dn_host, $db_user, $db_password));

require_once 'Session.php';
require_once 'SessionMySql.php';
Session::init(new SessionMySql('test', '127.0.0.1', 'root', 'toor'));

?>