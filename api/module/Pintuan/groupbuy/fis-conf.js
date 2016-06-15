/**
 * Created by win7 on 2016/2/3.
 */


fis.match('*.{js,css }', {
    release: '/static/$0'
});

// 启用 fis-spriter-csssprites 插件
fis.match('::package', {
    spriter: fis.plugin('csssprites')
});


/*
 *
 * 上线去掉
 *
 * */
fis.match('js/angular/*.js', {
    // fis-optimizer-uglify-js 插件进行压缩，已内置
    useHash: true,
    optimizer: fis.plugin('uglify-js'),
});



fis.match('js/app.js', {
    packTo: 'static/js/start.js',
});

fis.match('js/controller/*.js', {
    packTo: 'static/js/start.js'
});

fis.match('js/directive/*.js', {
    packTo: 'static/js/start.js'
});

fis.match('js/service/*.js', {
    packTo: 'static/js/start.js'
});



/*
*
* 线上使用
*
* */
fis.match('js/controller/*.js', {
    // fis-optimizer-uglify-js 插件进行压缩，已内置
    useHash: true,
    optimizer: fis.plugin('uglify-js'),
});

fis.match('js/directive/*.js', {
    // fis-optimizer-uglify-js 插件进行压缩，已内置
    useHash: true,
    optimizer: fis.plugin('uglify-js'),
});

fis.match('js/service/*.js', {
    // fis-optimizer-uglify-js 插件进行压缩，已内置
    useHash: true,
    optimizer: fis.plugin('uglify-js'),
});

fis.match('js/app.js', {
    // fis-optimizer-uglify-js 插件进行压缩，已内置
    useHash: true,
    optimizer: fis.plugin('uglify-js'),
});

fis.match('js/swiper.js', {
    // fis-optimizer-uglify-js 插件进行压缩，已内置
    useHash: true,
    optimizer: fis.plugin('uglify-js'),
});






// 对 CSS 进行图片合并
fis.match('*.css', {
    // 给匹配到的文件分配属性 `useSprite`
    useHash: true,
    useSprite: true,
    optimizer: fis.plugin('clean-css')
});


