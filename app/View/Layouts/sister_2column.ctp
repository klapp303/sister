<?php
/**
 * 変数の定義があれば記述
 */
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
  <head>
    <?php echo $this->Html->charset(); ?>
    <?php echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1')); ?>
    <?php echo $this->element('sister_metatag', array('title' => 'diary', 'ogp' => true)); ?>
    <?php
//    echo $this->Html->meta('icon');
    
    echo $this->Html->css(array(
        'common',
        'detail'
    ));
    
    $birthday = $this->Session->read('birthday');
    if ($birthday) {
        echo $this->Html->css(array(
            'birthday'
        ));
        //バースデー仕様のcssは動的なので直書き
        echo '<style type="text/css"><!--';
        if (@$thema_color) {
            echo '
                #container {
                  background-color: #' . $thema_color . ';
                }
                #content {
                  background-color: #' . $thema_color . ';
                }
                #content_main {
                  background-color: #' . $thema_color . ';
                }
            ';
        }
        if (@$shadow_color) {
            echo '
                #container {
                  box-shadow: -1px 1px 10px #' . $shadow_color . ';
                }
            ';
        }
        if (@$strong_color) {
            echo '
                .flash-msg_' . $birthday . ' {
                  background: #' . $strong_color . ';
                }
            ';
        }
        if (@$bg_color) {
            echo '
                body {
                  background: #' . $bg_color . ';
                }
            ';
        }
        echo '--></style>';
    }
    
    echo $this->Html->script(array(
        'jquery-1.11.3.min',
        'jquery-migrate-1.2.1.min',
        'sidemenu_fix.min',
        'jquery-move_top.min'
    ));
    
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    
    echo $this->Html->css(array( //スマホ用は後から上書き
        'mobile'
    ), array('media' => 'screen and (max-device-width: 480px)'));
    
    if (env('SERVER_ADDR') !== '127.0.0.1') {
        if (preg_match('#/console/#', $_SERVER['REQUEST_URI']) == 0) {
            echo $this->element('google_analytics');
        }
    }
    ?>
    <!--<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
  </head>
  <body>
    <div id="container">
      <div id="header">
        <?php echo $this->element('sister_header'); ?>
        <?php echo $this->element('sister_menu'); ?>
      </div>
      
      <div id="content_main">
        <?php echo $this->element('sister_breadcrumb'); ?>
        <?php echo $this->Flash->render(); ?>
        
        <?php echo $this->fetch('content'); ?>
      </div>
      
      <div id="content_side" class="pc">
        <div id="menu_side">
          <?php echo $this->element('searchbox'); ?>
          <?php echo $this->element('calendar'); ?>
          <?php echo $this->element('diarylist'); ?>
          <?php echo $this->element('genrelist'); ?>
          <!-- Google AdSense Start サイドバー -->
<!--          <ins class="adsbygoogle"
               style="display:block"
               data-ad-client="ca-pub-8547890407483708"
               data-ad-slot="6375367207"
               data-ad-format="rectangle"></ins>
          <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
          </script>-->
          <!-- Google AdSense End -->
        </div>
      </div>
      
      <div id="move_top">
        <?php echo $this->element('move_top'); ?>
      </div>
      
      <div id="footer" class="cf">
        <?php echo $this->element('sister_footer'); ?>
      </div>
    </div>
  </body>
</html>