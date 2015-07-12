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
		require("topsdk/request/UserSellerGetRequest.php");
		require("topsdk/request/ItemsInventoryGetRequest.php");
		require("topsdk/request/FenxiaoDistributorProductsGetRequest.php");

		$appkey = $_GET['appkey'];
		$secret = getAppSecret($appkey);
		$sessionKey = $_SESSION["sessionKey"];

	//获取授权卖家ID
		$c = new TopClient;
		$c->appkey = $appkey;
		$c->secretKey = $secret;
		$req = new UserSellerGetRequest;
		$req->setFields("nick");
		$resp = $c->execute($req, $sessionKey);
		$user = $resp->user->nick;
		echo "欢迎你，".$user."<br>";
	
	//获取仓库宝贝
		function getOneInventory($appkey, $secret, $sessionKey, $banner = "for_shelved")
		{
			$c = new TopClient;
			$c->appkey = $appkey;
			$c->secretKey = $secret;
			$req = new ItemsInventoryGetRequest;
			$req->setFields("num_iid");
			$req->setBanner($banner);
			$req->setPageNo(1);
			$req->setPageSize(1);
			$resp = $c->execute($req, $sessionKey);
			
			$resultCount = count($resp->items->item);
			if($resultCount > 0)
			{
				return $resp->items->item->num_iid;
			}
			else
			{
				return 1;
			}
		}

	//获取产品信息
		function getProductInfo($appkey, $secret, $sessionKey, $num_iid)
		{
			$c = new TopClient;
			$c->appkey = $appkey;
			$c->secretKey = $secret;
			$req = new FenxiaoDistributorProductsGetRequest;
			$req->setItemIds($num_iid);
			$resp = $c->execute($req, $sessionKey);

			return $resp->products->fenxiao_product->name;
		}

	//获取uniqpid产品ID
		function getUniqpid($keyword)
		{
			$snoopy = new Snoopy();
			$sourceURL = "http://s.taobao.com/search?q=".urlencode($keyword);
			$snoopy->fetch($sourceURL);			
			$links = $snoopy->results;
			$links = htmlspecialchars($links);

			preg_match("/uniqpid(.*?)nid/", $links, $matches);
			preg_match("/\d{9}/", $matches[1], $result);
			return $result[0];
			
			//以下版本2015.04.20宣布作废
			/*$snoopy = new Snoopy();
			$sourceURL = "http://s.taobao.com/search?q=".urlencode($keyword);
			$snoopy->fetchlinks($sourceURL);
			$links = $snoopy->results;

			foreach ($links as $key => $value)
			{
				if (strstr($value, "uniqpid"))
				{
					$start = strpos($value, "uniqpid=") + 9;
					$end = strpos($value, "nid") - 1;
					$result = substr($value, $start, $end - $start);
					return $result;
				}
			}*/
		}

	//获取同款产品他人的标题
		function getSamestyleTitle($uniqpid)
		{
			$snoopy = new Snoopy();
			$sourceURL = "http://s.taobao.com/search?type=samestyle&app=i2i&uniqpid=-".$uniqpid."&s=";
			$snoopy->fetch($sourceURL);
			$source = $snoopy->results;

			preg_match_all("/raw_title\"\:\"(.*?)\"\,\"pic_url/", $source, $matches);
			return $matches[1];
			
			//以下版本2015.04.20宣布作废
			/*$snoopy = new Snoopy();
			$sourceURL = "http://s.taobao.com/search?type=samestyle&app=i2i&uniqpid=-".$uniqpid."&s=";
			$snoopy->fetch($sourceURL);
			$source = $snoopy->results;
			$source = mb_convert_encoding($source ,"UTF-8","GBK");

			$source = htmlspecialchars($source);
			$a = explode('col title', $source);

			$result = array();
			foreach ($a as $key => $value)
			{
				if(strstr($value, "summary"))	
				{
					$start = strpos($value, "title") + 12;
					$end = strpos($value, "h3&gt;") - 30;
					$title = substr($value, $start, $end - $start);

					$title = explode('&gt;', $title);
					$title = trim($title[1]);
					$result[] = $title;
				}
			}
			return $result;
			*/
		}


	//自制字典
		$content = file_get_contents("sourceWord.txt");
		$keyWordArray = explode(",", $content);

	//******************************************************************************************************//
	//执行过程
		
		/*regular_shelved(定时上架)
		never_on_shelf(从未上架)
		off_shelf(我下架的)
		for_shelved(等待所有上架)
		sold_out(全部卖完)
		violation_off_shelf(违规下架的)*/
		$banner = "never_on_shelf";

		$num_iid = getOneInventory($appkey, $secret, $sessionKey, $banner);

		if($num_iid != 1)
		{
			echo "<div id='num_iid'>".$num_iid."</div>";

			$productName = getProductInfo($appkey, $secret, $sessionKey, $num_iid);
			echo $productName."<br>";

			$uniqpid = getUniqpid($productName);
			echo $uniqpid."<br>";

			$resultWordArray = array();
			$titleArray = getSamestyleTitle($uniqpid);
			foreach ($titleArray as $key => $value)
			{
				foreach ($keyWordArray as $keyword)
				{
					if(strstr($value, $keyword))
					{
						$resultWordArray[] = $keyword;
					}
				}
			}
			$arrayCountValue = array_count_values($resultWordArray);
			$title = "";
			foreach ($arrayCountValue as $key => $value)
			{
				$title .= $key;
			}
			
			$title = mb_substr($title, 0, 24, 'utf-8');
			if(strlen($title) < 10)
			{
				echo "<div id='title'>2015包邮===</div>";
			}
			else
			{
				echo "<div id='title'>2015包邮".$title."</div>";
			}
		}
		else
		{
			echo "仓库里面的都上完了。";
			exit;
		}

?>

<html>
	<button id='commit' autofocus="autofocus">确定修改</button>
	<div id='result'></div>

	<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
	<script type="text/javascript">

		function refreshPage() { window.location.reload(); } 

		$(document).ready(function(){
			num_iid = $("#num_iid").text();
			title = $("#title").text();
			$.get("commit.php?appkey=<?php echo $appkey;?>&num_iid=" + num_iid + "&title=" + title, function(result){
					$("#result").append(result);
					window.setTimeout("refreshPage()",3000);
			});
		});
	</script>
</html>