<?php echo $this->Html->css('top', array('inline' => false)); ?>
<?php $birthday = $this->Session->read('birthday'); ?>

<?php if ($birthday): ?>
  <?php if ($birthday_top_image_name): ?>
  <?php echo $this->Html->image('../files/birthday/' . $birthday_top_image_name, array('class' => 'img_top')); ?>
  <?php else: ?>
  <?php echo $this->Html->image('../files/top_maia.jpg', array('class' => 'img_top')); ?>
  <?php endif; ?>
<div class="bd-com_top mobile">
  <?php echo $birthday_voice_data['Voice']['nickname'] . ' お誕生日おめでとう！'; ?>
</div>
<?php else: ?>
  <?php echo $this->Html->image('../files/top_maia.jpg', array('class' => 'img_top')); ?>
<?php endif; ?>

<div class="side_top">
  <?php echo $this->element('twitter'); ?>
  <?php echo $this->element('banner_maker'); ?>
</div>

<?php if ($birthday): ?>
<div class="bd-com_top pc">
  <?php echo $birthday_voice_data['Voice']['nickname'] . ' お誕生日おめでとう！'; ?>
</div>
<?php else: ?>
<div class="sis-com_top pc">
  <?php echo nl2br($sister_comment[0]['SisterComment']['comment']); ?>
</div>
<?php endif; ?>


<div class="part_top">
  <hr class="hr_top">
  <h3 class="h_top"<?php echo (@$thema_color)? ' style="background-color: #' . $thema_color . ';"' : ''; ?>>お知らせ</h3>
</div>

<div class="inform_top">
  最終更新日<div class="inform-date"><?php echo $last_update; ?></div><hr>
  <?php foreach ($information_lists as $information_list) {
      echo $information_list['Information']['title'] . '<br><div class="inform-date">' . $information_list['Information']['date_from'] . '</div>';
      echo '<hr>';
  } ?>
</div>

<div class="part_top pc">
  <hr class="hr_top">
  <h3 class="h_top"<?php echo (@$thema_color)? ' style="background-color: #' . $thema_color . ';"' : ''; ?>>バナー</h3>
</div>

<div class="banner_top pc">
  <?php foreach ($banner_lists as $banner_list): ?>
  <a href="<?php echo $banner_list['Banner']['link_url']; ?>" alt="<?php echo $banner_list['Banner']['title']; ?>" target="_blank">
  <?php echo $this->Html->image('../files/banner/' . $banner_list['Banner']['image_name'], array('class' => '')); ?>
  </a><br>
  <?php endforeach; ?>
</div>