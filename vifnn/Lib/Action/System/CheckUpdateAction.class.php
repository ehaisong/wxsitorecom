<?php
class CheckUpdateAction extends BackAction{
	public function index(){
		$this->redirect('index.php?g=System&m=Update&a=index');
	}
}
?>