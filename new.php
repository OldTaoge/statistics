<?php
include_once("conn/conn.php");
$type=isset($_POST['type'])?$_POST['type']:(isset($_GET['type'])?$_GET['type']:'');
$class=isset($_POST['class'])?$_POST['class']:(isset($_GET['class'])?$_GET['class']:'');
$subject=isset($_POST['subject'])?$_POST['subject']:(isset($_GET['subject'])?$_GET['subject']:'');
$recond_explain=isset($_POST['recond_explain'])?$_POST['recond_explain']:(isset($_GET['recond_explain'])?$_GET['recond_explain']:'');
$upload_number=isset($_POST['upload_number'])?$_POST['upload_number']:(isset($_GET['upload_number'])?$_GET['upload_number']:'');
$must_upload=0;
$auth=isset($_GET['auth'])?$_GET['auth']:'';

if(($_POST['must_upload']!='' && isset($_POST['must_upload'])) || ($_GET['must_upload']!= '' && isset($_GET['must_upload'])))
{
    $must_upload=isset($_POST['must_upload'])?$_POST['must_upload'][0]:$_GET['must_upload'][0];
}
//print_r($must_upload);

if(isset($_POST['number_of_person'])|| isset($_GET['number_of_person']))
{
    $number_of_person=isset($_POST['number_of_person'])?$_POST['number_of_person']:(isset($_GET['number_of_person'])?$_GET['number_of_person']:'');
}
else
{
    $sql = "SELECT * FROM `class_information` WHERE `class`=$class ";
    $result = mysqli_query($conn, $sql);
    $myrow=mysqli_fetch_array($result);
    $number_of_person=$myrow[number_of_person];

}
$recond_subject=$subject;
//echo "subject=$subject<hr/>class=$class<hr/>type=$type";
if ($type=='new_reconds')
{

    $sql = "INSERT INTO `recond_information`(`recond_table_name`, `recond_explain`, `number_of_person`, `recond_subject`, `class`, `upload_file`, `must_upload`) VALUES ('','$recond_explain','$number_of_person','$recond_subject','$class','$upload_number','$must_upload')";
    mysqli_query($conn, $sql);

    $sql = "SELECT * FROM `recond_information` ORDER BY `id` DESC LIMIT 0,1";
    $result=mysqli_query($conn, $sql);
    $myrow=mysqli_fetch_array($result);
    //print_r($myrow);
    $sql = "UPDATE `recond_information` SET `recond_table_name`='re_$myrow[id]' WHERE id = $myrow[id]";
    mysqli_query($conn, $sql);
    $sql="CREATE TABLE re_$myrow[id](`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`p_id` INT NOT NULL,`re` BOOLEAN NOT NULL,`dt` DATETIME NOT NULL )ENGINE = MyISAM;";
    mysqli_query($conn, $sql);
    for ($i = 0; $i < $myrow['upload_file']; $i++)
    {
        $sql="ALTER TABLE `re_$myrow[id]` ADD `upload_file$i` VARCHAR(500) NOT NULL ";
        mysqli_query($conn, $sql);
    }
    //echo $sql;
    header("Location:new.php?type=restart_recond&class=$class&subject=$subject");
    echo "完成";
}
if ($type=='del_reconds')
{
    $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
    $result = mysqli_query($conn, $sql);
    $myrow = mysqli_fetch_array($result);
    //print_r($myrow);

    if ($myrow['upload_file'] != 0)
    {
        $path = "./upload/$subject/";
        //清空文件夹函数和清空文件夹后删除空文件夹函数的处理
        function deldir($path){
            //如果是目录则继续
            if(is_dir($path)){
                //扫描一个文件夹内的所有文件夹和文件并返回数组
                $p = scandir($path);
                foreach($p as $val){
                    //排除目录中的.和..
                    if($val !="." && $val !=".."){
                        //如果是目录则递归子目录，继续操作
                        if(is_dir($path.$val)){
                            //子目录中操作删除文件夹和文件
                            deldir($path.$val.'/');
                            //目录清空后删除空文件夹
                            @rmdir($path.$val.'/');
                        }else{
                            //如果是文件直接删除
                            unlink($path.$val);
                        }
                    }
                }
            }
        @rmdir($path);
        }
        deldir($path);
    }


    $sql = "DROP TABLE `$myrow[recond_table_name]`";
    mysqli_query($conn, $sql);
    $sql= "DELETE FROM `recond_information` WHERE `recond_information`.`id` = $myrow[id]";
    mysqli_query($conn, $sql);
    header("Refresh:1;url=result.php?auth=$auth");
    echo "完成";
}
if($type=='restart_recond')
{
    if($subject=='')
    {
        //echo $subject;
        //header("Refresh:3;url=result.php");
        echo "请注意不要空选。<br>三秒后返回。";
    }
    $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
    $result = mysqli_query($conn, $sql);
    $myrow = mysqli_fetch_array($result);

    for($i=1;$i<=$myrow[number_of_person];$i++)
    {
        $sql="INSERT INTO `$myrow[recond_table_name]` (`p_id`, `re`, `dt`) VALUES ('$i', '0', '0000-00-00 00:00:00')";
        //echo $sql."\n";
        mysqli_query($conn, $sql);
    }
    header("Refresh:1;url=result.php?auth=$auth");
    echo "完成";

}
if($type=='add_class')
{
  if($class!=''&&$number_of_person!='')
  {
    $sql = "INSERT INTO `class_information` (`class`, `number_of_person`) VALUES ($class, $number_of_person)";
    if(mysqli_query($conn, $sql))
    {
      header("Refresh:1;url=result.php?auth=$auth");
      echo "完成";
    }
    else
    {
      echo "错误<br>$sql";
    }
  }
}
if($type=='del_class')
{
  if($class!='')
  {
    $sql = "DELETE FROM `class_information` WHERE `class_information`.`class` = $class;";
    if(mysqli_query($conn, $sql))
    {
      header("Refresh:1;url=result.php?auth=$auth");
      echo "完成";
    }
    else
    {
      echo "错误<br>$sql";
    }
  }
}
