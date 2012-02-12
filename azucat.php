<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>授業支援システム-azucat</title>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
<link rel="stylesheet" type="text/css" href="http://www.mast.tsukuba.ac.jp/~s0911554/azucat_dir/main.css">
<script type="text/javascript"
  src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>


<?php
// カレントディレクトリ
$url = "http://www.mast.tsukuba.ac.jp/~s0911554/azucat"; 
$download_url = "http://www.mast.tsukuba.ac.jp/~s0911554/download"; 
//ローカルのホームディレクトリ
$local_url = "/home";
//getで与えられた値
$query = ereg_replace('/$','',$_SERVER['PATH_INFO']);
//見ているパス
$file_path = $local_url.$query;
$directory = $file_path."/";
//親ディレクトリへ
$parent_directory = $url.ereg_replace('/[^/]+$','',$query);
//もしホームディレクトリだったら、学年ごと配列を作る
if(!$query){
  $drc=dir($directory);
  while($fl=$drc->read()){
    //正規表現で、学年ごとにディレクトリを分ける
    preg_match("/^[a-zA-z][0-9][0-9]|^[a-zA-z]{3}[0-9]|^[a-zA-Z]{2}[0-9]/",$fl,$reg_num);
    if($reg_num[0]){
      if(!$hash[$reg_num[0]]){
        $hash[$reg_num[0]] = array();
      }
    }else{
      if(!$hash["etc"]){
        $hash["etc"] = array();
      }
    }
  }
  $drc->close();
  //カテゴリした中身
  $arr = array_keys($hash);
}
//ヘッダ終わりからbody開始まで
foreach($arr as $key){
print<<<JS1
    <script type="text/javascript">
    $(function(){
        $("button#{$key}").click(function(){
            $("#res").fadeOut(function(){
          $("#loading").show();
          $('p#res').load('./home.php?cate={$key}',function(){
            $("#loading").fadeOut(function(){
              $("#res").show();
              });
            });
          });
        });
              });
  </script>
JS1;
}
print<<<DOC_END
</head>
<body>
<div id="container">
<h1>azucat</h1>
<div id="terminal">
DOC_END;
//パスがディレクトリなら最後に"/"をつけたものを出力。
echo is_dir($file_path)?'<h2>'.$directory.'</h2>':'<h2>'.$file_path.'</h2>';
echo '<a href="'.$parent_directory.'">一つ上のディレクトリへ</a>';
//パスがディレクトリかファイルかで出力内容が変わる
//もしホームディレクトリだったら
if(!$query){
  $drc=dir($directory);
  //tdtrをうまくやるためにカウントで調節
  $count = -2;

  echo '<p>';
  sort($arr);
  foreach( $arr as $value ){
      echo "<button id='".$value."'>".$value."</button>&nbsp|&nbsp"; // 改行しながら値を表示
  }
  echo '・ω・';
  echo "<div id='loading'><img src='images/loading.gif' /></div><p id='res'>";
    echo "見たい学年のボタンを押してくださいです</p>";
  $drc->close();
  //ホームディレクトリ以外のディレクトリの場合
}else{
  if(is_dir($file_path)){
    $drc=dir($directory);
    print("<table>");
    //tdtrをうまくやるためにカウントで調節
    $count = -2;
    while($fl=$drc->read()){
      //ディレクトリの中身を五列表示
      if($count%4==0)print("<tr>");
      $lfl = $directory.$fl;
      $din = pathinfo($lfl);
      if(is_dir($lfl) && ($fl!=".." && $fl!=".")){
        print('<td><a class="dir" href="'.$url.$query.'/'.$din["basename"].'">'.$din["basename"].'</a>&nbsp;</td>');
      } else if($fl!=".." && $fl!=".") {
        print("<td>");
        $file_url = $lfl;
        print('<a href="'.$url.$query.'/'.$fl.'">'.$fl."</a>&nbsp;");
        print("</td>");
      }
      $count++;
      if($count%4==0)print("</tr>");
    }
    print("</table>");
    $drc->close();
  }else{
    //azucatぺろぺろぺろぺろ（＾ω＾）
    echo '<p><a href="'.$download_url.$query.'">download</a></p>';
    echo '<p id="source"><br />';
    $fp = @fopen($file_path, "r");
    while( ! feof( $fp ) ){
      echo htmlspecialchars(fgets( $fp, 9182 )) . "<br>";
    }
    fclose($fp);
    echo '</p>';
  }
}
/*
//拡張子の判別 
$ext2 = pathinfo($url_path);
$ext = $ext2["extension"];
//現在のパスがファイルかディレクトリかチェック
switch($ext){
  case false:
  case "html":case "php":case "perl":
  $file = $_GET["file"];
  //print $file;
  default:
  //echo 'this is file';
} 
*/
?>
</div>
</div>
</body>
</html>
