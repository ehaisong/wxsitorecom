<?php
class BlacklistModel extends XModel
{
    private $token;
    private $wechaId;
    private $siteUrl;

    public function checkList($wechaId,$token=null,$ip=null,$groupName=null,$moduleName=null,$actionName=null,$siteUrl=null)
    {
        $this->wechaId=$wechaId;
        $this->token=isset($token)?$token:session('token');
        $this->siteUrl=isset($siteUrl)?$siteUrl:C('site_url');
        $ip=isset($ip)?$ip:get_client_ip();
        $groupName=isset($groupName)?$groupName:GROUP_NAME;
        $moduleName=isset($moduleName)?$moduleName:MODULE_NAME;
        $actionName=isset($actionName)?$actionName:ACTION_NAME;
        $blacklist=S('blacklist_'.$token);
        if(empty($blacklist))
        {
            $blacklist=$this->where(array('status'=>'1','token'=>$token))->field('id,ips,url,post,limit_start,limit_end')->select();
            S('blacklist_'.$token,$blacklist);
        }
        foreach ($blacklist as $rule)
        {
            $ips=$this->getIps($rule['ips']);
            $time=time();
            if(in_array($ip,$ips))
            {
                //开始时间等于结束时间视为永久有效
                $condition=$rule['limit_start']!=$rule['limit_end']&&($time>=$rule['limit_start']&&$time<$rule['limit_end']);
                if($condition||$rule['limit_start']==$rule['limit_end'])
                {
                    if($this->matchUrl($rule['url'],$rule['post'],IS_POST,$groupName,$moduleName,$actionName))
                    {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    //匹配URL和参数
    private function matchUrl($url,$postParam,$isPost,$groupName,$moduleName,$actionName,$matchCase=true)
    {
        if($url=='*')
            return true;
        //不区分大小写
        if(!$matchCase)
        {
            $groupName=strtolower($groupName);
            $moduleName=strtolower($moduleName);
            $actionName=strtolower($actionName);
        }
        if(is_string($postParam))
        {
            $postParam=preg_replace('/\r\n/',',',$postParam);
            $postParam=preg_replace('/\&{2,}/',',',$postParam);
            $postParamArr=array();
            parse_str($postParam,$postParamArr);
            $postParam=$postParamArr;
        }
        $url=htmlspecialchars_decode($url);
        $url=str_replace(array('{siteUrl}','{wechat_id}','{token}'),array($this->siteUrl,$this->wechaId,$this->token),$url);
        $info=parse_url($url);
        parse_str($info['query'],$getParam);
        $g=$matchCase?$getParam['g']:strtolower($getParam['g']);
        $m=$matchCase?$getParam['m']:strtolower($getParam['m']);
        $a=$matchCase?$getParam['a']:strtolower($getParam['a']);
        unset($getParam['g']);
        unset($getParam['m']);
        unset($getParam['a']);
        unset($getParam['wecha_id']);
        if(!empty($g))
        {
            if($g=='*'||$g==$groupName)
            {
                if(!empty($m))
                {
                    if($m=='*'||$m==$moduleName)
                    {
                        if(!empty($a))
                        {
                            if($a=='*'||$a==$actionName)
                            {
                                if(!empty($getParam))
                                {
                                    if($this->matchParam($getParam, $_GET))
                                    {
                                        if($isPost&&!empty($postParam))
                                        {
                                            if(!$this->matchParam($postParam, $_POST))
                                            {
                                                return false;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        return false;
                                    }
                                }
                            }
                            else
                            {
                                return false;
                            }
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                return false;
            }
        }
        return true;
    }

    //匹配参数
    private function matchParam($param,$arr)
    {
        $match=true;
        foreach ($param as $key=>$value)
        {
            if(!isset($arr[$key])||$arr[$key]!=$value)
            {
                $match=false;
                break;
            }
        }
        return $match;
    }

    public function getIps($data)
    {
        $data=preg_replace('/\r\n/',',',$data);
        $data=preg_replace('/\,{2,}/',',',$data);
        $data=preg_replace('/\s/','',$data);
        $arr=explode(',',$data);
        $list=array();
        for($i=0;$i<count($arr);$i++)
        {
            if(!empty($arr[$i]))
            {
                if(strpos($arr[$i],'-')===false)
                {
                    $list[]=$arr[$i];
                }
                else
                {
                    list($start,$end)=explode('-',$arr[$i]);
                    preg_match('/\.(\d{0,3})$/',$start,$startMatch);
                    preg_match('/\.(\d{0,3})$/',$end,$endMatch);
                    for($j=(int)$startMatch[1];$j<(int)$endMatch[1]+1;$j++)
                    {
                        $list[]=preg_replace('/\.(\d{0,3})$/','.'.$j,$start);
                    }
                }
            }
        }
        return $list;
    }


}