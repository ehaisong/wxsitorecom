/*
 *
 * 指令系统
 *
 *  @author 赵仁杰
 *  @since 2016-1-27 14:00
 *
 * */
"use strict";
var dire = angular.module("shop.directive", ["shop.services"]);
var APIURL = window.location.protocol + "//" + window.location.host + "/";    // 接口路径

dire.directive("shopTitle", ["$location", function ($location) {    // 通用组件标题
    return {
        templateUrl: 'templates/public/title.html',
        restrict: 'E',
        scope: {
            navtitle: "@",
            back: "&",
            backhide: "@"
        },
        link: function (scope, element, attrs) {

        }
    }
}]);




/*

首页内容
    2016.4.8因项目发展需要去除


// 主页列表
dire.directive("indexShopList", ["$rootScope", "$http", "$location", "$routeParams", "gitip", function ($rootScope, $http, $location, $routeParams, gitip) {    // 主页商品列表
    return {
        templateUrl: "templates/groupbuy/indexshoplist.html",
        restrict: "E",
        link: function (scope, element, attrs) {
            scope.isshow = false;
            scope.shopNavIndex = 0;
            scope.replaceText = "";
            $rootScope.group_id = 0;    // 分组ID
            scope.indexhide = true;    // 控制是导航条是否显示
            var nextpage = true;

            var ipparam = "&url=" + encodeURIComponent(gitip.ip.split("?")[0]);
            scope.isnextpage = function (text) {
                angular.element(document).off("scroll");

                $http({
                    method: 'GET',
                    url: APIURL + 'index.php?g=Wap&m=PintuanReturn&a=infoReturn&token=' + $routeParams.id + ipparam
                }).success(function (data) {
                    if (data.err_code == 20000) {    // 没有登陆
                        // 跳到登录页面
                        alert("尚未登录");
                        window.location.href = data.err_msg + "?referer=" + encodeURIComponent(location.href);
                    } else if (data.err_code == 0) {    // 正常
                        $location.path("details/" + text + "/" + $routeParams.id);
                    } else if (data.err_code == 1000) {    // 常规错误
                        scope.dialogText = data.err_msg;
                        scope.dialogIsShow = true;
                        return false;
                    }
                }).error(function (err) {
                    scope.dialogText = "网络异常";
                    scope.dialogIsShow = true;
                    return false;
                });
            };

            var id = $routeParams.id;

            $http({
                method: 'GET',
                url: APIURL + 'index.php?g=Wap&m=PintuanReturn&a=mainReturn&token=' + id + ipparam   // 上线去掉
            }).success(function (data) {
                if (data.err_code == 20000) {    // 没有登陆
                    // 跳到登录页面
                    alert("尚未登录");
                    window.location.href = data.err_msg + "?referer=" + encodeURIComponent(location.href);
                } else if (data.err_code == 0) {

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

                    });
                    wx.error(function(res){
                        console.log(res)
                    });

                    // 正常
                    scope.navtitle = data.err_msg.title;
                    if (data.err_msg.next_page) {
                        nextpage = true;
                    } else {
                        nextpage = false;
                    }

                    if (data.err_msg.product_group_list.length > 0) {
                        scope.indexhide = false;
                    }

                    if (data.err_msg.product_group_list.length > 3) {         // 判断导航数据是否大于四条
                        scope.islen = true;                                         // 将点击按钮打开
                        scope.mainnav = data.err_msg.product_group_list.slice(0, 3);    // 截取导航条数据 前四条数据放在主导航
                        scope.sonnav = data.err_msg.product_group_list.slice(3);    // 第四条之后数据放在次导航
                    } else {
                        scope.islen = false;     // 将点击按钮关闭
                        scope.mainnav = data.err_msg.product_group_list;
                    }

                } else if (data.err_code == 1000) {    // 常规错误
                    scope.dialogText = data.err_msg;
                    scope.dialogIsShow = true;
                    $(".indexlistmore").css("display", "none");
                    scope.indexhide = true;
                    return false;
                } else {
                    scope.dialogText = "服务器异常";
                    scope.dialogIsShow = true;
                    return false;
                }
            }).error(function (err) {
                scope.dialogText = "网络异常";
                scope.dialogIsShow = true;
                return false;
            });

            var a = true;
            scope.down = function () {    // 点击下拉箭头操作
                if(a) {
                    scope.isshow = true;
                }else{
                    scope.isshow = false;
                }
                a = !a;

                if (scope.isshow == true) {    // 判断是否显示
                    $(".isshowicon span").removeClass("icon-caret-down").addClass("icon-caret-up");
                } else {
                    $(".isshowicon span").removeClass("icon-caret-up").addClass("icon-caret-down");
                }
            };

            var nowactiveindex = 1;

            $(document).on("click", ".index-nav-top li", function () {    // 点击顶部导航显示更多
                if($(this).index() == 0) {
                    nowactiveindex = 1
                }else if($(this).index() < 4){
                    nowactiveindex = $(this).index()
                }
                if (!$(this).find("span").hasClass("iconup")) {
                    $(this).addClass("indexnav-active").siblings("li").removeClass("indexnav-active");
                    scope.shopNavIndex = $(this).index();
                } else {
                    scope.isshow = false;
                }
            });

            $(document).on("click", ".indexlistClick", function () {    // 点击导航内容
                page = 0;
                scope.isloading = true;    // 显示出加载中
                scope.$apply(scope.isloading);

                var par = "";
                if (scope.oindex == "no") {
                    par = "&token=" + $routeParams.id;
                } else {
                    par = "&token=" + $routeParams.id + "&group_id=" + $rootScope.group_id;
                }

                $http({
                    method: 'GET',
                    url: APIURL + 'index.php?g=Wap&m=PintuanReturn&a=mainReturn' + par + ipparam
                }).success(function (data) {
                    if (data.err_code == 0) {    // 正常
                        if (data.err_msg.tuan_list.length > 0) {
                            scope.isshoplist = true;
                        } else {
                            scope.isshoplist = false;
                        }

                        if(nowactiveindex != -1) {
                            $(".indexlistClick").eq(nowactiveindex).addClass("indexnav-active").siblings("li").removeClass("indexnav-active")
                        }

                        if (data.err_msg.next_page) {
                            nextpage = true;
                            page = 2;
                        } else {
                            nextpage = false;
                            $(".indexlistmore").css("display", "none");
                        }

                        scope.shoplistdata = data.err_msg.tuan_list;
                        scope.isloading = false;

                        if (data.err_msg.next_page) {
                            nextpage = true;
                            page = page + 1;
                        } else {
                            nextpage = false;
                            $(".indexlistmore").css("display", "none");
                        }

                    } else if (data.err_code == 1000) {    // 常规错误
                        scope.dialogText = data.err_msg;
                        scope.dialogIsShow = true;
                        return false;
                    } else {
                        scope.dialogText = "服务器异常";
                        scope.dialogIsShow = true;
                        return false;
                    }
                }).error(function (err) {
                    scope.dialogText = "网络异常";
                    scope.dialogIsShow = true;
                    return false;
                });
            });

            scope.oindex = 0;
            scope.mainClick = function (index, id) {
                if (index == "全部") {
                    scope.oindex = "no"
                } else {
                    scope.oindex = index;
                    $rootScope.group_id = id;
                }
            };

            scope.sonClick = function (index, id) {
                var replaceDate = scope.sonnav[index];
                scope.sonnav[index] = scope.mainnav[scope.oindex];
                scope.mainnav[scope.oindex] = replaceDate;
                $rootScope.group_id = id;
                return false;
            };

            var page = 1,    // 当前页码
                isok = true;
            scope.isloading = true;    // 显示出加载中
            if (isok) {
                isok = false;
                if (nextpage) {
                    $http({
                        method: 'GET',
                        url: APIURL + 'index.php?g=Wap&m=PintuanReturn&a=mainReturn&token=' + id + ipparam    // 上线去掉
                    }).success(function (data) {
                        isok = true;
                        if (data.err_code == 0) {    // 正常
                            if (data.err_msg.next_page) {
                                nextpage = true;
                                page = page + 1;
                            } else {
                                nextpage = false;
                                $(".indexlistmore").css("display", "none");
                            }
                            scope.shoplistdata = data.err_msg.tuan_list;

                            if (data.err_msg.tuan_list.length > 0) {
                                scope.isshoplist = true;
                            } else {
                                scope.isshoplist = false;
                            }

                        } else if (data.err_code == 1000) {    // 常规错误
                            scope.dialogText = data.err_msg;
                            scope.dialogIsShow = true;
                            return false;
                        } else {
                            scope.dialogText = "服务器异常";
                            scope.dialogIsShow = true;
                            return false;
                        }
                    });
                }
            }

            if (scope.shoplistdata != "") {
                scope.isloading = false;    // 数据获取成功后关闭加载中
            }

            var islock = true;    // 函数节流
            angular.element(document).on("scroll", function () {     // 滚动加载数据
                if (!nextpage) {
                    return;
                }
                var more = $(".indexlistmore"),
                    height = $(window).height(),
                    scrollTop = $(window).scrollTop(),
                    elTop = more.offset().top,
                    group_id = "";

                if (elTop <= scrollTop + height + 20) {    // 在能看见加载更多前20个像素时开始请求
                    if ($rootScope.group_id != 0) {
                        group_id = "&group_id" + $rootScope.group_id;
                    }

                    if (islock) {
                        console.log(islock)
                        islock = false;

                        $http({
                            method: 'GET',
                            url: APIURL + 'index.php?g=Wap&m=PintuanReturn&a=mainReturn&token=' + id + "&page=" + page + group_id + ipparam  // 上线去掉
                        }).success(function (data) {
                            alert("ip1   " + ipparam)
                            if (data.err_code == 0) {    // 正常
                                for (var i = 0, j = data.err_msg.tuan_list.length; i < j; i++) {
                                    scope.shoplistdata.push(data.err_msg.tuan_list[i])
                                }
                                if (data.err_msg.next_page) {
                                    nextpage = true;
                                    page = page + 1;
                                } else {
                                    nextpage = false;
                                    more.css("display", "none");
                                    return;
                                }
                                islock = true;
                            } else if (data.err_code == 1000) {    // 常规错误
                                scope.dialogText = data.err_msg;
                                scope.dialogIsShow = true;
                                return false;
                            } else if (data.err_code == 10000) {    //没有openid,需要重新授权
                                scope.dialogText = data.err_msg;
                                scope.dialogIsShow = true;
                                return false;
                            } else if (data.err_code == 40000) {    //绑定手机
                                scope.dialogText = data.err_msg;
                                scope.dialogIsShow = true;
                                return false;
                            } else {
                                scope.dialogText = "服务器异常";
                                scope.dialogIsShow = true;
                            }
                        }).error(function () {
                            scope.dialogText = "网络异常";
                            scope.dialogIsShow = true;
                            return false;
                        });
                    }
                }
            });
        }
    }
}]);
*/


// 团购页面模板
dire.directive("detailtel", ["$rootScope", "$location", "$interval", "$routeParams", "$http", "gitip","$timeout", function ($rootScope, $location, $interval, $routeParams, http, gitip, $timeout) {    // 购物页面模板
    return {
        templateUrl: 'templates/groupbuy/detailtel.html',
        restrict: 'E',
        link: function (scope, element, attrs) {

            scope.gomybuy = function() {
                $location.path("mybuy/" + $routeParams.token);
            }

            var item_id = 0;
            scope.id = $routeParams.token;    // 团ID
            var ipparam = "&url=" + encodeURIComponent(gitip.ip.split("?")[0]);
            scope.isloading = true;
            http({
                method: 'GET',
                url: APIURL + 'index.php?g=Wap&m=PintuanReturn&a=infoReturn&token=' + scope.id + "&tuan_id=" + $routeParams.id + ipparam
            }).success(function (data) {
                    $timeout(function() {
                        scope.isloading = false;
                    },500);
                if (data.err_code == 20000) {    // 没有登陆
                    // 跳到登录页面
                    window.location.href = data.err_msg + "?referer=" + encodeURIComponent(location.href);
                } else if (data.err_code == 0) {    // 正常

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
                        wx.showOptionMenu();
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

                    switch (data.err_msg.tuan.status) {
                        case 1 :
                            scope.groupbuytypenostart = true;
                            break;
                        case 2 :
                            scope.groupbuytypeok = true;
                            break;
                        case 3 :
                            scope.groupbuytypeno = true;
                            break;
                    }




                    scope.detaildata = data.err_msg;
                    scope.carouselImg = data.err_msg.product_image_list;
                    scope.detaildata.shareUrl = $location.absUrl();
                    $rootScope.detaildata = scope.detaildata;    // 将数据保留起来，留到下个页面使用
                    $rootScope.tit = scope.detaildata.title;    // 标题
                    scope.navtitle = scope.detaildata.title;
                    var $body = $('body');
                    document.title = scope.navtitle;

                } else {    // 常规错误
                    scope.dialogText = data.err_msg;
                    scope.dialogIsShow = true;
                    return false;
                }
            }).error(function () {
                scope.isloading = false;
                scope.dialogText = "网络异常";
                scope.dialogIsShow = true;
                return false;
            });

            var isload = $interval(function () {
                if (scope.detaildata != "") {
                     $interval.cancel(isload);
                }
            }, 100);

            scope.goDetail = function (num) {
                var id = $routeParams.id;
                if (num == 1) {                 // 1人缘  2最优
                    $location.path("detailinfo/" + id + "/" + $routeParams.token + "/0/"+ item_id +"/0")
                } else if (num == 2) {
                    $location.path("detailinfo/" + id + "/" + $routeParams.token + "/1/" + item_id + "/0")
                }
            }

            scope.detailSrc = function (index, grade) {

                $rootScope.detailPeopleNum = scope.detaildata.tuan_config_list[index].number;
                if (grade) {
                    item_id = grade;
                }
                $rootScope.peoplePrice = scope.detaildata.tuan_config_list[0].price;    // 人缘开团
                scope.grade = grade;
                scope.isdialogShow = true;
                $rootScope.detailSactorPrice = scope.detaildata.tuan_config_list[index].price;    // 最优开团
            };
        }
    }
}]);


// 团购首页模板
dire.directive('detailinfotel', ["$rootScope", "$location", "$routeParams", "$interval", "$http", "wxShare", "publicfun","gitip", "$timeout", function ($rootScope, $location, $routeParams, $interval, $http, wxShare, publicfun, gitip, $timeout) {
    return {
        templateUrl: 'templates/groupbuy/detailinfotel.html',
        restrict: 'E',
        link: function (scope, element, attr) {
            $(".index-shop-info").css("height", "auto");
            scope.isclickNum = 0;
            scope.isok = 0;

            var nextisoknum = 0,
                nextisclicknum = 0,
                openClickpar = false;

            scope.showtuaninfo = function () {
                scope.istabchilshowa = true;
                scope.shopInfoShow = false;
            }

            scope.gomybuy = function() {
                $location.path("mybuy/" + $routeParams.token);
            }

            var $body = $('body');
            var propertyList = [],
                count = 0,
                stock = 0,
                nowpage = location.href,
                index = -1,
                param = "";    // 设置参数


            if ($routeParams.id) {    // 获取相关参数配置
                param += "&tuan_id=" + $routeParams.id;
            }
            if ($routeParams.token) {    // 获取相关参数配置
                param += "&token=" + $routeParams.token;
            }
            if ($routeParams.type) {
                param += "&type=" + $routeParams.type;
            }
            if ($routeParams.grade > -1) {
                param += "&item_id=" + $routeParams.grade;
            }
            scope.nowgroupbuyid = $routeParams.grade;
            if ($routeParams.colonelid > 0) {
                param += "&team_id=" + $routeParams.colonelid;
            }else{
                scope.istabchilshow = true;
            }

            var shareurl = gitip.ip;
            param += "&url=" + encodeURIComponent(shareurl.split("?")[0]);

            scope.isloading = true;
            $http({
                method: 'GET',
                url: APIURL + 'index.php?g=Wap&m=PintuanReturn&a=buyReturn' + param
            }).success(function (data) {

                $timeout(function() {
                    scope.isloading = false;
                },500);

                $(".dialog").css({
                    display : "block"
                });
                if (data.err_code == 20000) {    // 没有登陆
                    // 跳到登录页面
                    alert("尚未登录");
                    window.location.href = data.err_msg + "?referer=" + encodeURIComponent(location.href);
                } else if (data.err_code == 0) {    // 正常

                    scope.shop = data.err_msg.product;
                    scope.shopinfocon = data.err_msg.product;
                    scope.detaildata = data.err_msg;
                    scope.carouselImg = data.err_msg.product_image_list;
                    scope.shopcon = data.err_msg;

                    scope.shoptext = data.err_msg;
                    scope.navtitle = scope.shopcon.title;
                    document.title = scope.navtitle;
                    //if($routeParams.type == 1) {
                    //    scope.price = data.err_msg.tuan_config_list[scope.nowgroupbuyid - 1].price;
                    //}else{
                    //    scope.price = data.err_msg.tuan_config_list[0].price;
                    //}

                    for(var i = 0; i < scope.detaildata.tuan_config_list.length; i++) {
                        if($routeParams.type == 1) {
                            if(scope.detaildata.tuan_config_list[i].item_id == $routeParams.grade) {
                                scope.price = scope.detaildata.tuan_config_list[i].price;
                                scope.lastprice = scope.detaildata.tuan_config_list[i].price;
                            }
                        }else{
                            scope.price = data.err_msg.tuan_config_list[0].price;
                            scope.lastprice = data.err_msg.tuan_config_list[0].price;
                            break;
                        }

                    }



                    if($routeParams.colonelid > 0) {
                        scope.isintuan = true;
                    }else{
                        scope.isintuan = false;
                        $(".isshare").css({
                            "margin-left" : "12%"
                        })
                    }

                    scope.openClick = function() {
                        if(scope.shopcon.tuan_config.iscolonel) {
                            scope.dialogText = "您已开过当前团啦，不能再开了~~";
                            scope.dialogIsShow = true;
                            return false;
                        }else{
                            scope.isnext = true
                            openClickpar = true;
                        }
                    }

                    scope.detailclick = function() {
                        scope.isnext = true;
                        openClickpar = false;
                    }

                    if (scope.detaildata.sku_list) {
                        scope.isbuypriceshow = false;
                    } else {
                        scope.isbuypriceshow = true;
                    }

                    if(scope.detaildata.sku_list) {
                        scope.isbuypriceshow = false;
                    }else {
                        scope.isbuypriceshow = true;
                    }

                    if(scope.shopcon.product.min_price == scope.shopcon.product.max_price) {
                        scope.isbuypriceshow = true;
                    } else {
                        scope.isbuypriceshow = false;
                    }

                    var nowcount = scope.shopcon.tuan_config.count;
                    scope.detailnowcount = -1;
                    if(nowcount == 0) {
                        scope.detailnowcount = -1;
                    } else {
                        for(var i = 0; i < scope.shopcon.tuan_config_list.length; i++){
                            if(nowcount < scope.shopcon.tuan_config_list[i].number) {
                                scope.detailnowcount = i - 1;
                                console.log(i)
                                break;
                            }else{
                                scope.detailnowcount = scope.shopcon.tuan_config_list.length - 1;
                            }
                        }
                    }

                    switch (scope.detaildata.tuan.status) {
                        case 1 :
                            scope.groupbuytypenostart = true;
                            scope.islapeople = false;
                            $(".index-shop-lapeople").hide();
                            break;
                        case 2 :
                            scope.groupbuytypeok = true;
                            if (scope.detaildata.tuan.end_time * 1000 > Date.parse(new Date())) {
                                scope.islapeople = true;
                                scope.shopinfo.go = false
                            }
                            break;
                        case 3 :
                            scope.groupbuytypeno = true;
                            scope.islapeople = false;
                            scope.shopinfo.go = true

                            break;
                        case 4 :
                            scope.islapeople = false;
                            scope.shopinfo.go = true
                            break;
                    }

                    scope.shoppageinfo = data.err_msg;
                    if (scope.shopcon.sku_list) {
                        for (var o = 0, w = scope.shopcon.sku_list.length; o < w; o++) {    // 将所有的规格组合放入数组中
                            propertyList.push(scope.shopcon.sku_list[o].properties);
                        }
                    }

                    // 本团进度动画计算
                    var len = scope.shopcon.tuan_config.count / scope.shopcon.tuan_config.number * 100;
                    pressAnimate(len);

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

                    if (typeof $routeParams.grade != null || typeof $routeParams.grade != undefined) {

                        // 判断当前达标等级 从0开始
                        var grade = scope.detaildata.tuan_config.id;
                        for (var i = 0, j = scope.detaildata.tuan_config_list.length; i < j; i++) {
                            if (grade == scope.detaildata.tuan_config_list[i].id) {
                                index = i;
                                break;
                            }
                        }
                        scope.detailinfoGrade = scope.detaildata.tuan_config_list[index];    // 将当前团标准里信息赋值并在页面上使用
                    }

                    if (!scope.shopcon.property_list) {
                        scope.isstandarddata = false;
                    }

                    var timer = setInterval(function() {

                        if($(".lastpricedetailinfo").width() > 90) {
                            if($(document).width() - 90 < ($(".lastpricedetailinfo").width() + $(".lastpricedetailinfoa").width())) {
                                $(".lastpricedetailinfoa").css("margin-left",0);
                                $(".index-shop-info").css("height", "81px");
                            }
                        }
                        clearInterval(timer);
                    },200)

                } else{    // 常规错误
                    scope.dialogText = data.err_msg;
                    scope.dialogIsShow = true;
                    return false;
                }

            }).error(function () {
                scope.dialogText = "网络异常";
                scope.isloading = false;
                scope.dialogIsShow = true;
                return false;
            });


            // 最优开团为1  人缘开团为2
            if ($routeParams.type == 0) {
                scope.pressType = false;
            } else if ($routeParams.type == 1) {
                scope.pressType = true;
            }

            scope.removesNumber = function () {    // 选择数量时点击减号操作
                if (scope.confirmNumber > 1) {
                    scope.confirmNumber--;
                } else {
                    scope.dialogText = "最少选择一个！";
                    scope.dialogIsShow = true;
                }
                numdia(count);
            };

            scope.addNumber = function () {    // 加数量
                scope.confirmNumber++;
                numdia(count);
            };

            /**
             *
             * 若是超出了库存个数提示
             * @param count {Number} 当前库存个数
             *
             */
            function numdia(count) {
                if (count) {
                    if (scope.confirmNumber > count) {
                        scope.dialogText = "库存只有" + count + "个！";
                        scope.confirmNumber = count;
                        scope.dialogIsShow = true;
                    }
                }
            }

            scope.numblur = function () {    // 当input失去焦点判断值是否合法
                if (scope.confirmNumber >= 1) {
                    scope.confirmNumber = parseInt(scope.confirmNumber)
                } else {
                    scope.confirmNumber = 1;
                }
                numdia(count);
            };

            var isload = $interval(function () {
                if (scope.shop != "") {
                    scope.isloading = false;
                    $interval.cancel(isload);
                }
            }, 100);

            function pressAnimate(text) {
                $(".press-press-con").animate({
                    width: text + "%"
                }, 1000);
            }

            $(document).on("click", ".isclick", function () {    // 选择规格
                var docgui = 0,
                    docguiarr = "";

                scope.isbuypriceshow = true;
                scope.$apply(scope.isbuypriceshow);

                if ($(this).hasClass("clearing-span-err")) {
                    return;
                }

                var allid = "",    // 将所有的id拼起来放在这里
                    $isclick = $(".isclick"),
                    inputval = $(this).find("input").val(),
                    nowcode = [],
                    isSoldOut = true;

                $(this).addClass("clearing-span-active").siblings().removeClass("clearing-span-active");
                $(this).attr("data-isclick", 1).siblings().attr("data-isclick", 0);


                for (var c = 0; c < $isclick.length; c++) {
                    if ($isclick.eq(c).attr("data-isclick") == 1) {
                        docgui += 1;
                        docguiarr += $isclick.eq(c).find("input").val() + ";";
                    }
                }

                var clearingstandard = true;
                if ($(".clearing-standard").length == docgui) {    // 判断价格
                    for (var n = 0; n < scope.shopcon.sku_list.length; n++) {
                        if (docguiarr.slice(0, docguiarr.length - 1) == scope.shopcon.sku_list[n].properties) {
                            stock = scope.shopcon.sku_list[n].sku_id;
                            scope.price = scope.shopcon.sku_list[n].price;
                            count = scope.shopcon.sku_list[n].quantity;
                            scope.$apply(scope.price)
                            clearingstandard = false;
                        }
                    }

                    if (clearingstandard) {
                        scope.price = 0;
                        scope.$apply(scope.price)
                        clearingstandard = false;
                        isSoldOut = false;
                        for (var i = 0; i < $isclick.length; i++) {
                            $isclick.eq(i).removeClass("clearing-span-err");
                            $isclick.eq(i).removeClass("clearing-span-active");
                            $isclick.eq(i).attr("data-isclick", "0");
                        }
                        alert("非常抱歉，该商品已售罄，请您更换其他规格")
                        return;
                    }
                }

                if (isSoldOut) {
                    for (var i = 0; i < $isclick.length; i++) {    //所有选中的id
                        if ($isclick.eq(i).attr("data-isclick") == 1) {
                            var titid = $isclick.eq(i).parents(".clearing-standard").find(".clearing-standard-tit").attr("data-titid");
                            allid += titid + ":" + $isclick.eq(i).attr("data-shopid") + ";";
                        }
                    }

                    allid = allid.slice(0, allid.length - 1);

                    var propertyall = [];    // 所有规格id
                    for (var a = 0; a < scope.shopcon.property_list.length; a++) {
                        for (var s = 0; s < scope.shopcon.property_list[a].values.length; s++) {
                            propertyall.push(scope.shopcon.property_list[a].pid + ":" + scope.shopcon.property_list[a].values[s].vid);
                        }
                    }

                    var forshoptit = [];    // 所有能组合的规格id
                    for (var j = 0; j < scope.shopcon.sku_list.length; j++) {
                        forshoptit.push(scope.shopcon.sku_list[j].properties);
                    }


                    var nowupdown = [];    // 当前元素的同级所有ID
                    for (var i = 0; i < $(this).parents(".clearing-standard").siblings(".clearing-standard").find("input").length; i++) {
                        nowupdown.push($(this).parents(".clearing-standard").siblings(".clearing-standard").find("input").eq(i).val())
                    }

                    var okpar = publicfun.ishasparam(forshoptit, allid);
                    var okarr = publicfun.subArr(propertyall, okpar);    // 所有取消ID
                    var reokarr = [];    // 去除自己本身后所有取消的id

                    for (var j = 0; j < allid.split(";").length; j++) {
                        nowcode.push(allid[j].split(":")[0])
                    }

                    allid = allid.split(";");
                    for (var j = 0; j < propertyall.length; j++) {
                        for (var i = 0; i < allid.length; i++) {
                            if (propertyall[j].split(":")[0] == allid[i].split(":")[0]) {
                                reokarr.push(propertyall[j])
                            }
                        }
                    }

                    var delarr = publicfun.subArr(okarr, reokarr);    // 去重

                    if (allid.length != scope.shopcon.property_list.length) {
                        for (var h = 0; h < $isclick.length; h++) {
                            if ($isclick.eq(h).hasClass("clearing-span-err")) {
                                $isclick.eq(h).removeClass("clearing-span-err")
                            }
                        }
                    }

                    // 找到需要隐藏的元素隐藏
                    for (var j = 0; j < delarr.length; j++) {
                        for (var i = 0; i < $isclick.length; i++) {
                            if ($isclick.eq(i).find("input").val() == delarr[j]) {
                                $isclick.eq(i).removeClass("clearing-span-active")
                                $isclick.eq(i).addClass("clearing-span-err");
                                $isclick.eq(i).attr("data-isclick", 0);
                            }
                        }
                    }

                    var nowlen = scope.shopcon.property_list.length;
                    switch (nowlen) {
                        case 2:    // 只有两个规格时
                            if (allid.length == 2) {
                                var twoarr = [];    // 所有第一个可选的ID
                                var twoallarr = [];    // 所有第一个规格ID
                                for (var i = 0; i < forshoptit.length; i++) {
                                    if (forshoptit[i].indexOf(allid[1]) > -1) {
                                        twoarr.push(forshoptit[i].slice(0, allid[1].length + 1))
                                    }
                                }
                                for (var j = 0; j < scope.shopcon.property_list[0].values.length; j++) {
                                    twoallarr.push(scope.shopcon.property_list[0].pid + ":" + scope.shopcon.property_list[0].values[j].vid);
                                }
                                var okarr = publicfun.subArr(twoallarr, twoarr);
                                for (var i = 0; i < twoarr.length; i++) {
                                    for (var j = 0; j < $isclick.length; j++) {
                                        if (allid[0] != $isclick.eq(j).find("input").val() && $isclick.eq(j).find("input").val() == twoarr[i]) {
                                            $isclick.eq(j).removeClass("clearing-span-err");
                                        }

                                    }
                                }

                                if (twoarr.length = twoallarr.length) {
                                    return;
                                }

                                for (var i = 0; i < okarr.length; i++) {
                                    for (var j = 0; j < $isclick.length; j++) {
                                        if ($isclick.eq(j).find("input").val() == okarr[i]) {
                                            $isclick.eq(j).removeClass("clearing-span-active")
                                            $isclick.eq(j).addClass("clearing-span-err");
                                            $isclick.eq(j).attr("data-isclick", 0);
                                        }
                                    }
                                }
                            }
                            break;
                        case 3:
                            if (allid.length == 2) {
                                var twoarrid = [];

                                if (allid[0].split(":")[0] == scope.shopcon.property_list[0].pid && allid[1].split(":")[0] == scope.shopcon.property_list[2].pid) {    // 为真表示第一个和第三个
                                    for (var i = 0; i < forshoptit.length; i++) {
                                        if (forshoptit[i].indexOf(allid[0]) > -1 && forshoptit[i].indexOf(allid[1]) > -1) {
                                            twoarrid.push(forshoptit[i].split(";")[1]);
                                        }
                                    }
                                }
                                for (var i = 0; i < twoarrid.length; i++) {
                                    for (var j = 0; j < $isclick.length; j++) {
                                        if (twoarrid[i] == $isclick.eq(j).find("input").val()) {
                                            $isclick.eq(j).removeClass("clearing-span-err");
                                        }
                                    }
                                }
                            } else if (allid.length == 1) {
                                var onearrid = [];
                                if (allid[0].split(":")[0] == scope.shopcon.property_list[1].pid) {    // 为真表示第二个
                                    for (var i = 0; i < forshoptit.length; i++) {
                                        if (forshoptit[i].indexOf(allid[0]) > -1) {    // 如果能组合规格中有当前规格则将能组合规格中第一个和第三个元素存起来
                                            onearrid.push(forshoptit[i].split(";")[0]);
                                            onearrid.push(forshoptit[i].split(";")[2]);
                                        }
                                    }
                                }

                                for (var i = 0; i < onearrid.length; i++) {
                                    for (var j = 0; j < $isclick.length; j++) {
                                        if (onearrid[i] == $isclick.eq(j).find("input").val()) {
                                            $isclick.eq(j).removeClass("clearing-span-err");
                                        }
                                    }
                                }
                            } else if (allid.length == 3) {
                                var threearr = [];
                                var threearrid = [];
                                var okarr = publicfun.ishasparam(forshoptit, inputval);
                                var unarr = [];

                                for (var i = 0; i < okarr.length - 1; i++) {
                                    for (var j = 0; j < propertyall.length; j++) {
                                        if (okarr[i].split(":")[0] == propertyall[j].split(":")[0]) {
                                            threearr.push(propertyall[j]);
                                        }
                                    }
                                }
                                unarr = threearr.unique();
                                threearrid = publicfun.subArr(unarr, okarr);

                                for (var j = 0; j < $isclick.length; j++) {
                                    for (var i = 0; i < threearrid.length; i++) {
                                        if (threearrid[i] == $isclick.eq(j).find("input").val()) {
                                            $isclick.eq(j).addClass("clearing-span-err");
                                        }
                                    }
                                    for (var k = 0; k < okarr.length; k++) {
                                        if (okarr[i] == $isclick.eq(j).find("input").val()) {
                                            $isclick.eq(j).removeClass("clearing-span-err");
                                        }
                                    }
                                }
                            }
                            break;
                    }
                }
            });

            scope.next = function () {    // 提交订单事件
                var $detailinp = $(".custom-con-inp"),
                    $detailinparr = [];

                scope.isok = 0;
                for(var i = 0, j = $(".isclick").length; i < j; i++) {
                    if($(".isclick").eq(i).attr("data-isclick") == 1) {
                        nextisoknum = nextisoknum + 1;
                    }
                }

                // 将所有符合条件的放在一个数组里
                for (var k = 0; k < $detailinp.length; k++) {
                    for (var e = 0; e < $detailinp.eq(k).children().length; e++) {
                        if ($detailinp.eq(k).children().eq(e).attr("ng-show") == "true") {
                            $detailinparr.push($detailinp.eq(k).children().eq(e));
                        }
                    }
                }

                // 判断是否为空及是否合法
                for (var s = 0; s < $detailinparr.length; s++) {
                    if ($detailinparr[s].attr("datarequired") == 1) {
                        var dialogtext = $detailinparr[s].parents(".custom-con-inp").attr("datatype");
                        if ($detailinparr[s].val() == "") {
                            scope.dialogText = dialogtext + "不能为空";
                            scope.dialogIsShow = true;
                            return false;
                        } else if ($detailinparr[s].attr("data-detailtype") == "number") {
                            if (!publicfun.verification($detailinparr[s].val(), "number", dialogtext)) {
                                return false;
                            }
                        } else if ($detailinparr[s].attr("data-detailtype") == "email") {
                            if (!publicfun.verification($detailinparr[s].val(), "email", dialogtext)) {
                                return false;
                            }
                        } else if ($detailinparr[s].attr("data-detailtype") == "idno") {
                            if (!publicfun.verification($detailinparr[s].val(), "idno", dialogtext)) {
                                return false;
                            }
                        }
                    }
                }

                if (nextisoknum == nextisclicknum) {    // 点击下一步操作
                    // 下一步操作
                    var param = "&tuan_id=" + $routeParams.id + "&quantity=" + scope.confirmNumber + "&type=" + $routeParams.type;

                    if ($routeParams.token) {
                        param += "&token=" + $routeParams.token;
                    }
                    if ($routeParams.grade) {
                        param += "&item_id=" + $routeParams.grade;
                    }
                    if (stock) {
                        param += "&sku_id=" + stock;
                    }
                    if ($routeParams.colonelid > 0 && openClickpar === false) {
                        param += "&team_id=" + $routeParams.colonelid;
                    }

                    scope.isloading = true;
                    $http({
                        method: 'POST',
                        url: APIURL + 'index.php?g=Wap&m=PintuanReturn&a=payReturn' + param
                    }).success(function (data) {
                        scope.isloading = false;
                        if (data.err_code == 20000) {    // 没有登陆
                            // 跳到登录页面
                            alert("尚未登录");
                            window.location.href = data.err_msg + "?referer=" + encodeURIComponent(location.href);
                        } else if (data.err_code == 0) {    // 正常
                            window.location.href = data.err_msg.url;
                        } else {    // 常规错误
                            $(".dialog").css({
                                display : "block"
                            });
                            scope.dialogText = data.err_msg;
                            scope.dialogIsShow = true;
                            return false;
                        }
                    }).error(function (err) {
                        $(".dialog").css({
                            display : "block"
                        });
                        scope.isloading = false;
                        scope.dialogText = "网络异常";
                        scope.dialogIsShow = true;
                        return false;
                    });
                } else {
                    scope.dialogText = "请选择相应选项！";
                    scope.dialogIsShow = true;
                    return false;
                }
            };

        }
    }
}]);


// 判断循环是否结束
dire.directive('repeatFinish', ["lazyLoadService", "$timeout", function (lazyLoadService, $timeout) {
    return {
        link: function (scope, element, attr) {
            if (scope.$last == true) {
                $timeout(function () {
                    lazyLoadService.lazyLoad.init($("img"));
                }, 500);
            }
        }
    }
}]);


// 判断我的团购循环是否结束
dire.directive('buyRepeatFinish', ["lazyLoadService", "$timeout", function (lazyLoadService, $timeout) {
    return {
        link: function (scope, element, attr) {
            if (scope.$last == true) {
                if ($(".mybuy-shoplist").height() < ($(window).height() - 110)) {
                    $(".public-bottom").css({
                        position: "absolute",
                        bottom: "10px",
                        width: "100%"
                    })
                } else {
                    $(".public-bottom").css({
                        position: "initial",
                        bottom: "initial",
                        width: "100%"
                    })
                }
            }
        }
    }
}]);


dire.directive("carousel", [function () {    // 轮播
    return {
        templateUrl: "templates/public/carousel.html",
        restrict: "E",
        link: function (scope, element, attrs) {

        }
    }
}]);


// 判断轮播循环是否结束
dire.directive('carouselRepeatFinish', ["lazyLoadService", "$timeout", function (lazyLoadService, $timeout) {
    return {
        link: function (scope, element, attr) {
            if (scope.$last == true) {

                $timeout(function () {
                    var html = "";
                    var mySwiper = new Swiper('.swiper-container', {    // swiper插件初始化
                        pagination: '.swiper-pagination',
						loop: true,
						autoplay: 3000,
                    });
                }, 500);
            }
        }
    }
}]);


dire.directive("shopinfo", function () {    // 显示商品基本信息
    return {
        templateUrl: "templates/groupbuy/shopinfo.html",
        restrict: "E",
        link: function (scope, element, attrs) {
            scope.share = function () {
                scope.isshare = true;
            };
            scope.closeshare = function () {
                scope.isshare = false;
            }
        }
    }
});


dire.directive("tab", function () {    // tab切换功能
    return {
        templateUrl: "templates/public/tab.html",
        restrict: "E",
        link: function (scope, element, attrs, myctrl) {
            scope.shopInfoShow = true;
            $(".detailnavclick").on("click", function () {
                $(this).addClass("detail-nav-active").siblings().removeClass("detail-nav-active");
            });
        }
    }
});


dire.directive("dialog", function () {    // 弹框
    return {
        templateUrl: "templates/public/dialog.html",
        restrict: "EA",
        replace: true,
        link: function (scope, element, attrs) {
            scope.dialogIsShow = false;
        }
    }
});


dire.directive("loading", function () {    // 加载中
    return {
        templateUrl: "templates/public/loading.html",
        restrict: "EA",
        replace: true,
        link: function (scope, element, attrs) {
        }
    }
});


dire.directive("wxshare", function () {    // 分享功能
    return {
        templateUrl: "templates/public/wxshare.html",
        restrict: "EA",
        link: function (scope, element, attrs) {

            var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');
            ctx.moveTo(0, 4);
            ctx.strokeStyle = "#fff";
            ctx.lineWidth = 2;
            ctx.arcTo(100, 100, 190, 0, 200);
            ctx.stroke();

            scope.isshare = false;
        }
    }
});