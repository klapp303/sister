<?php echo $this->Html->css('voice', array('inline' => false)); ?>
<h3>イベント最新情報</h3>

<p class="intro_voice">
  <?php echo $voice['Voice']['name'] . ' さんの'; ?>イベント最新情報です。<br>
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

<?php foreach ($event_lists['events'] as $year => $val): ?>
<h4><?php echo $year . '年'; ?></h4>

<table class="tbl_events">
  <?php foreach ($val as $month => $val2): ?>
    <?php foreach ($val2 as $event): ?>
    <tr class="<?php echo ($event['closed'] == 1)? 'current_events' : ''; ?>">
      <?php //土日の色分け
      $datetime = new DateTime($event['date']);
      $ww = $datetime->format('w');
      ?>
      <td class="tbl-date_events txt-min
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
      <td class="tbl-place_events txt-min pc">
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