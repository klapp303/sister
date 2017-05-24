<?php echo $this->Html->css('voice', array('inline' => false)); ?>
<h3><?php foreach ($array_voiceMenu as $menu) {
    if ($menu['genre'] == $genre) {
        echo $menu['title'];
    }
} ?>一覧</h3>

  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'voice', 'action' => $voice['Voice']['system_name'], $genre)
  )); ?>
  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="voice-list pc">
    <tr><th class="tbl-date_voice">日付<?php echo $this->Paginator->sort('Product.date_from', '▼'); ?></th>
        <th class="tbl-ico_voice">ハード</th>
        <th class="tbl-title_voice<?php echo ($genre == 'music')? '-music' : ''; ?>">
          作品名<?php echo $this->Paginator->sort('Product.title', '▼'); ?></th>
        <th class="tbl-chara_voice"><?php echo ($genre == 'music')? '名義' : 'キャラクター'; ?>
                                    <?php echo $this->Paginator->sort('Product.charactor', '▼'); ?></th>
        <th>備考</th></tr>
    
    <?php foreach ($lists as $list): ?>
    <tr><td class="tbl-date_voice"><?php echo $list['Product']['date_from']; ?>
                                   <?php if (@$list['Product']['date_to'] == '2038-01-19'): ?>
                                   <br><span>～now on air</span>
                                   <?php elseif ($list['Product']['date_to']): ?>
                                   <br>～<?php echo $list['Product']['date_to']; ?>
                                   <?php endif; ?></td>
        <td class="tbl-ico_voice"><?php $hard_name = 'その他'; ?>
                                  <?php foreach ($array_genre as $p_genre): ?>
                                    <?php foreach ($p_genre['hard'] as $hard): ?>
                                      <?php if ($list['Product']['hard'] == $hard['value']) {
                                          $hard_name = $hard['name'];
                                      } ?>
                                    <?php endforeach; ?>
                                  <?php endforeach; ?>
                                  <?php echo $hard_name; ?></td>
        <td class="tbl-title_voice<?php echo ($genre == 'music')? '-music' : ''; ?>">
          <?php if ($list['Product']['link_url']): ?>
          <a href="<?php echo $list['Product']['link_url']; ?>" target="_blank">
            <?php echo $list['Product']['title']; ?></a>
          <?php else: ?>
          <?php echo $list['Product']['title']; ?>
          <?php endif; ?>
          <?php if ($list['Product']['genre'] == 'music' && @$list['Music']): //ディスコグラフィの場合は楽曲データも合わせて表示 ?>
          <?php $id = $list['Product']['id']; ?>
          <script>
              jQuery(function($) {
                  var id = <?php echo json_encode($id, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                  $('.js-hide-button_' + id).click(function() {
                      $('.js-hide_' + id).toggle();
                  });
              });
          </script>
          <button class="btn_music_voice js-hide-button_<?php echo $id; ?>">楽曲リスト</button>
          <ul style="display: none;" class="list_msuic_voice js-hide_<?php echo $id; ?>">
            <?php $i = 1; ?>
            <?php foreach ($list['Music'] as $music): ?>
            <li><span class="num_music_voice"><?php echo sprintf('%02d. ', $i); ?></span><span class="title_music_voice"><?php echo $music['title']; ?></span></li>
            <?php $i++; ?>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </td>
        <td class="tbl-chara_voice"><?php echo $list['Product']['charactor']; ?></td>
        <td><?php echo $list['Product']['note']; ?></td></tr>
    <?php endforeach; ?>
  </table>

  <table class="voice-list mobile">
    <tr><th colspan="3" class="tbl-title_voice<?php echo ($genre == 'music')? '-music' : ''; ?>">
          作品名<?php echo $this->Paginator->sort('Product.title', '▼'); ?></th></tr>
    <tr><th class="tbl-date_voice">日付<?php echo $this->Paginator->sort('Product.date_from', '▼'); ?></th>
        <th class="tbl-ico_voice">ハード</th>
        <th class="tbl-chara_voice"><?php echo ($genre == 'music')? '名義' : 'キャラクター'; ?>
                                    <?php echo $this->Paginator->sort('Product.charactor', '▼'); ?></th>
        <!--th>備考</th--></tr>
    
    <?php foreach ($lists as $list): ?>
    <tr><td colspan="3" class="tbl-title_voice<?php echo ($genre == 'music')? '-music' : ''; ?>">
          <?php if ($list['Product']['link_url']): ?>
          <a href="<?php echo $list['Product']['link_url']; ?>" target="_blank">
            <?php echo $list['Product']['title']; ?></a>
          <?php else: ?>
          <?php echo $list['Product']['title']; ?>
          <?php endif; ?>
          <?php if ($list['Product']['genre'] == 'music' && @$list['Music']): //ディスコグラフィの場合は楽曲データも合わせて表示 ?>
          <?php $id = $list['Product']['id']; ?>
          <!--script>
              jQuery(function($) {
                  var id = <?php // echo json_encode($id, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                  $('.js-hide-button_' + id).click(function() {
                      $('.js-hide_' + id).toggle();
                  });
              });
          </script-->
          <button class="btn_music_voice js-hide-button_<?php echo $id; ?>">楽曲リスト</button>
          <ul style="display: none;" class="list_msuic_voice js-hide_<?php echo $id; ?>">
            <?php $i = 1; ?>
            <?php foreach ($list['Music'] as $music): ?>
            <li><span class="num_music_voice"><?php echo sprintf('%02d. ', $i); ?></span><span class="title_music_voice"><?php echo $music['title']; ?></span></li>
            <?php $i++; ?>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </td></tr>
    <tr><td class="tbl-date_voice"><?php echo $list['Product']['date_from']; ?>
                                   <?php if (@$list['Product']['date_to'] == '2038-01-19'): ?>
                                   <br><span>～now on air</span>
                                   <?php elseif ($list['Product']['date_to']): ?>
                                   <br>～<?php echo $list['Product']['date_to']; ?>
                                   <?php endif; ?></td>
        <td class="tbl-ico_voice"><?php $hard_name = 'その他'; ?>
                                  <?php foreach ($array_genre as $p_genre): ?>
                                    <?php foreach ($p_genre['hard'] as $hard): ?>
                                      <?php if ($list['Product']['hard'] == $hard['value']) {
                                          $hard_name = $hard['name'];
                                      } ?>
                                    <?php endforeach; ?>
                                  <?php endforeach; ?>
                                  <?php echo $hard_name; ?></td>
        <td class="tbl-chara_voice"><?php echo $list['Product']['charactor']; ?></td>
        <!--td><?php // echo $list['Product']['note']; ?></td--></tr>
    <?php endforeach; ?>
  </table>