<?php echo $this->Html->css('voice', array('inline' => FALSE)); ?>
<h3><?php foreach ($array_voiceMenu AS $menu) {
  if ($menu['genre'] == $genre) {echo $menu['title'];}
} ?>一覧</h3>

  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'voice', 'action' => $voice['Voice']['system_name'], $genre)
  )); ?>
  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="voice-list pc">
    <tr><th class="tbl-date_voice">日付<?php echo $this->Paginator->sort('Product.date_from', '▼'); ?></th>
        <th class="tbl-ico_voice">ハード</th>
        <th class="tbl-title_voice<?php echo ($genre == 'music')? '-music': ''; ?>">
          作品名<?php echo $this->Paginator->sort('Product.title', '▼'); ?></th>
        <th class="tbl-chara_voice"><?php echo ($genre == 'music')? '名義': 'キャラクター'; ?>
                                    <?php echo $this->Paginator->sort('Product.charactor', '▼'); ?></th>
        <th>備考</th></tr>
    
    <?php foreach ($lists AS $list) { ?>
    <tr><td class="tbl-date_voice"><?php echo $list['Product']['date_from']; ?>
                                   <?php if (@$list['Product']['date_to'] == '2038-01-19') {echo '<br>～now on air';}
                                     elseif ($list['Product']['date_to']) {echo '<br>～'.$list['Product']['date_to'];} ?></td>
        <td class="tbl-ico_voice"><?php if ($list['Product']['hard'] == 'tv') {echo 'TV';}
                                    elseif ($list['Product']['hard'] == 'ova') {echo 'OVA';}
                                    elseif ($list['Product']['hard'] == 'pc') {echo 'PC';}
                                    elseif ($list['Product']['hard'] == 'ps3') {echo 'PS3';}
                                    elseif ($list['Product']['hard'] == 'ps2') {echo 'PS2';}
                                    elseif ($list['Product']['hard'] == 'ps') {echo 'PS';}
                                    elseif ($list['Product']['hard'] == 'psvita') {echo 'PSvita';}
                                    elseif ($list['Product']['hard'] == 'psp') {echo 'PSP';}
                                    elseif ($list['Product']['hard'] == 'xbox') {echo 'Xbox';}
                                    elseif ($list['Product']['hard'] == 'app') {echo 'スマホ';}
                                    elseif ($list['Product']['hard'] == 'web') {echo 'Web';}
                                    elseif ($list['Product']['hard'] == 'sg') {echo 'シングル';}
                                    elseif ($list['Product']['hard'] == 'al') {echo 'アルバム';}
                                    else {echo 'その他';} ?></td>
        <td class="tbl-title_voice<?php echo ($genre == 'music')? '-music': ''; ?>">
          <?php if ($list['Product']['link_url']) { ?>
            <a href="<?php echo $list['Product']['link_url']; ?>" target="_blank">
            <?php echo $list['Product']['title']; ?></a>
          <?php } else { ?>
            <?php echo $list['Product']['title']; ?>
          <?php } ?>
          <?php if ($list['Product']['genre'] == 'music' && @$list['Music']) { //ディスコグラフィの場合は楽曲データも合わせて表示 ?>
            <?php $id = $list['Product']['id']; ?>
            <script>
              jQuery(function($) {
                  var id = <?php echo json_encode($id, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                  $(function() {
                      $('.js-hide-button_' + id).click(
                          function() {
                              $('.js-hide_' + id).toggle();
                          }
                      );
                  });
              });
            </script>
            <button class="btn_music_voice js-hide-button_<?php echo $id; ?>">楽曲リスト</button>
            <ul style="display: none;" class="list_msuic_voice js-hide_<?php echo $id; ?>">
              <?php $i = 1; ?>
              <?php foreach ($list['Music'] AS $music) { ?>
              <li><span class="num_music_voice"><?php echo sprintf('%02d. ', $i) ?></span><span class="title_music_voice"><?php echo $music['title']; ?></span></li>
              <?php $i++; ?>
              <?php } ?>
            </ul>
            <?php } ?>
        </td>
        <td class="tbl-chara_voice"><?php echo $list['Product']['charactor']; ?></td>
        <td><?php echo $list['Product']['note']; ?></td></tr>
    <?php } ?>
  </table>

  <table class="voice-list mobile">
    <tr><th colspan="3" class="tbl-title_voice<?php echo ($genre == 'music')? '-music': ''; ?>">
          作品名<?php echo $this->Paginator->sort('Product.title', '▼'); ?></th></tr>
    <tr><th class="tbl-date_voice">日付<?php echo $this->Paginator->sort('Product.date_from', '▼'); ?></th>
        <th class="tbl-ico_voice">ハード</th>
        <th class="tbl-chara_voice"><?php echo ($genre == 'music')? '名義': 'キャラクター'; ?>
                                    <?php echo $this->Paginator->sort('Product.charactor', '▼'); ?></th>
        <!--th>備考</th--></tr>
    
    <?php foreach ($lists AS $list) { ?>
    <tr><td colspan="3" class="tbl-title_voice<?php echo ($genre == 'music')? '-music': ''; ?>">
          <?php if ($list['Product']['link_url']) { ?>
            <a href="<?php echo $list['Product']['link_url']; ?>" target="_blank">
            <?php echo $list['Product']['title']; ?></a>
          <?php } else { ?>
            <?php echo $list['Product']['title']; ?>
          <?php } ?>
          <?php if ($list['Product']['genre'] == 'music' && @$list['Music']) { //ディスコグラフィの場合は楽曲データも合わせて表示 ?>
            <?php $id = $list['Product']['id']; ?>
<!--            <script>
              jQuery(function($) {
                  var id = <?php // echo json_encode($id, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                  $(function() {
                      $('.js-hide-button_' + id).click(
                          function() {
                              $('.js-hide_' + id).toggle();
                          }
                      );
                  });
              });
            </script>-->
            <button class="btn_music_voice js-hide-button_<?php echo $id; ?>">楽曲リスト</button>
            <ul style="display: none;" class="list_msuic_voice js-hide_<?php echo $id; ?>">
              <?php $i = 1; ?>
              <?php foreach ($list['Music'] AS $music) { ?>
              <li><span class="num_music_voice"><?php echo sprintf('%02d. ', $i) ?></span><span class="title_music_voice"><?php echo $music['title']; ?></span></li>
              <?php $i++; ?>
              <?php } ?>
            </ul>
            <?php } ?>
        </td></tr>
    <tr><td class="tbl-date_voice"><?php echo $list['Product']['date_from']; ?>
                                   <?php if (@$list['Product']['date_to'] == '2038-01-19') {echo '<br>～now on air';}
                                     elseif ($list['Product']['date_to']) {echo '<br>～'.$list['Product']['date_to'];} ?></td>
        <td class="tbl-ico_voice"><?php if ($list['Product']['hard'] == 'tv') {echo 'TV';}
                                    elseif ($list['Product']['hard'] == 'ova') {echo 'OVA';}
                                    elseif ($list['Product']['hard'] == 'pc') {echo 'PC';}
                                    elseif ($list['Product']['hard'] == 'ps3') {echo 'PS3';}
                                    elseif ($list['Product']['hard'] == 'ps2') {echo 'PS2';}
                                    elseif ($list['Product']['hard'] == 'ps') {echo 'PS';}
                                    elseif ($list['Product']['hard'] == 'psvita') {echo 'PSvita';}
                                    elseif ($list['Product']['hard'] == 'psp') {echo 'PSP';}
                                    elseif ($list['Product']['hard'] == 'xbox') {echo 'Xbox';}
                                    elseif ($list['Product']['hard'] == 'app') {echo 'スマホ';}
                                    elseif ($list['Product']['hard'] == 'web') {echo 'Web';}
                                    elseif ($list['Product']['hard'] == 'sg') {echo 'シングル';}
                                    elseif ($list['Product']['hard'] == 'al') {echo 'アルバム';}
                                    else {echo 'その他';} ?></td>
        <td class="tbl-chara_voice"><?php echo $list['Product']['charactor']; ?></td>
        <!--td><?php // echo $list['Product']['note']; ?></td--></tr>
    <?php } ?>
  </table>