<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<h3>イベント参加履歴</h3>

<p class="intro_evelog">
  管理人のイベント参加履歴と参加予定です。<br>
  特に推してるのは竹達彩奈さん、内田真礼さん、麻倉ももさん（ ＾ω＾）<br>
  現場被りされる皆さん、今後ともよろしくです。
</p>

<?php foreach ($eventlog['schedule'] as $year => $val): ?>
<h4><?php echo $year . '年'; ?></h4>

<table class="tbl_evelog">
  <?php foreach ($val as $month => $val2): ?>
    <?php foreach ($val2 as $event): ?>
    <tr><td class="tbl-date_evelog txt-min">
          <?php list($yy, $mm, $dd) = explode('-', $event['date']);
                echo $mm . '/' . $dd; ?>
        </td>
        <td>
          <?php if ($event['event_title'] == $event['detail_title']) {
                    echo $event['event_title'];
                } else {
                    echo $event['event_title'] . ' ' . $event['detail_title'];
                } ?>
          <?php if (strpos($event['place'], 'その他') === false) { //スマホ用
                    echo '<span class="txt-min mobile">';
                    echo $event['place'];
                    echo '</span>';
                } else {
                    
                } ?>
          </span>
        </td>
        <td class="tbl-place_evelog txt-min pc">
          <?php if (strpos($event['place'], 'その他') === false) { //PC用
                    echo $event['place'];
                } else {
                    
                } ?>
        </td></tr>
    <?php endforeach; ?>
    <tr><td><br></td></tr>
  <?php endforeach; ?>
</table>
<?php endforeach; ?>