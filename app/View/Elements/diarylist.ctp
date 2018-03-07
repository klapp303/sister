<?php if (count($diary_lists) > 1 || @count($tag_diary_lists) > 0): ?>
<div id="diarylist" class="side-menu_block txt-min">
  <ul>
    <?php
    foreach ($diary_lists as $diary_list) {
        echo '<li>';
        echo '<a class="diarylist_menu" href="#article-' . $diary_list['Diary']['id'] . '">' . $diary_list['Diary']['title'] . '</a>';
        echo '</li>';
    }
    ?>
  </ul>
</div>
<?php endif; ?>