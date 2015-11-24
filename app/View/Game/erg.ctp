<p class="intro_erg">
  これはダミー文です。これはダミー文です。これはダミー文です。<br>
  これはダミー文です。これはダミー文です。これはダミー文です。<br>
  これはダミー文です。これはダミー文です。これはダミー文です。
</p>

<h3>ゲーム一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list">
    <tr><th>タイトル<?php echo $this->Paginator->sort('Game.title', '▼'); ?></th>
        <th>メーカー<?php echo $this->Paginator->sort('Game.maker_id', '▼'); ?></th>
        <th class="tbl-date">発売日<?php echo $this->Paginator->sort('Game.release_date', '▼'); ?></th>
        <th class="tbl-num">シナリオ<?php echo $this->Paginator->sort('Game.scenario_point', '▼'); ?></th>
        <th class="tbl-num">音楽<?php echo $this->Paginator->sort('Game.music_point', '▼'); ?></th>
        <th class="tbl-num">キャラ<?php echo $this->Paginator->sort('Game.chara_point', '▼'); ?></th>
        <th class="tbl-num">絵<?php echo $this->Paginator->sort('Game.still_point', '▼'); ?></th>
        <th class="tbl-num">システム<?php echo $this->Paginator->sort('Game.config_point', '▼'); ?></th></tr>
    
    <?php foreach ($game_lists AS $game_list) { ?>
    <tr><td><?php echo $this->Html->link($game_list['Game']['title'], '/game/erg/'.$game_list['Game']['id']); ?></td>
        <td><?php echo $game_list['Game']['maker_id']; ?></td>
        <td class="tbl-date"><?php echo $game_list['Game']['release_date']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['scenario_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['music_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['chara_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['still_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['config_point']; ?></td></tr>
    <?php } ?>
  </table>