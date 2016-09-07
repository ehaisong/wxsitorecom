<?php

class Domain_verify_codeModel extends Model
{
	public function getVerifyCode($topDomain, $serverName)
	{
		$verifyCodeData = $this->where(array("top_domain" => $topDomain, "server_name" => $serverName))->find();
		$verifyCode = (!empty($verifyCodeData) ? $verifyCodeData["verify_code"] : "vifnn");
		return $verifyCode;
	}

	public function setVerifyCode($topDomain, $serverName, $verifyCode)
	{
		$dateUtil = new DateUtil();
		$currentTime = $dateUtil->getCurrentTime();
		$verifyCodeData = $this->where(array("top_domain" => $topDomain, "server_name" => $serverName))->find();

		if (empty($verifyCodeData)) {
			$this->add(array("top_domain" => $topDomain, "server_name" => $serverName, "verify_code" => $verifyCode, "update_time" => $currentTime, "create_time" => $currentTime));
		}
		else {
			$this->where(array("id" => $verifyCodeData["id"]))->save(array("verify_code" => $verifyCode, "update_time" => $currentTime));
		}

		return true;
	}
}


?>
