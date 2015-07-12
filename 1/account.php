<?php

	function getAppSecret($appkey)
	{
		$result = "";
		switch ($appkey) {

			//女装店自动标题	
			case "23140726":
			$result = "8fad3a0cdd451304fc585f5430bce35b";
			break;

			//女装店自动上架
			case "21597684":
			$result = "6f66e71ae985e5df53fa42605a91f166";
			break;

			//男装店自动标题
			case "21808022":
			$result = "c41ff3e8ee562f789a077f94a37abd05";
			break;

			//男装店自动上架
			case "21807992":
			$result = "4a7d2ef7d9c292ee5dd7b44e164ab70f";
			break;
			
			default:
			$result = "undefine";
		}
		return $result;	
	}