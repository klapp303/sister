<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<?php $birthday = $this->Session->read('birthday'); ?>
<h3>プロフィール</h3>

<?php echo $this->Html->image('../files/author_cafe.jpg', array('class' => 'img_prof')); ?>

<p class="intro_prof">
  どうも、管理人です。<br>
  妹キャラには目がないです。<br>
  血が繋がってても、繋がってなくても。<br>
  はたまたお兄ちゃんと呼んでくれるだけの幼なじみでも。<br>
  <br>
  けよりなの麻衣ちゃん、ダカーポの由夢ちゃん、<br>
  そしてSAOの直葉ちゃんが妹キャラTOP3です(*´ω｀*)<br>
  シスプリは咲耶派でした。あ、きりりんももちろん大好きですよ？
</p>

<table class="tbl_prof">
  <tr>
    <td class="tbl-title_prof">
      お名前
    </td>
    <td class="tbl-text_prof">
      クラップ
    </td>
  </tr>
  <tr>
    <td class="tbl-title_prof">
      生息地
    </td>
    <td class="tbl-text_prof">
      都内某所
    </td>
  </tr>
  <tr>
    <td class="tbl-title_prof">
      好きなメーカー
    </td>
    <td class="tbl-text_prof">
      オーガスト、Alcot、feng、3rdeye、すみっこ etc
    </td>
  </tr>
  <tr>
    <td class="tbl-title_prof">
      よくやってるゲーム
    </td>
    <td class="tbl-text_prof">
      モンハン<s>、ポケモン、ガルフレ</s>
    </td>
  </tr>
  <tr>
    <td class="tbl-title_prof">
      好きな音楽
    </td>
    <td class="tbl-text_prof">
      梶浦さん、陛下、I've、エレガ、千代丸、<br>チーム竹達、プチミレ、トラセ etc
    </td>
  </tr>
  <tr>
    <td class="tbl-title_prof">
      追っかけてる声優
    </td>
    <td class="tbl-text_prof">
      ほっちゃん → ゆいにゃん → おとちん → あやち → もちょ
    </td>
  </tr>
</table>
<?php if (@$strong_color): ?>
<script>
    jQuery(function($) {
        $('.tbl-title_prof').css('background-color', '#<?php echo $strong_color; ?>');
    });
</script>
<?php endif; ?>