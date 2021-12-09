<?php

if($_SESSION['resume']) {
  header("Content-Disposition: attachment;filename={$_SESSION['resume']}");
  header("Content-Transfer-Encoding: binary"); 
  header('Pragma: no-cache'); 
}

else {
  header("location: dashboard.php");
}
?>