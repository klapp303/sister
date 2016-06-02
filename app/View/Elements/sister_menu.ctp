<ul class="menu-list">
  <?php $width = 800 / count($array_menu); //$array_menuはAppControllerで定義 ?>
  <?php foreach ($array_menu as $key => $menu) { ?>
    <?php if ($menu['menu']) { ?>
    <script>
    jQuery(function($) {
        var key = <?php echo json_encode($key, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        $(function() {
            $('.js-menu_' + key).hover(
                function() {
                    $('.js-hide_' + key).toggle();
                }
            );
        });
    });
    </script>
      <li style="width: <?php echo $width; ?>px;" class="js-menu_<?php echo $key; ?> cursor-def"><span class="menu-title"><?php echo $menu['title']; ?></span>
        <ul style="display: none;" class="menu-list-sub js-hide_<?php echo $key; ?>">
          <?php foreach ($menu['menu'] as $sub_menu) { ?>
            <li style="width: <?php echo $width; ?>px;"><?php echo $this->Html->link($sub_menu['label'], $sub_menu['link']); ?></li>
          <?php } ?>
        </ul>
      </li>
    <?php } else { ?>
      <li style="width: <?php echo $width; ?>px;"><?php echo $this->Html->link($menu['title'], $menu['link']); ?></li>
    <?php } ?>
  <?php } ?>
</ul>