<?php
/**
 * 変数の定義があれば記述
 */
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>
      <?php echo '虹妹ｐｒｐｒ'; ?>
    </title>
    <?php
//    echo $this->Html->meta('icon');
    
    echo $this->Html->css(array(
        'console_common',
        'console_detail'
    ));
    
    echo $this->Html->script(array(
        'jquery-1.11.3.min',
        'jquery-migrate-1.2.1.min'
    ));
    
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
  </head>
  <body>
    <div id="container_pop">
      <div id="content_pop">
        <?php echo $this->Flash->render(); ?>
        
        <?php echo $this->fetch('content'); ?>
      </div>
    </div>
  </body>
</html>