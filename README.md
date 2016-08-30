Marketools.
===============================
A powerful wechat public account management platform!


特别说明
--------------------------------
如果从GIT新部署系统，首页会出现循环重定向访问不了的情况。
解决方法是：登入后台，设置一下前台模板即可。同时可以清除缓存。


```
# 一
以下目录是缓存目录或上传文件目录，禁止提交到GIT
已配置禁止这些文件的提交，详见根目录 .gitignore
部署时注意创建目录，以防程序没有自动创建这些目录而报错
cd "in your www workdir"
mkdir ./log
mkdir ./uploads
mkdir ./Conf/db.php
mkdir ./Conf/info.php
mkdir ./Conf/Home/config.php
mkdir ./Conf/logs
mkdir ./cms/cache
mkdir ./cms/upload
mkdir ./cms/smarty/templates_c
mkdir ./vifnnData

# 二
以下目录或文件是静态资源，文件较大，禁止提交到GIT
部署的时候请使用FTP手动上传的方式上传到服务器
/Conf/etc/dict.utf8.xdb
/vifnn/Lib/ORG/phpexcel
/cms/editor
/cms/smarty
/cms/smarty/templates/tpls
/cms/templates
/cms/manage
/tpl/User/default/common
/tpl/Home/agent_1/common
/tpl/Home/vifnn/common
/tpl/Home/vifnn2/common
/tpl/Home/vifnn3/common
/tpl/Home/vifnn4/common
/tpl/Home/vifnn5/common
/tpl/Home/vifnn6/common
/tpl/static

# 三
由于原版安装程序没有自动更改权限，所以需手动更新以下目录权限为 0777
cd "in your www workdir"
chmod -R 0777 ./tpl
chmod -R 0777 ./Conf
chmod -R 0777 ./vifnnData
chmod -R 0777 ./uploads
```
