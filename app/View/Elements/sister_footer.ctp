<?php $birthday = $this->Session->read('birthday'); ?>
<?php if ($birthday) {
    echo $this->Html->image('../files/birthday/cake.png', array('class' => 'img-bd_footer'));
} ?>
<p class="foot-txt">
  <span>copyright&#169 2015-2016</span> <?php echo $this->Html->link('虹妹ｐｒｐｒ推進委員会', '/console/', array('target' => '_blank', 'class' => 'pc')); ?>
  <span class="mobile">虹妹ｐｒｐｒ推進委員会</span>
</p>