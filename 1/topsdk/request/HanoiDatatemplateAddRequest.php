<?php
/**
 * TOP API: taobao.hanoi.datatemplate.add request
 * 
 * @author auto create
 * @since 1.0, 2013-08-18 16:49:39
 */
class HanoiDatatemplateAddRequest
{
	/** 
	 * appName
	 **/
	private $appName;
	
	/** 
	 * name:String类型，数据模板的名称
opened:int类型，标识此数据模板是否对其他人可见
	 **/
	private $parameter;
	
	private $apiParas = array();
	
	public function setAppName($appName)
	{
		$this->appName = $appName;
		$this->apiParas["app_name"] = $appName;
	}

	public function getAppName()
	{
		return $this->appName;
	}

	public function setParameter($parameter)
	{
		$this->parameter = $parameter;
		$this->apiParas["parameter"] = $parameter;
	}

	public function getParameter()
	{
		return $this->parameter;
	}

	public function getApiMethodName()
	{
		return "taobao.hanoi.datatemplate.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->appName,"appName");
		RequestCheckUtil::checkNotNull($this->parameter,"parameter");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}