<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<h3>イベント参加履歴</h3>

<p class="intro_evelog">
  管理人のイベント参加履歴と参加予定です。<br>
  特に推してるのは竹達彩奈さん、内田真礼さん、麻倉ももさん（ ＾ω＾）<br>
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
</p>

<?php foreach ($eventlog['schedule'] as $year => $val): ?>
<h4><?php echo $year . '年'; ?></h4>

<table class="tbl_evelog">
  <?php foreach ($val as $month => $val2): ?>
    <?php foreach ($val2 as $event): ?>
    <tr class="<?php echo ($event['closed'] == 1)? 'current_evelog' : ''; ?>">
      <td class="tbl-date_evelog txt-min">
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

<p class="intro_evelog">
  <?php echo $description; ?>
</p>