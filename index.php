<?php

    session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Book Desk</title>
</head>
<body style="text-align:center;">

<br>
<br>
<h1>漫画orライトノベル？選択してください!!!</h1>
<form action="desk.php" method="POST" >
<?php
echo("
<table border='0' width='300'>
    <tr align='center'>
        <td>"."<img src='./images/11.png' higth=120>"."</td>
        <td>"."<img src='./images/31.png' higth=120>"."</td>
    </tr>
    <tr>
        <td align='center'><input type='radio' name='item' value='0'></td>
		<td align='center'><input type='radio' name='item' value='1'></td>
    </tr>
</table>")

?>
<button type="submit">決める</button>
</form>
</body>
</html>