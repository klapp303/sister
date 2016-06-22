<?php echo $this->Html->css('game', array('inline' => false)); ?>
<?php $birthday = $this->Session->read('birthday'); ?>
<h3 class="title_erg"><?php echo $game_detail['Game']['title']; ?></h3>

<table class="detail-list_erg pc <?php echo ($birthday)? 'detail-list_erg_' . $birthday : ''; ?>">
  <tr><th class="tbl-num_erg">評価</th><th colspan="2" class="tbl-maker_erg">メーカー</th><th class="tbl-date_erg">発売日</th><th class="tbl-link_erg">サイトURL</th></tr>
  <tr>
    <td class="tbl-num_erg"><?php echo $game_detail['Game']['point']; ?></td>
    <td class="tbl-tmb_erg"><a href="<?php echo $game_detail['Maker']['link_url']; ?>" target="_blank">
        <?php echo $this->Html->image('../files/maker/' . $game_detail['Maker']['image_name'], array('alt' => $game_detail['Maker']['title'], 'class' => 'img-maker_erg')); ?></a></td>
    <td><?php echo $game_detail['Maker']['title']; ?></td>
    <td class="tbl-date_erg"><?php echo $game_detail['Game']['release_date']; ?></td>
    <td class="tbl-link_erg"><?php if ($game_detail['Game']['link_url']) {?>
                               <a href="<?php echo $game_detail['Game']['link_url']; ?>" target="_blank"><?php echo $game_detail['Game']['link_url']; ?></a>
                             <?php } ?></td>
  </tr>
</table>

<table class="detail-list_erg mobile <?php echo ($birthday)? 'detail-list_erg_' . $birthday : ''; ?>">
  <tr><th class="tbl-1_erg_mobile">評価</th><th colspan="2" class="tbl-2_erg_mobile">メーカー</th></tr>
  <tr><th class="tbl-1_erg_mobile">発売日</th><th colspan="2" class="tbl-2_erg_mobile">サイトURL</th></tr>
  <tr>
    <td class="tbl-1_erg_mobile"><?php echo $game_detail['Game']['point']; ?></td>
    <td><a href="<?php echo $game_detail['Maker']['link_url']; ?>" target="_blank">
        <?php echo $this->Html->image('../files/maker/' . $game_detail['Maker']['image_name'], array('alt' => $game_detail['Maker']['title'], 'class' => 'img-maker_erg')); ?></a></td>
    <td><?php echo $game_detail['Maker']['title']; ?></td>
  </tr>
  <tr>
    <td class="tbl-1_erg_mobile"><?php echo $game_detail['Game']['release_date']; ?></td>
    <td colspan="2" class="tbl-2_erg_mobile"><?php if ($game_detail['Game']['link_url']) {?>
                                               <a href="<?php echo $game_detail['Game']['link_url']; ?>" target="_blank">
                                                 <?php echo mb_strimwidth($game_detail['Game']['link_url'], 0, 32, '...'); ?></a>
                                             <?php } ?></td>
  </tr>
</table>

<table class="detail-list_erg detail-point_erg <?php echo ($birthday)? 'detail-list_erg_' . $birthday : ''; ?>">
  <tr><th>シナリオ</th><th>音楽</th><th>キャラ</th><th>絵</th><th>システム</th></tr>
  <tr>
    <td><?php echo $game_detail['Game']['scenario_point']; ?></td>
    <td><?php echo $game_detail['Game']['music_point']; ?></td>
    <td><?php echo $game_detail['Game']['chara_point']; ?></td>
    <td><?php echo $game_detail['Game']['still_point']; ?></td>
    <td><?php echo $game_detail['Game']['config_point']; ?></td>
  </tr>
</table>

<h3>シナリオ</h3>
<span class="point"><?php echo $game_detail['Game']['scenario_point']; ?></span>
<p class="review"><?php echo nl2br($game_detail['Game']['scenario_review']); ?></p>

<h3>音楽</h3>
<span class="point"><?php echo $game_detail['Game']['music_point']; ?></span>
<p class="review"><?php echo nl2br($game_detail['Game']['music_review']); ?></p>

<h3>キャラ</h3>
<span class="point"><?php echo $game_detail['Game']['chara_point']; ?></span>
<p class="review"><?php echo nl2br($game_detail['Game']['chara_review']); ?></p>

<h3>絵</h3>
<span class="point"><?php echo $game_detail['Game']['still_point']; ?></span>
<p class="review"><?php echo nl2br($game_detail['Game']['still_review']); ?></p>

<h3>システム</h3>
<span class="point"><?php echo $game_detail['Game']['config_point']; ?></span>
<p class="review"><?php echo nl2br($game_detail['Game']['config_review']); ?></p>

<h3>総評</h3>
<span class="point"><?php echo $game_detail['Game']['point']; ?></span>
<p class="review"><?php echo nl2br($game_detail['Game']['review']); ?></p>