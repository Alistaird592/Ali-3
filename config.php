<?php 
ob_start(); 
session_start();

$timezone=date_default_timezone_set("Europe/London");

$conn=mysqli_connect("localhost", "root","", "social");

?>