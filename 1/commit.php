<?php

	//配置
		session_start();
		if(strlen($_SESSION['sessionKey']) < 50)
		{
			echo "<script>window.location.href='http://container.open.taobao.com/container?appkey=".$_GET['appkey']."'</script>";
		}

		require("config.php");
		require("account.php");

		require("Snoopy.class.php");
		require("topsdk/TopClient.php");
		require("topsdk/RequestCheckUtil.php");
		require("topsdk/request/ItemUpdateRequest.php");

		$appkey = $_GET['appkey'];
		$secret = getAppSecret($appkey);
		$sessionKey = $_SESSION["sessionKey"];

		$num_iid = $_GET['num_iid'];
		$title = $_GET['title'];

		$c = new TopClient;
		$c->appkey = $appkey;
		$c->secretKey = $secret;
		$req = new ItemUpdateRequest;
		$req->setNumIid($num_iid);
		$req->setTitle($title);
		//$req->setListTime("2000-01-01 00:00:00");
		$req->setApproveStatus("onsale");
		$resp = $c->execute($req, $sessionKey);

		if($resp->item->num_iid == $num_iid)
		{
			echo "yes";
		}
?>