<?php

require 'dependencies/PDOcon.php';
require 'dependencies/funcs.php';

echo EncryptPass("Duck2013", "rbodewes@roc-dev.com");

echo "<br><br><br>";

echo getToken(64);
