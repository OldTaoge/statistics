<?php
$class=isset($_GET['class'])?$_GET['class']:'';
$subject=isset($_GET['subject'])?$_GET['subject']:'';
$auth=isset($_GET['auth'])?$_GET['auth']:'';
$group=isset($_GET['group'])?$_GET['group']:'';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<title>统计结果</title>
<style type="text/css">
.Fen_18px {
	font-size: 18px;
}
</style>
</head>
<body>
<form action="" method="get">
<table align="center">
  <tr>
    <td><span style="font-size: 36px; font-family: Georgia, 'Times New Roman', Times, serif; font-weight: bold; text-align: center;">信息统计结果</span></td>
  </tr>
</table>
<table width="90%" border="1">
  <tr>
    <td width="20%" class="Fen_18px">请选择班级:</td>
    <td>
      <select onchange="window.location=this.value;">
        <option value=" "> </option>
        <?php
		    include_once("conn/conn.php");
            $j=$i=0;
            $sql = "SELECT * FROM `recond_information`";
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
                        echo "<option value=result.php?class=$class&auth=$auth selected=selected>$class</option>";
                    }
                    else
                    {
                        echo "<option value=result.php?class=$classes[$i]&auth=$auth>$classes[$i]</option>";
                    }
                }
            }
            ?>
      </select>
    </td>
  </tr>
  <tr>
    <td class="Fen_18px">请选择项目:</td>
    <td>
    <select name="subject" onchange="window.location=this.value;">
        <option value=" "> </option>
            <?php
                $sql = "SELECT * FROM `recond_information` WHERE `class` = $class";
                $result = mysqli_query($conn, $sql);
                while($myrow = mysqli_fetch_array($result))
                    {
                        if($subject==$myrow[recond_subject])
                        {
                            echo "<option value=result.php?class=$class&subject=$myrow[recond_subject]&auth=$auth selected=selected>$myrow[recond_explain]</option>";
                        }
                        else
                        {
                            echo "<option value=result.php?class=$class&subject=$myrow[recond_subject]&auth=$auth>$myrow[recond_explain]</option>";
                        }
                    }

            ?>

    </select>
    </td>
  </tr>
  <?php
    $sql = "SELECT * FROM `recond_information` WHERE `recond_subject` = $subject";
    $result = mysqli_query($conn, $sql);
    $myrow=mysqli_fetch_array($result);
//     print_r($myrow);
    if ($myrow['special_group'] != '0')
    {
        $group_data=unserialize($myrow['special_group']);
//         print_r($group_data);

        ?>
    <tr>
    <td class="Fen_18px">请选择分组:</td>
    <td>
    <select name="group" onchange="window.location=this.value;">
        <option value=" "> </option>
            <?php
                for ($i=0;$i<$group_data['count'];$i++)
                    {
                        $i_exp=$i.'_exp';
                        $i_gp=$i.'_gp';
                        if($group == $group_data[$i_gp])
                        {
                            echo "<option value=result.php?class=$class&subject=$myrow[recond_subject]&group=$group_data[$i_gp]&auth=$auth selected=selected>$group_data[$i_exp]</option>";
                        }
                        else
                        {
                            echo "<option value=result.php?class=$class&subject=$myrow[recond_subject]&group=$group_data[$i_gp]&auth=$auth>$group_data[$i_exp]</option>";
                        }
                    }
                    ?>
                      <tr>
                        <td class="Fen_18px">已记录：</th>
                        <td>
                          <table>
                        <?php

                    $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
                    $result = mysqli_query($conn, $sql);
                    $myrow = mysqli_fetch_array($result);

                    $upload_file_number=$myrow['upload_file'];

                    $sql="SELECT * FROM `$myrow[recond_table_name]` ORDER BY `id` DESC LIMIT 0,$myrow[number_of_person]";

                    $j=$i=$myrow[number_of_person];
                    $result=mysqli_query($conn, $sql);
                    while($myrow=mysqli_fetch_array($result))
                    {
                        $pid[$i]=$myrow['p_id'];
                        $persons[$i]=$myrow['re'];
                        $dt[$i]=$myrow['dt'];
                        $i--;
                    }
                    $i=0;
                    $max=$j;
                    for($i=1;$i<=$max;$i++)
                    {
                    if($persons[$i]==1)
                        {

                        $sql = "SELECT * FROM `group_91` WHERE `p_id` = $pid[$i]";
                        $result = mysqli_query($conn, $sql);
                        $myrow = mysqli_fetch_array($result);
                        if ($myrow['group'] != $group_data[$group])
                            continue;

                        $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
                        $result = mysqli_query($conn, $sql);
                        $myrow = mysqli_fetch_array($result);

                        $sql = "SELECT * FROM `name_$class` WHERE `id` = $pid[$i]";
                        $result = mysqli_query($conn, $sql);
                        $myrow = mysqli_fetch_array($result);
                        $name=$myrow[name];

                        $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
                        $result = mysqli_query($conn, $sql);
                        $myrow = mysqli_fetch_array($result);
                        $information_explain=unserialize($myrow['information_data']);


                        $sql_photo = "SELECT * FROM `$myrow[recond_table_name]` WHERE `p_id` = $pid[$i] ORDER BY `$myrow[recond_table_name]`.`id`  DESC  LIMIT 0,$myrow[number_of_person] ";
                            $result=mysqli_query($conn, $sql_photo);
                            $myrow=mysqli_fetch_array($result);
                                $information_data=unserialize($myrow['data']);

                                    $informations=array();
                                    foreach($information_data as $tab_key => $tab_value)
                                    {
                                    $informations[$tab_key]=$tab_value;
                        }
                        foreach($information_explain as $tab_key => $tab_value)
                        {
                        $informations[$tab_key]=$tab_value;
                        }

                        echo "<tr><td class= Fen_18px>$name</td><td>$dt[$i]</td>";

                            for($l=0;$l<$informations['count'];$l++)
                            {
                            $l_explain=$l.'_explain';
                            $l_data=$l.'_data';
                            echo "<td class= Fen_18px>$informations[$l_explain]：</td><td>$informations[$l_data]</td>";
                            }

                            for($k=0; $k < $upload_file_number; $k++)
                            {
                            $upload_file_name = "upload_file".$k;
                            echo "<td><img src=$myrow[$upload_file_name] /></td>";
                            }

                                echo "</tr>";

                            }

                            }

                            ?>

                        </table></th>
                      </tr>
                      <tr>
                        <td class="Fen_18px">未记录：</th>
                        <td>
                          <table>
                              <?php
                              for($i=1;$i<=$max;$i++)
                              {
                              if($persons[$i]==0)
                                  {
                                  $sql = "SELECT * FROM `group_91` WHERE `p_id` = $pid[$i]";
                                  $result = mysqli_query($conn, $sql);
                                  $myrow = mysqli_fetch_array($result);
                                  if ($myrow['group'] != $group)
                                      continue;

                                  $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
                                  $result = mysqli_query($conn, $sql);
                                  $myrow = mysqli_fetch_array($result);

                                  $sql = "SELECT * FROM `name_$class` WHERE `id` = $pid[$i]";
                                  $result = mysqli_query($conn, $sql);
                                  $myrow = mysqli_fetch_array($result);

                                  echo "<tr><td class= Fen_18px>$myrow[name]</td></tr>";
                                  }

                              }
                    		  echo '</table></th>';

            ?>

    </select>
    </td>
  </tr>
        <?php
    }
    if ($group == '')
    {



  ?>
  <tr>
    <td class="Fen_18px">已记录：</th>
    <td>
      <table>
    <?php



    $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
    $result = mysqli_query($conn, $sql);
    $myrow = mysqli_fetch_array($result);

    $upload_file_number=$myrow['upload_file'];

    $sql="SELECT * FROM `$myrow[recond_table_name]` ORDER BY `id` DESC LIMIT 0,$myrow[number_of_person]";

    $j=$i=$myrow[number_of_person];
    $result=mysqli_query($conn, $sql);
    while($myrow=mysqli_fetch_array($result))
    {
        $pid[$i]=$myrow['p_id'];
        $persons[$i]=$myrow['re'];
        $dt[$i]=$myrow['dt'];
        $i--;
    }
    $i=0;
    $max=$j;
    for($i=1;$i<=$max;$i++)
    {
    if($persons[$i]==1)
        {

        $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
        $result = mysqli_query($conn, $sql);
        $myrow = mysqli_fetch_array($result);

        $sql = "SELECT * FROM `name_$class` WHERE `id` = $pid[$i]";
        $result = mysqli_query($conn, $sql);
        $myrow = mysqli_fetch_array($result);
        $name=$myrow[name];

        $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
        $result = mysqli_query($conn, $sql);
        $myrow = mysqli_fetch_array($result);
        $information_explain=unserialize($myrow['information_data']);


        $sql_photo = "SELECT * FROM `$myrow[recond_table_name]` WHERE `p_id` = $pid[$i] ORDER BY `$myrow[recond_table_name]`.`id`  DESC  LIMIT 0,$myrow[number_of_person] ";
            $result=mysqli_query($conn, $sql_photo);
            $myrow=mysqli_fetch_array($result);
                $information_data=unserialize($myrow['data']);

                    $informations=array();
                    foreach($information_data as $tab_key => $tab_value)
                    {
                    $informations[$tab_key]=$tab_value;
        }
        foreach($information_explain as $tab_key => $tab_value)
        {
        $informations[$tab_key]=$tab_value;
        }

        echo "<tr><td class= Fen_18px>$name</td><td>$dt[$i]</td>";

            for($l=0;$l<$informations['count'];$l++)
            {
            $l_explain=$l.'_explain';
            $l_data=$l.'_data';
            echo "<td class= Fen_18px>$informations[$l_explain]：</td><td>$informations[$l_data]</td>";
            }

            for($k=0; $k < $upload_file_number; $k++)
            {
            $upload_file_name = "upload_file".$k;
            echo "<td><img src=$myrow[$upload_file_name] /></td>";
            }

                echo "</tr>";

            }

            }

            ?>

        </table></th>
      </tr>
      <tr>
        <td class="Fen_18px">未记录：</th>
        <td>
          <table>
              <?php
              for($i=1;$i<=$max;$i++)
              {
              if($persons[$i]==0)
                  {
                  $sql = "SELECT * FROM `recond_information` WHERE `class`=$class and `recond_subject`='$subject'";
                  $result = mysqli_query($conn, $sql);
                  $myrow = mysqli_fetch_array($result);

                  $sql = "SELECT * FROM `name_$class` WHERE `id` = $pid[$i]";
                  $result = mysqli_query($conn, $sql);
                  $myrow = mysqli_fetch_array($result);

                  echo "<tr><td class= Fen_18px>$myrow[name]</td></tr>";
                  }

              }
    		  echo '</table></th>';
  }
          ?>


  </tr>
<?php
if($auth == "auth_keys")
{
?>
  <tr><td colspan="2"><input type="button" value="清空数据" onclick= window.location.href="new.php?<?php echo 'type=restart_recond&class='.$class.'&subject='.$subject.'&auth=auth_key';?>" /></td></tr>
  <tr><td colspan="2"><input type="button" value="添加说明" onclick= window.location.href="senior.php?<?php echo 'tl=1&class='.$class.'&subject='.$subject.'&auth=auth_key';?>" /></td></tr>
  <tr><td colspan="2"><input type="button" value="高级设置" onclick= window.location.href="senior.php?<?php echo 'type=list&class='.$class.'&subject='.$subject.'&auth=auth_key';?>" /></td></tr>
<?php
}
?>
</table>

</form>
</body>
</html>
