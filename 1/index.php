<?php

	session_start();
	if(strlen($_SESSION['sessionKey']) < 50)
	{
		echo "<script>window.location.href='http://container.open.taobao.com/container?appkey=".$_GET['appkey']."'</script>";
	}
	else
	{
		echo "<script>window.location.href='run.php?appkey=".$_GET['appkey']."'</script>";
	}