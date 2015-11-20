<ul class="menu-list">
  <li class="js-menu_1 cursor-def"><span class="menu-title">サイト管理</span>
    <ul class="menu-list-sub js-hide_1">
      <li><?php echo $this->Html->link('お知らせを更新する', '/console/information/'); ?></li>
      <li><?php echo $this->Html->link('バナーを設定する', '/console/banner/'); ?></li>
      <li><?php echo $this->Html->link('リンクを設定する', '/console/#/'); ?></li>
    </ul>
  </li>
  <li class="js-menu_2 cursor-def"><span class="menu-title">コンテンツ管理</span>
    <ul class="menu-list-sub js-hide_2">
      <li><?php echo $this->Html->link('メニュー1', '/console/#/'); ?></li>
      <li><?php echo $this->Html->link('メニュー2', '/console/#/'); ?></li>
      <li><?php echo $this->Html->link('メニュー3', '/console/#/'); ?></li>
    </ul>
  </li>
  <li class="js-menu_3 cursor-def"><span class="menu-title">ブログ管理</span>
    <ul class="menu-list-sub js-hide_3">
      <li><?php echo $this->Html->link('日記を書く', '/console/diary/'); ?></li>
      <li><?php echo $this->Html->link('画像をアップする', '/console/photo/'); ?></li>
      <li><?php echo $this->Html->link('ジャンルで分ける', '/console/diary_genre/'); ?></li>
      <li><?php echo $this->Html->link('ブログを確認する', '/diary/', array('target' => '_bllank')); ?></li>
    </ul>
  </li>
  <li class="js-menu_4 cursor-def"><span class="menu-title">オプション</span>
    <ul class="menu-list-sub js-hide_4">
      <li><?php echo $this->Html->link('管理者の追加', '/console/#/'); ?></li>
      <li><?php echo $this->Html->link('ログアウト', '/console/#/'); ?></li>
    </ul>
  </li>
</ul>