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

<table class="tbl_events">
  <?php foreach ($event_data['events'] as $key => $event): ?>
    <tr class="<?php echo (@$event['current'] == 1)? 'current_events' : ''; ?>">
      <td class="tbl-date_events txt-min">
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
      </td>
      <td class="tbl-place_events txt-min pc">
        <?php if (strpos($event['place'], 'その他') === false) { //PC用会場
                  echo $event['place'];
              } else {
                  
              } ?>
      </td>
    </tr>
    <tr><td><br></td></tr>
  <?php endforeach; ?>
</table>