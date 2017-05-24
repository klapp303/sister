jQuery(function($) {
    $(function() {
        //プルダウンのoption内容をコピー
        var pd2 = $("#lv2Pulldown option").clone();
//        var pd3 = $("#lv3Pulldown option").clone();
        
        //1→2連動
        $("#lv1Pulldown").change(function() {
            //lv1のvalue取得
            var lv1Val = $("#lv1Pulldown").val();
            
            //lv2Pulldownのdisabled解除
            $("#lv2Pulldown").removeAttr("disabled");
            
            //一旦、lv2Pulldownのoptionを削除
            $("#lv2Pulldown option").remove();
            
            //(コピーしていた)元のlv2Pulldownを表示
            $(pd2).appendTo("#lv2Pulldown");
            
            //選択値以外のクラスのoptionを削除
            $("#lv2Pulldown option[class != p" + lv1Val + "]").remove();
            
            //「▼選択」optionを先頭に表示
            $("#lv2Pulldown").prepend('<option selected="selected"></option>');
            
            //lv3Pulldown disabled処理
//            $("#lv3Pulldown").attr("disabled", "disabled");
//            $("#lv3Pulldown option").remove();
//            $("#lv3Pulldown").prepend('<option value="0" selected="selected">▼選択</option>');
        });
        
        //2→3連動
//        $("#lv2Pulldown").change(function() {
//            //lv2のvalue取得
//            var lv2Val = $("#lv2Pulldown").val();
//            
//            //lv3Pulldownのdisabled解除
//            $("#lv3Pulldown").removeAttr("disabled");
//            
//            //一旦、lv3Pulldownのoptionを削除
//            $("#lv3Pulldown option").remove();
//            
//            //(コピーしていた)元のlv3Pulldownを表示
//            $(pd3).appendTo("#lv3Pulldown");
//            
//            //選択値以外のクラスのoptionを削除
//            ("#lv3Pulldown option[class != p" + lv2Val + "]").remove();
//            
//            //「▼選択」optionを先頭に表示
//            $("#lv3Pulldown").prepend('<option value="0" selected="selected">▼選択</option>');
//        });
    });
});