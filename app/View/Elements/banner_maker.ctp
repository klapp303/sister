<div class="banner-side_top pc">
  <ul>
  <?php foreach ($maker_lists as $maker_list) { ?>
    <li><a href="<?php echo $maker_list['Maker']['link_url']; ?>" target="_blank">
      <?php echo $this->Html->image('../files/maker/' . $maker_list['Maker']['image_name'], array('alt' => $maker_list['Maker']['title'], 'class' => 'banner-maker_top')); ?></a></li>
  <?php } ?>
  </ul>
</div>