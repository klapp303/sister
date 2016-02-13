<?php
  $array_menu = array(
      1 => array(
          'title' => 'ご案内',
          'link' => '#',
          'menu' => array(
              1 => array('label' => 'このサイトについて', 'link' => '/information/'),
              2 => array('label' => '管理人について', 'link' => '/author/'),
              3 => array('label' => 'リンク', 'link' => '/link/')
          )
      ),
      2 => array(
          'title' => 'ゲーム etc',
          'link' => '#',
          'menu' => array(
              1 => array('label' => 'エロゲレビュー', 'link' => '/game/erg/'),
              2 => array('label' => 'モンハンメモ', 'link' => '/game/mh/')
          )
      ),
      3 => array(
          'title' => '音楽 etc',
          'link' => '#',
          'menu' => array(
              //1 => array('label' => '音楽レビュー', 'link' => '#'),
              //2 => array('label' => '作曲者からみる', 'link' => '#')
          )
      ),
      4 => array(
          'title' => '声優 etc',
          'link' => '#',
          'menu' => array(
              1 => array('label' => 'おとちん', 'link' => '/voice/otochin/')
          )
      ),
      5 => array(
          'title' => 'ブログ',
          'link' => '/diary/',
          'menu' => array()
      )
  );
?>
<ul class="menu-list">
  <?php $i = 1; ?>
  <?php foreach ($array_menu AS $menu) { ?>
  <?php if ($menu['menu']) { ?>
    <script>
    jQuery(function($) {
        var i = <?php echo json_encode($i, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        $('.js-menu_' + i).hover(
                function() {
                $('.js-hide_' + i).show();
                },
                function() {
                $('.js-hide_' + i).hide();
                }
        );
    });
    </script>
    <li class="js-menu_<?php echo $i; ?> cursor-def"><span class="menu-title"><?php echo $menu['title']; ?></span>
      <ul style="display: none;" class="menu-list-sub js-hide_<?php echo $i; ?>">
        <?php foreach ($menu['menu'] AS $sub_menu) { ?>
        <li><?php echo $this->Html->link($sub_menu['label'], $sub_menu['link']); ?></li>
        <?php } ?>
      </ul>
    </li>
  <?php } else { ?>
    <li><?php echo $this->Html->link($menu['title'], $menu['link']); ?></li>
  <?php } ?>
  <?php $i++; ?>
  <?php } ?>
</ul>