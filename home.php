<?php
$url = "http://www.mast.tsukuba.ac.jp/~s0911554/azucat"; 
$cate = $_GET['cate']; 
$cont = array();
  $drc=dir('/home/');
  if($cate=='etc'){
    while($fl=$drc->read()){
      //正規表現で、学年ごとにディレクトリを分ける
      preg_match("/^[a-zA-z][0-9][0-9]|^[a-zA-z]{3}[0-9]|^[a-zA-Z]{2}[0-9]/",$fl,$reg_num);
      if(!$reg_num[0]){
        array_push($cont,$fl);
      }
    }
  }else{
    while($fl=$drc->read()){
      //正規表現で、学年ごとにディレクトリを分ける
      preg_match("/^{$cate}/",$fl,$reg_num);
      if($reg_num[0]){
        array_push($cont,$fl);
      }
    }
  }
  sort($cont);
  echo "<table>";
  foreach($cont as $val){
    //ディレクトリの中身を五列表示
    //ホームでカテゴリ分けしている内容
    if($count%10==0)echo "<tr>";
    if($val !=".." && $val!="."){
      echo '<td>';
      if($count%5==0){
      echo '&nbsp;<a class="dir" href="'.$url.'/'.$val.'">'.$val."</a>&nbsp;";
      }else{
      echo '<a class="dir" href="'.$url.'/'.$val.'">'.$val."</a>&nbsp;";
      }
      echo "</td>";
      $count++;
    }

    if($count%10==0)echo "</tr>";

    if($count%100==0&&$count!=0)echo "</table><table><tr>";
  }
echo "</table>";

$drc->close();

?>
