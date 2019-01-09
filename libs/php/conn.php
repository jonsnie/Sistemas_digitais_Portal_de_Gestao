<?php

$user     = getenv("DB_USER");
$pass     = getenv("DB_PASS");
$database = getenv("DB_NAME");
$host     = getenv("DB_HOST");
$port     = getenv("DB_PORT");

$conn_neogrid = pg_connect("host=".$host." port=".$port." dbname=".$database." user=".$user." password=".$pass);

?>
