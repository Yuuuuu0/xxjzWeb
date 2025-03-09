<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class="no-js">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?php echo (C("XXJZ_DESCRIPTION")); ?>">
  <meta name="keywords" content="<?php echo (C("XXJZ_KEYWORDS")); ?>">
  <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  
  <title><?php echo (C("XXJZ_TITLE")); ?></title>
  
  <!--<script src="/Public/Home/js/pace.min.js"></script>-->
  <!--<link rel="stylesheet" href="/Public/Home/css/pace/pace-theme-center-circle.css"/>-->
  <script src="//cdn.bootcss.com/pace/1.0.2/pace.min.js"></script>
  <link href="//cdn.bootcss.com/pace/1.0.2/themes/blue/pace-theme-minimal.css" rel="stylesheet">

  <!-- Set render engine for 360 browser -->
  <meta name="renderer" content="webkit">

  <!-- No Baidu Siteapp-->
  <meta http-equiv="Cache-Control" content="no-siteapp"/>

  <link rel="icon" type="image/png" href="/Public/Home/i/favicon.png">

  <!-- Add to homescreen for Chrome on Android -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="icon" sizes="192x192" href="/Public/Home/i/app-icon72x72@2x.png">

  <!-- Add to homescreen for Safari on iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="<?php echo (C("XXJZ_TITLE")); ?>"/>
  <link rel="apple-touch-icon-precomposed" href="/Public/Home/i/app-icon72x72@2x.png">

  <!-- Tile icon for Win8 (144x144 + tile color) -->
  <meta name="msapplication-TileImage" content="/Public/Home/i/app-icon72x72@2x.png">
  <meta name="msapplication-TileColor" content="#0e90d2">

  <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
  <!--
  <link rel="canonical" href="https://jz.xxgzs.org/">
  -->
  
  <link rel="stylesheet" href="/Public/Home/css/amazeui.min.css">
  <link rel="stylesheet" href="/Public/Home/css/app.css">
</head>

<style>
    .am-tabs-secondary .am-tabs-nav{line-height:40px;background-color:#eee}.am-tabs-secondary .am-tabs-nav a{color:#222;line-height:42px}.am-tabs-secondary .am-tabs-nav>.am-active a{background-color:#3bb4f2;color:#fff}
</style>

<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，<?php echo (C("XXJZ_TITLE")); ?>暂不支持。 请升级浏览器以获得更好的体验！</p>
<![endif]-->



  <header data-am-widget="header" style="background-color:#19a7f0"
          class="am-header am-header-default am-header-fixed">
      <div class="am-header-left am-header-nav">
          <a href="/index.php?s=/Home/User/index" class="">

                <i class="am-header-icon am-icon-user"></i>
          </a>
      </div>

      <h1 data-am-widget="gotop" class="am-header-title">
          <a href="#top" class="">
            <?php echo (C("XXJZ_TITLE")); ?>
          </a>
      </h1>
      
    <div class="am-header-right am-header-nav" data-am-widget="menu" data-am-menu-collapse>
      <div class="am-dropdown" data-am-dropdown="{boundary: '.am-topbar'}">
        <ul class="am-dropdown-toggle">
            <i class="am-header-icon am-icon-reorder" data-am-dropdown-toggle> </i>
        </ul>
        <ul class="am-dropdown-content">
            <li class=""><a href="/index.php?s=/Home/Chart/index" class="am-icon-pie-chart" > &nbsp;年度统计</a></li>
            <li class="am-divider"></li>
            <li class=""><a href="/index.php?s=/Home/Funds/index" class="am-icon-credit-card"> &nbsp;资金账户</a></li>
            <li class="am-divider"></li>            
            <li class=""><a href="/index.php?s=/Home/Class/index" class="am-icon-list-ul"> &nbsp;分类管理</a></li>
            <li class="am-divider"></li>
            <li class=""><a href="/index.php?s=/Home/User/index" class="am-icon-cog" > &nbsp;设置选项</a></li>
            <li class="am-divider"></li>
            <?php if($PushAdminUrl): ?><!-- <li class=""><a href="/index.php?s=<?php echo ($PushAdminUrl); ?>" class="am-icon-chain" > &nbsp;推送管理</a></li>
              <li class="am-divider"></li> --><?php endif; ?>
            <li class=""><a href="/index.php?s=/Home/Login/logout" class="am-icon-sign-out"> &nbsp;退出登录</a></li>
        </ul>
      </div>
    </div>    
  </header>


    <?php if($refresh == true): ?><script language="JavaScript">
            location=location;
        </script><?php endif; ?>

    <style type="text/css">
      .zoomImage{
        width:100%;
        height:0;
        padding-bottom: 100%;
        overflow:hidden;
        background-position: center center;
        background-repeat: no-repeat;
        -webkit-background-size:cover;
        -moz-background-size:cover;
        background-size:cover;
      }
    </style>

    <form class="am-form am-form-horizontal" enctype="multipart/form-data" method="post" data-am-validator="H5validation:false">
        <fieldset>
            <legend>编辑</legend>
                <div class="am-input-group">
                    <span class="am-input-group-label"><b>金额</b></span>
                    <input type="number" class="am-form-field" id="text-money" name="edit_money" step=0.01 value="<?php echo ($ShowData["money"]); ?>">
                    <p></p>
                </div>
                <div><p></p></div>
                <?php if(count($FundsData) > 1): ?><div class="am-input-group">
                      <span class="am-input-group-label"><b>账户</b></span>
                      <select id="select-funds" class="am-form-field" name="edit_funds">
                      <?php if(is_array($FundsData)): foreach($FundsData as $key=>$data): if($data['id'] == $DbFunds): ?><option value="<?php echo ($data["id"]); ?>" selected><?php echo ($data["name"]); ?></option>
                          <?php else: ?>
                              <option value="<?php echo ($data["id"]); ?>"><?php echo ($data["name"]); ?></option><?php endif; endforeach; endif; ?>
                      </select>
                      <span class="am-form-caret"></span>
                  </div>
                  <div><p></p></div><?php endif; ?>
                <div class="am-input-group">
                    <span class="am-input-group-label"><b>类别</b></span>
                    <select id="select-type" class="am-form-field" name="edit_type" onchange="ChangClass(<?php echo ($MoneyClass); ?>,'select-type','select-class')">
                        <?php if($ShowData['type'] == '收入'): ?><option value="1">收入</option>
                            <option value="2">支出</option>
                        <?php else: ?>
                            <option value="2">支出</option>
                            <option value="1">收入</option><?php endif; ?>
                    </select>
                </div>
                <div><p></p></div>
                <div class="am-input-group">
                    <span class="am-input-group-label"><b>分类</b></span>
                    <select id="select-class" class="am-form-field" name="edit_class">
                    <option value="<?php echo ($ShowData["classid"]); ?>"><?php echo ($ShowData["class"]); ?></option>
                    <?php if(is_array($DbClass)): foreach($DbClass as $key=>$ClassName): if($key != $ShowData['classid']): ?><option value="<?php echo ($key); ?>"><?php echo ($ClassName); ?></option><?php endif; endforeach; endif; ?>
                    </select>
                    <span class="am-form-caret"></span>
                </div>
                <div><p></p></div>
                <div class="am-input-group">
                    <span class="am-input-group-label"><b>备注</b></span>
                    <input type="text" class="am-form-field" id="text-mark" name="edit_mark" value="<?php echo ($ShowData["mark"]); ?>">
                    <span class="am-input-group-btn am-form-file">
                        <button type="button" class="am-btn am-btn-default" id="btn-upload-image" data-am-loading="{spinner: 'circle-o-notch', loadingText: '上传中...', resetText: '上传完成'}">
                            <i class="am-icon-cloud-upload"></i> 上传图片
                        </button>
                        <input id="doc-form-file" type="file" name="edit_photo[]" accept=".<?php echo (implode(', .', C("IMAGE_EXT"))); ?>" multiple />
                    </span>
                </div>
                <div><p></p></div>
                <div class="am-input-group">
                    <span class="am-input-group-label"><b>时间</b></span>
                    <input type="date" class="am-form-field" id="text-time" name="edit_time" value="<?php echo date('Y-m-d',$ShowData['time']); ?>" />
                </div>

                <?php if($ImageData != false ): ?><p><hr data-am-widget="divider" style="" class="am-divider am-divider-dashed" /></p>
                <ul data-am-widget="gallery" class="am-gallery am-avg-sm-3
  am-avg-md-4 am-avg-lg-5 am-gallery-bordered" data-am-gallery="{pureview:{target: 'a'}}" id="doc-image-list" >
                    <?php if(is_array($ImageData)): foreach($ImageData as $key=>$image): ?><li id="doc-image-<?php echo ($image["id"]); ?>">
                        <a href="javascript:void(0);" data-id="<?php echo ($image["id"]); ?>" data-acid="<?php echo ($acid); ?>" class="am-close am-close-alt am-close-spin am-icon-times" style="position: absolute; margin: 5px;"></a>
                        <div class="am-gallery-item">
                          <a href="<?php echo ($image["url"]); ?>">
                            <!-- <img src="<?php echo ($image["url"]); ?>" alt="<?php echo ($image["name"]); ?>" style="height: 20vw;" /> -->
                            <div class="zoomImage" style="background-image:url(<?php echo ($image["url"]); ?>)"></div>
                            <h3 class="am-gallery-title">
                                <?php echo ($image["name"]); ?>
                            </h3>
                            <div class="am-gallery-desc">
                                <?php echo (date("Y-m-d",$image["time"])); ?>
                            </div>
                          </a>
                        </div>
                      </li><?php endforeach; endif; ?>
                </ul><?php endif; ?>

                <p><hr data-am-widget="divider" style="" class="am-divider am-divider-dashed" /></p>
                <p><input type="submit" class="am-btn am-btn-secondary am-btn-block" name="edit_submit" value="修改"></p>
                
                <p><a href="javascript:void(0);" class="am-btn am-btn-block am-btn-danger" 
                    onclick="isDelete('/index.php?s=/Home/Edit/del/id/<?php echo ($ShowData['id']); ?>')" >删除</a></p>
                
                <p><a href="<?php echo ($refURL); ?>" class="am-btn am-btn-block am-btn-default">返回</a></p>
                
        </fieldset>
    </form>
    <div class="am-modal am-modal-confirm" tabindex="-1" id="delete-confirm">
      <div class="am-modal-dialog">
        <div class="am-modal-hd">删除图片</div>
        <div class="am-modal-bd">
          您确定要删除这张图片附件吗？
        </div>
        <div class="am-modal-footer">
          <span class="am-modal-btn" data-am-modal-confirm>删除</span>
          <span class="am-modal-btn" data-am-modal-cancel>取消</span>
        </div>
      </div>
    </div>
    <div style="display: none;">
        <button type="button" class="am-btn am-btn-warning" id="doc-confirm-toggle">Close</button>
    </div>

  <div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default" style="height: 60px;    line-height: 60px;">
      <ul class="am-navbar-nav am-cf am-avg-sm-4" style="background-color:#19a7f0">
          <li >
            <a href="/index.php?s=/Home/Index/index" class="">
                <span class="am-icon-home am-icon-sm"></span>
                <span class="am-navbar-label">主页</span>
            </a>
          </li>
          <li >
            <a href="/index.php?s=/Home/Add/index" class="">
                <span class="am-icon-plus-square am-icon-sm"></span>
                <span class="am-navbar-label">记账</span>
            </a>
          </li>
          <li >
            <a href="/index.php?s=/Home/Find/index" class="">
                <span class="am-icon-search am-icon-sm"></span>
                <span class="am-navbar-label">查询</span>
            </a>
          </li>
      </ul>
  </div>


<footer class="am-margin-top">
<!--   <hr/>
  <p class="am-text-center">
    <small>由小歆工作室提供</small>
  </p> -->
</footer>
<!-- 以上页面内容 开发时删除 -->

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/Public/Home/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->

<script src="/Public/Home/js/function.min.js"></script>

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="/Public/Home/js/jquery.min.js"></script>
<!--<![endif]-->
<script src="/Public/Home/js/amazeui.min.js"></script>

<script type="text/javascript">
$(function(){
    var div_money_arr = document.getElementsByClassName('money-format');
    for (var i = 0; i < div_money_arr.length; i++) {
        div_money_arr[i].innerText= number_format(div_money_arr[i].innerText,<?php echo (C("MONEY_FORMAT_DECIMALS")); ?>,'<?php echo (C("MONEY_FORMAT_POINT")); ?>','<?php echo (C("MONEY_FORMAT_THOUSANDS")); ?>');
    }
});
</script>


    <script>
      const IMAGE_SIZE = <?php echo (C("IMAGE_SIZE")); ?>;
      const IMAGE_COUNT = <?php echo (C("IMAGE_COUNT")); ?>;
      const IMAGE_EXT = "<?php echo (implode(',', C("IMAGE_EXT"))); ?>";
      function GetImageLength() {
          return $('ul.am-gallery').children('li').length;
      }

      function DeleteImage(acid, id, callback) {
          $.post("/index.php?s=/Home/Edit/deleteImage",{acid:acid,id:id},function(ret){
            callback(ret);
          });
      }

      function ResetFormFile() {
          $('#doc-form-file').val('');
      }

      function ResetHtml() {
          var url = location.href;
          var mark = location.hash;
          if (mark != '') {
            url = url.replace(mark, '')
          }
          location.href = url + "#doc-form-file"; 
          location.reload(true);
          // location=location;
      }

      function UploadImage(files, callback) {
        $('#btn-upload-image').button('loading');
        var formData = new FormData()
        formData.append("acid", <?php echo ($acid); ?>);
        $.each(files, function() {
            formData.append("file[]", this);
        });
        $.ajax({
            url: '/index.php?s=/Home/Add/upload',
            type: 'POST',
            cache: false,
            data: formData,
            processData: false,
            contentType: false,
            success: function (json) {
                //上传成功的处理
                console.log(json);
                $('#btn-upload-image').button('reset');
                if (callback) {
                    callback(json);
                }
            }
        });
      }

      $(function() {
        $('#doc-form-file').on('change', function() {
          var fileNames = '';
          var fileSize = 0;
          if (this.files.length + GetImageLength() > IMAGE_COUNT) {
            alert("每个账单图片数量不能超过" + IMAGE_COUNT + "个，请筛选后重新上传。");
            ResetFormFile();
            return;
          }
          $.each(this.files, function() {
            fileNames += '<span class="am-badge">' + this.name + '</span> ';
            fileSize += this.size;
          });
          if (fileSize > IMAGE_SIZE) {
            alert("单次上传文件体积不能超过" + (IMAGE_SIZE/1024/1024) + "MB，请压缩后重新上传。");
            ResetFormFile();
            return;
          }
          UploadImage(this.files, function(ret) {
            ResetHtml();
          });
        });

        $('#doc-image-list').find('.am-close').add('#doc-confirm-toggle').
          on('click', function() {
          $('#delete-confirm').modal({
            relatedTarget: this,
            onConfirm: function(options) {
              var $dataset = $(this.relatedTarget).prev('a').context.dataset;
              DeleteImage($dataset.acid, $dataset.id, function(ret){
                ResetHtml();
              });
            },
            onCancel: function() {
              console.log($(this.relatedTarget));
            }
          });
        });

      });

    </script>


</body>
</html>