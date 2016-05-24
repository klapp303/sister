<?php echo $this->Html->css('game', array('inline' => FALSE)); ?>
<p class="intro_erg">
  えちぃゲームのレビューです。<br>
  5つの項目から<b>主観で</b>判定しております。妹キャラは大事。<br>
  まとまったデータや攻略情報は他の充実したサイトを見ればいいと思うよ！<br>
  平均的なものより秀でたものがあるタイトルの方が評価が高くなってる…ハズ(｀・ω・´)
</p>

<h3>ゲーム一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="game-list pc">
    <tr><th>タイトル<?php echo $this->Paginator->sort('Game.title', '▼'); ?></th>
        <th>メーカー<?php echo $this->Paginator->sort('Game.maker_id', '▼'); ?></th>
        <th class="tbl-date">発売日<?php echo $this->Paginator->sort('Game.release_date', '▼'); ?></th>
        <th class="tbl-num">評価<?php echo $this->Paginator->sort('Game.point', '▼'); ?></th>
        <th class="tbl-num">シナ<br>リオ<?php echo $this->Paginator->sort('Game.scenario_point', '▼'); ?></th>
        <th class="tbl-num">音楽<?php echo $this->Paginator->sort('Game.music_point', '▼'); ?></th>
        <th class="tbl-num">キャラ<br>　　<?php echo $this->Paginator->sort('Game.chara_point', '▼'); ?></th>
        <th class="tbl-num">&nbsp;絵<?php echo $this->Paginator->sort('Game.still_point', '▼'); ?></th>
        <th class="tbl-num">シス<br>テム<?php echo $this->Paginator->sort('Game.config_point', '▼'); ?></th></tr>
    
    <?php foreach ($game_lists AS $game_list) { ?>
    <tr><td><?php echo $this->Html->link($game_list['Game']['title'], '/game/erg/'.$game_list['Game']['id']); ?></td>
        <td><?php echo $game_list['Maker']['title']; ?></td>
        <td class="tbl-date"><?php echo $game_list['Game']['release_date']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['scenario_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['music_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['chara_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['still_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['config_point']; ?></td></tr>
    <?php } ?>
  </table>

  <table class="game-list mobile">
    <tr><th colspan="2">タイトル<?php echo $this->Paginator->sort('Game.title', '▼'); ?></th>
        <th>メーカー<?php echo $this->Paginator->sort('Game.maker_id', '▼'); ?></th>
        <th class="tbl-num">発売日<?php echo $this->Paginator->sort('Game.release_date', '▼'); ?></th>
        <th class="tbl-num">評価<?php echo $this->Paginator->sort('Game.point', '▼'); ?></th></tr>
    <tr><th class="tbl-num">シナ<br>リオ<?php echo $this->Paginator->sort('Game.scenario_point', '▼'); ?></th>
        <th class="tbl-num">音楽<?php echo $this->Paginator->sort('Game.music_point', '▼'); ?></th>
        <th class="tbl-num">キャラ<br>　　<?php echo $this->Paginator->sort('Game.chara_point', '▼'); ?></th>
        <th class="tbl-num">&nbsp;絵<?php echo $this->Paginator->sort('Game.still_point', '▼'); ?></th>
        <th class="tbl-num">シス<br>テム<?php echo $this->Paginator->sort('Game.config_point', '▼'); ?></th></tr>
    
    <?php foreach ($game_lists AS $game_list) { ?>
    <tr><td colspan="2"><?php echo $this->Html->link($game_list['Game']['title'], '/game/erg/'.$game_list['Game']['id']); ?></td>
        <td><?php echo $game_list['Maker']['title']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['release_date']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['point']; ?></td></tr>
    <tr><td class="tbl-num"><?php echo $game_list['Game']['scenario_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['music_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['chara_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['still_point']; ?></td>
        <td class="tbl-num"><?php echo $game_list['Game']['config_point']; ?></td></tr>
    <?php } ?>
  </table>