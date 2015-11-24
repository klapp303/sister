<?php echo $this->Html->css('top', array('inline' => FALSE)); ?>

<img src="/files/top_maia.jpg" alt="" class="img_top">

<div class="side_top">
  <?php echo $this->element('twitter'); ?>
  <?php echo $this->element('banner_maker'); ?>
</div>

<div class="sis-com_top">
  <?php echo nl2br($sister_comment[0]['SisterComment']['comment']); ?>
</div>

<div class="part_top">
  <hr class="hr_top">
  <h3 class="h_top">お知らせ</h3>
</div>

<div class="inform_top">
  <?php foreach ($information_lists AS $information_list) {
    echo $information_list['Information']['title'].'<br><div class="inform-date">'.$information_list['Information']['date_from'].'</div>';
    echo '<hr>';
  } ?>
</div>

<div class="part_top">
  <hr class="hr_top">
  <h3 class="h_top">バナー</h3>
</div>

<div class="banner_top">
  <?php foreach ($banner_lists AS $banner_list) {
    echo '<a href="'.$banner_list['Banner']['link_url'].'" alt="'.$banner_list['Banner']['title'].'" target="_blank"><img src="/files/banner/'.$banner_list['Banner']['image_name'].'"></a><br>';
  } ?>
</div>