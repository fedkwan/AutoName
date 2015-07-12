<?php

	session_start();
	$_SESSION['sessionKey'] = $_GET['top_session'];
	print_r($_SESSION);
	echo "<script>window.location.href='run.php?appkey=".$_GET['top_appkey']."'</script>";
