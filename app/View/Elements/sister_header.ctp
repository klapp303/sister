<h1>
  <div class="head-title">
  <?php if (preg_match('#/console/#', $_SERVER['REQUEST_URI'])) { ?>
    <?php echo $this->Html->link('管理画面', '/console/'); ?>
  <?php } else { ?>
    <a href="/"><span>虹(+0.5)の妹たちを</span> <span>ｐｒｐｒするサイト</span></a>
  <?php } ?>
  </div>
</h1>