<?php $birthday = $this->Session->read('birthday'); ?>
<?php if ($birthday && !preg_match('#/console/#', $_SERVER['REQUEST_URI'])) {
    echo $this->Html->image('../files/birthday/' . $header_image_name, array('class' => 'img-bd_header'));
} ?>
<h1>
  <div class="head-title">
  <?php if (preg_match('#/console/#', $_SERVER['REQUEST_URI'])): ?>
  <?php echo $this->Html->link('管理画面', '/console/'); ?>
  <?php else: ?>
    <?php if (@$header_title): ?>
    <a href="/"><span><?php echo $header_title; ?></span></a>
    <?php else: ?>
    <a href="/"><span>虹(+0.5)の妹たちを</span> <span>ｐｒｐｒするサイト</span></a>
    <?php endif; ?>
  <?php endif; ?>
  </div>
</h1>