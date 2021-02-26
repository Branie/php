<html lang="en">

<body>
  <h1 style="text-align:center">爬蟲小工具</h1>
  <hr>
</body>

<?php
//ini_set("display_errors", "On");
//error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set("Asia/Taipei");

//引用3t_func的 function
require_once('./gethtml_func.php');

$http = $_GET["url"];
echo "解析網址： " . $http . "<hr>";

if(preg_match("/www.tourbus.com/", $http)){
  
  print("現在是金廷旅行社格式");

  $url = $http;
  $ch = curl_init();
  $timeout = 15;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, "Google bot");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_exec($ch);
  $buffer = curl_exec($ch);
  curl_close($ch);

  if (!($buffer)) {
    echo "<br>網頁擷取失敗(錯誤代碼：1)，請聯絡系統管理員。<br>";
  } else {

    $rempreg = ['#\s#', '#\n#', '#&nbsp;#']; //移除空白
    $reppreg = ['#</p>#', '#</div>#', '#</li>#', '#</h3>#', '#</h2>#', '#</h1>#', '#<br>#', '#</span>#']; //取代成換行

    //商品名稱
    $travelpricing_start = strpos($buffer, '<div class="tourBag">');
    $travelpricing_end = strpos($buffer, '<div class="social-plugin">');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content ."</textarea><hr>";

    preg_match('#<h2>(.*)</h2>#', $content, $title);
    $title[0] = preg_replace('#<h2>#', '', $title[0]);
    $title[0] = preg_replace("#</h2>#", "", $title[0]);
    $title = $title[0];
    echo "<br>商品名稱：<br>";

    $content = remove_preg($content, $rempreg); //刪掉
    $content = replace($content, $reppreg); //取代成換行
    $content = strip_tags($content); //移除html語法
    echo "<textarea style='width: 100%; height: 5%;'>" . $content . "</textarea><hr>";

    //備註
    $travelpricing_start = strpos($buffer, '<!-- 優惠模組 -->');
    $travelpricing_end = strpos($buffer, '"isNotTours4Fun"/>');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content2 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content ."</textarea><hr>";

    preg_match('#<h3>(.*)</h3>#', $content2, $remarks);
    $remarks[0] = preg_replace('#<h3>#', '', $remarks[0]);
    $remarks[0] = preg_replace('#</h3>#', '', $remarks[0]);
    $remarks = $remarks[0];
    echo "<br>備註：<br>";

    $content2 = remove_preg($content2, $rempreg); //刪掉
    $content2 = replace($content2, $reppreg); //取代成換行
    $content2 = strip_tags($content2); //移除html語法
    //print("取代後 : ");
    echo "<textarea style='width: 100%; height: 10%;'>" . $content2 . "</textarea><hr>";

    //圖文詳情&行程內容
    $travelpricing_start = strpos($buffer, '<span class="subTitle">特色 </span>');
    $travelpricing_end = strpos($buffer, '<ul class="tab" id="tabGroup">');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content3 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content3 ."</textarea><hr>";

    preg_match('#<p>(.*)</p>#', $content3, $photo);
    $photo[0] = preg_replace('#<p>#', '', $photo[0]);
    $photo[0] = preg_replace('#</p>#', '', $photo[0]);
    $photo = $photo[0];
    echo "<br>圖文詳情&行程內容：<br>";

    $content3 = remove_preg($content3, $rempreg); //刪掉
    $content3 = replace($content3, $reppreg); //取代成換行
    $content3 = strip_tags($content3); //移除html語法
    //print("取代後 : ");
    echo "<textarea style='width: 100%; height: 20%;'>" . $content3 . "</textarea><hr>";


    //費用說明
    $travelpricing_start = strpos($buffer, '<!-- 費用說明 -->');
    $travelpricing_end = strpos($buffer, '<!-- 注意事項 -->');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content5 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content5 ."</textarea><hr>";

    preg_match('#<p>(.*)</p>#', $content5, $message);
    $message[0] = preg_replace('#<p>#', '', $message[0]);
    $message[0] = preg_replace('#</p>#', '', $message[0]);
    $message = $message[0];
    echo "<br>費用說明<br>";

    $content5 = remove_preg($content5, $rempreg); //刪掉
    $content5 = replace($content5, $reppreg); //取代成換行
    $content5 = strip_tags($content5); //移除html語法
    //print("取代後 : ");
    echo "<textarea style='width: 100%; height: 15%;'>" . $content5 . "</textarea><hr>";


    //注意事項(不一定每一個都有，空白正常)
    $travelpricing_start = strpos($buffer, '<!-- 注意事項 -->');
    $travelpricing_end = strpos($buffer, '<!-- 路線地圖 -->');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content6 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content5 ."</textarea><hr>";

    preg_match('#<p>(.*)</p>#', $content6, $notice);
    $notice[0] = preg_replace('#<p>#', '', $notice[0]);
    $notice[0] = preg_replace('#</p>#', '', $notice[0]);
    $notice = $notice[0];
    echo "<br>注意事項：(不一定都有，空白正常) <br>";


    $content6 = remove_preg($content6, $rempreg); //刪掉
    $content6 = replace($content6, $reppreg); //取代成換行
    $content6 = strip_tags($content6); //移除html語法
    //print("取代後 : ");
    echo "<textarea style='width: 100%; height: 15%;'>" . $content6 . "</textarea><hr>";
  }
  $buffer = "";
 
}else if(preg_match("/www.fun2tw.com/", $http)){

  
  print("現在是屏東旅行社格式");

  $url = $http;
  $ch = curl_init();
  $timeout = 15;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, "Google bot");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_exec($ch);
  $buffer = curl_exec($ch);
  curl_close($ch);

  if (!($buffer)) {
    echo "<br>網頁擷取失敗(錯誤代碼：1)，請聯絡系統管理員。<br>";
  } else {


    //屏東格式(多了行程套票、PT_09格式有點不一樣)
    $rempreg = ['#\s#', '#\n#', '#&nbsp;#', '#&bull;#']; //移除空白
    $reppreg = ['#</p>#', '#</li>#', '#</h3>#', '#</h2>#', '#</h1>#', '#<br/>#', '#</span>#']; //取代成換行

    //商品名稱
    $travelpricing_start = strpos($buffer, '<h2 class="l-titles l-titles-indent">');
    $travelpricing_end = strpos($buffer, '<div class="news-content">');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content ."</textarea><hr>";

    preg_match('#<h2>(.*)</h2>#', $content, $title);
    $title[0] = preg_replace('#<h2>#', '', $title[0]);
    $title[0] = preg_replace("#</h2>#", "", $title[0]);
    $title = $title[0];
    echo "<br>商品名稱：<br>";

    $content = remove_preg($content, $rempreg); //刪掉
    $content = replace($content, $reppreg); //取代成換行
    $content = strip_tags($content); //移除html語法
    echo "<textarea style='width: 100%; height: 5%;'>" . $content . "</textarea><hr>";


    //商品特色
    $travelpricing_start = strpos($buffer, '行程特色');
    $travelpricing_end = strpos($buffer, '詳細行程');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content1 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content ."</textarea><hr>";

    preg_match('#行程特色(.*)詳細行程#', $content1, $characteristic);
    $characteristic[0] = preg_replace('#行程特色#', '', $characteristic[0]);
    $characteristic[0] = preg_replace('#詳細行程#', '', $characteristic[0]);
    $characteristic = $characteristic[0];
    echo "<br>商品特色：<br>";

    $content1 = remove_preg($content1, $rempreg); //刪掉
    $content1 = replace($content1, $reppreg); //取代成換行
    $content1 = strip_tags($content1); //移除html語法
    //print("取代後 : ");
    echo "<textarea style='width: 100%; height: 25%;'>" . $content1 . "</textarea><hr>";

    //行程內容
    $travelpricing_start = strpos($buffer, '詳細行程');
    $travelpricing_end = strpos($buffer, '費用說明');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content3 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content3 ."</textarea><hr>";

    preg_match('#詳細行程(.*)費用說明#', $content3, $photo);
    $photo[0] = preg_replace('#詳細行程#', '', $photo[0]);
    $photo[0] = preg_replace('#費用說明#', '', $photo[0]);
    $photo = $photo[0];
    echo "<br>行程內容：<br>";

    $content3 = remove_preg($content3, $rempreg); //刪掉
    $content3 = replace($content3, $reppreg); //取代成換行
    $content3 = strip_tags($content3); //移除html語法

    echo "<textarea style='width: 100%; height: 40%;'>" . $content3 . "</textarea><hr>";

    //費用說明
    $travelpricing_start = strpos($buffer, '費用說明');
    $travelpricing_end = strpos($buffer, '費用包含');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content5 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content5 ."</textarea><hr>";

    preg_match('#費用說明(.*)報名流程#', $content5, $message);
    $message[0] = preg_replace('#費用說明#', '', $message[0]);
    $message[0] = preg_replace('#費用包含#', '', $message[0]);
    $message = $message[0];
    echo "<br>費用說明<br>";

    $content5 = remove_preg($content5, $rempreg); //刪掉
    $content5 = replace($content5, $reppreg); //取代成換行
    $content5 = strip_tags($content5); //移除html語法

    echo "<textarea style='width: 100%; height: 15%;'>" . $content5 . "</textarea><hr>";


    //費用包含
    $travelpricing_start = strpos($buffer, '費用包含：');
    $travelpricing_end = strpos($buffer, '費用不包含：');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content7 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content5 ."</textarea><hr>";

    preg_match('#費用包含：(.*)費用不包含：#', $content5, $price);
    $price[0] = preg_replace('#費用包含：#', '', $price[0]);
    $price[0] = preg_replace('#費用不包含：#', '', $price[0]);
    $price = $price[0];
    echo "<br>費用包含<br>";

    $content7 = remove_preg($content7, $rempreg); //刪掉
    $content7 = replace($content7, $reppreg); //取代成換行
    $content7 = strip_tags($content7); //移除html語法
 
    echo "<textarea style='width: 100%; height: 20%;'>" . $content7 . "</textarea><hr>";

    //費用不包含
    $travelpricing_start = strpos($buffer, '費用不包含：');
    $travelpricing_end = strpos($buffer, '注意事項');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content8 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content5 ."</textarea><hr>";

    preg_match('#費用不包含：(.*)注意事項#', $content8, $unprice);
    $unprice[0] = preg_replace('#費用不包含：#', '', $unprice[0]);
    $unprice[0] = preg_replace('#注意事項#', '', $unprice[0]);
    $unprice = $price[0];
    echo "<br>費用不包含<br>";

    $content8 = remove_preg($content8, $rempreg); //刪掉
    $content8 = replace($content8, $reppreg); //取代成換行
    $content8 = strip_tags($content8); //移除html語法

    echo "<textarea style='width: 100%; height: 15%;'>" . $content8 . "</textarea><hr>";

    //注意事項
    $travelpricing_start = strpos($buffer, '注意事項');
    $travelpricing_end = strpos($buffer, '景點介紹');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content9 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content5 ."</textarea><hr>";

    preg_match('#注意事項(.*)景點介紹#', $content9, $notice);
    $notice[0] = preg_replace('#注意事項#', '', $notice[0]);
    $notice[0] = preg_replace('#景點介紹#', '', $notice[0]);
    $notice = $notice[0];
    echo "<br>注意事項<br>";

    $content9 = remove_preg($content9, $rempreg); //刪掉
    $content9 = replace($content9, $reppreg); //取代成換行
    $content9 = strip_tags($content9); //移除html語法
  
    echo "<textarea style='width: 100%; height: 25%;'>" . $content9 . "</textarea><hr>";


    //景點介紹
    $travelpricing_start = strpos($buffer, '景點介紹');
    $travelpricing_end = strpos($buffer, '我要訂購這個行程');
    $travelpricing_len = $travelpricing_end - $travelpricing_start;
    $content10 = substr($buffer, $travelpricing_start, $travelpricing_len);
    //echo "<textarea style='width: 100%; height: 30%;'>". $content5 ."</textarea><hr>";

    preg_match('#景點介紹(.*)我要訂購這個行程#', $content10, $attractions);
    $attractions[0] = preg_replace('#景點介紹#', '', $attractions[0]);
    $attractions[0] = preg_replace('#我要訂購這個行程#', '', $attractions[0]);
    $attractions = $attractions[0];
    echo "<br>景點介紹<br>";

    $content10 = remove_preg($content10, $rempreg); //刪掉
    $content10 = replace($content10, $reppreg); //取代成換行
    $content10 = strip_tags($content10); //移除html語法

    echo "<textarea style='width: 100%; height: 15%;'>" . $content10 . "</textarea><hr>";
  }
  $buffer = "";
}else{
    print("網址錯誤，請輸入金廷or屏東旅行社網址");
}

?>
<footer style="text-align:center">
  <input type="button" onclick="history.back()" value="回到上一頁"></input>
</footer>

</html>