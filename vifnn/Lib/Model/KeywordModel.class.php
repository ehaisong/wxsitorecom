<?php
class KeywordModel
{
    protected $fun;
    protected $token;
    protected $eventData;//微信推送的事件数据
    protected $siteUrl;

    public function __construct($token,$eventData,$fun,$sitUrl)
    {
        $this->token=$token;
        $this->eventData=$eventData;
        $this->fun=$fun;
        $this->sitUrl=$sitUrl;
    }

    //处理关键词
    /*
     * 应该将WeixinAction里面的keyword独立成模型方法
     * */
    public function handler($keyword)
    {
        $apiRes=$this->getApi();
        if(!empty($apiRes))
        {
            if($apiRes[1]=='xml')
            {
                header("Content-type: text/xml");
                echo $apiRes[0];
                exit();
            }
            return $apiRes;
        }
        return A('Home/Weixin')->api('keyword',$keyword,$this->token,$this->eventData,$this->siteUrl);
    }

    //第三方处理结果
    protected function getApi()
    {
        if(!(strpos($this->fun,'api') === FALSE))
        {
            $apiData=M('Api')->where(array('token'=>$this->token,'status'=>1,'noanswer'=>0))->select();
            $excecuteNoKeywordReply=0;
            if ($apiData)
            {
                foreach($apiData as $apiArray)
                {
                    if (!$apiArray['keyword'])
                    {
                        $excecuteNoKeywordReply=1;
                        break;
                    }
                }
            }
            if ($excecuteNoKeywordReply)
            {
                $nokeywordReply=$this->nokeywordApi(0,$apiData);
                if ($nokeywordReply)
                    return $nokeywordReply;
            }
            if ($this->eventData['Content']&&$apiData)
            {
                foreach($apiData as $apiArray)
                {
                    if(!(strpos($this->eventData['Content'],$apiArray['keyword']) === FALSE))
                    {
                        $api=$apiArray;
                        break;
                    }
                }
                if($api!=false)
                {
                    $vo['fromUsername']=$this->eventData['FromUserName'];
                    $vo['Content']=$this->eventData['Content'];
                    $vo['toUsername']=$this->token;
                    $api['url']=$this->getApiUrl($api['url'],$api['apitoken']);
                    if($api['type']==2)
                    {
                        if (intval($api['is_colation']))
                            $vo['Content']=trim(str_replace($api['keyword'],'',$vo['Content']));
                        $apiRes=$this->curlApi($api['url'],$vo,0,0);
                        return array($apiRes,'text');
                    }
                    else
                    {
                        //$xml = file_get_contents("php://input");
                        $xml=$GLOBALS["HTTP_RAW_POST_DATA"];
                        if (intval($api['is_colation']))
                            $xml=str_replace(array($api['keyword'],$api['keyword'].' '),'',$xml);
                        $xml=$this->handleApiXml($xml);
                        $apiRes=$this->curlApi($api['url'],$xml,0);
                        return array($apiRes,'xml');
                    }
                }
            }
        }
    }

    //没有关键词时处理结果
    protected function nokeywordApi($noanswer=1,$apiData=0)
    {
        if(!(strpos($this->fun,'api') === FALSE))
        {
            $apiwhere=array('token'=>$this->token,'status'=>1,'noanwser'=>$noanswer);
            $apiwhere['noanswer']=$noanswer?array('gt',0):0;
            $api=$apiData?$apiData[0]:M('Api')->where($apiwhere)->find();
            if($api!=false)
            {
                $vo['fromUsername']=$this->eventData['FromUserName'];
                $vo['Content']=$this->eventData['Content'];
                if (intval($api['is_colation']))
                {
                    $vo['Content']=trim(str_replace($api['keyword'],'',$this->eventData['Content']));
                }
                $vo['toUsername']=$this->token;
                $api['url']=$this->getApiUrl($api['url'],$api['apitoken']);
                if($api['type']==2)
                {
                    $apiRes=$this->curlApi($api['url'],$vo,0,0);
                    if ($apiRes!==false)
                        return array($apiRes,'text');
                }
                else
                {
                    $xml = file_get_contents("php://input");
                    if (intval($api['is_colation']))
                    {
                        $xml=str_replace(array($api['keyword'],$api['keyword'].' '),'',$xml);
                    }
                    $xml=$this->handleApiXml($xml);
                    $apiRes=$this->curlApi($api['url'],$xml,0);
                    if ($apiRes!==false)
                        return array($apiRes,'xml');
                }
            }
        }
    }

    //获取Api完整URL地址
    private function getApiUrl($url,$token)
    {
        $timestamp = time();
        $nonce = $_GET["nonce"];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $signature = sha1( $tmpStr );
        $url=str_replace('&amp;','&',$url);
        $param='fromthirdapi=1&signature='.$signature.'&timestamp='.$timestamp.'&nonce='.$nonce.'&apitoken='.$this->token;
        $url=strpos($url,'?')?$url.'&'.$param:($url.'?'.$param);
        return $url;
    }

    //请求第三方Api
    private function curlApi($url, $data,$converturl=1,$xmlmode=1)
    {
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        if ($converturl)
            $url.=strpos($url,'?')?('&token='.$this->token):('?token='.$this->token);
        if ($xmlmode)
        {
            $headers = array(
                "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1",
                "Accept-Language: en-us,en;q=0.5",
                "Referer:http://mp.weixin.qq.com/",
                'Content-type: text/xml'
            );
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        $err=curl_errno($ch);
        curl_close($ch);
        return $err?false:$tmpInfo;
    }

    //处理XML
    private function handleApiXml($xml)
    {
        if (strpos($xml,'<Event><![CDATA[CLICK]]></Event>'))
        {
            $xml=str_replace('<Event><![CDATA[CLICK]]></Event>','',$xml);
            $xml=str_replace('<MsgType><![CDATA[event]]></MsgType>','<MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$this->eventData['Content'].']]></Content>',$xml);
        }
        return $xml;
    }
}