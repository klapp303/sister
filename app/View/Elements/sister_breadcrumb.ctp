<?php $breadcrumbs = array(); ?>
<?php foreach ($array_menu as $menu) { //$array_menuはAppControllerで定義 ?>
    <?php if (preg_match('*' . $menu['link'] . '*', $_SERVER['REQUEST_URI'])) {
        $breadcrumbs = array(
            0 => array('menu' => $menu['title'], 'link' => $menu['link'])
        );
        break;
    } ?>
    <?php foreach ($menu['menu'] as $sub_menu) { ?>
        <?php if (preg_match('*' . $sub_menu['link'] . '*', $_SERVER['REQUEST_URI'])) {
            $breadcrumbs = array(
                0 => array('menu' => $menu['title'], 'link' => $menu['link']),
                1 => array('menu' => $sub_menu['label'], 'link' => $sub_menu['link'])
            );
            break;
        } ?>
    <?php } ?>
<?php } ?>
<?php if (isset($sub_page) == true) {
    array_push($breadcrumbs, array('menu' => $sub_page, 'link' => '#'));
} ?>
<?php if ($breadcrumbs) { ?>
  <h2 class="breadcrumb cf">
    <ol>
      <li><?php echo $this->Html->link('HOME', '/'); ?></li>
      <?php $tmp = $breadcrumbs; //後でforeach構文で最後の処理をするため ?>
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li>
          <span>＞　</span>
          <?php if (next($tmp)) { ?>
            <?php if ($breadcrumb['link'] == '#') { ?>
              <?php echo $breadcrumb['menu']; ?>
            <?php } else { ?>
              <?php echo $this->Html->link($breadcrumb['menu'], $breadcrumb['link']); ?>
            <?php } ?>
          <?php } else { ?>
            <?php echo $breadcrumb['menu']; ?>
          <?php } ?>
        </li>
      <?php } ?>
    </ol>
  </h2>
<?php } ?>