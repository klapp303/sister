<?php
  $array_consoleMenu = array(
      1 => array(
          'title' => 'サイト管理',
          'menu' => array(
              1 => array('label' => 'お知らせを更新する', 'link' => '/console/information/'),
              2 => array('label' => 'セリフを登録する', 'link' => '/console/comment/'),
              3 => array('label' => 'バナーを設定する', 'link' => '/console/banner/'),
              4 => array('label' => 'リンクを設定する', 'link' => '/console/link/'),
              5 => array('label' => 'サイトを確認する', 'link' => '/', 'target' => '_blank')
          )
      ),
      2 => array(
          'title' => 'コンテンツ管理',
          'menu' => array(
              1 => array('label' => 'エロゲレビューを書く', 'link' => '/console/game/'),
              2 => array('label' => 'メーカーバナーの管理', 'link' => '/console/maker/'),
              3 => array('label' => '音楽レビューを書く', 'link' => '/console/#/'),
              4 => array('label' => '声優を追加する', 'link' => '/console/voice_add/')
          )
      ),
      3 => array(
          'title' => 'ブログ管理',
          'menu' => array(
              1 => array('label' => '日記を書く', 'link' => '/console/diary/'),
              2 => array('label' => '画像をアップする', 'link' => '/console/photo/'),
              3 => array('label' => 'ジャンルで分ける', 'link' => '/console/diary_genre/'),
              4 => array('label' => 'ブログを確認する', 'link' => '/diary/', 'target' => '_blank')
          )
      ),
      4 => array(
          'title' => 'オプション',
          'menu' => array(
              1 => array('label' => '管理者を追加する', 'link' => '/console/admin/'),
              2 => array('label' => 'ログアウト', 'link' => '/logout/')
          )
      )
  );
?>
<?php
  foreach ($voice_lists AS $voice_list) {
    array_push($array_consoleMenu[2]['menu'], array('label' => $voice_list['Voice']['nickname'].'情報の追加', 'link' => '/console/voice/'.$voice_list['Voice']['system_name']));
  }
?>
<ul class="menu-list">
  <?php foreach ($array_consoleMenu AS $key => $menu) { ?>
  <script>
  jQuery(function($) {
      var key = <?php echo json_encode($key, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
      $(function() {
          $('.js-menu_' + key).click(
              function(){
                  $('.js-hide_' + key).toggle();
              }
          );
      });
  });
  </script>
  <li class="js-menu_<?php echo $key; ?> cursor-def"><span class="menu-title"><?php echo $menu['title']; ?></span>
    <ul style="display: none;" class="menu-list-sub js-hide_<?php echo $key; ?>">
      <?php foreach ($menu['menu'] AS $sub_menu) { ?>
      <li><?php echo $this->Html->link($sub_menu['label'], $sub_menu['link'], (@$sub_menu['target'] == '_blank')? array('target' => '_blank'): ''); ?></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
</ul>