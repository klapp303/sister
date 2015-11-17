<h1>
  <span class="head-title">
  <?php if (preg_match('#/console/#', $_SERVER['REQUEST_URI'])) {
    echo $this->Html->link('管理画面', '/console/');
  } else {
    echo $this->Html->link('虹(+0.5)の妹たちをｐｒｐｒするサイト', '/');
  } ?>
  </span>
</h1>