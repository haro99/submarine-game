<?php
    $flag = 0;
    $error = array();
    if( !empty($_POST['push']) )
    {

        $map = $_POST['map'];
        $mymap = $_POST['mymap'];
        $enemy = (int)$_POST['enemy'];
        $flag = 1;

        $error = validation($_POST);

        if( empty($error) )
        {
            $report = array();
            $y = (int)$_POST['ypoint'];
            $x = (int)$_POST['xpoint'];
            if($mymap[$y][$x] == 'ー')
            {
                $report[] = "座標". $y. "、". $x. "の位置に魚雷発射！";

                if($map[$y][$x] == 1)
                {
                    $report[] = "敵艦にヒット！";
                    $mymap[$y][$x] = "〇";
                    $enemy--;
                }
                else
                {
                    $report[] = "ミス！そのマスに敵艦はありませんでした";
                    $mymap[$y][$x] = "×";
                }
            }
            else
            {
                $report[] = "その座標にはもう撃ちました、他の座標を入力してください";
            }
        }
    }
    else
    {
        $enemy = 3;
        $map = array(
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0]
            );
        $mymap = array(
            ["ー", "ー", "ー"],
            ["ー", "ー", "ー"],
            ["ー", "ー", "ー"]
            );

        for($i = 0; $i < 3; $i++)
        {
            $y = rand(0, 2);
            $x = rand(0, 2);
            if($map[$y][$x] == 0)
            {
                $map[$y][$x] = 1;
            }
            else continue;
        }
    }

    //座標の入力のバリデーション確認
    function validation ($date)
    {
        $error = array();
        //Ｘ座標の入力のバリデーション
        if( $date['xpoint'] === "" )
        {
            $error[] = "ｘ座標が未入力です、0から2の数字を入れてください";
        }
        else if((int)$date['xpoint'] < 0 || (int)$date['xpoint'] > 2)
        {
            $error[] = "Ｘ座標が襟が外です、正しい座標を入力してください";
        }
        //Ｙ座標の入力のバリデーション
        if( $date['ypoint'] === "" )
        {
            $error[] = "Ｙ座標が未入力です、0から2の数字を入れてください";
        }
        else if((int)$date['ypoint'] < 0 || (int)$date['ypoint'] > 2)
        {
            $error[] = "Ｙ座標がエリア外です、正しい座標を入力してください";
        }

        return $error;
    }
    //var_dump($_POST);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">

    <title>潜水艦ゲーム</title>
</head>
<body>
    <h1>潜水艦ゲーム！</h1>
    <p>マップの位置を指定して相手の潜水艦を落とそう！</p>
    <h3>マップ</h3>
    <table border="1" width="500" rules="all">
    <?php
    /* 
        自分の狙ったマスをテーブルで表示
    */
        foreach($mymap as $line)
        {
            echo "<tr>";
            foreach($line as $number)
            {
                echo "<td align=\"center\">$number</td>\n";
            }
            echo "</tr>";
        }
    ?>
    </table>
    <!--エラー表示欄-->
        <?php if( !empty($error) ): ?>
        <ul class="error">
        <?php foreach( $error as $value):?>
            <li><?php echo $value; ?></li>
        <?php endforeach;?>
        </ul>
        <?php endif; ?>
        <?php if( !empty($report) ): ?>
        <ul class="error">
        <?php foreach( $report as $value):?>
            <li><?php echo $value; ?></li>
        <?php endforeach;?>
        </ul>
        <?php endif; ?>
        <?php if( $enemy == 0): ?>
        <p>敵艦を全部撃破した！おめでとう！</p>
        <?php else:?>
        <p>敵艦残り<?php echo $enemy;?></p>
        <?php endif;?>
    <p>x座標とY座標を半角で入れてGOを押して発射！</p>
    <form action="" method="post">
        <p>ｘ座標<input type="text" name="xpoint"></p>
        <p>Ｙ座標<input type="text" name="ypoint"></p>
        <?php if($enemy > 0):?>
        <input type="submit" name="push" value="GO"> 
        <?php else: ?>
        <input type="submit" name="reset" value="もう一回やる？">
        <?php endif; ?>
        <input type="hidden" name="enemy" value="<?php echo $enemy; ?>">
        <?php
            /*
                hiddenでmapとmymapをPOSTで送信
                mapは配置の配列
                mymapは撃った場所の履歴の配列
            */
            for($i = 0; $i < 3; $i++)
            {
                for($j = 0; $j < 3; $j++)
                {
                    echo "<input type=\"hidden\" name=\"map[$i][$j]\" value=\"{$map[$i][$j]}\">";
                    echo "<input type=\"hidden\" name=\"mymap[$i][$j]\" value=\"{$mymap[$i][$j]}\">";
                }
            }
        ?>
    </form>
</body>
</html>