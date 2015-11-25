<div class="banner-side_top">
  <ul>
  <?php foreach ($maker_lists AS $maker_list) { ?>
    <li><a href="<?php echo $maker_list['Maker']['link_url']; ?>" target="_blank"><img src="/files/maker/<?php echo $maker_list['Maker']['image_name']; ?>" alt="<?php echo $maker_list['Maker']['title']; ?>" class="banner-maker_top"></a></li>
  <?php } ?>
  </ul>
</div>