<!-- File:index.php -->
<!-- Copyright(C) OldTaoge 2020.All rights reserved.-->
<!-- By GPL v3.0 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<?php
	include_once("conn/conn.php");
	$class=isset($_GET['class'])?$_GET['class']:'';
	$subject=isset($_GET['subject'])?$_GET['subject']:'';
	$wjxx=isset($_GET['wjxx'])?$_GET['wjxx']:'';
?>
    <title><?php if($class != '' && $class !=17) echo $class.'班'.$wjxx;?>信息统计系统</title>
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
	<span style="font-size: 36px; font-family: Georgia, 'Times New Roman', Times, serif; font-weight: bold; text-align: center;">信息统计系统</span>
        </td>
      </tr>
    </table>



<form action="add.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="class" value="<?php echo "$class";?>" />
<input type="hidden" name="addtype" value="as"/>
<input type="hidden" name="ask" value=0 />
<input type="hidden" name="subject" value="<?php echo $subject;?>"/>
<table width="90%" border="1">
      <tr>
        <td width="20%" class="Fen_18px">选择班级</td>
        <td>
          <select onchange="window.location=this.value;">
            <option value=" "> </option>
            <?php
            $j=$i=0;
            $sql = "SELECT `class` FROM `recond_information`";
            $result=mysqli_query($conn, $sql);
            while($myrow=mysqli_fetch_array($result))
            {
                $classes[$i]=$myrow['class'];
                $i++;
            }
            $i--;
            $max=$i;
            for($j=$i;$j>0;$j--)
            {
                for($i=$j-1;$i>=0;$i--)
                {
                    if ($classes[$j]==$classes[$i])
                    {
                        $classes[$i]=0;
                    }
                }
            }
            for($i=0;$i<=$max;$i++)
            {
                for($j=$i;$j<=$max;$j++)
                {
                    if($classes[$i]>$classes[$j])
                    {
                        $classes[$i]+=$classes[$j];
                        $classes[$j]=$classes[$i]-$classes[$j];
                        $classes[$i]-=$classes[$j];
                    }
                }
            }
            for($i=0;$i<=$max;$i++)
            {
                if($classes[$i]!=0)
                {
                    if($class==$classes[$i])
                    {
                        echo "<option value=index.php?class=$class selected=selected>$class</option>";
                    }
                    else
                    {
                        echo "<option value=index.php?class=$classes[$i]>$classes[$i]</option>";
                    }
                }
            }
            ?>
          </select>
        </td>
  </tr>
	   <tr>
	    <td width="20%" class="Fen_18px">选择项目</td>
        <td>
          <select onchange="window.location=this.value;"  >
            <option value=" "> </option>
            <?php
                $sql = "SELECT * FROM `recond_information` WHERE `class` = $class";
                $result = mysqli_query($conn, $sql);
                while($myrow = mysqli_fetch_array($result))
                    {
                        if($subject==$myrow[recond_subject])
                        {
                            echo "<option value=index.php?class=$class&subject=$myrow[recond_subject] selected=selected>$myrow[recond_explain]</option>";
                        }
                        else
                        {
                            echo "<option value=index.php?class=$class&subject=$myrow[recond_subject] >$myrow[recond_explain]</option>";
                        }
                    }
            ?>

          </select>
        </td>
  </tr>
      <tr>
        <td width="20%" class="Fen_18px">选择姓名</td>
        <td>
          <select name="name" >
            <option value="0"> </option>
            <?php
//                $sql = "SELECT * FROM `name_$class`";
				$sql = "SELECT * FROM `name_$class` ORDER BY `name_$class`.`id` ASC";
                //echo $sql;
                $result=mysqli_query($conn, $sql);
                while ($myrow = mysqli_fetch_array($result))
                {
                    echo "<option value=$myrow[id]>$myrow[name]</option>";

                }
            ?>
          </select>
        </td>
  </tr>
      <tr>
        <td width="20%" class="Fen_18px">说明</td>
        <td>
            <?php
                $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
                $result = mysqli_query($conn, $sql);
                $myrow=mysqli_fetch_array($result);
                echo nl2br($myrow[tl]);
            ?>
        </td>
  </tr>
  <?php
    for($i = 0; $i < $myrow['upload_file'] ; $i++)
    {
  ?>
  <tr>
    <td colspan="2">
      <p>提交照片：</p><input type="file" name="upload[]"/>
    </td>
  </tr>
  <?php
    }
  ?>
  <?php
    if($myrow['upload_information']==1)
    {
  ?>
  <tr>
    <td >
      <p>附加数据：</p>
      </td>
      <td>
        <table>
        <?php
        $information_data=unserialize($myrow['information_data']);
        for($i=0;$i<$information_data['count'];$i++)
        {
        ?>
            <tr>
                <td><?php echo $information_data[$i.'_explain']?>
                </td>
                <td><input type="text" name="information_<?php echo $i;?>"/>
                </td>
            </tr>
        <?php
        }
        ?>
        </table>
    </td>
  </tr>
  <?php
    }
  ?>
      <tr>
        <td colspan="2"><input type="submit" value="提交" /></td>
  </tr>
</table>
</form>
</body>
</html>
