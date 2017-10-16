<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<h3>イベント参加履歴</h3>

<p class="intro_evelog">
  管理人のイベント参加履歴と参加予定です。<br>
  特に推してるのは竹達彩奈さん、内田真礼さん、麻倉ももさん（ ＾ω＾）<br>
  <?php if (!@$cr_year): ?>
  次回は <?php if (@$current_event): ?>
        <!--<span class="current_evelog">-->
        <?php echo $current_event['date_m'] . '月';
              echo $current_event['date_d'] . '日 ';
              if ($current_event['event_title'] == $current_event['detail_title']) {
                  echo $current_event['event_title'];
              } else {
                  echo $current_event['event_title'] . ' ' . $current_event['detail_title'];
              } ?>
        <!--</span>-->
        <?php else: ?>
        未定
        <?php endif; ?> です。
  <?php else: ?>
  …よくこのページを見つけましたね（；＾ω＾）
  <?php endif; ?>
</p>

<?php foreach ($eventlog['schedule'] as $year => $val): ?>
<h4><?php echo $year . '年'; ?></h4>

<table class="tbl_evelog">
  <?php foreach ($val as $month => $val2): ?>
    <?php foreach ($val2 as $event): ?>
    <tr class="<?php echo ($event['closed'] == 1)? 'current_evelog' : ''; ?>">
      <?php //土日の色分け
      $datetime = new DateTime($event['date']);
      $ww = $datetime->format('w');
      ?>
      <td class="tbl-date_evelog txt-min
          <?php if ($ww == 0 && $event['closed'] < 2) {
              echo ' sun';
          } elseif ($ww == 6 && $event['closed'] < 2) {
              echo ' sat';
          } ?>">
        <?php list($yy, $mm, $dd) = explode('-', $event['date']);
              echo $mm . '/' . $dd; ?>
      </td>
      <td>
        <?php if ($event['event_title'] == $event['detail_title']) {
                  echo $event['event_title'];
              } else {
                  echo $event['event_title'] . ' ' . $event['detail_title'];
              } ?>
        <?php if (strpos($event['place'], 'その他') === false) { //スマホ用会場
                  echo '<span class="txt-min mobile">';
                  echo $event['place'];
                  echo '</span>';
              } else {
                  
              } ?>
        <?php if (@$event['report']) { //レポリンク
                  echo '<span class="icon-button-min">';
                  echo '<a href="/diary/' . $event['report'] . '">レポ</a>';
                  echo '</span>';
              } elseif (@$event['comment']) { //一言リンク
                  echo '<span class="icon-button-min">';
                  echo '<a href="/diary/' . $event['comment'] . '">一言</a>';
                  echo '</span>';
              } ?>
      </td>
      <td class="tbl-place_evelog txt-min pc">
        <?php if (strpos($event['place'], 'その他') === false) { //PC用会場
                  echo $event['place'];
              } else {
                  
              } ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <tr><td><br></td></tr>
  <?php endforeach; ?>
</table>
<?php endforeach; ?>

<?php if (@$cr_year): ?>
<h4>参加履歴データ</h4>

<?php
//参加履歴データを取得
$data = getEventlogReport($eventlog, $cr_year);
if (!empty($eventlog_pre['schedule'])) {
    $data_pre = getEventlogReport($eventlog_pre, $cr_year -1);
} else {
    $data_pre = false;
}
?>
<table>
  <tr>
    <th>イベント数</th>
    <th>内ライブ数</th>
    <th>レポート数</th>
    <th>竹達彩奈さん</th>
    <th>内田真礼さん</th>
    <th>麻倉ももさん</th>
  </tr>
  
  <tr>
    <td class="tbl-num"><?php echo $data['all']; ?><?php $pre = ($data_pre)? ($data['all'] - $data_pre['all']) : 0;
                                                   echo ($pre > 0)? ' (+' . $pre . ')' : ' (' . $pre . ')'; ?></td>
    <td class="tbl-num"><?php echo $data['live']; ?><?php $pre = ($data_pre)? ($data['live'] - $data_pre['live']) : 0;
                                                    echo ($pre > 0)? ' (+' . $pre . ')' : ' (' . $pre . ')'; ?></td>
    <td class="tbl-num"><?php echo $data['report']; ?><?php $pre = ($data_pre)? ($data['report'] - $data_pre['report']) : 0;
                                                      echo ($pre > 0)? ' (+' . $pre . ')' : ' (' . $pre . ')'; ?></td>
    <td class="tbl-num"><?php echo $data['ayachi']; ?><?php $pre = ($data_pre)? ($data['ayachi'] - $data_pre['ayachi']) : 0;
                                                      echo ($pre > 0)? ' (+' . $pre . ')' : ' (' . $pre . ')'; ?></td>
    <td class="tbl-num"><?php echo $data['taso']; ?><?php $pre = ($data_pre)? ($data['taso'] - $data_pre['taso']) : 0;
                                                    echo ($pre > 0)? ' (+' . $pre . ')' : ' (' . $pre . ')'; ?></td>
    <td class="tbl-num"><?php echo $data['mocho']; ?><?php $pre = ($data_pre)? ($data['mocho'] - $data_pre['mocho']) : 0;
                                                     echo ($pre > 0)? ' (+' . $pre . ')' : ' (' . $pre . ')'; ?></td>
  </tr>
</table>
<p class="intro_evelog txt-min">※()内は前年比</p>
<?php endif; ?>

<p class="intro_evelog">
  <?php echo $description; ?>
</p>

<?php
//参加履歴データの計算関数
function getEventlogReport($eventlog = false, $year = null, $data = [])
{
    $data['all'] = 0;
    $data['live'] = 0;
    $data['report'] = 0;
    $data['ayachi'] = 0;
    $data['taso'] = 0;
    $data['mocho'] = 0;
    
    foreach ($eventlog['schedule'][$year] as $month => $val) {
        foreach ($val as $event) {
            //開催前のイベントは入れない
            if ($event['closed'] == 0) {
                continue;
            }
            
            //イベント数
            $data['all']++;
            //ライブ数
            if ($event['genre'] == 'ライブ') {
                $data['live']++;
            }
            //レポ数
            if (@$event['report'] || @$event['comment']) {
                $data['report']++;
            }
            //キャスト別参加数
            foreach ($event['cast'] as $cast) {
                if ($cast == '竹達彩奈' || $cast == 'petit milady') {
                    $data['ayachi']++;
                }
                if ($cast == '内田真礼') {
                    $data['taso']++;
                }
                if ($cast == '麻倉もも' || $cast == 'TrySail') {
                    $data['mocho']++;
                }
            }
        }
    }
    
    return $data;
}
