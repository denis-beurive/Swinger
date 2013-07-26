<?php

// This file is just a redirection to the file that implements the Swinger's bootstrap sequence.
// It is the application's entry point.
//
// Note: In your Apache's configuration file, you should have something like:
//       RewriteCond %{DOCUMENT_ROOT}/$1 !-f
//       RewriteRule ^(.*)$ /swinger.php/$1 [last,qsappend]
//
// By default, the Swinger library is under the directory "./swinger".
// But you may want to move these files (the entire directory "./swinger") to another location.
// You should do that if you want to use a single copy of the Swinger library for several applications.
// If you want to do that, just change the value of SWINGER_PATH.

define (SWINGER_PATH, __DIR__ . DIRECTORY_SEPARATOR . 'swinger');
include SWINGER_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Swing.php';

?>