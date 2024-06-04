<?php
   session_start();
   
   $link = new mysqli("localhost", "root", "", "datacube");
   $link->set_charset("utf8mb4");
?>