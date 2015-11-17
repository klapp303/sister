<ul class="menu-list">
  <li><?php echo $this->Html->link('メニュー1', '/console/#/'); ?></li>
  <li><?php echo $this->Html->link('メニュー2', '/console/#/'); ?></li>
  <li><?php echo $this->Html->link('メニュー3', '/console/#/'); ?></li>
  <li class="js-menu_4 cursor-def"><span class="menu-title">ブログ管理</span>
    <ul class="menu-list-sub js-hide_4">
      <li><?php echo $this->Html->link('日記を書く', '/console/diary/'); ?></li>
      <li><?php echo $this->Html->link('ジャンルの管理', '#'); ?></li>
      <li><?php echo $this->Html->link('画像の管理', '#'); ?></li>
    </ul>
  </li>
</ul>