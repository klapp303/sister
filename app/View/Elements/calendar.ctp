<?php
$first_week = date('w', mktime(0, 0, 0, $calendar['current']['month'], 1, $calendar['current']['year']));
$last_day = date('t', mktime(0, 0, 0, $calendar['current']['month'], 1, $calendar['current']['year']));
$last_week = date('w', mktime(0,0, 0, $calendar['current']['month'], $last_day, $calendar['current']['year']));
?>
<div id="calendar" class="side-menu_block">
  <div class="cal-header">
    <span class="cal-link fl"><?php echo $this->Html->link('<<', '/diary/' . $calendar['pre']['year'] . '/' . $calendar['pre']['month']); ?></span>
    <?php echo $calendar['current']['year'] . '年' . $calendar['current']['month'] . '月'; ?>
    <span class="cal-link fr"><?php echo $this->Html->link('>>', '/diary/' . $calendar['next']['year'] . '/' . $calendar['next']['month']); ?></span>
  </div>
  <div class="cal-body cf">
    <table>
      <tr><th class="txt-alt">日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th class="sat">土</th></tr>
      <tr>
        <?php
        for ($i = 0; $i < $first_week; $i++) {
            echo '<td></td>';
        }
        for ($i = 1; $i <= $last_day; $i++) {
            if ($first_week == 0) {
                $first_week = 7;
            }
            if (($i %7) == (7 - $first_week)) {
                if (in_array($i, $calendar['diary_cal_lists'])) {
                    echo '<td>';
                    echo $this->Html->link($i, '/diary/' . $calendar['current']['year'] . '/' . $calendar['current']['month'] . '/' . $i);
                    echo '</td><tr></tr>';
                } else {
                    echo '<td>' . $i . '</td></tr><tr>';
                }
            } else {
                if (in_array($i, $calendar['diary_cal_lists'])) {
                    echo '<td>';
                    echo $this->Html->link($i, '/diary/' . $calendar['current']['year'] . '/' . $calendar['current']['month'] . '/' . $i);
                    echo '</td>';
                } else {
                    echo '<td>' . $i . '</td>';
                }
            }
        }
        for ($i = 0; $i < (7 - $last_week -1); $i++) {
            echo '<td></td>';
        }
        ?>
      </tr>
    </table>
  </div>
</div>