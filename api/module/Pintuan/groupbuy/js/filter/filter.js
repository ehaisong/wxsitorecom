/*
 *
 * 过滤器
 *
 *  @author 赵仁杰
 *  @since 2016-2-16 17:47
 *
 * */

"use strict";
var filter = angular.module("shop.filter",[]);

filter.filter('numFilter', [function() {    // 过滤几级团
    return function(input) {

        var num = parseInt(input + 1);

        if(num <= 10) {
            num = num + "";
            return chinaNum(num)
        }else if(num > 10 && num <= 19){
            num = num + "";
            return "十"+chinaNum(num.substr(1))
        }else if(num < 99 && num > 19){
            if(num % 10 == 0) {
                num = num + "";
                return chinaNum(num.substr(0,1))+"十";
            }
            num = num + "";
            return chinaNum(num.substr(0,1))+ "十" +chinaNum(num.substr(1,2));
        }

        function chinaNum(num) {
            switch (num) {
                case "1":
                    return "一";
                    break;
                case "2":
                    return "二";
                    break;
                case "3":
                    return "三";
                    break;
                case "4":
                    return "四";
                    break;
                case "5":
                    return "五";
                    break;
                case "6":
                    return "六";
                    break;
                case "7":
                    return "七";
                    break;
                case "8":
                    return "八";
                    break;
                case "9":
                    return "九";
                    break;
                case "10":
                    return "十";
                    break;
            }
        }

    };
}]);


filter.filter('typeFilter',[function() {    // 判断是什么类型的团购
    return function (input) {
        if(input == 0) {
            return  "人缘开团";
        }else{
            return "最优开团";
        }
    }
}]);

