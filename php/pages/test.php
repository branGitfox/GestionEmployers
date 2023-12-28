<?php 

$connection = mysqli_connect('127.0.0.1','root','','tavaratra');
$tables = array();
$result = mysqli_query($connection,"SHOW TABLES");
while($row = mysqli_fetch_row($result)){
  $tables[] = $row[0];
}
$return = '';
foreach($tables as $table){
  $result = mysqli_query($connection,"SELECT * FROM ".$table);
  $num_fields = mysqli_num_fields($result);
  
  $return .= 'DROP TABLE '.$table.';';
  $row2 = mysqli_fetch_row(mysqli_query($connection,"SHOW CREATE TABLE ".$table));
  $return .= "\n\n".$row2[1].";\n\n";
  
  for($i=0;$i<$num_fields;$i++){
    while($row = mysqli_fetch_row($result)){
      $return .= "INSERT INTO ".$table." VALUES(";
      for($j=0;$j<$num_fields;$j++){
        $row[$j] = addslashes($row[$j]);
        if(isset($row[$j])){ $return .= '"'.$row[$j].'"';}
        else{ $return .= '""';}
        if($j<$num_fields-1){ $return .= ',';}
      }
      $return .= ");\n";
    }
  }
  $return .= "\n\n\n";
}

$db_name = date('Y-m-d').'.sql';
//save file
$handle = fopen($db_name,"w+");
fwrite($handle,$return);
fclose($handle);
echo "Successfully backed up";

// /**IMPORTATION D"UNE BASE DE DONNEE */
// <?php
// $connection = mysqli_connect('localhost','root','','test');
// $filename = 'backup.sql';
// $handle = fopen($filename,"r+");
// $contents = fread($handle,filesize($filename));
// $sql = explode(';',$contents);
// foreach($sql as $query){
//   $result = mysqli_query($connection,$query);
//   if($result){
//       echo '<tr><td><br></td></tr>';
//       echo '<tr><td>'.$query.' <b>SUCCESS</b></td></tr>';
//       echo '<tr><td><br></td></tr>';
//   }
// }
// fclose($handle);
// echo 'Successfully imported';
?>

