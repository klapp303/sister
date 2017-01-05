<?php $birthday = $this->Session->read('birthday'); ?>
<?php if ($birthday && !preg_match('#/console/#', $_SERVER['REQUEST_URI'])) {
    echo $this->Html->image('../files/birthday/' . $footer_image_name, array('class' => 'img-bd_footer'));
} ?>
<p class="foot-txt">
  <?php if (@$footer_title) { ?>
    <span>copyright&#169 2015-2017</span> <?php echo $this->Html->link($footer_title, '/console/', array('target' => '_blank', 'class' => 'pc')); ?>
    <span class="mobile"><?php echo $footer_title; ?></span>
  <?php } else { ?>
    <span>copyright&#169 2015-2017</span> <?php echo $this->Html->link('虹妹ｐｒｐｒ推進委員会', '/console/', array('target' => '_blank', 'class' => 'pc')); ?>
    <span class="mobile">虹妹ｐｒｐｒ推進委員会</span>
  <?php } ?>
</p>