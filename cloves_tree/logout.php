<?php 
include('./conn/config.php');
	session_start();
	session_destroy();
?>
<script>
	window.location = 'login.php';
</script>