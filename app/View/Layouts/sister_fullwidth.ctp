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
	<title>
    <?php if (isset($this->request['id']) == true && isset($game_detail['Game']['title']) == true) {
        echo $this->element('common_tag', array('title' => 'short'));
        echo ' '.$game_detail['Game']['title'];
    } else {
        echo $this->element('common_tag', array('title' => 'normal'));
    } ?>
	</title>
	<?php
//  echo $this->Html->meta('icon');
  
  echo $this->Html->css(array(
      'common',
      'detail'
  ));
  
  echo $this->Html->script(array(
      'jquery-1.11.3.min',
      'jquery-migrate-1.2.1.min',
      'jquery-move_top'
  ));
  
  echo $this->fetch('meta');
  echo $this->fetch('css');
  echo $this->fetch('script');
  
  echo $this->Html->css(array( //スマホ用は後から上書き
      'mobile'
  ), array('media' => 'screen and (max-device-width: 480px)'));
  
  if (env('SERVER_ADDR') !== '127.0.0.1') {
      echo $this->element('google_analytics');
  }
  ?>
</head>
<body>
	<div id="container">
		<div id="header">
			<?php echo $this->element('sister_header'); ?>
      <?php echo $this->element('sister_menu'); ?>
		</div>
    
    <div id="content">
      <?php echo $this->element('sister_breadcrumb'); ?>
			<?php echo $this->Flash->render(); ?>
      
			<?php echo $this->fetch('content'); ?>
		</div>
    
    <div id="move_top">
      <?php echo $this->element('move_top'); ?>
    </div>
    
    <div id="footer">
      <?php echo $this->element('sister_footer'); ?>
		</div>
	</div>
</body>
</html>