<?php
//titleの設定
//通常ページ
if (@$title == 'normal') {
    //個別ページ
    if (isset($this->request['id']) == true && @$sub_page) {
        $title_metatag = $sub_page . ' -虹妹ｐｒｐｒ-';
    //声優コンテンツページ
    } elseif ($this->name == 'Voice' && @$voice['Voice']['name']) {
        $title_metatag = $voice['Voice']['name']  . ' -虹妹ｐｒｐｒ-';
    //ツールページ
    } elseif ($this->name == 'Tools' && @$sub_page) {
        $title_metatag = $sub_page . ' -虹妹ｐｒｐｒ-';
    //静的ページ
    } else {
        $title_metatag = '声優ライブとイベントレポのサイト -虹妹ｐｒｐｒ-';
    }
//日記ページ
} elseif (@$title == 'diary') {
    //個別ページ
    if (isset($this->request['id']) == true && @$sub_page) {
        $title_metatag = $sub_page . ' -虹妹ｐｒｐｒ-';
    //ジャンルページ
    } elseif (isset($this->request['genre_id']) == true && @$genre_metatag) {
        $title_metatag = '声優ライブとイベントレポ ' . $genre_metatag . ' -虹妹ｐｒｐｒ-';
    } elseif ($this->action == 'past') {
        $title_metatag = '声優ライブとイベントレポ ' . $genre_metatag . ' -虹妹ｐｒｐｒ';
    //タグページ
    } elseif (isset($this->request['tag_id']) == true && @$tag_metatag) {
        $title_metatag = '声優ライブとイベントレポ ' . $tag_metatag . ' -虹妹ｐｒｐｒ-';
    } else {
        $title_metatag = '声優ライブとイベントレポのサイト -虹妹ｐｒｐｒ-';
    }
//管理画面
} elseif (@$title == 'console') {
    $title_metatag = '管理画面 -虹妹ｐｒｐｒ-';
//ログインページetc
} else {
    $title_metatag = '虹妹ｐｒｐｒ';
}

//OGPタグの設定
$current_url = $this->Html->url('', true);
$sister_image = 'files/top_maia.jpg';
$sister_description = '声優ライブとイベントレポのサイト -虹妹ｐｒｐｒ- | 竹達彩奈さん、内田真礼さん、麻倉ももさんを特に応援しています。';
if (@$ogp) {
    $birthday = $this->Session->read('birthday');
    //og:type
    if ($this->name == 'Top') {
        $ogp_type = 'website';
    } elseif (@$title == 'diary') {
        $ogp_type = 'blog';
    } else {
        $ogp_type = 'article';
    }
    //og:image
    if (@$title == 'diary') {
        if (isset($this->request['id']) == true && @$ogp_image) {
            //個別ページのサムネイルはcontrollerで設定
        } else {
            $ogp_image = $this->Html->url('/', true) . $sister_image;
        }
//    } elseif ($birthday) {
//        $ogp_image = $this->Html->url('/', true) . $sister_image;
    } else {
        $ogp_image = $this->Html->url('/', true) . $sister_image;
    }
    //og:description
    if (@$title == 'diary') {
        //個別ページ
        if (isset($this->request['id']) == true && @$diary_lists[0]) {
            $ogp_description = mb_strimwidth(strip_tags($diary_lists[0]['Diary']['text']), 0, 250, '...', 'UTF-8');
        } else {
            $ogp_description = $sister_description;
        }
    } elseif ($this->name == 'Game' || $this->name == 'Tools') {
        $ogp_description = $title_metatag;
    } else {
        $ogp_description = $sister_description;
    }
} ?>
<title><?php echo $title_metatag; ?></title>
<?php if (@$ogp): ?>
<!-- OGPtag start -->
<meta property="og:title" content="<?php echo $title_metatag; ?>" />
<meta property="og:type" content="<?php echo $ogp_type; ?>" />
<meta property="og:url" content="<?php echo $current_url; ?>" />
<meta property="og:image" content="<?php echo $ogp_image; ?>" />
<meta property="og:site_name" content="声優ライブとイベントレポのサイト -虹妹ｐｒｐｒ-" />
<meta property="og:description" content="<?php echo $ogp_description; ?>" />
<!-- OGPtag end -->
<!-- TwitterCard start -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@klapp_kamp" />
<!-- TwitterCard end -->
<?php endif; ?>