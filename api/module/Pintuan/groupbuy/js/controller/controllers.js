/*
 *
 * 控制器
 *
 *  @author 赵仁杰
 *  @since 2016-1-27 14:00
 *
 * */
"use strict";
var ctrl = angular.module("shop.controller",[]);


/*
 首页内容
 2016.4.8因项目发展需要去除

// 主页
ctrl.controller("indexCtrl",["$rootScope", "$scope", "$routeParams", "$location", function($rootScope, scope, $routeParams, $location) {
    if($routeParams.id == -1 || $routeParams.id == "undefined") {
        window.history.back(-1);
    }

    $("body").css("padding-bottom" , "60px");

    $rootScope.storeid = $routeParams.id;

    scope.shopinfo = {
        type : true,
        price : true,
        go : true,
        pricee : true
    };
    scope.howshow = "index";

    $(document).on("click", ".nav-item", function() {
        if(!$(this).hasClass("nav-special-item")) {
            $(this).find(".submenu").show()
            $(this).siblings().find(".submenu").hide();
        }
    });

    scope.indexnavgo = function(text) {
        if(text == "my") {
            $location.path('mybuy/'+$routeParams.id);
        }
    }

1}]);
*/



// 团购页面
ctrl.controller("detailCtrl",["$rootScope", "$scope", "$location", "$routeParams", "$http", "$interval", function($rootScope, scope, $location, $routeParams, $http, $interval){
    scope.howshow = "detail";
    scope.shopInfoShow = true;
    scope.isdialogShow = false;
    scope.tabtitObj = {
        firstName : "商品详情",
        lastName : "拼团说明"
    };
    $("body").css("padding-bottom" , "0");
    scope.tabisshowdetail = true;
    scope.tabisshowdetailinfo = false;

    scope.back = function() {    // 返回操作
        $location.path('main/'+$routeParams.token);
    };

    scope.backid = $routeParams.id;
    scope.backtit = $routeParams.tit;
    scope.backtoken = $routeParams.token;

        scope.showtab = function() {
        scope.shopInfoShow = false;
        scope.shopstateShow = true
    }
}]);


// 团购首页
ctrl.controller("detailinfoCtrl",[
    "$rootScope",
    "$scope",
    "$location",
    "$routeParams",
    "$interval",
    "wxShare",
    "$http",
    "publicfun",
    function($rootScope, scope, $location, $routeParams, $interval, wxShare, $http, publicfun){

    $("body").css("padding-bottom" , "80px");
    scope.navtitle = "团购首页";
    scope.isright = true;
    scope.confirmNumber = 1;
    scope.isok = 0;
    scope.price = 0;
    scope.howshow = "detailinfo";
    scope.isshare = false;
    scope.tabtitObj = {
        firstName : "商品详情",
        lastName : "参团人员"
    };
    scope.dialogIsShow = true;
    scope.tabisshowdetail = false;
    scope.tabisshowdetailinfo = true;
        scope.shopinfo = {
            type : false,
            price : true,
            go : false,
            pricee : true
        };
    scope.nextpageisok = false;
    var usericonPage = 1;

    var id = $routeParams.id;
    var token = $routeParams.token;
    scope.back = function() {    // 后退功能
        $location.path('details/'+id + "/"+token);
    };

    var isuserlist = true;
    var lock = true;    // 函数节流

    scope.showtab = function() {


        var param = '&tuan_id='+$routeParams.id + "&token=" + $routeParams.token;
        if ($routeParams.colonelid > 0) {
            param += "&team_id=" + $routeParams.colonelid;
        }

        /*
         *
         * 获取用户列表
         *
         * */
        if(isuserlist) {
            $http({
                method:'GET',
                url: APIURL+'index.php?g=Wap&m=PintuanReturn&a=buylistReturn'+param
            }).success(function(data) {
                isuserlist = false;
                scope.isloading = false;
                if(data.err_code == 0) {    // 正常
                    if(data.err_msg.order_list) {
                        scope.detailorderinfo = data.err_msg;

                        scope.usericona = true;
                        scope.usericonb = false;
                    }else{
                        scope.usericonb = true;
                        scope.usericona = false;
                    }

                    if(data.err_msg.next_page == true) {
                        scope.nextpageisok = true;
                    }else{
                        scope.nextpageisok = false;
                    }


                    if(!scope.nextpageisok) {
                        console.log(scope.nextpageisok);
                        return;
                    }

                    angular.element(document).on("scroll",function() {     // 滚动加载数据
                        if(!scope.nextpageisok) {
                            return;
                        }
                        loadres();
                    });

                }else {    // 常规错误
                    scope.dialogText = data.err_msg;
                    scope.dialogIsShow = true;
                    return false;
                }
            }).error(function(err) {
                scope.isloading = false;
                scope.dialogText = "网络异常";
                return false;
            });
        }

        scope.shopInfoShow = false;
        scope.shopstateShow = true;

        function loadres(){
            var more = $(".detailinfonextmore"),
                height = $(window).height(),
                scrollTop = $(window).scrollTop(),
                elTop = more.offset().top,
                group_id = "";

            if(elTop <= scrollTop + height + 20) {    // 在能看见加载更多前20个像素时开始请求
                if($rootScope.group_id != 0) {
                    group_id = "&group_id" + $rootScope.group_id;
                }

                if(lock) {
                    lock = false;
                    scope.isloading = true;
                    $http({
                        method:'GET',
                        url: APIURL+'index.php?g=Wap&m=PintuanReturn&a=buylistReturn&tuan_id='+$routeParams.id + "&token=" + $routeParams.token + "&page="+usericonPage
                    }).success(function(data) {
                        scope.isloading = false;
                        if(data.err_code == 0) {    // 正常
                            if(data.err_msg.order_list) {
                                var html = "";
                                for(var i = 0; i < data.err_msg.order_list.length; i++) {
                                    var type = publicfun.seltype(data.err_msg.order_list[i].data_type);
                                    var time = publicfun.datatime( data.err_msg.order_list[i].add_time * 1000);
                                    html += '<li class="filtime"><div class="fl fl-ex"><img class="filimg" src=" ' + data.err_msg.user_list[data.err_msg.order_list[i].uid].avatar + '" alt="">'+ data.err_msg.user_list[data.err_msg.order_list[i].uid].nickname  +'</div>';
                                    html += '<div class="fl fl-ex">'+ type +'</div><div class="fl fl-ex">'+ time +'</div><div class="clear"></div></li>'
                                }
                                $(".filtimefirst").after(html);
                            }
                            usericonPage = usericonPage + 1;
                            lock = true;

                            if(data.err_msg.next_page) {
                                scope.nextpageisok = true;
                            }else{
                                angular.element(document).off("scroll");
                                scope.nextpageisok = false;
                            }

                        }else if(data.err_code == 1000){    // 常规错误
                            scope.dialogText = data.err_msg;
                            scope.dialogIsShow = true;
                            return false;
                        }else{
                            scope.dialogText = "服务器异常";
                            scope.dialogIsShow = true;
                            return false;
                        }
                    }).error(function(err) {
                        scope.isloading = false;
                        scope.dialogText = "网络异常";
                        return false;
                    });
                }
            }
        }

    }
}]);


// 我的团购
ctrl.controller("mybuyctrl",["$rootScope", "$scope", "$http", "$routeParams", "$location", "gitip", function($rootScope, scope, $http, $routeParams, $location, gitip) {
    var ipparam = "&url=" + encodeURIComponent(gitip.ip.split("?")[0]);
    scope.howshow = "mybuy";
    scope.nav = ["全部","拼团中","拼团失败","拼团成功"];
    $("body").css("padding-bottom" , "20px");
    scope.backhide = true;
    scope.token = $routeParams.token;

    var param = '&token='+$routeParams.token,
        clickparam = "",
        isnext = false,
        page = 2,
        isok = true;

    scope.back = function() {
        window.history.back(-1);
    };

    scope.indexnavgo = function(text) {
        if(text = "index") {
            $location.path('main/'+$routeParams.token);
        }
    }

    scope.clicknav = function(index) {
        var $li = $(".mybuy-nav li");
        $li.eq(index).addClass("mybuy-nav-active");
        $li.eq(index).siblings().removeClass("mybuy-nav-active");

        $("html").css({
            "position" : "relative",
            "height" : "100%"
        })

        switch(index) {
            case 0 :
                clickparam = "";
                break;
            case 1 :
                clickparam = "&type=" + 1;
                break;
            case 2 :
                clickparam = "&type=" + 3;
                break;
            case 3 :
                clickparam = "&type=" + 2;
                break;
        }
        scope.isloading = true;
        $http({
            method: 'GET',
            url: APIURL+'index.php?g=Wap&m=PintuanReturn&a=myorderReturn' + param + clickparam + ipparam
        }).success(function(data) {
            scope.isloading = false;
            if(data.err_code == 20000) {    // 没有登陆
                // 跳到登录页面
                alert("尚未登录");
                window.location.href= data.err_msg+"?referer="+encodeURIComponent(location.href);
            }else if(data.err_code == 0) {    // 正常

                wx.config({
                    debug: false,
                    appId: data.err_msg.share_data.appId,
                    timestamp: data.err_msg.share_data.timestamp,
                    nonceStr: data.err_msg.share_data.nonceStr+"",
                    signature: data.err_msg.share_data.signature,
                    jsApiList: [
                        'onMenuShareTimeline',
                        'onMenuShareAppMessage',
                        'onMenuShareQQ',
                        'onMenuShareWeibo',
                        'onMenuShareQZone'
                    ]
                });
                wx.ready(function(){
                });
                wx.error(function(res){
                    console.log(res)
                });


                scope.buydata = data.err_msg;
                if(scope.buydata.next_page) {
                    isnext = true;
                    scope.isshow = true
                }else{
                    isnext = false;
                    scope.isshow = false
                }

                try{
                    if(scope.buydata.order_list.length > 0) {
                        scope.isshoplist = false;
                    }else{
                        scope.isshoplist = true;
                        $(".public-bottom").css({
                            position: "absolute",
                            bottom: "10px",
                            width : "100%"
                        })
                    }
                }
                catch (e){
                    scope.isshoplist = true;
                    $(".public-bottom").css({
                        position: "absolute",
                        bottom: "10px",
                        width : "100%"
                    })
                }



                setTimeout(function() {
                    if($(".mybuy-shoplist").height() < ($(window).height() - 110)) {
                        $(".public-bottom").css({
                            position: "absolute",
                            bottom: "10px",
                            width : "100%"
                        })
                    }else{
                        $(".public-bottom").css({
                            position: "initial",
                            bottom: "initial",
                            width : "100%"
                        })
                    }
                }, 100)

            }else if(data.err_code == 1000){    // 常规错误
                scope.dialogText = data.err_msg;
                scope.dialogIsShow = true;
                return false;
            }
        }).error(function(err) {
            scope.isloading = false;
            scope.dialogText = "网络异常";
            scope.dialogIsShow = true;
            return false;
        });

    }

    scope.isloading = true;

    $http({
        method: 'GET',
        url: APIURL+'index.php?g=Wap&m=PintuanReturn&a=myorderReturn' + param
    }).success(function(data) {
        scope.isloading = false;
        if(data.err_code == 20000) {    // 没有登陆
            // 跳到登录页面
            alert("尚未登录");
            window.location.href= data.err_msg+"?referer="+encodeURIComponent(location.href);
        }else if(data.err_code == 0) {    // 正常

            scope.buydata = data.err_msg;
            if(scope.buydata.next_page) {
                isnext = true;
                scope.isshow = true
            }else{
                isnext = false;
                scope.isshow = false
            }

            try{
                if(scope.buydata.order_list.length > 0) {
                    scope.isshoplist = false;
                }else{
                    scope.isshoplist = true;
                }
            }
            catch (e){
                scope.isshoplist = true;
            }

        }else if(data.err_code == 1000){    // 常规错误
            scope.dialogText = data.err_msg;
            scope.dialogIsShow = true;
            return false;
        }
    }).error(function(err) {
        scope.isloading = false;
        scope.dialogText = "网络异常";
        scope.dialogIsShow = true;
        return false;
    });

    angular.element(document).on("scroll",function() {     // 滚动加载数据
        if(!isnext) {
            return;
        }

        var more = $(".indexlistmore"),
            height = $(window).height(),
            scrollTop = $(window).scrollTop(),
            elTop = more.offset().top;

        if(elTop <= scrollTop + height + 20) {    // 在能看见加载更多前20个像素时开始请求
            if(isok) {
            isok = false;
                scope.isloading = true;
                $http({
                    method: 'GET',
                    url: APIURL+'index.php?g=Wap&m=PintuanReturn&a=myorderReturn' + param + clickparam + "&page=" + page
                }).success(function(data) {
                    scope.isloading = false;
                    isok = true;
                    if(data.err_code == 20000) {    // 没有登陆
                        // 跳到登录页面
                        alert("尚未登录");
                        window.location.href= data.err_msg+"?referer="+encodeURIComponent(location.href);
                    }else if(data.err_code == 0) {    // 正常

                        for(var i = 1; i < data.err_msg.order_list.length; i++) {
                            scope.buydata.order_list.push(data.err_msg.order_list[i])
                        }

                        if(data.err_msg.next_page) {
                            isnext = true;
                            scope.isshow = true
                            page = page + 1;
                        }else{
                            isnext = false;
                            scope.isshow = false
                        }

                    }else if(data.err_code == 1000){    // 常规错误
                        scope.dialogText = data.err_msg;
                        scope.dialogIsShow = true;
                        return false;
                    }
                }).error(function(err) {
                    scope.isloading = false;
                    isok = true;
                    scope.dialogText = "网络异常";
                    scope.dialogIsShow = true;
                    return false;
                });
            }
        }
    });
}]);


// 拼团流程页面
ctrl.controller("flowCtrl",["$scope", "$location", "$routeParams", function(scope, $location, $routeParams){
    scope.navtitle = "拼团流程";
    scope.howshow = "flow";
    var id = $routeParams.id;
    var token = $routeParams.token;
    scope.back = function() {
        $location.path('details/'+ id + "/" + token);
    };

}]);


// 订单
ctrl.controller("orderctrl",["$scope", "$http", "$routeParams", "$location", "wxShare", function(scope, $http, $routeParams, $location, wxShare){

    var param = "&token=" + $routeParams.token + "&order_id=" + $routeParams.id;
    scope.APIURL = APIURL;
    scope.token = $routeParams.token;

    var shareurl = "";
    if(location.href.split("?").length > 1){
        shareurl = location.href.split("?")[0] +"#"+ location.href.split("?")[1].split("#")[1];
        location.href = shareurl;
    }else{
        shareurl = location.href.split("#")[0]
    }

    param += "&url=" + encodeURIComponent(shareurl);

    $http({
        method: 'GET',
        url: APIURL+'index.php?g=Wap&m=PintuanReturn&a=orderinfoReturn' + param
    }).success(function(data) {
        scope.isloading = false;
        if(data.err_code == 20000) {    // 没有登陆
            // 跳到登录页面
            alert("尚未登录");
            window.location.href= data.err_msg+"?referer="+encodeURIComponent(location.href);
        }else if(data.err_code == 0) {    // 正常
            scope.data = data.err_msg.order;
            scope.tuan_id=data.err_msg.tuan.tuan_id;

            wx.config({
                debug: false,
                appId: data.err_msg.share_data.appId,
                timestamp: data.err_msg.share_data.timestamp,
                nonceStr: data.err_msg.share_data.nonceStr+"",
                signature: data.err_msg.share_data.signature,
                jsApiList: [
                    'onMenuShareTimeline',			//获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
                    'onMenuShareAppMessage',			//获取“分享给朋友”按钮点击状态及自定义分享内容接口
                    'onMenuShareQQ',
                    'onMenuShareWeibo',
                    'onMenuShareQZone'
                ]
            });


            wx.ready(function(){
                wx.showOptionMenu();
                // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
                var shareObj = {    // 分享参数
                    title: data.err_msg.share_data.custom_sharetitle,
                    desc: data.err_msg.share_data.custom_sharedsc,
                    link: data.err_msg.share_data.url,
                    imgUrl: data.err_msg.share_data.share_img
                };

                wx.onMenuShareTimeline({
                    title: shareObj.title,    // 标题
                    link: shareObj.link,    // 链接
                    imgUrl: shareObj.imgUrl,    // 图片
                    success: function (res) {
                    },
                    cancel: function (res) {
                    }
                });

                //分享到好友
                wx.onMenuShareAppMessage({
                    title: shareObj.title,    // 标题
                    desc: shareObj.desc,    // 描述
                    link: shareObj.link,    // 链接
                    imgUrl: shareObj.imgUrl,    // 图片
                    success: function (res) {
                    },
                    cancel: function (res) {
                    }
                });

                //分享到QQ
                wx.onMenuShareQQ({
                    title: shareObj.title,
                    desc: shareObj.desc,
                    link: shareObj.link,
                    imgUrl: shareObj.imgUrl,
                    success: function (res) {
                    },
                    cancel: function (res) {
                    }
                });

                //分享到腾讯微博
                wx.onMenuShareWeibo({
                    title: shareObj.title,
                    desc: shareObj.desc,
                    link: shareObj.link,
                    imgUrl: shareObj.imgUrl,
                    success: function (res) {
                    },
                    cancel: function (res) {
                    }
                });

                //分享到QQ空间
                wx.onMenuShareQZone({
                    title: shareObj.title,
                    desc: shareObj.desc,
                    link: shareObj.link,
                    imgUrl: shareObj.imgUrl,
                    success: function (res) {
                    },
                    cancel: function (res) {
                    }
                });
            });

            wx.error(function(res){
                // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
                console.log(res)
            });



        }else if(data.err_code == 1000){    // 常规错误
            scope.dialogText = data.err_msg;
            scope.dialogIsShow = true;
            return false;
        }
    }).error(function(err) {
        scope.isloading = false;
        isok = true;
        scope.dialogText = "网络异常";
        scope.dialogIsShow = true;
        return false;
    });
    scope.share = function() {
        scope.isshare = true;
    };
    scope.closeshare = function() {
        scope.isshare = false;
    };

}]);