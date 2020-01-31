<?php
    include_once("conn/conn.php");
    $class=isset($_POST['class'])?$_POST['class']:(isset($_GET['class'])?$_GET['class']:'');
	$addtype=isset($_POST['addtype'])?$_POST['addtype']:(isset($_GET['addtype'])?$_GET['addtype']:'');
	$subject=isset($_POST['subject'])?$_POST['subject']:(isset($_GET['subject'])?$_GET['subject']:'');
	$name=isset($_POST['name'])?$_POST['name']:(isset($_GET['name'])?$_GET['name']:'');
	$ask=isset($_POST['ask'])?$_POST['ask']:(isset($_GET['ask'])?$_GET['ask']:0);
	$tl=isset($_POST['tl'])?$_POST['tl']:'';
	$ts=isset($_POST['ts'])?$_POST['ts']:'';
	$auth=isset($_GET['auth'])?$_GET['auth']:'';

	if ($tl!='')
	{
	    $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
	    $result = mysqli_query($conn, $sql);
	    $myrow=mysqli_fetch_array($result);

	    $sql="UPDATE `recond_information` SET `tl` = '$ts' WHERE `recond_information`.`id` = $myrow[id]";
	    mysqli_query($conn, $sql);
	    $url="<span style=font-size:36px>添加完成</span>";
	    $url=urlencode($url);
	    $jump=urlencode("result.php?auth=$auth");
	    header("Location:display.php?display=$url&jumpto=$jump");
	    exit();
	}
	if ($addtype=='as')
	{
	    if($ask==1)
	    {

	        $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
	        $result = mysqli_query($conn, $sql);
	        $myrow=mysqli_fetch_array($result);

	        $information_data=unserialize($myrow['information_data']);
	        $informations=array();
	        for($i=0;$i<$information_data['count'];$i++)
	        {
	        $informations['count']=$information_data['count'];
	        $informations[$i.'_data']=$_POST['information_'.$i];
	        }
	        $informations=serialize($informations);

	        $sql = "SELECT * FROM `$myrow[recond_table_name]` ORDER BY `$myrow[recond_table_name]`.`id`  DESC  LIMIT 0,$myrow[number_of_person]";
	        $recond_table_name=$myrow[recond_table_name];
	        $result = mysqli_query($conn, $sql);

	        while($myrow = mysqli_fetch_array($result))
	        {
	            //print_r($myrow);
	            //echo $myrow[p_id].$name.'<hr />';
	            if($myrow[p_id]==$name)
	            {

	                $time = date('Y-m-d H:i:s');
	                if ($information_data['count']==0)
	                {
	                   $sql = "UPDATE `$recond_table_name` SET `re` = 1 , `dt`= '$time' WHERE `$recond_table_name`.`id` = $myrow[id]";
	                }
	                else
	                {
	                    $sql = "UPDATE `$recond_table_name` SET `re` = 1 , `dt`= '$time' , `data`= '$informations' WHERE `$recond_table_name`.`id` = $myrow[id]";
	                }
	                //echo $sql;
	                if(mysqli_query($conn, $sql))
	                {
	                    $url="<span style=font-size:36px>添加完成</span>";
	                    $url=urlencode($url);
	                    $jump=urlencode("index.php");
	                    header("Location:display.php?display=$url&jumpto=$jump");
	                    exit();

// 	                    header("Refresh:3;url=index.php");
// 	                    echo"添加完成";

	                }
	                else
	                {
	                    $url="<span style=font-size:36px>添加失败······</span>";
	                    $url=urlencode($url);
	                    $jump=urlencode("index.php");
	                    header("Location:display.php?display=$url&jumpto=$jump");
	                    exit();
// 	                    echo"添加失败······<br />请将此页面发送至duwentao2006@gmail.com<br />Error Info: <br />".mysqli_error($conn);

	                }

	            }

	        }

	    }
	    else
	    {
            if($name == 0)
            {
                $url="<span style=font-size:36px>请选择姓名 </span>";
                $url=urlencode($url);
                $jump=urlencode("index.php");
                header("Location:display.php?display=$url&jumpto=$jump");
                exit();
            }

	        $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
	        //echo $sql;
	        $result = mysqli_query($conn, $sql);
	        //print_r($result);
	        $myrow=mysqli_fetch_array($result);
	        if (!empty($_FILES['upload']))
	        {
// 	             	            print_r($_FILES['upload']);
// 	             	            echo "<hr />";
	            for($i = 0; $i < $myrow['upload_file']; $i++)
	            {
	                if($_FILES['upload'][error][$i] == 4 && $myrow['must_upload'] == 1)
	                {
	                    $url="<span style=font-size:36px>提交图片失败，请检查图片数量 </span>";
                        $url=urlencode($url);
                        $jump=urlencode("index.php");
	                    header("Location:display.php?display=$url&jumpto=$jump");
	                    exit();
	                }
	                if($_FILES['upload'][error][$i] == 1 || $_FILES['upload'][error][$i] == 2 || $_FILES['upload'][error][$i] == 3 || $_FILES['upload'][error][$i] == 6 || $_FILES['upload'][error][$i] == 7)
	                {
	                    $url="<span style=font-size:36px>提交图片失败 </span>";
                        $url=urlencode($url);
                        $jump=urlencode("index.php");
	                    header("Location:display.php?display=$url&jumpto=$jump");
	                    exit();
	                }
	                if($_FILES['upload'][error][$i] == 0)
	                {
// 	                    $time = date('Y-m-d H:i:s');
	                    $month = date('m');
	                    $day = date('d');
	                    mkdir("./upload/", 0777);
	                    mkdir("./upload/$subject/", 0777);
	                    mkdir("./upload/$subject/$month/", 0777);
	                    mkdir("./upload/$subject/$month/$day/", 0777);
	                    mkdir("./upload/$subject/$month/$day/$class/", 0777);
	                    mkdir("./upload/$subject/$month/$day/$class/$name/", 0777);
	                    $filename = $_FILES['upload']['name'][$i];
// 	                    echo $_FILES['upload']['tmp_name'][$i]."<hr />";
// 	                    echo "./upload/$month/$day/$class/$name/".$filename;
	                    move_uploaded_file($_FILES['upload']['tmp_name'][$i], "./upload/$subject/$month/$day/$class/$name/".$filename);
	                    $filename = "./upload/$subject/$month/$day/$class/$name/".$filename;

	                    $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
	                    $result = mysqli_query($conn, $sql);
	                    $myrow=mysqli_fetch_array($result);
	                    $recond_table_name=$myrow[recond_table_name];

	                    $sql = "UPDATE `$myrow[recond_table_name]` SET `upload_file$i`='$filename' WHERE `p_id`= $name ORDER BY `$myrow[recond_table_name]`.`id`  DESC ";
// 	                    $sql = "SELECT * FROM `` WHERE `p_id` = $p_id ORDER BY `$myrow[recond_table_name]`.`id`  DESC  LIMIT 0,$myrow[number_of_person] ";

 	                    //echo $sql;
	                    mysqli_query($conn, $sql);
	                }
	                if($_FILES['upload'][error][$i] == 4)
	                {
	                    $sql = "UPDATE `$myrow[recond_table_name]` SET `upload_file$i`='' WHERE `p_id`= $name ORDER BY `$myrow[recond_table_name]`.`id`  DESC ";
	                    mysqli_query($conn, $sql);
	                }
	            }
	        }



$url = <<< str
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<style type="text/css">
.Fen_18px {
	font-size: 18px;
}
</style>
</head>
<body>
<table align="center">
      <tr>
        <td>
	<span style="font-size: 36px; font-family: Georgia, 'Times New Roman', Times, serif; font-weight: bold; text-align: center;">请确认以下信息</span>
        </td>
      </tr>
    </table>
<table width="90%" border="1">
  <tr>
    <td width="20%" class="Fen_18px">班级</td>
    <td>&nbsp;$class</td>
  </tr>
  <tr>
    <td width="20%" class="Fen_18px">项目</td>
    <td>&nbsp;$myrow[recond_explain]</td>
  </tr>
str
;
    $sql_photo = "SELECT * FROM `$myrow[recond_table_name]` WHERE `p_id` = $name ORDER BY `$myrow[recond_table_name]`.`id`  DESC  LIMIT 0,$myrow[number_of_person] ";
    //echo $sql_photo;


    $sql = "SELECT * FROM `name_$myrow[class]` WHERE `id` = $name";
    $result = mysqli_query($conn, $sql);
    $myrow=mysqli_fetch_array($result);
    $p_id=$name;
    $name = $myrow[name];


$url = $url . <<< str
  <tr>
    <td width="20%" class="Fen_18px">姓名</td>
    <td>&nbsp;$name</td>
  </tr>
str
;
$result = mysqli_query($conn, $sql_photo);
$myrow = mysqli_fetch_array($result);
for($j = 0; $j < $i ; $j++)
{
    $upload_file_name = "upload_file".$j;
    $url = $url . <<< str
<tr>
        <td colspan="2">
          <img src="$myrow[$upload_file_name]" />
        </td>
      </tr>
str
;
}
$sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
$result = mysqli_query($conn, $sql);
$myrow=mysqli_fetch_array($result);
if($myrow['upload_information']==1)
    {
$url = $url . <<< str

      <tr><td>
      <p>附加数据：</p>
      </td>
      <td>
        <table>
str
;
        $information_data=unserialize($myrow['information_data']);
        for($i=0;$i<$information_data['count'];$i++)
        {
            $information=$_POST['information_'.$i];
            $i_explain=$i.'_explain';
$url = $url . <<< str
            <tr>
                <td>$information_data[$i_explain]：
                </td>
                <td>$information
                </td>
            </tr>
str
;

        }
        $url = $url . <<< str
        </table>
    </td>
  </tr>
  <tr>
    <td><form action="add.php" method="post">

str
;
     }

for($i=0;$i<$information_data['count'];$i++)
    {
    $information=$_POST['information_'.$i];
    $url = $url . "<input type=hidden name=information_$i value=$information />";
    }
if($i==0)
{
    $url = $url . '  <tr><td><form action="add.php" method="post">';
}
$url = $url . <<< str

    <input type="hidden" name="ask" value=1 />
    <input type="hidden" name="class" value="$class" />
    <input type="hidden" name="addtype" value="as"/>
    <input type="hidden" name="subject" value="$subject" />
    <input type="hidden" name="name" value="$p_id" />
    <input type="submit" value="确认提交"/>

    </form></td>
    <td><form action="index.php" method="post"><input type="submit" value="重置" /></form></td>
  </tr>
</table>

</body>
</html>
str
;

$url=$url.$url2;
$url=urlencode($url);
$jump=urlencode("index.php");
$sjs=mt_rand();
$time=date("YmdHis");
$filename=md5($time.$sjs);
$file=fopen("/tmp/$filename", "w");
fwrite($file, $url);
header("Location:display.php?display=$filename&from=file");

	    }
	}
?>

