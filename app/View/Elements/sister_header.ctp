<h1>
  <div class="head-title">
  <?php if (preg_match('#/console/#', $_SERVER['REQUEST_URI'])) {
    echo $this->Html->link('管理画面', '/console/');
  } else {
    echo '<a href="/"><span>虹(+0.5)の妹たちを</span> <span>ｐｒｐｒするサイト</span></a>';
  } ?>
  </div>
</h1>