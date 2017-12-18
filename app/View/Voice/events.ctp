<?php echo $this->Html->css('voice', array('inline' => false)); ?>
<?php echo $this->Html->script('remodal/remodal.min', array('inline' => false)); ?>
<?php echo $this->Html->css('remodal/remodal-default-theme', array('inline' => false)); ?>
<?php echo $this->Html->css('remodal/remodal', array('inline' => false)); ?>
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
    <?php //土日の色分け
    $datetime = new DateTime($event['date']);
    $ww = $datetime->format('w');
    $ww_arr = ['日', '月', '火', '水', '木', '金', '土'];
    ?>
    
    <!-- remodal start -->
    <div class="remodal" data-remodal-id="evelog-detail_<?php echo $event['detail_id']; ?>">
      <button data-remodal-action="close" class="remodal-close"></button>
      <h4><?php if ($event['event_title'] == $event['detail_title']) {
                  echo $event['event_title'];
              } else {
                  echo $event['event_title'] . ' ' . $event['detail_title'];
              } ?></h4>
      <p>
        日程：<?php list($yy, $mm, $dd) = explode('-', $event['date']);
                   echo $mm . '/' . $dd . ' (' . $ww_arr[$ww] . ')'; ?><br>
        <?php if ($event['time_open'] && $event['time_start']): ?>
        開場：<?php list($hh, $mmm, $ss) = explode(':', $event['time_open']);
                   echo $hh . ':' . $mmm; ?>　
        開演：<?php list($hh, $mmm, $ss) = explode(':', $event['time_start']);
                   echo $hh . ':' . $mmm; ?><br>
        <?php elseif ($event['time_start']): ?>
        開演：<?php list($hh, $mmm, $ss) = explode(':', $event['time_start']);
                   echo $hh . ':' . $mmm; ?><br>
        <?php endif; ?>
        場所：<?php echo $event['place']; ?><br>
        <?php if ($event['cast']): ?>
        出演者：<?php foreach($event['cast'] as $cast_k => $cast) {
            echo (@$event['cast'][$cast_k +1])? $cast . ', ' : $cast;
        } ?>
        <?php endif; ?>
      </p>
        <?php if ($event['setlist']): ?>
          <ul style="list-style-type: none; display: table;">
          <?php foreach ($event['setlist'] as $setlist_k => $setlist): ?>
            <li style="display: table">
              <?php echo $setlist_k +1 . '. '; ?>
              <?php echo $setlist['title'] . ' / '; ?>
              <?php echo $setlist['artist']; ?>
            </li>
          <?php endforeach; ?>
          </ul>
        <?php endif; ?>
    </div>
    <!-- remodal end -->
    
    <tr class="<?php echo ($event['closed'] == 1)? 'current_events' : ''; ?>">
      <td class="tbl-date_events txt-min
          <?php if ($ww == 0 && $event['closed'] < 2) {
              echo ' sun';
          } elseif ($ww == 6 && $event['closed'] < 2) {
              echo ' sat';
          } ?>"><a data-remodal-target="evelog-detail_<?php echo $event['detail_id']; ?>">
        <?php list($yy, $mm, $dd) = explode('-', $event['date']);
              echo $mm . '/' . $dd; ?>
      </a></td>
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