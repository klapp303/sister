jQuery(function($) {
    //スキル選択時に背景色を変更
    $('[class^=js-skill-]').change(function() {
        if ($(this).is(':checked')) {
            var skillClass = $(this).attr('class');
            var skillClass_array = skillClass.split(' ');
            //もし択一のcheckboxならば他の選択肢の背景色は戻しておく
            if (skillClass_array[1]) {
                var text = skillClass_array[0];
                while (text.substr(text.length -1) !== '-' || !text) {
                    var text = text.substr(0, text.length -1);
                }
                var skillId = text;
                $('[id^=' + skillId + ']').css('background-color', '');
            }
            $('#' + skillClass_array[0]).css('background-color', '#a9bcf5');
        } else {
            var skillClass = $(this).attr('class');
            var skillClass_array = skillClass.split(' ');
            $('#' + skillClass_array[0]).css('background-color', '');
        }
    });
    
    //スキルのcheckboxを択一にする
    var arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
    $.each(arr, function(i, val) {
        $('.js-check-' + val).click(function() {
            if ($(this).prop('checked')) {
                //一旦全てのcheckをクリアして…
                $('.js-check-' + val).prop('checked', false);
                //選択されたものだけをcheckする
                $(this).prop('checked', true);
            }
        });
    });
});