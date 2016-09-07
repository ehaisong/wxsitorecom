<?php
class XModel extends Model
{
    const FIELD_BOTH='both';//字段可有可无
    const FIELD_NOT_EXIST='not_exist';//字段不存在
    const FIELD_EXIST='exist';//字段存在
    const FIELD_NOT_HAS='not_has';
    const VALUE_EMPTY='empty';//字段存在且值为空
    const VALUE_NOT_EMPTY='not_empty';//字段存在且值不为空
    const ACTION_INSERT='add';//新增操作
    const ACTION_UPDATE='update';//更新操作
    const ACTION_ALL='all';//所有操作
    protected $actName;//操作名称
    protected $actDesc;//操作描述
    protected $_desc=array();//字段描述
    protected $_rules=array();//处理规则 array(关联字段,回调函数,回调参数,处理函数,处理参数,字段条件,操作条件)
    protected $_step=array('allow','deny','must','validate','ignore','assign');//默认处理步骤
    protected $denyInfo='非法输入{$desc}';//非法字段提示
    protected $mustInfo='必须输入{$desc}';//必须字段提示
    protected $errorCode;//错误码
    protected $relationMap=array();

    //完整处理
    public function processing($formData=null,$actRules=null)
    {
        return $this->processingBase($formData,$actRules,true);
    }

    //单独处理
    public function processingAlone($formData=null,$actRules=null)
    {
        return $this->processingBase($formData,$actRules,false);
    }

    //基础处理
    private function processingBase($formData=null,$actRules=null,$includeAll)
    {
        $formData=isset($formData)?$formData:(IS_GET?I('get.'):I('post.'));
        $formData=$this->processingMap($formData);
        //字符串则查询预定义的规则
        $actRules=isset($actRules)?$actRules:$this->getActionName($formData);
        $proRules=$this->getRules($actRules);
        if(!empty($proRules)) {
            for($i=0;$i<count($this->_step);$i++)
            {
                //验证
                $formData=$this->processingRules($formData,$this->groupByHandler($proRules,$this->_step[$i]),$includeAll);
                if($formData===false)
                    return false;
            }
        }
        //填充
        $this->data($this->getExistPart($formData,false));
        return true;
    }

    //允许类型处理
    public function proAllow($actRules,$formData=null)
    {
        return $this->proBase('allow',$actRules,$formData);
    }

    //禁止类型处理
    public function proDeny($actRules,$formData=null)
    {
        return $this->proBase('deny',$actRules,$formData);
    }

    //验证类型处理
    public function proValidate($actRules,$formData=null)
    {
        return $this->proBase('validate',$actRules,$formData);
    }

    //忽略类型处理
    public function proIgnore($actRules,$formData=null)
    {
        return $this->proBase('ignore',$actRules,$formData);
    }

    //赋值类型处理
    public function proAssign($actRules,$formData=null)
    {
        return $this->proBase('assign',$actRules,$formData);
    }

    //基础单独类型处理
    public function proBase($type,$actRules,$formData=null)
    {
        $formData=isset($formData)?$formData:(IS_GET?I('get.'):I('post.'));
        $formData=$this->processingMap($formData);
        $proRules=call_user_func_array(array($this,'get'.ucfirst($type).'Rules'),array($actRules));
        $formData=$this->processingRules($formData,$proRules,false);//验证
        if($formData===false)
            return false;
        //填充
        $this->data($this->getExistPart($formData,false));
        return true;
    }

    //表单字段映射,real=true 假转真 false 真转假
    public function processingMap($formData,$real=true)
    {
        $newForm=array();
        foreach($formData as $key=>$value) {
            $newKey=$this->getMapField($key,$real);
            $newForm[empty($newKey)?$key:$newKey]=$value;
        }
        return $newForm;
    }

    //获取字段描述
    public function getFieldDesc($field)
    {
        $realName=$this->getMapField($field);
        $field=empty($realName)?$field:$realName;
        $tmp=$this->_desc[$field];
        $tmp=isset($tmp)?(is_array($tmp)?$tmp:explode(',',$tmp)):array();
        return isset($tmp[0])?$tmp[0]:'';
    }

    //获取映射的字段名 real=true 假转真 false 真转假
    public function getMapField($field,$real=true)
    {
        if($real) {
            if(isset($this->_map)&&isset($this->_map[$field]))
                return $this->_map[$field];
            if(isset($this->_desc)) {
                foreach($this->_desc as $key=>$value)
                {
                    $value=is_string($value)?explode(',',$value):$value;
                    if(isset($value[1])&&$value[1]==$field)
                        return $key;
                }
            }
        } else {
            if(isset($this->_map)) {
                foreach($this->_map as $key=>$value)
                {
                    if($value==$field)
                        return $key;
                }
            }
            if(isset($this->_desc)&&isset($this->_desc[$field])) {
                $arr=is_string($this->_desc[$field])?explode(',',$this->_desc[$field]):$this->_desc[$field];
                if(isset($arr[1]))
                    return $arr[1];
            }
        }
        return '';
    }

    //获取表单的操作名称
    public function getActionName($formData)
    {
        $pks=$this->getActionPks($formData);
        return empty($pks)?self::ACTION_INSERT:self::ACTION_UPDATE;
    }

    //获取表单中包含的主键值
    public function getActionPks($formData)
    {
        $arr=array();
        $pks=is_string($this->pk)?array($this->pk):$this->pk;//主要考虑多个主键
        for($i=0;$i<count($pks);$i++)
        {
            if(isset($formData[$pks[$i]])&&!empty($formData[$pks[$i]]))
                $arr[$pks[$i]]=$formData[$pks[$i]];
        }
        return $arr;
    }

    //获取相应的操作规则数组
    protected function getRules($actRules,$includeAll=true)
    {
        $this->actName=is_string($actRules)?$actRules:'';
        $actRules=is_array($actRules)?$actRules:(isset($this->_rules[$actRules])?$this->_rules[$actRules]:array());
        //读取描述
        if(!empty($actRules))
        {
            if(empty($this->actName)&&isset($actRules['name']))
                $this->actName=$actRules['name'];
            $tmpName=$this->actName;
            $this->actDesc=isset($actRules['desc'])?$actRules['desc']:($tmpName==self::ACTION_INSERT?"新增":($tmpName==self::ACTION_UPDATE?'更新':'全部'));
        }
        $arr=array();
        foreach($this->_rules as $key=>$value)
        {
            $tmp=array($value);
            if(is_string($key)) {
                if(!$this->getRulesCondition($key,$includeAll))
                    continue;
                $tmp=$this->getActionRules($value);
            }
            for($i=0;$i<count($tmp);$i++)
            {
                $tmp[$i][5]=isset($tmp[$i][5])?$tmp[$i][5]:self::FIELD_BOTH;//默认字段可有可无
                $tmp[$i][6]=isset($tmp[$i][6])?$tmp[$i][6]:self::ACTION_ALL;//默认所有操作
                if($this->getRulesCondition($tmp[$i][6],$includeAll))
                    $arr[]=$tmp[$i];
            }
        }
        return $arr;
    }

    //规则名是否满足条件
    private function getRulesCondition($key,$includeAll)
    {
        if($this->actName==$key||($key==self::ACTION_ALL&&$includeAll))return true;
        if(strpos($key,'^')===0) {
            if(!in_array($this->actName,explode(',',substr($key,1))))return true;
        } elseif(strpos($key,',')!==false) {
            if(in_array($this->actName,explode(',',$key)))return true;
        }
        return false;
    }

    //获取操作相关的规则
    protected function getActionRules($actRules)
    {
        $arr=array();
        foreach($actRules as $key=>$value)
        {
            $tmp=is_string($key)?call_user_func_array(array($this,'get'.ucfirst($key).'Rules'),array($value)):array($value);
            for($i=0;$i<count($tmp);$i++)
            {
                $tmp[$i][6]=$this->actName;
                $arr[]=$tmp[$i];
            }
        }
        return $arr;
    }

    //处理规则
    public function processingRules($formData,$proRules,$includeAll=true)
    {
        for($i=0;$i<count($proRules);$i++)
        {
            //字段 处理函数 处理参数 结果处理函数 结果处理参数 处理条件 处理操作
            list($field,$callback,$callParam,$handler,$handlerParam,$condition,$action)=$proRules[$i];
            //执行对应操作
            if($this->getRulesCondition($action,$includeAll)) {
                $field=$field=='*'?array_keys($formData):$field;//*表示所有已存在的字段
                $field=is_array($field)?$field:(strpos($field,',')!==false?explode(',',$field):$field);
                $value=$this->getFieldsVal($formData,$field);
                //满足条件则执行
                if($this->getConditionResult($formData,$field,$condition)) {
                    $result=null;
                    if(!empty($callback)) {
                        $callback=$this->getFunction($callback);
                        $params=array($value);
                        //主要考虑兼容系统函数
                        if(in_array($callback[0],array('c','u')))
                            array_push($params,$field,$formData);
                        if(isset($callParam))
                            $params[]=$callParam;
                        $result=call_user_func_array($callback[1],$params);
                    }
                    if(!empty($handler)) {
                        $handler=$this->getFunction($handler,'c','handler');
                        $params=array($result,$field,$value,$formData);
                        if(isset($handlerParam))$params[]=$handlerParam;
                        $hanRes=call_user_func_array($handler[1],$params);
                    }
                    if($hanRes===false||is_null($hanRes))
                        return false;
                    $formData=$hanRes;
                }
            }
        }
        return $formData;
    }

    //获取单个字段值或多个字段值
    private function getFieldsVal($formData,$field)
    {
        $value=is_array($field)?array():$formData[$field];
        //多个字段则值为对应的关联数组
        if(is_array($field)) {
            for($j=0;$j<count($field);$j++)
            {
                if(array_key_exists($field[$j],$formData))
                    $value[$field[$j]]=$formData[$field[$j]];
            }
        }
        return $value;
    }

    //获取条件判断结果
    private function getConditionResult($formData,$field,$condition)
    {
        if($condition==self::FIELD_BOTH)return true;
        $exist=is_array($field)?true:array_key_exists($field,$formData);//存在
        $empty=is_array($field)?true:empty($formData[$field]);//为空
        if(is_array($field)) {
            for($i=0;$i<count($field);$i++)
            {
                if(!array_key_exists($field[$i],$formData))
                    $exist=false;
                if(!empty($formData[$field[$i]]))
                    $empty=false;
            }
        }
        $result=($condition==self::FIELD_EXIST&&$exist)|| ($condition==self::FIELD_NOT_EXIST&&!$exist)||
            ($condition==self::VALUE_EMPTY&&$exist&&$empty)||($condition==self::VALUE_NOT_EMPTY&&!$empty)||($condition==self::FIELD_NOT_HAS&&(!$exist||$empty));
        return $result;
    }

    //获取函数名
    private function getFunction($name,$default='c',$suffix='callback')
    {
        $type=$default;//f 系统函数 c 回调方法 u 用户函数
        $fun=$name;
        //识别类型 [类型]:[函数]
        if(is_string($name)&&strpos($name,':')!==false)
            list($type,$fun)=explode(':',$name);
        //class,test或,test
        if(is_string($fun)&&strpos($fun,',')!==false) {
            $type='c';
            $fun=explode(',',$fun);
        }
        //array('test')或array($this,'test')
        if(is_array($fun)) {
            $type='c';
            if(count($fun)==1)array_unshift($fun,$this);
            if(empty($fun[0]))$fun[0]=$this;
        }
        if($type=='c'&&is_string($fun)) {
            //调用当前类优先使用符合规则的方法
            $funArr=explode('_',$fun);
            $str='';
            for($i=0;$i<count($funArr);$i++)
            {
                $str.=($i==0)?strtolower($funArr[$i]):ucfirst($funArr[$i]);
            }
            $fun=method_exists($this,$str.ucfirst($suffix))?array($this,$str.ucfirst($suffix)):(method_exists($this,$str)?array($this,$str):array($this,$fun));
        }
        if($type=='c'&&!method_exists($fun[0],$fun[1]))
            E($fun[1].' undefined');
        if(($type=='f'||$type=='u')&&!function_exists($fun))
            E($fun.' undefined');
        return array($type,$fun);
    }

    //按照处理方式分组
    private function groupByHandler($proRules,$handler)
    {
        $group=array();
        for($i=0;$i<count($proRules);$i++)
        {
            if($proRules[$i][3]==$handler)
                $group[]=$proRules[$i];
        }
        return $group;
    }

    //获取数据表字段存在部分
    public function getExistPart($formData,$map=true)
    {
        $formData=$map?$this->processingMap($formData):$formData;//字段映射
        $data=array();
        $fields=$this->getDbFields();
        for($i=0;$i<count($fields);$i++)
        {
            if(array_key_exists($fields[$i],$formData))
                $data[$fields[$i]]=$formData[$fields[$i]];
        }
        return $data;
    }

    //设置映射
    public function setMap($map)
    {
        $this->_map=array_merge($this->_map,$map);
    }

    //设置步骤
    public function setStep($step='')
    {
        $this->_step=is_string($step)?explode(',',$step):$step;
        return $this;
    }

    //获取错误码
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    //获取错误信息
    public function getError()
    {
        return parent::getError();
    }

    /**********get***********************************************/

    //获取白名单规则
    protected function getAllowRules($allow)
    {
        $arr=array();
        if(!empty($allow)) {
            $allow=is_array($allow)?$allow:explode(',',$allow);
            $fields=$this->getDbFields();
            $deny=array();
            for($i=0;$i<count($fields);$i++)
            {
                if(!in_array($fields[$i],$allow))
                    $deny[]=$fields[$i];
            }
            $arr=$this->getDenyRules($deny);
        }
        return $arr;
    }

    //获取黑名单规则
    protected function getDenyRules($deny)
    {
        $arr=array();
        if(!empty($deny)) {
            $deny=is_array($deny)?$deny:explode(',',$deny);
            for($i=0;$i<count($deny);$i++)
            {
                $arr[]=array($deny[$i],null,null,$this->denyInfo,self::FIELD_EXIST);
            }
            $arr=$this->getValidateRules($arr);
        }
        return $arr;
    }

    //获取必须规则(不存在则报错)
    protected function getMustRules($must)
    {
        $arr=array();
        if(!empty($must)) {
            $must=is_array($must)?$must:explode(',',$must);
            for($i=0;$i<count($must);$i++)
            {
                $arr[]=array($must[$i],null,null,$this->mustInfo,self::FIELD_NOT_EXIST);
            }
            $arr=$this->getValidateRules($arr);
        }
        return $arr;
    }

    //获取验证规则
    protected function getValidateRules($validate)
    {
        $arr=array();
        if(!empty($validate)) {
            for($i=0;$i<count($validate);$i++)
            {
                $validate[$i][4]=isset($validate[$i][4])?$validate[$i][4]:self::FIELD_EXIST;
                $arr[]=array($validate[$i][0],$validate[$i][1],$validate[$i][2],'validate',$validate[$i][3],$validate[$i][4]);
            }
        }
        return $arr;
    }

    //获取忽略规则
    protected function getIgnoreRules($ignore)
    {
        $arr=array();
        if(!empty($ignore)) {
            $ignore=is_array($ignore)?$ignore:explode(',',$ignore);
            for($i=0;$i<count($ignore);$i++)
            {
                $arr[]=array($ignore[$i],null,null,'ignore',null,self::VALUE_EMPTY);
            }
        }
        return $arr;
    }

    //获取赋值规则
    protected function getAssignRules($assign)
    {
        $arr=array();
        if(!empty($assign)) {
            for($i=0;$i<count($assign);$i++)
            {
                $assign[$i][3]=isset($assign[$i][3])?$assign[$i][3]:self::FIELD_EXIST;
                $arr[]=array($assign[$i][0],$assign[$i][1],$assign[$i][2],'assign',null,$assign[$i][3]);
            }
        }
        return $arr;
    }

    /**********handler*******************************************/

    //处理验证结果
    protected function validateHandler($result,$field,$value,$formData,$param)
    {
        if($result)
            return $formData;
        if(is_array($field)) {
            $desc=array();$tmp=array();
            for($i=0;$i<count($field);$i++)
            {
                $falseName=$this->getMapField($field[$i],false);
                $tmpDesc=$this->getFieldDesc($field[$i]);
                $desc[]=$tmpDesc?$tmpDesc:($falseName?$falseName:$field[$i]);
                $tmp[]=$value[$field[$i]];
            }
            $value=$tmp;
        } else {
            $falseName=$this->getMapField($field,false);
            $tmpDesc=$this->getFieldDesc($field);
            $desc=$tmpDesc?$tmpDesc:($falseName?$falseName:$field);
        }
        $param=$this->parseValidateError($param,'$desc',$desc);
        $param=$this->parseValidateError($param,'$field',$field);
        $param=$this->parseValidateError($param,'$value',$value);
        $this->error=str_replace(array('{$actName}','{$actDesc}'),
            array($this->actName,$this->actDesc),$param);
        return false;
    }

    //解析验证错误信息文本
    private function parseValidateError($error,$search,$replace)
    {
        if(is_array($replace)) {
            $error=str_replace('{'.$search.'}',implode('、',$replace),$error);
            for($i=0;$i<count($replace);$i++)
            {
                $error=str_replace('{'.$search.'['.$i.']}',$replace[$i],$error);
            }
        } else {
            $error=str_replace('{'.$search.'}',$replace,$error);
        }
        return $error;
    }

    //处理忽略结果
    protected function ignoreHandler($result,$field,$value,$formData,$param)
    {
        $field=is_array($field)?$field:array($field);
        for($i=0;$i<count($field);$i++)
        {
            unset($formData[$field[$i]]);
        }
        return $formData;
    }

    //处理赋值结果
    protected function assignHandler($result,$field,$value,$formData,$param)
    {
        $merge=array();//关联数组直接合并
        $arr=array();//索引数组按字段位置赋值
        $result=is_array($result)?$result:array($result);
        foreach($result as $key=>$val)
        {
            if(is_string($key))$merge[$key]=$val;
            if(is_int($key))$arr[]=$val;
        }
        $field=is_array($field)?$field:array($field);
        for($i=0;$i<count($field);$i++)
        {
            if(isset($arr[$i]))
                $formData[$field[$i]]=$arr[$i];
        }
        return array_merge($formData,$merge);
    }

    /**********callback******************************************/

    //正则表达式验证
    protected function regexCallback($value,$field,$formData,$param)
    {
        $list=C('REGEX_PATTERN');
        $param=!empty($list)&&isset($list[$param])?$list[$param]:$param;
        return preg_match($param,$value);
    }

    protected function notRegexCallback($value,$field,$formData,$param)
    {
        return !$this->regexCallback($value,$field,$formData,$param);
    }

    //confirm
    protected function confirmCallback($value,$field,$formData,$param)
    {
        return $value===$formData[$param];
    }

    //compare
    protected function compareCallback($value,$field,$formData,$param)
    {
        $result=false;
        preg_match('/^([\>|\<|\!]?\={0,2})(.*)$/',$param,$arr);
        $arr[1]=empty($arr[1])?'==':$arr[1];
        eval('$result=($value'.$arr[1].'$arr[2]);');
        return $result;
    }

    //in
    protected function inCallback($value,$field,$formData,$param)
    {
        $param=is_array($param)?$param:explode(',',$param);
        return in_array($value,$param);
    }

    //no_tin
    protected function notInCallback($value,$field,$formData,$param)
    {
        $param=is_array($param)?$param:explode(',',$param);
        return !in_array($value,$param);
    }

    //length
    protected function lengthCallback($value,$field,$formData,$param)
    {
        return $this->getRangeResult(mb_strlen($value,'utf-8'),$param);
    }

    //strlen
    protected function strlenCallback($value,$field,$formData,$param)
    {
        return $this->getRangeResult(strlen($value),$param);
    }

    //between
    protected function betweenCallback($value,$field,$formData,$param)
    {
        return $this->getRangeResult($value,$param);
    }

    //not_between
    protected function notBetweenCallback($value,$field,$formData,$param)
    {
        return $this->getRangeResult($value,$param,true);
    }

    //expire
    protected function expireCallback($value,$field,$formData,$param)
    {
        return $this->getRangeResult(NOW_TIME,$param);
    }

    //time_range
    protected function timeRangeCallback($value,$field,$formData,$param)
    {
        $value=is_numeric($value)?$value:strtotime($value);
        return $this->getRangeResult($value,$param);
    }

    //ip_allow
    protected function ipAllowCallback($value,$field,$formData,$param)
    {
        $param=is_array($param)?$param:explode(',',$param);
        return in_array(get_client_ip(),$param);
    }

    //ip_deny
    protected function ipDenyCallback($value,$field,$formData,$param)
    {
        $param=is_array($param)?$param:explode(',',$param);
        return !in_array(get_client_ip(),$param);
    }

    //unique
    protected function uniqueCallback($value,$field,$formData,$param)
    {
        $value=is_array($value)?$value:array($field=>$value);
        $field=is_array($field)?$field:array($field);
        for($i=0;$i<count($field);$i++)
        {
            $where[$field[$i]]=$value[$field[$i]];
        }
        $num=$this->where($where)->count();
        return $num==0;
    }

    //exc_unique
    protected function excUniqueCallback($value,$field,$formData,$param)
    {
        $value=is_array($value)?$value:array($field=>$value);
        $field=is_array($field)?$field:array($field);
        for($i=0;$i<count($field);$i++)
        {
            $where[$field[$i]]=$value[$field[$i]];
        }
        $pks=$this->getActionPks($formData);
        if(!empty($pks)) {
            $tmp=array();
            foreach($pks as $key=>$value)
            {
                $tmp[$key]=array('neq',$value);
            }
            $tmp['_logic']='or';
            $where[]=$tmp;
        }
        $num=$this->where($where)->count();
        return $num==0;
    }

    //least
    protected function leastCallback($value,$filed,$formData,$param)
    {
        $result=false;
        foreach($value as $key=>$val)
        {
            if(!empty($val))$result=true;
        }
        return $result;
    }

    //field
    protected function fieldCallback($value,$field,$formData,$param)
    {
        return $formData[$param];
    }

    //string
    protected function stringCallback($value,$field,$formData,$param)
    {
        return $param;
    }

    //读取配置文件
    protected function configCallback($value,$field,$formData,$param)
    {
        return C($param);
    }

    //取值范围
    private function getRangeResult($value,$range,$not=false)
    {
        if(is_array($range)||strpos($range,',')!==false) {
            //取值范围
            list($min,$max)=is_array($range)?$range:explode(',',$range);
            if($not) {
                $result=false;
                if($min!==''&&!is_null($min)&&$value<$min)$result=true;
                if($max!==''&&!is_null($max)&&$value>$max)$result=true;
            } else {
                $result=true;
                if($min!==''&&!is_null($min)&&$value<$min)$result=false;
                if($max!==''&&!is_null($max)&&$value>$max)$result=false;
            }
        } else {
            $result=$not?$value!=$range:$value == $range;
        }
        return $result;
    }

    //列表转换
    public function listMap($list,$type='default')
    {
        $tmp=array();
        foreach ($list as $item){
            $tmp[]=$this->itemMap($item,$type);
        }
        return $tmp;
    }

    //单项转换
    public function itemMap($item,$type='default')
    {
        if(strpos($type,'@')!==false){
            list($modelName,$type)=explode('@',$type);
            $map=XD($modelName)->getRelationMap($type?$type:'default');
        }else{
            $map=$this->relationMap[$type];
        }
        $tmp=array();
        foreach ($item as $key=>$value){
            if(array_key_exists($key,$map)){
                preg_match('/^([[0-9a-zA-Z_\-]+)(\[([0-9a-zA-Z_\-\:\@\/]+)\])?$/',$map[$key],$matches);
                $nKey=$matches[1];
                if(!empty($matches[3])){
                    $arr=explode(':',$matches[3]);
                    if($arr[0]=='callback'){
                        $fun=explode('@',$arr[1]);
                        $value=call_user_func_array($fun,array($value));
                    }else{
                        $value=$arr[0]=='item'?$this->itemMap($value,$arr[1]):$this->listMap($value,$arr[1]);
                    }
                }
                $tmp[$nKey]=$value;
            }else{
                $tmp[$key]=$value;
            }
        }
        return $tmp;
    }

    public function getRelationMap($type='default')
    {
        return $this->relationMap[$type]?$this->relationMap[$type]:array();
    }

}