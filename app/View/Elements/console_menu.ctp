<script>
jQuery(function($) {
    $(function() {
      $('.js-menu_1').click(function(){
        $('.js-hide_1').toggle();
      });
    });
    $(function() {
      $('.js-menu_2').click(function(){
        $('.js-hide_2').toggle();
      });
    });
    $(function() {
      $('.js-menu_3').click(function(){
        $('.js-hide_3').toggle();
      });
    });
    $(function() {
      $('.js-menu_4').click(function(){
        $('.js-hide_4').toggle();
      });
    });
});
</script>
<ul class="menu-list">
  <li class="js-menu_1 cursor-def"><span class="menu-title">サイト管理</span>
    <ul class="menu-list-sub js-hide_1">
      <li><?php echo $this->Html->link('お知らせを更新する', '/console/information/'); ?></li>
      <li><?php echo $this->Html->link('セリフを登録する', '/console/comment/'); ?></li>
      <li><?php echo $this->Html->link('バナーを設定する', '/console/banner/'); ?></li>
      <li><?php echo $this->Html->link('リンクを設定する', '/console/link/'); ?></li>
      <li><?php echo $this->Html->link('サイトを確認する', '/', array('target' => '_blank')); ?></li>
    </ul>
  </li>
  <li class="js-menu_2 cursor-def"><span class="menu-title">コンテンツ管理</span>
    <ul class="menu-list-sub js-hide_2">
      <li><?php echo $this->Html->link('エロゲレビューを書く', '/console/game/'); ?></li>
      <li><?php echo $this->Html->link('メーカーバナーの管理', '/console/maker/'); ?></li>
      <li><?php echo $this->Html->link('音楽レビューを書く', '/console/#/'); ?></li>
      <?php foreach ($voice_lists AS $voice_list) { ?>
      <li><?php echo $this->Html->link($voice_list['Voice']['nickname'].'情報の追加', '/console/voice/'.$voice_list['Voice']['system_name']); ?></li>
      <?php } ?>
      <li><?php echo $this->Html->link('声優を追加する', '/console/voice_add/'); ?></li>
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
      <li><?php echo $this->Html->link('管理者を追加する', '/console/admin/'); ?></li>
      <li><?php echo $this->Html->link('ログアウト', '/logout/'); ?></li>
    </ul>
  </li>
</ul>