/*
 *
 * 服务
 *
 *  @author 赵仁杰
 *  @since 2016-1-27 14:00
 *
 * */
"use strict";
var serv = angular.module("shop.services",[]);
var APIURL = window.location.protocol +"//"+ window.location.host + "/";    // 接口路径

serv.service("indexShopListJson",["$http", "$routeParams", function(http, $routeParams) {    // 主页商品列表json接口
    var id = $routeParams.id;
    var indexShopList = http({
        method:'GET',
        url: APIURL + 'webapp.php?c=tuan&store_id=' + id    // 上线去掉
    });
    return {
        indexShopList: indexShopList
    };
}]);


serv.service("detailsIndexInfoJson",["$rootScope","$http","$routeParams", function(rootscope, http, $routeParams) {    // 团购首页数据json接口

    var param = "";

    if($routeParams.id) {
        param = "tuan_id="+$routeParams.id;
    }

    if($routeParams.type) {
        param += "&type="+$routeParams.type;
    }

    if($routeParams.grade > -1) {
        param += "&item_id="+$routeParams.grade;
    }



    var detilsInfo = http({
        method:'GET',
        url: APIURL+'webapp.php?c=tuan&a=tuan_info&'+param
    });
    return {
        detilsInfo: detilsInfo
    };
}]);


serv.service("lazyLoadService",["$rootScope", function(rootscope) {    // 图片懒加载服务

/*
 *  注意：
 *      在需要使用图片懒加载的图片src中写上用户看不见时使用的小图片路径，datasrc里写上实际需要加载的图片路径，示例
 *      <img src="a.png" datasrc="//www.baidu.com/img/270_7573fb368053e6805e63b56352ce7287.gif" alt="">
 *      调用方法：
 *          lazyLoad.init($("img"));
*/

    var lazyLoad = {
        init : function(el) {    // 初始化
            if(el == "") {
                return;
            }
            this.winHeight = $(window).height();
            this.el = el;
            var me = this;
            $(window).on("scroll",function() {
                me.bind();
            });

            this.bind();
        },

        bind : function() {
            var me = this;
            me.el.each(function() {
                if(!$(this).attr("data-lazy") && me.isshow($(this))) {
                    me.showImg($(this));
                }
            });
        },

        isshow : function(el) {
            var winScrollTop = $(window).scrollTop();
            if(el.offset().top < this.winHeight + winScrollTop) {
                el.attr("data-lazy","true");
                return true;
            }else{
                return false;
            }
        },

        showImg : function(el) {
            el.attr("src",el.attr("datasrc"));
        }
    };
    return {
        lazyLoad: lazyLoad
    };
}]);


serv.service("wxShare",["$rootScope",function(rootscope) {    // 分享功能

//    var wx_share_params = {
//        appId : "",
//        timestamp : "",
//        nonceStr : "",
//        signature : "",
//        init : function(appId, timestamp, nonceStr, signature) {
//            this.appId = appId;
//            this.timestamp = timestamp;
//            this.nonceStr = nonceStr;
//            this.signature = signature;
//        }
//    };
//
//    wx.config({
//        debug: false,
//        appId: wx_share_params.appId,
//        timestamp: wx_share_params.timestamp,
//        nonceStr: wx_share_params.nonceStr,
//        signature: wx_share_params.signature,
//        jsApiList: [
//            'onMenuShareTimeline',			//获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
//            'onMenuShareAppMessage',			//获取“分享给朋友”按钮点击状态及自定义分享内容接口
//            'onMenuShareQQ',
//            'onMenuShareWeibo',
//            'onMenuShareQZone'
//        ]
//    });
//
//
//// 分享参数配置
//    var wx_share_element = {
//        title : document.title,
//        desc : document.title,
//        link :location.href.split('#')[0],
//        imgUrl : "http://g.hiphotos.baidu.com/zhidao/wh%3D600%2C800/sign=3ce632af738b4710ce7af5caf3feefc5/b8389b504fc2d56203721a63e61190ef77c66cc2.jpg",
//
//        init:function(obj){
//            if(obj.title != null && obj.title!=''){
//                this.title = obj.title;
//            }
//
//            if(obj.desc != null && obj.desc!=''){
//                this.desc = obj.desc;
//            }
//
//            if(obj.link != null && obj.link!=''){
////                var oauthUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="+appid+"&redirect_uri=";
////                oauthUrl = oauthUrl + encodeURIComponent(obj.link);
////                oauthUrl = oauthUrl + "&response_type=code&scope=snsapi_base&state="+shareUuid+"#wechat_redirect";
////                this.link = oauthUrl;
//            }
//            if(obj.imgUrl != null && obj.imgUrl!=''){
//                this.imgUrl = obj.imgUrl;
//            }
//
//        }
//    };
//
//    wx.ready(function () {
//        //自定义页面初始化的方法,用于获取微信授权成功后的回调
//        //initLoad();
//        initWxReady();
//    });
//
//
//    function initWxReady(){
//        //分享到朋友圈
//        wx.onMenuShareTimeline({
//            title: wx_share_element.title,    // 标题
//            link: wx_share_element.link,    // 链接
//            imgUrl: wx_share_element.imgUrl,    // 图片
//            success: function (res) {
//                alert('已分享到朋友圈');
//            },
//            cancel: function (res) {
//                alert('已取消分享到朋友圈');
//            }
//        });
//
//
//        //分享到好友
//        wx.onMenuShareAppMessage({
//            title: wx_share_element.title,    // 标题
//            desc: wx_share_element.desc,    // 描述
//            link: wx_share_element.link,    // 链接
//            imgUrl: wx_share_element.imgUrl,    // 图片
//            success: function (res) {
//                alert('已分享到好友');
//            },
//            cancel: function (res) {
//                alert('已取消分享到好友');
//            }
//        });
//
//
//        //分享到QQ
//        wx.onMenuShareQQ({
//            title: wx_share_element.title,
//            desc: wx_share_element.desc,
//            link: wx_share_element.link,
//            imgUrl: wx_share_element.imgUrl,
//            success: function (res) {
//                alert('已分享到QQ');
//            },
//            cancel: function (res) {
//                alert('已取消分享到QQ');
//            }
//        });
//
//
//        //分享到腾讯微博
//        wx.onMenuShareWeibo({
//            title: wx_share_element.title,
//            desc: wx_share_element.desc,
//            link: wx_share_element.link,
//            imgUrl: wx_share_element.imgUrl,
//            success: function (res) {
//                alert('已分享到腾讯微博');
//            },
//            cancel: function (res) {
//                alert('已取消分享到腾讯微博');
//            }
//        });
//
//
//        //分享到QQ空间
//        wx.onMenuShareQZone({
//            title: wx_share_element.title,
//            desc: wx_share_element.desc,
//            link: wx_share_element.link,
//            imgUrl: wx_share_element.imgUrl,
//            success: function (res) {
//                alert('已分享到QQ空间');
//            },
//            cancel: function (res) {
//                alert('已取消分享到QQ空间');
//            }
//        });
//    }
//    return {
//        wx_share_element : wx_share_element,
//        wx_share_params : wx_share_params,
//        initWxReady : initWxReady
//    };
}]);


serv.service("publicfun",["$rootScope", "$location", "$http", "$routeParams", function(rootscope, $location, http, $routeParams) {    // 通用功能
    /**
     *
     * 验证信息是否正确
     *
     * @param val  需要验证的值
     * @param text  类型
     * @param logtext  提示类型文字
     * @returns {boolean}  是否正确，不正确的时候会自动提示
     */

    function verification(val, text, logtext) {

        var emailReg  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,    // 邮箱正则
            idcardReg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;

        if(text == "number") {
            if(/^\d+(\.\d+)?$/.test(val)) {
                return true;
            }else{
                scope.dialogText = "您输入的"+ logtext +"不正确，请重新输入！";
                scope.dialogIsShow = true;
                return false;
            }
        }else if(text == "email") {
            if (emailReg.test(val)){
                return true;
            } else {
                scope.dialogText = "您输入的"+ logtext +"不正确，请重新输入！";
                scope.dialogIsShow = true;
                return false;
            }
        }else if(text == "idno") {
            if (idcardReg.test(val)){
                return true;
            } else {
                scope.dialogText = "您输入的"+ logtext +"不正确，请重新输入！";
                scope.dialogIsShow = true;
                return false;
            }
        }
    }


    /**
     *
     * 取数组不同的元素
     *
     * @param arra  数组一
     * @param arrb  数组二
     * @returns {Array}  返回一个去重后的数组
     */
    function uniq(arra,arrb) {
        var c = [];
        var tmp = arra.concat(arrb);
        var o = {};
        for (var i = 0; i < tmp.length; i ++) (tmp[i] in o) ? o[tmp[i]] ++ : o[tmp[i]] = 1;
        for (var x in o) if (o[x] == 1) c.push(x);

        for(var j = 0; j < c.length; j++) {
            for(var k = 0; k < arrb.length; k++) {
                if(arrb[k] == c[j]) {
                    c.remove(j)
                }
            }
        }
        return c;
    }


    /**
     * 数组去重
     * @param arr 传入一个需要去重的数组
     * @returns {Array}
     */

    function hovercUnique(arr) {
        var result = [], hash = {};
        for (var i = 0, elem; (elem = arr[i]) != null; i++) {
            if (!hash[elem]) {
                result.push(elem);
                hash[elem] = true;
            }
        }
        return result;
    }


    /**
     *
     * 完成筛选并返回需要没有库存的ID
     *
     * @param arrlist  [String]  能选中的组合
     * @param arr  [String]  当前组合
     *
     * @returns {Array}
     */

    function ishasparam(arrlist, arr) {

        var arrsubstr = "";

        for(var i = 0; i < arrlist.length; i++) {
            if(arrlist[i].indexOf(arr) > -1) {
                if(arrlist[i].indexOf(arr) == 0) {    // 为真表示是第一个
                    arrsubstr += arrlist[i].slice(arr.length + 1)+";";
                    //console.log("前面")
                }else if(arrlist[i].substr(arrlist[i].length-arr.length) == arr){    // 如果为真表示它是最后一个
                    arrsubstr += arrlist[i].slice(0,arrlist[i].length - arr.length -1)+";";
                    //console.log('后面')
                }else{    // 如果都不是表示是中间的
                    arrsubstr += arrlist[i].slice(0,arr.length) + arrlist[i].slice(arrlist[i].length - arr.length -1)+";"
                    //console.log("中间")
                }
            }
        }
        var arrsubstrArr = arrsubstr.split(";");
        arrsubstrArr = hovercUnique(arrsubstrArr);
        return arrsubstrArr;
    }


    /**
     * 单个数组去重
     *
     */

    Array.prototype.unique = function()
    {
        var n = {},r=[]; //n为hash表，r为临时数组
        for(var i = 0; i < this.length; i++) //遍历当前数组
        {
            if (!n[this[i]]) //如果hash表中没有当前项
            {
                n[this[i]] = true; //存入hash表
                r.push(this[i]); //把当前数组的当前项push到临时数组里面
            }
        }
        return r;
    };


    /**
     *
     * 需要删除的数组下标
     *
     * @param dx 下标
     * @returns {Number}
     */
    Array.prototype.remove=function(dx) {
        if(isNaN(dx)||dx>this.length){return false;}
        for(var i=0,n=0;i<this.length;i++)
        {
            if(this[i]!=this[dx])
            {
                this[n++]=this[i]
            }
        }
        this.length-=1
    };

    /**
     *
     * 返回时间
     *
     * @param text
     * @returns {string}
     */
    function datatime(text) {
        var date = new Date(text);
        var Y = date.getFullYear() + '-';
        var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        var D = date.getDate() + ' ';
        var h = date.getHours() + ':';
        var m = date.getMinutes() + ':';
        var s = date.getSeconds();
        return Y+M+D+h+m+s
    }


    function seltype(text) {    // 返回相关开团内容
        if(text== 0) {
            return  "人缘开团";
        }else{
            return "最优开团";
        }
    }


    /**
     *
     * 找不同
     *
     * @param a  需要查找的
     * @param b  这个数组的结果不会显示到最终结果里
     * @returns Array  返回一个数组
     */
    function subArr(a, b) {
        return a.filter(function(i) {
            return b.indexOf(i) == -1
        })
    }


    return {
        verification: verification,   // 验证信息是否正确
        uniq : uniq,                  // 取数组不同的元素
        hovercUnique : hovercUnique,  // 数组去重
        ishasparam : ishasparam,      // 完成筛选并返回需要没有库存的ID
        datatime : datatime,          // 返回时间
        seltype : seltype,            // 选择相关开团内容
        subArr : subArr               // 找不同
    };
}]);


serv.service("gitip",["$rootScope", function(rootscope) {    // 通用功能

    var shareurl = "";
    if(location.href.split("?").length > 1){
        shareurl = location.href.split("?")[0] +"#"+ location.href.split("?")[1].split("#")[1];
        location.href = shareurl;

    }else{
        shareurl = location.href.split("#")[0]
    }

    return {
        ip : shareurl
    };
}]);










