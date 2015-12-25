<?php echo $this->Html->css('voice', array('inline' => FALSE)); ?>
<h3>出演<?php if ($genre == 'anime') {echo 'アニメ';}
         elseif ($genre == 'game') {echo 'ゲーム';}
         elseif ($genre == 'other') {echo '作品';} ?>一覧</h3>

  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'voice', 'action' => $actor, $genre)
  )); ?>
  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="voice-list">
    <tr><th class="tbl-date_voice">日付<?php echo $this->Paginator->sort($Actor.'.date_from', '▼'); ?></th>
        <th class="tbl-ico_voice">ハード</th>
        <th class="tbl-title_voice">作品名<?php echo $this->Paginator->sort($Actor.'.title', '▼'); ?></th>
        <th class="tbl-chara_voice">キャラクター<?php echo $this->Paginator->sort($Actor.'.charactor', '▼'); ?></th>
        <th>備考</th></tr>
    
    <?php foreach ($lists AS $list) { ?>
    <tr><td class="tbl-date_voice"><?php echo $list[$Actor]['date_from']; ?></td>
        <td class="tbl-ico_voice"><?php if ($list[$Actor]['hard'] == 'tv') {echo 'TV';}
                              elseif ($list[$Actor]['hard'] == 'ova') {echo 'OVA';}
                              elseif ($list[$Actor]['hard'] == 'pc') {echo 'PC';}
                              elseif ($list[$Actor]['hard'] == 'ps3') {echo 'PS3';}
                              elseif ($list[$Actor]['hard'] == 'ps2') {echo 'PS2';}
                              elseif ($list[$Actor]['hard'] == 'ps') {echo 'PS';}
                              elseif ($list[$Actor]['hard'] == 'psvita') {echo 'PSvita';}
                              elseif ($list[$Actor]['hard'] == 'psp') {echo 'PSP';}
                              elseif ($list[$Actor]['hard'] == 'xbox') {echo 'Xbox';}
                              elseif ($list[$Actor]['hard'] == 'app') {echo 'スマホ';}
                              elseif ($list[$Actor]['hard'] == null) {echo '';}
                              else {echo 'その他';} ?></td>
        <td class="tbl-title_voice"><?php if ($list[$Actor]['link_url']) { ?>
                                      <a href="<?php echo $list[$Actor]['link_url']; ?>" target="_blank">
                                        <?php echo $list[$Actor]['title']; ?></a>
                                    <?php } else { ?>
                                      <?php echo $list[$Actor]['title']; ?>
                                    <?php } ?></td>
        <td class="tbl-chara_voice"><?php echo $list[$Actor]['charactor']; ?></td>
        <td><?php echo $list[$Actor]['note']; ?></td></tr>
    <?php } ?>
  </table>