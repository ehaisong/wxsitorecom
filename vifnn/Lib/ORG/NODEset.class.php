<?php
class NODEset{
	public function index(){
		$rollback = M('Node')->where(array('title'=>'回滚程序','name'=>'rollback','level'=>3,'status'=>1,'display'=>0))->find();

		//根节点
		$genjiedian = M('Node')->where(array('pid'=>0,'level'=>1,'status'=>1,'display'=>0))->getField('id');
		//站点管理 (Site)
		$genjiedian_Site = M('Node')->where(array('name'=>'Site','pid'=>intval($genjiedian),'level'=>0,'status'=>1,'display'=>1))->getField('id');		
		//站点管理 (Site)->站点设置 (Site)->【添加】微信支付服务商 (weixinPay)(显示)
		$genjiedian_Site_Site = M('Node')->where(array('name'=>'Site','pid'=>intval($genjiedian_Site),'level'=>2,'status'=>1,'display'=>2))->getField('id');
		$genjiedian_Site_Site_weixin_pay = M('Node')->where(array('name'=>'weixinPay','pid'=>intval($genjiedian_Site_Site),'level'=>3,'status'=>1,'display'=>2))->getField('id');
		if($genjiedian_Site_Site_weixin_pay == ''){
			$genjiedian_Site_Site_weixin_pay = M('Node')->add(array('title'=>'微信支付服务商','name'=>'weixinPay','pid'=>intval($genjiedian_Site_Site),'level'=>3,'status'=>1,'display'=>2,'sort'=>20));
		}
		//站点管理 (Site)->站点设置 (Site)->【添加】微信托管 (wechat_api)(显示)
		$genjiedian_Site_Site = M('Node')->where(array('name'=>'Site','pid'=>intval($genjiedian_Site),'level'=>2,'status'=>1,'display'=>2))->getField('id');		
		$genjiedian_Site_Site_wechat_api = M('Node')->where(array('name'=>'wechat_api','pid'=>intval($genjiedian_Site_Site),'level'=>3,'status'=>1,'display'=>2))->getField('id');		
		if($genjiedian_Site_Site_wechat_api == ''){
			$genjiedian_Site_Site_wechat_api = M('Node')->add(array('title'=>'微信托管','name'=>'wechat_api','pid'=>intval($genjiedian_Site_Site),'level'=>3,'status'=>1,'display'=>2,'sort'=>15));
		}
		//添加插件
		$genjiedian_Site_Site_cj = M('Node')->where(array('name'=>'Cj','pid'=>intval($genjiedian_Site),'level'=>2,'status'=>1,'display'=>2))->getField('id');	
		if($genjiedian_Site_Site_cj == ''){
			$genjiedian_Site_Site_cj = M('Node')->add(array('title'=>'增值插件','name'=>'Cj','pid'=>intval($genjiedian_Site),'level'=>2,'status'=>1,'display'=>2,'sort'=>15));
		}
	//	$rollback = M('Node')->where(array('title'=>'回滚程序','name'=>'rollback','level'=>3,'status'=>1,'display'=>0))->find();
	   M('Node')->where(array('title'=>'登录授权','name'=>'wechat_api'))->save(array('title'=>'微信托管'));
       M('Node')->where(array('title'=>'微WIFI','name'=>'rippleos_key'))->save(array('display'=>0));

		if($rollback == ''){//首页(System)
				$genjiedian_Site_System = M('Node')->where(array('name'=>'System','pid'=>intval($genjiedian_Site),'level'=>2,'status'=>1,'display'=>0))->getField('id');
					//站点管理 (Site)->首页(System)->【添加】后台管理 (index)(隐藏)
					$genjiedian_Site_System_index = M('Node')->where(array('name'=>'index','pid'=>intval($genjiedian_Site_System),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Site_System_index == ''){
						$genjiedian_Site_System_index = M('Node')->add(array('title'=>'后台管理','name'=>'index','pid'=>intval($genjiedian_Site_System),'level'=>3,'status'=>1,'display'=>0));
					}
					
				//后台首页(SystemIndex)
				$genjiedian_Site_SystemIndex = M('Node')->where(array('name'=>'SystemIndex','pid'=>intval($genjiedian_Site),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//站点管理 (Site)->后台首页(SystemIndex)->【添加】后台管理 (index)(隐藏)
					$genjiedian_Site_SystemIndex_index = M('Node')->where(array('name'=>'index','pid'=>intval($genjiedian_Site_SystemIndex),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Site_SystemIndex_index == ''){
						$genjiedian_Site_SystemIndex_index = M('Node')->add(array('title'=>'后台管理','name'=>'index','pid'=>intval($genjiedian_Site_SystemIndex),'level'=>3,'status'=>1,'display'=>0));
					}
				//站点设置 (Site)
				$genjiedian_Site_Site = M('Node')->where(array('name'=>'Site','pid'=>intval($genjiedian_Site),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//站点管理 (Site)->站点设置 (Site)->【添加】优化修复数据库 (mysqlajax)(隐藏)
					$genjiedian_Site_Site_mysqlajax = M('Node')->where(array('name'=>'mysqlajax','pid'=>intval($genjiedian_Site_Site),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Site_Site_mysqlajax == ''){
						$genjiedian_Site_Site_mysqlajax = M('Node')->add(array('title'=>'优化修复数据库','name'=>'mysqlajax','pid'=>intval($genjiedian_Site_Site),'level'=>3,'status'=>1,'display'=>0));
					}
			//客户管理 (User)
			$genjiedian_User = M('Node')->where(array('name'=>'User','pid'=>intval($genjiedian),'level'=>0,'status'=>1,'display'=>1))->getField('id');
				//管理组 (Group)
				$genjiedian_User_Group = M('Node')->where(array('name'=>'Group','pid'=>intval($genjiedian_User),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//客户管理 (User)->管理组 (Group)->【删除】更新套餐 (update)
					$genjiedian_User_Group_update_del = M('Node')->where(array('name'=>'update','pid'=>intval($genjiedian_User_Group),'level'=>3,'status'=>1,'display'=>0))->delete();
					//客户管理 (User)->管理组 (Group)->【添加】排序 (role_sort)(隐藏)
					$genjiedian_User_Group_role_sort = M('Node')->where(array('name'=>'role_sort','pid'=>intval($genjiedian_User_Group),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_User_Group_role_sort == ''){
						$genjiedian_User_Group_role_sort = M('Node')->add(array('title'=>'排序','name'=>'role_sort','pid'=>intval($genjiedian_User_Group),'level'=>3,'status'=>1,'display'=>0));
					}
					//客户管理 (User)->管理组 (Group)->【添加】权限浏览 (access)(隐藏)
					$genjiedian_User_Group_access = M('Node')->where(array('name'=>'access','pid'=>intval($genjiedian_User_Group),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_User_Group_access == ''){
						$genjiedian_User_Group_access = M('Node')->add(array('title'=>'权限浏览','name'=>'access','pid'=>intval($genjiedian_User_Group),'level'=>3,'status'=>1,'display'=>0));
					}
					//客户管理 (User)->管理组 (Group)->【添加】权限编辑 (access_edit)(隐藏)
					$genjiedian_User_Group_access_edit = M('Node')->where(array('name'=>'access_edit','pid'=>intval($genjiedian_User_Group),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_User_Group_access_edit == ''){
						$genjiedian_User_Group_access_edit = M('Node')->add(array('title'=>'权限编辑','name'=>'access_edit','pid'=>intval($genjiedian_User_Group),'level'=>3,'status'=>1,'display'=>0));
					}
				//套餐管理 (User_group)
				$genjiedian_User_User_group = M('Node')->where(array('name'=>'User_group','pid'=>intval($genjiedian_User),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//客户管理 (User)->套餐管理 (User_group)->【添加】套餐列表 (index)(菜单显示)
					$genjiedian_User_User_group_index = M('Node')->where(array('name'=>'index','pid'=>intval($genjiedian_User_User_group),'level'=>3,'status'=>1,'display'=>2))->getField('id');
					if($genjiedian_User_User_group_index == ''){
						$genjiedian_User_User_group_index = M('Node')->add(array('title'=>'套餐列表','name'=>'index','pid'=>intval($genjiedian_User_User_group),'level'=>3,'status'=>1,'display'=>2));
					}
					//客户管理 (User)->套餐管理 (User_group)->【添加】修改套餐 (edit)(隐藏)
					$genjiedian_User_User_group_edit = M('Node')->where(array('name'=>'edit','pid'=>intval($genjiedian_User_User_group),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_User_User_group_edit == ''){
						$genjiedian_User_User_group_edit = M('Node')->add(array('title'=>'修改套餐','name'=>'edit','pid'=>intval($genjiedian_User_User_group),'level'=>3,'status'=>1,'display'=>0));
					}
					//客户管理 (User)->套餐管理 (User_group)->【添加】删除套餐 (del)(隐藏)
					$genjiedian_User_User_group_del = M('Node')->where(array('name'=>'del','pid'=>intval($genjiedian_User_User_group),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_User_User_group_del == ''){
						$genjiedian_User_User_group_del = M('Node')->add(array('title'=>'删除套餐','name'=>'del','pid'=>intval($genjiedian_User_User_group),'level'=>3,'status'=>1,'display'=>0));
					}
				//客户管理 (Users)
				$genjiedian_User_Users = M('Node')->where(array('name'=>'Users','pid'=>intval($genjiedian_User),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//客户管理 (Users)->客户管理 (Users)->【添加】客户搜索 (search)(隐藏)
					$genjiedian_User_Users_search = M('Node')->where(array('name'=>'search','pid'=>intval($genjiedian_User_Users),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_User_Users_search == ''){
						$genjiedian_User_Users_search = M('Node')->add(array('title'=>'客户搜索','name'=>'search','pid'=>intval($genjiedian_User_Users),'level'=>3,'status'=>1,'display'=>0));
					}
					//客户管理 (Users)->客户管理 (Users)->【添加】设为系统用户 (syname)(隐藏)
					$genjiedian_User_Users_syname = M('Node')->where(array('name'=>'syname','pid'=>intval($genjiedian_User_Users),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_User_Users_syname == ''){
						$genjiedian_User_Users_syname = M('Node')->add(array('title'=>'设为系统用户','name'=>'syname','pid'=>intval($genjiedian_User_Users),'level'=>3,'status'=>1,'display'=>0));
					}
					//客户管理 (Users)->客户管理 (Users)->【添加】取消系统用户 (sysname)(隐藏)
					$genjiedian_User_Users_sysname = M('Node')->where(array('name'=>'sysname','pid'=>intval($genjiedian_User_Users),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_User_Users_sysname == ''){
						$genjiedian_User_Users_sysname = M('Node')->add(array('title'=>'取消系统用户','name'=>'sysname','pid'=>intval($genjiedian_User_Users),'level'=>3,'status'=>1,'display'=>0));
					}
			//公众号管理 (token)
			$genjiedian_token = M('Node')->where(array('name'=>'token','pid'=>intval($genjiedian),'level'=>2,'status'=>1,'display'=>1))->getField('id');
			//【修改为应用】公众号管理 (token)
			$genjiedian_token_up = M('Node')->where(array('name'=>'token','pid'=>intval($genjiedian),'level'=>2,'status'=>1,'display'=>1))->save(array('level'=>0));
				//公众号管理 (Token)
				$genjiedian_token_Token = M('Node')->where(array('name'=>'Token','pid'=>intval($genjiedian_token),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//公众号管理 (token)-> 公众号管理 (Token)->【添加】公众号列表 (index)(菜单显示)
					$genjiedian_token_Token_index = M('Node')->where(array('name'=>'index','pid'=>intval($genjiedian_token_Token),'level'=>3,'status'=>1,'display'=>2))->getField('id');
					if($genjiedian_token_Token_index == ''){
						$genjiedian_token_Token_index = M('Node')->add(array('title'=>'公众号列表','name'=>'index','pid'=>intval($genjiedian_token_Token),'level'=>3,'status'=>1,'display'=>2));
					}
					//公众号管理 (token)-> 公众号管理 (Token)->【添加】公众号删除 (del)(隐藏)
					$genjiedian_token_Token_del = M('Node')->where(array('name'=>'del','pid'=>intval($genjiedian_token_Token),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_token_Token_del == ''){
						$genjiedian_token_Token_del = M('Node')->add(array('title'=>'公众号删除','name'=>'del','pid'=>intval($genjiedian_token_Token),'level'=>3,'status'=>1,'display'=>0));
					}
			//功能模块(Function)
			$genjiedian_Function = M('Node')->where(array('name'=>'Function','pid'=>intval($genjiedian),'level'=>0,'status'=>1,'display'=>1))->getField('id');
				//关于我们 (Aboutus)
				$genjiedian_Function_Aboutus = M('Node')->where(array('name'=>'Aboutus','pid'=>intval($genjiedian_Function),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//功能模块 (Function)->关于我们 (Aboutus)->【添加】列表 (index)(菜单显示)
					$genjiedian_Function_Aboutus_index = M('Node')->where(array('name'=>'index','pid'=>intval($genjiedian_Function_Aboutus),'level'=>3,'status'=>1,'display'=>2))->getField('id');
					if($genjiedian_Function_Aboutus_index == ''){
						$genjiedian_Function_Aboutus_index = M('Node')->add(array('title'=>'列表','name'=>'index','pid'=>intval($genjiedian_Function_Aboutus),'level'=>3,'status'=>1,'display'=>2));
					}
					//功能模块 (Function)->关于我们 (Aboutus)->【添加】添加 (add)(菜单显示)
					$genjiedian_Function_Aboutus_index = M('Node')->where(array('name'=>'add','pid'=>intval($genjiedian_Function_Aboutus),'level'=>3,'status'=>1,'display'=>2))->getField('id');
					if($genjiedian_Function_Aboutus_index == ''){
						$genjiedian_Function_Aboutus_index = M('Node')->add(array('title'=>'列表','name'=>'add','pid'=>intval($genjiedian_Function_Aboutus),'level'=>3,'status'=>1,'display'=>2));
					}
					//功能模块 (Function)->关于我们 (Aboutus)->【添加】修改 (edit)(隐藏)
					$genjiedian_Function_Aboutus_edit = M('Node')->where(array('name'=>'edit','pid'=>intval($genjiedian_Function_Aboutus),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Aboutus_edit == ''){
						$genjiedian_Function_Aboutus_edit = M('Node')->add(array('title'=>'修改','name'=>'edit','pid'=>intval($genjiedian_Function_Aboutus),'level'=>3,'status'=>1,'display'=>0));
					}
					//功能模块 (Function)->关于我们 (Aboutus)->【添加】删除 (del)(隐藏)
					$genjiedian_Function_Aboutus_del = M('Node')->where(array('name'=>'del','pid'=>intval($genjiedian_Function_Aboutus),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Aboutus_del == ''){
						$genjiedian_Function_Aboutus_del = M('Node')->add(array('title'=>'删除','name'=>'del','pid'=>intval($genjiedian_Function_Aboutus),'level'=>3,'status'=>1,'display'=>0));
					}
				//功能介绍 (Funintro)
				$genjiedian_Function_Funintro = M('Node')->where(array('name'=>'Funintro','pid'=>intval($genjiedian_Function),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//功能模块 (Function)->功能介绍 (Funintro)->【添加】执行添加分类 (adds)(隐藏)
					$genjiedian_Function_Funintro_adds = M('Node')->where(array('name'=>'adds','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Funintro_adds == ''){
						$genjiedian_Function_Funintro_adds = M('Node')->add(array('title'=>'执行添加分类','name'=>'adds','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0));
					}
					//功能模块 (Function)->功能介绍 (Funintro)->【添加】分类修改 (edits)(隐藏)
					$genjiedian_Function_Funintro_edits = M('Node')->where(array('name'=>'edits','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Funintro_edits == ''){
						$genjiedian_Function_Funintro_edits = M('Node')->add(array('title'=>'分类修改','name'=>'edits','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0));
					}
					//功能模块 (Function)->功能介绍 (Funintro)->【添加】分类删除 (dels)(隐藏)
					$genjiedian_Function_Funintro_dels = M('Node')->where(array('name'=>'dels','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Funintro_dels == ''){
						$genjiedian_Function_Funintro_dels = M('Node')->add(array('title'=>'分类删除','name'=>'dels','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0));
					}
					//功能模块 (Function)->功能介绍 (Funintro)->【添加】执行分类修改 (upsaves)(隐藏)
					$genjiedian_Function_Funintro_upsaves = M('Node')->where(array('name'=>'upsaves','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Funintro_upsaves == ''){
						$genjiedian_Function_Funintro_upsaves = M('Node')->add(array('title'=>'执行分类修改','name'=>'upsaves','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0));
					}
					//功能模块 (Function)->功能介绍 (Funintro)->【添加】确认设置分类 (search)(隐藏)
					$genjiedian_Function_Funintro_search = M('Node')->where(array('name'=>'search','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Funintro_search == ''){
						$genjiedian_Function_Funintro_search = M('Node')->add(array('title'=>'确认设置分类','name'=>'search','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0));
					}
					//功能模块 (Function)->功能介绍 (Funintro)->【添加】添加功能 (add)
					$genjiedian_Function_Funintro_add = M('Node')->where(array('name'=>'add','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Funintro_add == ''){
						$genjiedian_Function_Funintro_add = M('Node')->add(array('title'=>'添加','name'=>'add','pid'=>intval($genjiedian_Function_Funintro),'level'=>3,'status'=>1,'display'=>0));
					}
				//功能模块 (Function)
				$genjiedian_Function_Function = M('Node')->where(array('name'=>'Function','pid'=>intval($genjiedian_Function),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//功能模块 (Function)->功能模块 (Function)->【添加】列表 (index)(菜单显示)
					$genjiedian_Function_Function_index = M('Node')->where(array('name'=>'index','pid'=>intval($genjiedian_Function_Function),'level'=>3,'status'=>1,'display'=>2))->getField('id');
					if($genjiedian_Function_Function_index == ''){
						$genjiedian_Function_Function_index = M('Node')->add(array('title'=>'列表','name'=>'index','pid'=>intval($genjiedian_Function_Function),'level'=>3,'status'=>1,'display'=>2));
					}
					//功能模块 (Function)->功能模块 (Function)->【添加】修改 (edit)(隐藏)
					$genjiedian_Function_Function_edit = M('Node')->where(array('name'=>'edit','pid'=>intval($genjiedian_Function_Function),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Function_edit == ''){
						$genjiedian_Function_Function_edit = M('Node')->add(array('title'=>'修改','name'=>'edit','pid'=>intval($genjiedian_Function_Function),'level'=>3,'status'=>1,'display'=>0));
					}
					//功能模块 (Function)->功能模块 (Function)->【添加】删除 (del)(隐藏)
					$genjiedian_Function_Function_del = M('Node')->where(array('name'=>'del','pid'=>intval($genjiedian_Function_Function),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_Function_Function_del == ''){
						$genjiedian_Function_Function_del = M('Node')->add(array('title'=>'删除','name'=>'del','pid'=>intval($genjiedian_Function_Function),'level'=>3,'status'=>1,'display'=>0));
					}
			//扩展管理 (extent)
			$genjiedian_extent = M('Node')->where(array('name'=>'extent','pid'=>intval($genjiedian),'level'=>0,'status'=>1,'display'=>1))->getField('id');
				//平台支付 (Platform)
				$genjiedian_extent_Platform = M('Node')->where(array('name'=>'Platform','pid'=>intval($genjiedian_extent),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//扩展管理 (extent)->平台支付 (Platform)->【添加】处理账单 (paid)(隐藏)
					$genjiedian_extent_Platform_paid = M('Node')->where(array('name'=>'paid','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Platform_paid == ''){
						$genjiedian_extent_Platform_paid = M('Node')->add(array('title'=>'处理账单','name'=>'paid','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0));
					}
					//扩展管理 (extent)->平台支付 (Platform)->【添加】一键处理 (paid_all)(隐藏)
					$genjiedian_extent_Platform_paid_all = M('Node')->where(array('name'=>'paid_all','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Platform_paid_all == ''){
						$genjiedian_extent_Platform_paid_all = M('Node')->add(array('title'=>'一键处理','name'=>'paid_all','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0));
					}
					//扩展管理 (extent)->平台支付 (Platform)->【添加】添加 (add)(隐藏)
					$genjiedian_extent_Platform_add = M('Node')->where(array('name'=>'add','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Platform_add == ''){
						$genjiedian_extent_Platform_add = M('Node')->add(array('title'=>'添加','name'=>'add','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0));
					}
					//扩展管理 (extent)->平台支付 (Platform)->【添加】删除 (del)(隐藏)
					$genjiedian_extent_Platform_del = M('Node')->where(array('name'=>'del','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Platform_del == ''){
						$genjiedian_extent_Platform_del = M('Node')->add(array('title'=>'删除','name'=>'del','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0));
					}
					//扩展管理 (extent)->平台支付 (Platform)->【添加】修改 (edit)(隐藏)
					$genjiedian_extent_Platform_edit = M('Node')->where(array('name'=>'edit','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Platform_edit == ''){
						$genjiedian_extent_Platform_edit = M('Node')->add(array('title'=>'修改','name'=>'edit','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0));
					}
					//扩展管理 (extent)->平台支付 (Platform)->【添加】插入数据库 (insert)(隐藏)
					$genjiedian_extent_Platform_insert = M('Node')->where(array('name'=>'insert','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Platform_insert == ''){
						$genjiedian_extent_Platform_insert = M('Node')->add(array('title'=>'插入数据库','name'=>'insert','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0));
					}
					//扩展管理 (extent)->平台支付 (Platform)->【添加】更新数据库 (upsave)(隐藏)
					$genjiedian_extent_Platform_upsave = M('Node')->where(array('name'=>'upsave','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Platform_upsave == ''){
						$genjiedian_extent_Platform_upsave = M('Node')->add(array('title'=>'更新数据库','name'=>'upsave','pid'=>intval($genjiedian_extent_Platform),'level'=>3,'status'=>1,'display'=>0));
					}
				//客户案例 (Case)
				$genjiedian_extent_Case = M('Node')->where(array('name'=>'Case','pid'=>intval($genjiedian_extent),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//扩展管理 (extent)->客户案例 (Case)->【添加】执行分类添加 (adds)(隐藏)
					$genjiedian_extent_Case_adds = M('Node')->where(array('name'=>'adds','pid'=>intval($genjiedian_extent_Case),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Case_adds == ''){
						$genjiedian_extent_Case_adds = M('Node')->add(array('title'=>'执行分类添加','name'=>'adds','pid'=>intval($genjiedian_extent_Case),'level'=>3,'status'=>1,'display'=>0));
					}
					//扩展管理 (extent)->客户案例 (Case)->【添加】分类修改 (edits)(隐藏)
					$genjiedian_extent_Case_edits = M('Node')->where(array('name'=>'edits','pid'=>intval($genjiedian_extent_Case),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Case_edits == ''){
						$genjiedian_extent_Case_edits = M('Node')->add(array('title'=>'分类修改','name'=>'edits','pid'=>intval($genjiedian_extent_Case),'level'=>3,'status'=>1,'display'=>0));
					}
					//扩展管理 (extent)->客户案例 (Case)->【添加】执行分类修改 (upsaves)(隐藏)
					$genjiedian_extent_Case_upsaves = M('Node')->where(array('name'=>'upsaves','pid'=>intval($genjiedian_extent_Case),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Case_upsaves == ''){
						$genjiedian_extent_Case_upsaves = M('Node')->add(array('title'=>'执行分类修改','name'=>'upsaves','pid'=>intval($genjiedian_extent_Case),'level'=>3,'status'=>1,'display'=>0));
					}
					//扩展管理 (extent)->客户案例 (Case)->【添加】分类删除 (dels)(隐藏)
					$genjiedian_extent_Case_dels = M('Node')->where(array('name'=>'dels','pid'=>intval($genjiedian_extent_Case),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Case_dels == ''){
						$genjiedian_extent_Case_dels = M('Node')->add(array('title'=>'分类删除','name'=>'dels','pid'=>intval($genjiedian_extent_Case),'level'=>3,'status'=>1,'display'=>0));
					}
				//充值记录 (Records)
				$genjiedian_extent_Records = M('Node')->where(array('name'=>'Records','pid'=>intval($genjiedian_extent),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//扩展管理 (extent)->充值记录 (Records)->【添加】确认充值 (send)(隐藏)
					$genjiedian_extent_Records_send = M('Node')->where(array('name'=>'send','pid'=>intval($genjiedian_extent_Records),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					if($genjiedian_extent_Records_send == ''){
						$genjiedian_extent_Records_send = M('Node')->add(array('title'=>'确认充值','name'=>'send','pid'=>intval($genjiedian_extent_Records),'level'=>3,'status'=>1,'display'=>0));
					}
				//新闻管理 (News)
				$genjiedian_extent_News = M('Node')->where(array('name'=>'News','pid'=>intval($genjiedian_extent),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					//扩展管理 (extent)->新闻管理 (News)->【修改】更新数据库 (pusave->upsave)
					$genjiedian_extent_News_upsave_up =  M('Node')->where(array('name'=>'pusave','pid'=>intval($genjiedian_extent_News),'level'=>3,'status'=>1,'display'=>0))->save(array('name'=>'upsave'));
			
			$jiedian_del = M('Node')->where(array('pid'=>0,'level'=>3,'status'=>1,'display'=>0))->delete();
			$jiedian_del = M('Node')->where(array('pid'=>0,'level'=>3,'status'=>1,'display'=>2))->delete();
			$jiedian_del = M('Node')->where(array('pid'=>0,'level'=>0,'status'=>1,'display'=>1))->delete();
			$jiedian_del = M('Node')->where(array('pid'=>0,'level'=>2,'status'=>1,'display'=>2))->delete();
			$jiedian_del = M('Node')->where(array('pid'=>0,'level'=>2,'status'=>1,'display'=>0))->delete();
		}
		$Group_add = M('Node')->where(array('title'=>'创建套餐','name'=>'add','level'=>3,'status'=>1,'display'=>2))->find();
		$Group_index = M('Node')->where(array('title'=>'套餐列表','name'=>'index','level'=>3,'status'=>1,'display'=>2))->find();
		if($Group_add != ''){
			$Group_add = M('Node')->where(array('title'=>'创建套餐','name'=>'add','level'=>3,'status'=>1,'display'=>2))->save(array('title'=>'创建管理组'));
		}
		if($Group_index != ''){
			$Group_index = M('Node')->where(array('title'=>'套餐列表','name'=>'index','level'=>3,'status'=>1,'display'=>2))->save(array('title'=>'管理组列表'));
		}
		$Site_insert = M('Node')->where(array('title'=>'保存测试','name'=>'insert','level'=>3,'status'=>1,'display'=>0))->find();
		if($Site_insert != ''){
			$Site_insert = M('Node')->where(array('title'=>'保存测试','name'=>'insert','level'=>3,'status'=>1,'display'=>0))->save(array('title'=>'保存'));
		}
		$genjiedian = M('Node')->where(array('pid'=>0,'level'=>1,'status'=>1,'display'=>0))->getField('id');
		$genjiedian_extent = M('Node')->where(array('name'=>'extent','pid'=>intval($genjiedian),'level'=>0,'status'=>1,'display'=>1))->getField('id');
		$Examine_image = M('Node')->where(array('pid'=>intval($genjiedian_extent),'name'=>'Examine_image','level'=>2,'status'=>1,'display'=>2))->find();
		if($Examine_image == ''){
			
			$Examine_image = M('Node')->add(array('title'=>'图片审核','pid'=>intval($genjiedian_extent),'name'=>'Examine_image','level'=>2,'status'=>1,'display'=>2));
			$Examine_image_index = M('Node')->add(array('title'=>'图片列表','pid'=>intval($Examine_image),'name'=>'index','level'=>3,'status'=>1,'display'=>2));
			$Examine_image_del = M('Node')->add(array('title'=>'删除','pid'=>intval($Examine_image),'name'=>'del','level'=>3,'status'=>1,'display'=>0));
			$Examine_image_set = M('Node')->add(array('title'=>'审核','pid'=>intval($Examine_image),'name'=>'set','level'=>3,'status'=>1,'display'=>0));
			$Examine_image_set_all = M('Node')->add(array('title'=>'一键审核','pid'=>intval($Examine_image),'name'=>'set_all','level'=>3,'status'=>1,'display'=>0));
		}
		
		$Examine_image_id = $Examine_image['id']?$Examine_image['id']:$Examine_image;
		$Examine_image_info = M('Node')->where(array('pid'=>intval($Examine_image_id),'name'=>'info','level'=>3,'status'=>1,'display'=>0))->find();
		if($Examine_image_info == ''){
			$Examine_image_info = M('Node')->add(array('title'=>'查看详情','pid'=>intval($Examine_image_id),'name'=>'info','level'=>3,'status'=>1,'display'=>0));
		}
		$Susceptible = M('Node')->where(array('pid'=>intval($genjiedian_extent),'name'=>'Susceptible','level'=>2,'status'=>1,'display'=>2))->find();
		if($Susceptible == ''){
			$genjiedian = M('Node')->where(array('pid'=>0,'level'=>1,'status'=>1,'display'=>0))->getField('id');
			$genjiedian_extent = M('Node')->where(array('name'=>'extent','pid'=>intval($genjiedian),'level'=>0,'status'=>1,'display'=>1))->getField('id');
			$Susceptible = M('Node')->add(array('title'=>'敏感词过滤','pid'=>intval($genjiedian_extent),'name'=>'Susceptible','level'=>2,'status'=>1,'display'=>2));
			$Susceptible_index = M('Node')->add(array('title'=>'列表','pid'=>intval($Susceptible),'name'=>'index','level'=>3,'status'=>1,'display'=>2));
			$Susceptible_add = M('Node')->add(array('title'=>'添加','pid'=>intval($Susceptible),'name'=>'add','level'=>3,'status'=>1,'display'=>2));
			$Susceptible_adds = M('Node')->add(array('title'=>'批量添加','pid'=>intval($Susceptible),'name'=>'adds','level'=>3,'status'=>1,'display'=>2));
			$Susceptible_del = M('Node')->add(array('title'=>'删除','pid'=>intval($Susceptible),'name'=>'del','level'=>3,'status'=>1,'display'=>0));
			$Susceptible_set = M('Node')->add(array('title'=>'开启关闭','pid'=>intval($Susceptible),'name'=>'set','level'=>3,'status'=>1,'display'=>0));
			$Susceptible_set_all = M('Node')->add(array('title'=>'一键开启关闭','pid'=>intval($Susceptible),'name'=>'set_all','level'=>3,'status'=>1,'display'=>0));
			$Susceptible_edit = M('Node')->add(array('title'=>'修改','pid'=>intval($Susceptible),'name'=>'edit','level'=>3,'status'=>1,'display'=>0));
		}
		$genjiedian_Site = M('Node')->where(array('name'=>'Site','pid'=>intval($genjiedian),'level'=>0,'status'=>1,'display'=>1))->getField('id');
		
        $extNode=M('Node')->where(array('title'=>'扩展管理'))->find();
        $useNode=M('Node')->where(array('title'=>'数据统计'))->find();
		if (!$useNode){
			$platFormID=M('Node')->add(array('name'=>'Use','title'=>'数据统计','status'=>1,'remark'=>'','pid'=>$extNode['id'],'level'=>2,'sort'=>0,'display'=>2));
	        M('Node')->add(array('name'=>'index','title'=>'统计图表','status'=>1,'remark'=>'','pid'=>$platFormID,'level'=>3,'sort'=>0,'display'=>2));
		}
		
		//自定义导航
		/* $cusNode=M('Node')->where(array('title'=>'自定义导航'))->find();
		if($cusNode == ''){
			$cusFormID=M('Node')->add(array('name'=>'Customs','title'=>'自定义导航','status'=>1,'remark'=>'','pid'=>$extNode['id'],'level'=>2,'sort'=>0,'display'=>2));
			M('Node')->add(array('name'=>'index','title'=>'导航列表','status'=>1,'remark'=>'','pid'=>$cusFormID,'level'=>3,'sort'=>0,'display'=>2));
		}*/


		 //选择理由
		/* $res=M('Node')->where(array('name'=>'Reason'))->find();
		 if(!$res){
		 	$genjiedian_extent = M('Node')->where(array('name'=>'extent','pid'=>intval($genjiedian),'level'=>0,'status'=>1,'display'=>1))->getField('id');
		 	$lastid=M('Node')->add(array('name'=>'Reason','title'=>'选择理由','status'=>1,'remark'=>'','pid'=>$genjiedian_extent,'level'=>2,'sort'=>0,'display'=>2));
		 	M('Node')->add(array('name'=>'add','title'=>'添加','status'=>1,'remark'=>'0','pid'=>$lastid,'level'=>3,'sort'=>0,'display'=>2));
		 	M('Node')->add(array('name'=>'index','title'=>'文章列表','status'=>1,'remark'=>'0','pid'=>$lastid,'level'=>3,'sort'=>0,'display'=>2));
		 }*/
		 //扩展管理设置
	/* 	 $extendset=M('Node')->where(array('name'=>'Extendset'))->find();
		 if(!$extendset){
		 	$genjiedian_extent = M('Node')->where(array('name'=>'extent','pid'=>intval($genjiedian),'level'=>0,'status'=>1,'display'=>1))->getField('id');
		 	$lastid=M('Node')->add(array('name'=>'Extendset','title'=>'模板选择','status'=>1,'remark'=>'','pid'=>$genjiedian_extent,'level'=>2,'sort'=>0,'display'=>2));
		 }
		*/ 
		 
	}
}
?>