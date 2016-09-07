<?php
class CompanyModel extends XModel
{
    public function filterFields($data)
    {
        return array('id'=>$data['id'],'name'=>$data['name'],'shortname'=>$data['shortname'],'address'=>$data['address'],'logourl'=>$data['logourl'],
            'cat_name'=>$data['cat_name'],'isbranch'=>$data['isbranch']);
    }

    public function getList($token)
    {
        $list=$this->where(array('token'=>$token))->select();
        foreach ($list as &$item)
        {
            $item=$this->filterFields($item);
        }
        return $list;
    }
}