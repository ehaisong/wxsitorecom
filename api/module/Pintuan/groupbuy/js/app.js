/*
 *
 * 配置文件、主入口文件
 *
 *  @author 赵仁杰
 *  @since 2016-1-27 14:00
 *
 * */
"use strict";
var app = angular.module("myapp",['ngRoute', "shop.controller", "shop.services", "shop.directive", "shop.filter", "ngAnimate", "ngSanitize", "mobiscroll-datetime"]);
app.config(["$routeProvider", function($routeProvider) {

    $routeProvider.
    //when('/main/:id', {
    //    templateUrl : "templates/groupbuy/indextem.html",
    //    controller: 'indexCtrl'
    //}).
    when('/details/:id/:token', {
        templateUrl: 'templates/groupbuy/detail.html',
        controller: 'detailCtrl'
    }).
    when('/detailinfo/:id/:token/:type/:grade/:colonelid', {
        templateUrl: 'templates/groupbuy/detailinfo.html',
        controller: 'detailinfoCtrl'
    }).
    when('/flow/:id/:token', {
        template: '<shop-title navtitle="{{navtitle}}" back="back()"></shop-title>' +
        '<div class="flow"><img src="images/flow.jpg" alt=""></div>',
        controller: 'flowCtrl'
    }).when('/mybuy/:token', {
        templateUrl: 'templates/groupbuy/mybuy.html',
        controller: 'mybuyctrl'
    }).when('/order/:id/:token', {
        templateUrl: 'templates/groupbuy/ordertel.html',
        controller: 'orderctrl'
    })
}]);

