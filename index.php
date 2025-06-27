<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Istituto storico della Resistenza e dell'Età contemporanea di Forlì-Cesena";
$templateParams["nome"] = "home.php";
$templateParams["css"] = "css/style.css";
$templateParams["js"] = array("js/base.js");

require 'template/base.php';
?>