<?php

class UserDishModel extends XModel
{
	protected $tableName = "dish";

	public function delDish($cid, $id)
	{
		$info = $this->where(array("cid" => $cid, "id" => $id))->field("id,spec")->find();

		if (empty($info)) {
			return false;
		}

		if (!empty($info["spec"])) {
			$skuModel = D("DishSku");
			$skuModel->where(array("product_id" => $info["id"]))->delete();
		}

		return $this->where(array("id" => $info["id"]))->delete();
	}
}


?>
