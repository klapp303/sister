<?php //paginatorの設定
//ページ数がない場合
if ($paginator_setting['max_page'] == 1) {
    
//ページ数が少ない場合
} elseif ($paginator_setting['max_page'] < $paginator_option['modulus']) {
    for ($i = 1; $i < $paginator_setting['current_page']; $i++) {
        echo $this->Html->link($i, '/diary/past/' . $i);
        echo ' ' . $paginator_option['separator'] . ' ';
    }
    echo $paginator_setting['current_page'];
    for ($i = $paginator_setting['current_page'] +1; $i <= $paginator_setting['max_page']; $i++) {
        echo ' ' . $paginator_option['separator'] . ' ';
        echo $this->Html->link($i, '/diary/past/' . $i);
    }
    
//始めの場合
} elseif ($paginator_setting['current_page'] <= ceil($paginator_option['modulus'] /2 +1)) {
    for ($i = 1; $i < $paginator_setting['current_page']; $i++) {
        echo $this->Html->link($i, '/diary/past/' . $i);
        echo ' ' . $paginator_option['separator'] . ' ';
    }
    echo $paginator_setting['current_page'];
    for ($i = $paginator_setting['current_page'] +1; $i <= $paginator_option['modulus'] +1; $i++) {
        echo ' ' . $paginator_option['separator'] . ' ';
        echo $this->Html->link($i, '/diary/past/' . $i);
    }
    echo $this->Html->link($paginator_option['last'], '/diary/past/' . $paginator_setting['max_page']);
    
//終わりの場合
} elseif ($paginator_setting['current_page'] >= $paginator_setting['max_page'] - floor($paginator_option['modulus'] /2)) {
    echo $this->Html->link($paginator_option['first'], '/diary/past/1');
    for ($i = $paginator_setting['max_page'] - $paginator_option['modulus']; $i < $paginator_setting['current_page']; $i++) {
        echo $this->Html->link($i, '/diary/past/' . $i);
        echo ' ' . $paginator_option['separator'] . ' ';
    }
    echo $paginator_setting['current_page'];
    for ($i = $paginator_setting['current_page'] +1; $i <= $paginator_setting['max_page']; $i++) {
        echo ' ' . $paginator_option['separator'] . ' ';
        echo $this->Html->link($i, '/diary/past/' . $i);
    }
    
//真ん中の場合
} else {
    echo $this->Html->link($paginator_option['first'], '/diary/past/1');
    for ($i = $paginator_setting['current_page'] - ceil($paginator_option['modulus'] /2); $i < $paginator_setting['current_page']; $i++) {
        echo $this->Html->link($i, '/diary/past/' . $i);
        echo ' ' . $paginator_option['separator'] . ' ';
    }
    echo $paginator_setting['current_page'];
    for ($i = $paginator_setting['current_page'] +1; $i <= $paginator_setting['current_page'] + floor($paginator_option['modulus'] /2); $i++) {
        echo ' ' . $paginator_option['separator'] . ' ';
        echo $this->Html->link($i, '/diary/past/' . $i);
    }
    echo $this->Html->link($paginator_option['last'], '/diary/past/' . $paginator_setting['max_page']);
}
?>