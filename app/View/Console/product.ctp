<?php echo $this->Html->script('jquery-select_select', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-checked', array('inline' => false)); ?>
<?php //ジャンルとハードの選択肢を定義しておく
    $array_genre = array(
        1 => array(
            'name' => 'アニメ',
            'value' => 'anime',
            'class' => 'panime',
            'hard' => array(
                1 => array('name' => 'TV', 'value' => 'tv'),
                2 => array('name' => 'OVA', 'value' => 'ova')
            )
        ),
        2 => array(
            'name' => 'ゲーム',
            'value' => 'game',
            'class' => 'pgame',
            'hard' => array(
                1 => array('name' => 'PC', 'value' => 'pc'),
                2 => array('name' => 'PS3', 'value' => 'ps3'),
                3 => array('name' => 'PS2', 'value' => 'ps2'),
                4 => array('name' => 'PS', 'value' => 'ps'),
                5 => array('name' => 'PSvita', 'value' => 'psvita'),
                6 => array('name' => 'PSP', 'value' => 'psp'),
                7 => array('name' => 'Xbox', 'value' => 'xbox'),
                8 => array('name' => 'スマホ', 'value' => 'app')
            )
        ),
        3 => array(
            'name' => 'ラジオ',
            'value' => 'radio',
            'class' => 'pradio',
            'hard' => array(
                1 => array('name' => 'Web', 'value' => 'web'),
            )
        ),
        4 => array(
            'name' => '音楽',
            'value' => 'music',
            'class' => 'pmusic',
            'hard' => array(
                1 => array('name' => 'シングル', 'value' => 'sg'),
                2 => array('name' => 'アルバム', 'value' => 'al')
            )
        ),
        5 => array(
            'name' => 'その他',
            'value' => 'other',
            'class' => 'pother',
            'hard' => array()
        )
    );
?>
<h3><?php echo $profile['Voice']['nickname']; ?>出演作品の登録</h3>

  <table>
    <?php if (preg_match('#/console/voice/' . $profile['Voice']['system_name'] . '/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Product', array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'product_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
      <?php echo $this->Form->input('voice_actor', array('type' => 'hidden', 'value' => $profile['Voice']['system_name'])); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Product', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'product_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
      <?php echo $this->Form->input('voice_id', array('type' => 'hidden', 'value' => $profile['Voice']['id'])); ?>
      <?php echo $this->Form->input('voice_actor', array('type' => 'hidden', 'value' => $profile['Voice']['system_name'])); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>作品名</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>キャラ/名義</td>
      <td><?php echo $this->Form->input('charactor', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>備考（任意）</td>
      <td><?php echo $this->Form->input('note', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 5)); ?></td>
    </tr>
    <tr>
      <td>ジャンル</td>
      <?php $genre_lists = []; ?>
      <?php foreach ($array_genre as $genre) {
          $genre_lists = array_merge($genre_lists, array($genre['value'] => $genre['name']));
      } ?>
      <?php $genre_lists = array_merge(array('' => ''), $genre_lists); ?>
      <td id="SelectGenre"><?php echo $this->Form->input('genre', array('type' => 'select', 'label' => false, 'id' => 'lv1Pulldown', 'options' => $genre_lists)); ?></td>
    </tr>
    <tr>
      <td>ハード（任意）</td>
      <?php if (preg_match('#/console/voice/' . $profile['Voice']['system_name'] . '/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
        <?php echo $this->Form->input('pre_genre', array('type' => 'hidden', 'value' => $this->request->data['Product']['genre'])); ?>
        <?php echo $this->Form->input('pre_hard', array('type' => 'hidden', 'value' => $this->request->data['Product']['hard'])); ?>
        <td id="SelectHard">
          <select name="data[Product][hard]" id="lv2Pulldown" disabled="disabled">
            <?php echo (!$this->request->data['Product']['hard'])? '<option>ジャンルを選択してください</option>' : ''; ?>
            <?php foreach ($array_genre as $genre) { ?>
              <?php foreach ($genre['hard'] as $hard) { ?>
                <option value="<?php echo $hard['value']; ?>" class="<?php echo $genre['class']; ?>" <?php echo ($this->request->data['Product']['hard'] == $hard['value'])? 'selected="selected"' : ''; ?>>
                  <?php echo $hard['name']; ?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </td>
      <?php } else { //登録用 ?>
        <td id="SelectHard">
          <select name="data[Product][hard]" id="lv2Pulldown" disabled="disabled">
            <option>ジャンルを選択してください</option>
            <?php foreach ($array_genre as $genre) { ?>
              <?php foreach ($genre['hard'] as $hard) { ?>
                <option value="<?php echo $hard['value']; ?>" class="<?php echo $genre['class']; ?>">
                  <?php echo $hard['name']; ?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </td>
      <?php } ?>
    </tr>
    
    <?php if (@$this->request->data['Product']['hard'] == 'sg') {
        $mode_music = 'sg';
    } elseif (@$this->request->data['Product']['hard'] == 'al') {
        $mode_music = 'al';
    } ?>
    <tr class="tbl-music_voice<?php echo (@$mode_music)? '_' . $mode_music : ''; ?>">
      <td></td>
      <td>
        <table>
          <tr class="txt-min"><td>　　曲名</td><td>作詞者</td><td>作曲者</td></tr>
          <?php for ($i = 0; $i < 15; $i++) { ?>
            <?php if (preg_match('#/console/voice/' . $profile['Voice']['system_name'] . '/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
              <?php echo $this->Form->input('Music.' . $i . '.id', array('type' => 'hidden')); ?>
            <?php } ?>
            <tr class="tbl-music_voice-<?php echo ($i < 5)? 'sg' : 'al'; ?><?php echo (@$mode_music)? '_' . $mode_music : ''; ?>">
              <td><?php echo sprintf('%02d', $i +1); ?><?php echo $this->Form->input('Music.' . $i . '.title', array('type' => 'text', 'label' => false, 'size' => 21)); ?></td>
              <td><?php echo $this->Form->input('Music.' . $i . '.writer', array('type' => 'text', 'label' => false, 'size' => 8)); ?></td>
              <td><?php echo $this->Form->input('Music.' . $i . '.composer', array('type' => 'text', 'label' => false, 'size' => 8)); ?></td>
            </tr>
          <?php } ?>
        </table>
      </td>
    </tr>
    
    <tr>
      <td>日付</td>
      <td><?php echo $this->Form->input('date_from', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2000)); ?> ～
          <?php if (preg_match('#/console/voice/'.$profile['Voice']['system_name'].'/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->input('date_to', array('type' => 'date', 'label' => false, 'class' => 'js-input_date_to', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => 2038, 'minYear' => 2000, 'disabled' => ($this->request->data['Product']['date_to'] == null)? 'disabled' : '')); ?>
            <?php echo $this->Form->input('date_to_null', array('type' => 'checkbox', 'label' => false, 'class' => 'js-checkbox_date_to', 'checked' => ($this->request->data['Product']['date_to'] == null)? 'checked' : '')); ?>null
          <?php } else { //登録用 ?>
            <?php echo $this->Form->input('date_to', array('type' => 'date', 'label' => false, 'class' => 'js-input_date_to', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => 2038, 'minYear' => 2000, 'disabled' => 'disabled')); ?>
            <?php echo $this->Form->input('date_to_null', array('type' => 'checkbox', 'label' => false, 'class' => 'js-checkbox_date_to', 'checked' => 'checked')); ?>null
          <?php } ?></td>
    </tr>
    <tr><td></td><td><span class="txt-min">※放送中は終了日を 2038/01/19 に設定する</span></td></tr>
    <tr>
      <td>リンクURL（任意）</td>
      <td><?php echo $this->Form->input('link_url', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/voice/' . $profile['Voice']['system_name'] . '/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
                               <?php echo $this->Form->submit('修正する'); ?>
                             <?php } else { //登録用 ?>
                               <?php echo $this->Form->submit('登録する'); ?>
                             <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<?php if (!empty($profile)) { ?>
  <h3><?php echo $profile['Voice']['nickname']; ?>プロフィール情報</h3>
  
    <table class="prof-list_voice">
      <tr><td class="tbl-ico"><?php if ($profile['Voice']['publish'] == 0) { ?>
                                <span class="icon-false">非公開</span>
                              <?php } elseif ($profile['Voice']['publish'] == 1) { ?>
                                <span class="icon-true">公開</span>
                              <?php } ?></td>
          <td><?php echo $this->Html->link('プロフィールを変更する', '/console/voice_edit/' . $profile['Voice']['system_name']); ?></td>
          <td><?php echo $this->Html->link('サイトを確認する', '/voice/' . $profile['Voice']['system_name'], array('target' => '_blank')); ?></td>
          <td><?php echo $this->Form->postLink('削除する', array('controller' => 'Console', 'action' => 'voice_delete', $profile['Voice']['id']), null, '本当に ' . $profile['Voice']['name'] . ' の声優データを削除しますか'); ?></td></tr>
    </table>
<?php } ?>

<h3><?php echo $profile['Voice']['nickname']; ?>出演作品一覧</h3>

  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'console', 'action' => 'voice', $profile['Voice']['system_name'])
  )); ?>
  <?php echo $this->Paginator->numbers($paginator_option); ?>

<div class="detail-list-scr">
  <table class="tbl-list_voice">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Product.id', '▼'); ?></th>
        <th class="tbl-title_voice">作品名<?php echo $this->Paginator->sort('Product.title', '▼'); ?></th>
        <th class="tbl-chara_voice">キャラ/名義<?php echo $this->Paginator->sort('Product.charactor', '▼'); ?></th>
        <th class="tbl-date_voice">状態<br>
                                   日付<?php echo $this->Paginator->sort('Product.date_from', '▼'); ?></th>
        <th class="tbl-ico">ジャンル<br>
                            ハード</th>
        <th>備考</th>
        <th class="tbl-act_voice">action</th></tr>
    
    <?php foreach ($product_lists as $product_list) { ?>
      <tr><td class="tbl-num"><?php echo $product_list['Product']['id']; ?></td>
          <td class="tbl-title_voice"><?php if ($product_list['Product']['link_url']) { ?>
                                        <a href="<?php echo $product_list['Product']['link_url']; ?>" target="_blank">
                                          <?php echo $product_list['Product']['title']; ?></a>
                                      <?php } else { ?>
                                        <?php echo $product_list['Product']['title']; ?>
                                      <?php } ?></td>
          <td class="tbl-chara_voice"><?php echo $product_list['Product']['charactor']; ?></td>
          <td class="tbl-date_voice"><?php if ($product_list['Product']['publish'] == 0) { ?>
                                       <span class="icon-false">非公開</span>
                                     <?php } elseif ($product_list['Product']['publish'] == 1) { ?>
                                       <span class="icon-true">公開</span>
                                     <?php } ?><br>
                                     <?php echo $product_list['Product']['date_from']; ?>
                                     <?php if (@$product_list['Product']['date_to'] == '2038-01-19') { ?>
                                       <br><span>～now on air</span>
                                     <?php } elseif ($product_list['Product']['date_to']) { ?>
                                       <br>～<?php echo $product_list['Product']['date_to']; ?>
                                     <?php } ?></td>
          <td class="tbl-ico"><?php foreach ($array_genre as $genre) { ?>
                                <?php if ($product_list['Product']['genre'] == $genre['value']) { ?>
                                  <span><?php echo $genre['name']; ?></span><br>
                                <?php } ?>
                              <?php } ?>
                              <?php $hard_name = 'その他'; ?>
                              <?php foreach ($array_genre as $genre) { ?>
                                <?php foreach ($genre['hard'] as $hard) { ?>
                                  <?php if ($product_list['Product']['hard'] == $hard['value']) {
                                      $hard_name = $hard['name'];
                                  } ?>
                                <?php } ?>
                              <?php } ?>
                              <span><?php echo $hard_name; ?></span></td>
          <td><?php echo $product_list['Product']['note']; ?></td>
          <td class="tbl-act_voice"><?php echo $this->Html->link('修正', '/console/voice/' . $profile['Voice']['system_name'] . '/edit/' . $product_list['Product']['id']); ?>
                                    <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'product_delete', $product_list['Product']['id'], $profile['Voice']['system_name']), null, '本当に#' . $product_list['Product']['id'] . ' を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
</div>

<?php $array_hard = array('', '_sg', '_al'); ?>
<?php foreach($array_hard as $hard) { ?>
  <script>
      jQuery(function($) {
          var hard = <?php echo json_encode($hard, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
          $('#SelectHard select').change(function() { //formのchangeイベントがあれば値を取得
              var val = $(this).val();
              if (val == 'sg') {
                  $('.tbl-music_voice' + hard).show();
                  $('.tbl-music_voice-sg' + hard).show();
                  $('.tbl-music_voice-al' + hard).hide();
              } else if (val == 'al') {
                  $('.tbl-music_voice' + hard).show();
                  $('.tbl-music_voice-sg' + hard).show();
                  $('.tbl-music_voice-al' + hard).show();
              } else {
                  $('.tbl-music_voice' + hard).hide();
                  $('.tbl-music_voice-sg' + hard).hide();
                  $('.tbl-music_voice-al' + hard).hide();
              }
          });
          $('#SelectGenre select').change(function() { //formのchangeイベントがあれば値を取得
              var val = $(this).val();
              if (val !== 'music') {
                  $('.tbl-music_voice' + hard).hide();
                  $('.tbl-music_voice-sg' + hard).hide();
                  $('.tbl-music_voice-al' + hard).hide();
              }
          });
      });
  </script>
<?php } ?>