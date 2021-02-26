<!DOCTYPE html>
<html lang="en">

	<body>
        <h1 style="text-align:center">爬蟲小工具</h1>
        <hr>
        <table>
                <th>旅行社官網</th>
                <tr>
                <!--
                        <td>
                        <a href="https://www.taiwantourbus.com.tw/C/tw/home"  target="_blank" >明利觀巴</a>
                        </td>
                -->
                        <td>
                        <a href="http://www.tourbus.com.tw/C/tw/home"  target="_blank" >金廷官網</a>
                        </td>
                        <td>
                        <a href="https://www.fun2tw.com/" target="_blank" >屏東官網</a>
                        </td>
                </tr>
        </table>
        <form text action="gethtml_final.php" method="get">
                <div style="width:100%;text-align:center">
                <p>請輸入要解析的網址：</p>
                <input type="url" name="url" size="60">
                <br><br>
                <input type="submit" value="送出">
        </div>
        </form>

	</body>
</html>