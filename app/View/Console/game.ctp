<?php echo $this->Html->script('jquery-tmb', array('inline' => false)); ?>
<h3>エロゲレビューの作成</h3>

  <table>
    <?php if (preg_match('#/console/game/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Game', array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'game_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Game', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'game_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>タイトル</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>メーカー</td>
      <?php $maker_lists = array_merge(array(0 => '選択してください'), $maker_lists); ?>
      <td><?php echo $this->Form->input('maker_id', array('type' => 'select', 'label' => false, 'options' => $maker_lists)); ?></td>
    </tr>
    <tr>
      <td>発売日</td>
      <td><?php echo $this->Form->input('release_date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2000)); ?></td>
    </tr>
    <tr>
      <td>リンク先URL</td>
      <td><?php echo $this->Form->input('link_url', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    
    <?php $point_lists = array(10 => 10, 9 => 9, 8 => 8, 7 => 7, 6 => 6, 5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1); ?>
    <?php
    $array_review = array(
        'シナリオ' => 'scenario',
        '音楽' => 'music',
        'キャラ' => 'chara',
        '絵' => 'still',
        'システム' => 'config'
    );
    ?>
    <?php foreach ($array_review as $key => $review) { ?>
    <script>
        jQuery(function($) {
            var review = <?php echo json_encode($review, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
            $(function() {
                $('.js-button_' + review).click(
                    function(){
                        $('.js-hide_' + review).toggle();
                    }
                );
            });
        });
    </script>
      <tr>
        <td><?php echo $key; ?></td>
        <td><?php echo $this->Form->input($review.'_point', array('type' => 'select', 'label' => false, 'options' => $point_lists)); ?>
            <button type="button" class="js-button_<?php echo $review; ?>">レビューを書く</button></td>
      </tr>
      <tr style="display: none;" class="js-hide_<?php echo $review; ?>">
        <td><?php echo $key; ?><br>レビュー</td>
        <td><?php echo $this->Form->input($review.'_review', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 10, 'class' => 'js-insert_area')); ?></td>
      </tr>
    <?php } ?>
    <tr>
      <td>レビュー</td>
      <td><?php echo $this->Form->input('review', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 10, 'class' => 'js-insert_area')); ?></td>
    </tr>
    
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/game/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
                              <?php echo $this->Form->submit('修正する'); ?>
                             <?php } else { //登録用 ?>
                              <?php echo $this->Form->submit('作成する'); ?>
                             <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>エロゲレビュー一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

<div class="detail-list-scr">
  <table class="tbl-list_game">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Game.id', '▼'); ?></th>
        <th class="tbl-title_game">タイトル<?php echo $this->Paginator->sort('Game.title', '▼'); ?><br>
                                   メーカー<?php echo $this->Paginator->sort('Maker.title', '▼'); ?></th>
        <th class="tbl-date_game">状態　<br>
                                  発売日<?php echo $this->Paginator->sort('Game.release_date', '▼'); ?></th>
        <th class="tbl-num_game">評価<?php echo $this->Paginator->sort('Game.point', '▼'); ?><br>
                                 シナ<br>リオ<?php echo $this->Paginator->sort('Game.scenario_point', '▼'); ?></th>
        <th class="tbl-num_game">音楽<?php echo $this->Paginator->sort('Game.music_point', '▼'); ?><br>
                                 キャラ<br>　　<?php echo $this->Paginator->sort('Game.chara_point', '▼'); ?></th>
        <th class="tbl-num_game">&nbsp;絵<?php echo $this->Paginator->sort('Game.still_point', '▼'); ?><br>
                                 シス<br>テム<?php echo $this->Paginator->sort('Game.config_point', '▼'); ?></th>
        <th class="tbl-act_game">action</th></tr>
    
    <?php foreach ($game_lists as $game_list) { ?>
      <tr><td class="tbl-num"><?php echo $game_list['Game']['id']; ?></td>
          <td><?php echo $game_list['Game']['title']; ?><br>
              （<?php echo $game_list['Maker']['title']; ?>）</td>
          <td class="tbl-date_game"><?php if ($game_list['Game']['publish'] == 0) { ?>
                                      <span class="icon-false">非公開</span>
                                    <?php } elseif ($game_list['Game']['publish'] == 1) { ?>
                                      <span class="icon-true">公開</span>
                                    <?php } ?><br>
                                    <?php echo $game_list['Game']['release_date']; ?></td>
          <td class="tbl-num_game"><?php echo $game_list['Game']['point']; ?><br>
                                   <?php echo $game_list['Game']['scenario_point']; ?></td>
          <td class="tbl-num_game"><?php echo $game_list['Game']['music_point']; ?><br>
                                   <?php echo $game_list['Game']['chara_point']; ?></td>
          <td class="tbl-num_game"><?php echo $game_list['Game']['still_point']; ?><br>
                                   <?php echo $game_list['Game']['config_point']; ?></td>
          <td class="tbl-act_game"><?php echo $this->Html->link('レビューの確認', '/game/erg/' . $game_list['Game']['id'], array('target' => '_blank')); ?><br>
                                   <?php echo $this->Html->link('修正', '/console/game/edit/' . $game_list['Game']['id']); ?>
                                   <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'game_delete', $game_list['Game']['id']), null, '本当に#' . $game_list['Game']['id'] . 'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
</div>