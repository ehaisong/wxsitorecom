<?php
class PlatformAction extends UserAction{
	public $pay_config_db;
	public function _initialize(){
		parent::_initialize();
		$this->pay_config_db = M("Alipay_config");

		if (!$this->token) {
			exit();
		}
	}
	public function index(){
		$database_platform_pay = D("Platform_pay");
		$condition_platform_pay["token"] = $this->token;
		$count = $database_platform_pay->where($condition_platform_pay)->count();
		$page = new Page($count, 25);
		$platform_list = $database_platform_pay->where($condition_platform_pay)->order("`time` DESC")->limit($page->firstRow . "," . $page->listRows)->select();

		if (!empty($platform_list)) {
			foreach ($platform_list as $key => $value ) {
				$from = strtolower($value["from"]);

				switch ($from) {
				case $from:
					$platform_list[$key]["from"] = "餐饮";
					break;

				case $from:
					$platform_list[$key]["from"] = "商城";
					break;

				case $from:
					$platform_list[$key]["from"] = "酒店";
					break;

				case $from:
					$platform_list[$key]["from"] = "商业";
					break;

				case $from:
					$platform_list[$key]["from"] = "团购";
					break;

				case $from:
					$platform_list[$key]["from"] = "外卖";
					break;

				case $from:
					$platform_list[$key]["from"] = "行业应用";
					break;

				case $from:
					$platform_list[$key]["from"] = "会员卡充值";
					break;

				case $from:
					$platform_list[$key]["from"] = "微医疗";
					break;

				case $from:
					$platform_list[$key]["from"] = "一元购";
					break;

				case $from:
					$platform_list[$key]["from"] = "生活圈";
					break;

				case $from:
					$platform_list[$key]["from"] = "砍价";
					break;

				case $from:
					$platform_list[$key]["from"] = "众筹";
					break;

				case $from:
					$platform_list[$key]["from"] = "秒杀";
					break;

				case $from:
					$platform_list[$key]["from"] = "微店";
					break;

				case $from:
					$platform_list[$key]["from"] = "商城分销";
					break;

				case $from:
					$platform_list[$key]["from"] = "降价拍";
					break;
				}

				$platform_count["all"] += $value["price"];

				if ($value["paid"]) {
					$platform_count["ok"] += $value["price"];
				}else {
					$platform_count["none"] += $value["price"];
				}
			}
		}
		$this->assign("page", $page->show());
		$this->assign("platform_list", $platform_list);
		$this->assign("platform_count", $platform_count);
		$this->display();
	}
}


?>
