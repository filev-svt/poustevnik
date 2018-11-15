<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 26.03.18
 * Time: 19:59
 */

function getDBConnection () {

    $mysql = new PDO("mysql:host=127.0.0.1;port=8889;dbname=mydb","root", "root");

    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mysql->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);

    return $mysql;
}