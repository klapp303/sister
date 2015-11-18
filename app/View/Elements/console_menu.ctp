<ul class="menu-list">
  <li><?php echo $this->Html->link('メニュー1', '/console/#/'); ?></li>
  <li><?php echo $this->Html->link('メニュー2', '/console/#/'); ?></li>
  <li><?php echo $this->Html->link('メニュー3', '/console/#/'); ?></li>
  <li class="js-menu_4 cursor-def"><span class="menu-title">ブログ管理</span>
    <ul class="menu-list-sub js-hide_4">
      <li><?php echo $this->Html->link('日記を書く', '/console/diary/'); ?></li>
      <li><?php echo $this->Html->link('画像をアップする', '#'); ?></li>
      <li><?php echo $this->Html->link('ジャンルで分ける', '#'); ?></li>
      <li><?php echo $this->Html->link('ブログを確認する', '/diary/', array('target' => '_bllank')); ?></li>
    </ul>
  </li>
</ul>