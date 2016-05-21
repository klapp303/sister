<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

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
    <?php if (isset($this->request['id']) == TRUE && isset($diary_lists[0]['Diary']['title']) == TRUE) {
      echo $this->element('common_tag', array('title' => 'short'));
      echo ' '.$diary_lists[0]['Diary']['title'];
    } else {
      echo $this->element('common_tag', array('title' => 'normal'));
    } ?>
	</title>
	<?php
//		echo $this->Html->meta('icon');

		echo $this->Html->css(array(
        'common',
        'detail'
    ));

    echo $this->Html->script(array(
        'jquery-1.11.3.min',
        'jquery-migrate-1.2.1.min',
        'sidemenu_fix'
    ));

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

    echo $this->Html->css(array( //スマホ用は後から上書き
        'mobile'
    ), array('media' => 'screen and (max-device-width: 480px)'));

    if (env('SERVER_ADDR') !== '127.0.0.1') {
      if (preg_match('#/console/#', $_SERVER['REQUEST_URI'])==0) {
        echo $this->element('google_analytics');
      }
    }
	?>
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
      <?php echo $this->element('genrelist'); ?>
		</div>
    </div>
    
    <div id="footer" class="cf">
      <?php echo $this->element('sister_footer'); ?>
		</div>
	</div>
</body>
</html>