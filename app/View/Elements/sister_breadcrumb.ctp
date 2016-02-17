<?php $breadcrumbs = array(); ?>
<?php foreach ($array_menu AS $menu) { //$array_menuはAppControllerで定義 ?>
  <?php if (preg_match('*'.$menu['link'].'*', $_SERVER['REQUEST_URI'])) {
    $breadcrumbs = array(
        0 => array('menu' => $menu['title'], 'link' => $menu['link'])
    );
    break;
  } ?>
  <?php foreach ($menu['menu'] AS $sub_menu) { ?>
  <?php if (preg_match('*'.$sub_menu['link'].'*', $_SERVER['REQUEST_URI'])) {
    $breadcrumbs = array(
        0 => array('menu' => $menu['title'], 'link' => $menu['link']),
        1 => array('menu' => $sub_menu['label'], 'link' => $sub_menu['link'])
    );
    break;
  } ?>
  <?php } ?>
<?php } ?>
<h2 class="breadcrumb cf">
  <ol>
    <li><?php echo $this->Html->link('HOME', '/'); ?></li>
    <?php $tmp = $breadcrumbs; //後でforeach構文で最後の処理をするため ?>
    <?php foreach ($breadcrumbs AS $breadcrumb) { ?>
    <li>
      <span>＞　</span>
      <?php if ($breadcrumb['link'] == '#' || next($tmp)) { ?>
        <?php echo $breadcrumb['menu']; ?>
      <?php } else { ?>
        <?php echo $this->Html->link($breadcrumb['menu'], $breadcrumb['link']); ?>
      <?php } ?>
    </li>
    <?php } ?>
  </ol>
</h2>