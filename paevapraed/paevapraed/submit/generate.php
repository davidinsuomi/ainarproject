<?php
include_once( "../utils.php" );
define( "CODESALT", "df76d7e7f8df8" );
echo encrypt( "RANDURI", CODESALT );
?>