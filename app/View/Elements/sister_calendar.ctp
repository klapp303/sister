<?php
  $first_week = date('w', mktime(0, 0, 0, $month, 1, $year));
  $last_day = date('t', mktime(0, 0, 0, $month, 1, $year));
  $last_week = date('w', mktime(0,0, 0, $month, $last_day, $year));
?>
<div id="calendar">
  <div class="cal-header">
    <span class="cal-link fl"><?php echo $this->Html->link('<<', '/diary/'.$prev_year.'/'.$prev_month); ?></span>
    <?php echo $year.'年'.$month.'月'; ?>
    <span class="cal-link fr"><?php echo $this->Html->link('>>', '/diary/'.$next_year.'/'.$next_month); ?></span>
  </div>
  <div class="cal-body cf">
    <table>
      <tr><th class="txt-alt">日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th class="sat">土</th></tr>
      <tr><?php
        for ($i = 0; $i < $first_week; $i++) {
          echo '<td></td>';
        }
        for ($i = 1; $i <= $last_day; $i++) {
          if ($first_week == 0) {$first_week = 7;}
          if (($i % 7) == (7 - $first_week) ) {
            if (in_array($i, $diary_cal_lists)) {
              echo '<td>';
              echo $this->Html->link($i, '/diary/'.$year.'/'.$month.'/'.$i);
              echo '</td><tr></tr>';
            } else {
              echo '<td>'.$i.'</td></tr><tr>';
            }
          } else {
            if (in_array($i, $diary_cal_lists)) {
              echo '<td>';
              echo $this->Html->link($i, '/diary/'.$year.'/'.$month.'/'.$i);
              echo '</td>';
            } else {
              echo '<td>'.$i.'</td>';
            }
          }
        }
        for ($i = 0; $i < (7 - $last_week - 1); $i++) {
          echo '<td></td>';
        }
      ?></tr>
    </table>
  </div>
</div>