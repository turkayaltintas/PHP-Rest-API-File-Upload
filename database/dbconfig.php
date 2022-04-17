<?php
require_once ('MysqliDb.php');
$mysqli = new mysqli ('localhost', 'turkay', '1', 'filetransfer');
$db = new MysqliDb ($mysqli);