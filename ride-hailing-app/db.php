<?php 
$host = "localhost"; 
$db_user = "elisa.okyere"; 
$db_pass = "MaamesLushLyfe"; 
$db_name = "ecommerce_2026A_elisa_okyere"; 
$conn = new mysqli($host, $db_user, $db_pass, $db_name); 
 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
}