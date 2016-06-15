<?php
return array(
    'ENUM_LIST'=>array(
        'status'=>array(
            array('value'=>'1','text'=>'开启'),
            array('value'=>'0','text'=>'关闭'),
        ),
        'reward_type'=>array(
            array('value'=>'cash','text'=>'红包'),
            array('value'=>'score','text'=>'积分')
        ),
        'boolean'=>array(
            array('value'=>'1','text'=>'是'),
            array('value'=>'0','text'=>'否')
        ),
    ),
    'SCORE_USE_CAT'=>array(
        '2'=>'积分换券',
        '3'=>'后台赠送',
        '98'=>'分享',
        '4'=>'使用礼品券',
        '5'=>'签到积分',
        '6'=>'会员充值',
        '7'=>'线下获取积分',
        '8'=>'线下消费积分',
        '9'=>'推广海报奖励'
    ),
    'WEIXIN_API_CONFIG'=>array(
        'create_qrcode'=>array(
            'url'=>'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}',
            'auth'=>''
        ),
        'show_qrcode'=>array(
            'url'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode',
            'auth'=>''
        ),
        'media_upload'=>array(
            'url'=>'https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type={$type}',
            'auth'=>''
        ),
        'custom_send'=>array(
            'url'=>'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}',
            'auth'=>''
        )
    ),
    'REGEX_PATTERN'=>array(
        'require'   =>  '/\S+/',
        'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
        'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
        'currency'  =>  '/^\d+(\.\d+)?$/',
        'number'    =>  '/^\d+$/',
        'zip'       =>  '/^\d{6}$/',
        'integer'   =>  '/^[-\+]?\d+$/',
        'pos_int'   =>  '/^[1-9]\d*$/',
        'pos_0_int'   =>  '/^[0-9]\d*$/',
        'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
        'english'   =>  '/^[A-Za-z]+$/',
        'mobile'    =>  '/^1[3-8]+\\d{9}$/',
        'no_blank'  =>  '/^\S+$/',
        'ip'=>'/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/'
    ),
    'EXTENSION_PATH'=>'./uploads/extension',
    /*
     * 注册场景处理类
     * 'type'=>'Type@index,Type2@index'
     */
    'REG_SCENES'=>array(
        'extension'=>'CommonScene@extension',
        'helping'=>'HelpingScene@add_share',
        'Qrlogin'=>'Qrlogin@wxautobus',
	    'GameShare' => 'GameShareScene@getShare',
        'BoostShare' => 'BoostShareScene@getShare',
    ),
    /* meihua congif */
    'IS_MEIHUA'             => false,
    'MEIHUA_URL'            => '',
    'MEIHUA_TOKEN'			=> '',
    'DEFAULT_TPL_ID'=>'2'//默认模板
);