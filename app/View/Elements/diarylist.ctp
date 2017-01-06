<div id="diarylist" class="side-menu_block">
  <ul>
    <?php
    foreach ($diary_lists as $diary_list) {
        echo '<li>';
        echo '<a class="diarylist_menu" href="#article-' . $diary_list['Diary']['id'] . '">' . $diary_list['Diary']['title'] . '</a>';
//        echo $this->Html->link($diary_list['Diary']['title'], '/diary/' . $diary_list['Diary']['id']);
        echo '</li>';
    }
    ?>
  </ul>
</div>