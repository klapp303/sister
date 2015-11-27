<script>
jQuery(function($) {
    $('.js-menu_1').hover(
              function() {
              $('.js-hide_1').show();
              },
              function() {
              $('.js-hide_1').hide();
              }
      );
    $('.js-menu_2').hover(
            function() {
              $('.js-hide_2').show();
            },
            function() {
              $('.js-hide_2').hide();
    }
    );
    $('.js-menu_3').hover(
            function() {
              $('.js-hide_3').show();
            },
            function() {
              $('.js-hide_3').hide();
            }
    );
    $('.js-menu_4').hover(
            function() {
              $('.js-hide_4').show();
            },
            function() {
              $('.js-hide_4').hide();
            }
    );
});
</script>
<ul class="menu-list">
  <li class="js-menu_1 cursor-def"><span class="menu-title">ご案内</span>
    <ul class="menu-list-sub js-hide_1">
      <li><?php echo $this->Html->link('このサイトについて', '/information/'); ?></li>
      <li><?php echo $this->Html->link('管理人について', '/author/'); ?></li>
      <li><?php echo $this->Html->link('リンク', '/link/'); ?></li>
    </ul>
  </li>
  <li class="js-menu_2 cursor-def"><span class="menu-title">ゲーム etc</span>
    <ul class="menu-list-sub js-hide_2">
      <li><?php echo $this->Html->link('エロゲレビュー', '/game/erg/'); ?></li>
      <li><?php echo $this->Html->link('モンハンメモ', '/game/mh/'); ?></li>
    </ul>
  </li>
  <li><?php echo $this->Html->link('音楽 etc', '#'); ?></li>
  <!--li class="js-menu_3 cursor-def"><span class="menu-title">音楽 etc</span>
    <ul class="menu-list-sub js-hide_3">
      <li><?php /*echo $this->Html->link('音楽レビュー', '#');*/ ?></li>
      <li><?php /*echo $this->Html->link('作曲者からみる', '#');*/ ?></li>
    </ul>
  </li-->
  <li class="js-menu_4 cursor-def"><span class="menu-title">声優 etc</span>
    <ul class="menu-list-sub js-hide_4">
      <li><?php echo $this->Html->link('おとちん', '#'); ?></li>
      <!--li><?php /*echo $this->Html->link('あやち', '#');*/ ?></li-->
    </ul>
  </li>
  <li><?php echo $this->Html->link('ブログ', '/diary/'); ?></li>
</ul>