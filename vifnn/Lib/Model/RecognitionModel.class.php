<?php
class RecognitionModel extends Model
{
    /**
     * @param $type 扫描时处理类型 可以在配置文件中"REG_SCENE"注册，默认加载ORG/CommonScene.class.php执行[type]方法
     * @param string $type_id 关联的外键id可以为空
     * @param string $data
     * @param int $expire 有效期单位s $expire<=0||$expire>2592000则使用永久二维码
     * @param null $token
     */
    public function createQrcode($type='keyword',$type_id='',$data='',$expire=0,$token=null)
    {
        $apis=C('WEIXIN_API_CONFIG');
        $token=isset($token)?$token:session('token');
        $action_name=($expire<=0||$expire>2592000)?WxAccountModel::QR_LIMIT_STR_SCENE:WxAccountModel::QR_SCENE;
        $expire_seconds=$action_name==WxAccountModel::QR_SCENE?$expire:0;
        $scene['token']=$token;
        $scene['type']=$type;
        $scene['type_id']=$type_id;
        $scene['data']=json_encode($data);
        $scene['action_name']=$action_name;
        $scene['expire_seconds']=$expire_seconds;
        $sha1=sha1(serialize($scene));
        $time=time();
        $result=$this->updateQrcodeBySha1($sha1,$token);
        if(!empty($result))
            return $result;
        $scene['sha1']=$sha1;
        $scene['create_time']=time();
        M()->startTrans();
        $id=$this->add($scene);
        if(!$id)
        {
            M()->rollback();
            return false;
        }
        $scene_str=$action_name==WxAccountModel::QR_SCENE?'':(string)$id;
        $wxAccount=new WxAccountModel();
        $result=$wxAccount->localToWeixin($token)->createQrcode($action_name,$expire_seconds,$id,$scene_str);
        if(!$result)
        {
            $this->error='渠道二维码创建失败！'.$wxAccount->getError();
            M()->rollback();
            return false;
        }
        $sceneUpdate['expiration']=$action_name==WxAccountModel::QR_SCENE?($time+$expire_seconds):0;
        $sceneUpdate['ticket']=$result['ticket'];
        $sceneUpdate['url']=$result['url'];
        $num=$this->where(array('id'=>$id))->save($sceneUpdate);
        if(!$num)
        {
            $this->error='渠道二维码创建失败！';
            M()->rollback();
            return false;
        }
        $tmp['ticket']=$result['ticket'];
        $tmp['url']=$result['url'];
        $tmp['show']=$apis['show_qrcode']['url'].'?ticket='.$result['ticket'];
        $tmp['id']=$id;
        M()->commit();
        return $tmp;
    }

    //查询并更新二维码
    public function updateQrcode($id,$token=null)
    {
        $token=isset($token)?$token:session('token');
        $result=is_array($id)?$id:($this->where(array('id'=>$id))->field('id,ticket,url,expiration,action_name,expire_seconds')->find());
        if(empty($result))
        {
            $this->error='二维码不存在';
            return false;
        }
        $time=time();
        $expire=$result['expire_seconds'];
        $action_name=($expire<=0||$expire>2592000)?WxAccountModel::QR_LIMIT_STR_SCENE:WxAccountModel::QR_SCENE;
        $expire_seconds=$action_name==WxAccountModel::QR_SCENE?$expire:0;
        $scene_str=$action_name==WxAccountModel::QR_SCENE?'':(string)$result['id'];
        $apis=C('WEIXIN_API_CONFIG');
        $tmp['ticket']=$result['ticket'];
        $tmp['url']=$result['url'];
        //已经过期了
        if($result['action_name']==WxAccountModel::QR_SCENE&&$time>=$result['expiration'])
        {
            $wxAccount=new WxAccountModel();
            $res=$wxAccount->localToWeixin($token)->createQrcode($action_name,$expire_seconds,$result['id'],$scene_str);
            if(!$res)
            {
                $this->error='渠道二维码创建失败！'.$wxAccount->getError();
                return false;
            }
            $sceneUpdate['expiration']=$action_name==WxAccountModel::QR_SCENE?($time+$expire_seconds):0;
            $tmp['ticket']=$sceneUpdate['ticket']=$res['ticket'];
            $tmp['url']=$sceneUpdate['url']=$res['url'];
            $num=$this->where(array('id'=>$result['id']))->save($sceneUpdate);
            if(!$num)
            {
                $this->error='更新失败';
                return false;
            }
        }
        $tmp['show']=$apis['show_qrcode']['url'].'?ticket='.$tmp['ticket'];
        $tmp['id']=$result['id'];
        return $tmp;
    }

    //通过ticket查询并更新二维码
    public function updateQrcodeByTicket($ticket,$token=null)
    {
        $token=isset($token)?$token:session('token');
        $result=$this->where(array('ticket'=>$ticket))->field('id,ticket,url,expiration,action_name,expire_seconds')->find();
        return $this->updateQrcode($result?$result:array(),$token);
    }

    //通过数据摘要查询并更新二维码
    public function updateQrcodeBySha1($sha1,$token=null)
    {
        $sha1=is_array($sha1)?sha1(serialize($sha1)):$sha1;
        $token=isset($token)?$token:session('token');
        $result=$this->where(array('sha1'=>$sha1))->field('id,ticket,url,expiration,action_name,expire_seconds')->find();
        return $this->updateQrcode($result?$result:array(),$token);
    }
}