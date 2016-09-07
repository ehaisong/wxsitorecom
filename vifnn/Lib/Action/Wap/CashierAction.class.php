<?php

class CashierAction extends WapAction
{
	public function __construct()
	{
		parent::_initialize();
		$this->assign("wecha_id", $this->wecha_id);
	}

	public function index()
	{
		if (isset($_POST["username"])) {
			$data = $_POST;

			if ($data["type"] == "merchant") {
				$user = M("cashier_merchants")->where(array("username" => $data["username"]))->find();
			}
			else if ($data["type"] == "employee") {
				$user = M("cashier_employee")->where(array("account" => $data["username"]))->find();
			}

			if (!$user) {
				$this->error("用户名不存在！");
				exit();
			}

			if (md5(md5($data["password"] . "_" . $user["salt"]) . $user["salt"]) != $user["password"]) {
				$this->error("密码错误！", $_SERVER["HTTP_REFERER"]);
				exit();
			}

			if ($user["status"] == 0) {
				$this->error("您的账户正在审核中，不可登录使用！");
				exit();
			}
			else if ($user["status"] == 2) {
				$this->error("您的账户暂时被禁止登录，请联系管理员处理！");
				exit();
			}

			$notiData = array();
			$notiData["eid"] = $user["eid"];
			$notiData["mid"] = $user["mid"];
			$notiData["storeid"] = $user["storeid"];
			$notiData["username"] = $user["username"];
			$notiData["phone"] = $data["phone"];
			$notiData["type"] = 1;
			$notiData["status"] = 1;
			$notiData["addtime"] = time();
			$notiData["openid"] = $this->wecha_id;
			$notiData["wxname"] = $this->fans["wechaname"];
			M("cashier_notification")->add($notiData);

			if ($user["eid"] == 0) {
				$utype = "商家账号";
			}
			else {
				$utype = "员工账号";
			}

			$this->assign("utype", $utype);
			$this->assign("username", $user["username"]);
			$this->assign("isnoti", 1);
		}
		else {
			if (isset($_GET["atype"]) && ($_GET["atype"] == "relieve")) {
				M("cashier_notification")->where(array("openid" => $this->wecha_id))->delete();
				$this->assign("isnoti", 0);
			}
			else {
				$notiInfo = M("cashier_notification")->where(array("openid" => $this->wecha_id))->find();

				if ($notiInfo) {
					$this->assign("isnoti", 1);

					if ($notiInfo["eid"] == 0) {
						$utype = "商家账号";
					}
					else {
						$utype = "员工账号";
					}

					$this->assign("utype", $utype);
					$this->assign("username", $notiInfo["username"]);
				}
				else {
					$this->assign("isnoti", 0);
				}
			}
		}

		$this->assign("token", $this->token);
		$this->display();
	}
}


?>
