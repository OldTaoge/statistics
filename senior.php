<?php
$class_p=isset($_POST['class'])?$_POST['class']:'';
$class=isset($_GET['class'])?$_GET['class']:$class_p;
$subject_p=isset($_POST['subject'])?$_POST['subject']:'';
$subject=isset($_GET['subject'])?$_GET['subject']:$subject_p;
$tl=isset($_POST['tl'])?$_POST['tl']:(isset($_GET['tl'])?$_GET['tl']:'');
$type_p=isset($_POST['type'])?$_POST['type']:'';
$type=isset($_GET['type'])?$_GET['type']:$type_p;
$auth=isset($_GET['auth'])?$_GET['auth']:'';

if($tl == 1)
{
    if($subject=='')
    {
        header("Refresh:3;url=result.php?auth=$auth");
        echo "请注意不要空选。<br>三秒后返回。";
        exit();
    }
?>
    <form action="add.php" method="post">
    <table width="90%" border="1">
      <tr>
        <td>请在此写下您的留言</td>
      </tr>
      <tr>
        <td><textarea name="ts"></textarea></td>
      </tr>
      <tr>
        <td>
          <input type="hidden" name="tl" value=1 />
          <input type="hidden" name="subject" value="<?php echo $subject;?>" />
          <input type="hidden" name="class" value="<?php echo $class;?>" />
          <input type="submit" value="提交" />
        </td>
      </tr>
    </table>
    </form>
<?php
}

if($type=='list')
{
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    	<title>高级</title>
    </head>
    <body>
		<table width="90%" border="1">
  <tr>
    <th colspan="2">请选择您要进行的操作</th>
  </tr>
  <tr>
    <td width="20%">新建统计</td>
    <td>
      <form action="new.php" method="post">
        <input type="hidden" name="type" value="new_reconds" />
        <input type="hidden" name="subject" value="<?php echo date('YmdHis');?>" />
        <table border="0">
          <tr>
            <td>
              &nbsp;班级：
            </td>
            <td>
              &nbsp;
              <select name="class">
        <?php
		    include_once("conn/conn.php");
            $j=$i=0;
            $sql = "SELECT `class` FROM `class_information`";
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
                  echo "<option value=$classes[$i] >$classes[$i]</option>";
                }
            }
            ?>
      </select>
            </td>
          </tr>
          <tr>
            <td>
              &nbsp;项目：
            </td>
            <td>
              &nbsp;<input autocomplete="off" name="recond_explain" type="text" />
            </td>
          </tr>
          <tr>
            <td colspan="2">
            &nbsp;是否需要上传照片:<span style="font-size: 9px">(0:不需要，其它数字表示数量)</span>              <input type="text" autocomplete="off" name="upload_number" value="0" /></td>
          </tr>
          <tr>
            <td colspan="2">
              &nbsp;必须上传：
              <input type="checkbox" name="must_upload[]" value="1" />
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" value="提交" />
            </td>
          </tr>
        </table>

      </form>
    </td>
  </tr>
  <tr>
    <td>删除统计</td>
    <td>
    <form action="new.php" method="post">
        <input type="hidden" name="type" value="del_reconds" />
        <input type="hidden" name="class" value="<?php echo $class;?>"/>
        <table border="0">
          <tr>
            <td>
              &nbsp;班级：
            </td>
            <td>
              &nbsp;
            <select onChange="window.location=this.value;">
            <option value=" "> </option>
        <?php
		    include_once("conn/conn.php");
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
                        echo "<option value=senior.php?type=list&class=$class selected=selected>$class</option>";
                    }
                    else
                    {
                        echo "<option value=senior.php?type=list&class=$classes[$i]>$classes[$i]</option>";
                    }
                }
            }
            ?>
      </select>
            </td>
          </tr>
          <tr>
            <td>
              &nbsp;项目：
            </td>
            <td>
              &nbsp;
              <select name="subject">
                      <?php
                          $sql = "SELECT * FROM `recond_information` WHERE `class` = $class";
                          $result = mysqli_query($conn, $sql);
                          while($myrow = mysqli_fetch_array($result))
                              {
                                    echo "<option value=$myrow[recond_subject]>$myrow[recond_explain]</option>";
                              }
                      ?>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" value="提交" />
            </td>
          </tr>
          </form>
            </td>
          </tr>
        </table>

    </td>
  </tr>
  <tr>
    <td>
      新建班级：
    </td>
    <td>
      <form action="new.php" method="post">
      <table>
        <tr>
          <td>
            班级：
          </td>
          <td>
            <input type="text" autocomplete="off" name="class"/>
          </td>
        </tr>
        <tr>
          <td>
            人数：
          </td>
          <td>
            <input type="text" autocomplete="off" name="number_of_person"/>
          </td>
        </tr>
        <tr>
          <td>
            <input type="submit" value="提交"/>
          </td>
        </tr>
      </table>
      <input type="hidden" name="type" value="add_class" />
      </form>
    </td>
  </tr>
  <tr>
    <td>
      删除班级：
    </td>
    <td>
      <form action="new.php" method="post">
      <table>
        <tr>
          <td>
            班级：
          </td>
          <td>
            <select name="class">
        <?php
		    include_once("conn/conn.php");
            $j=$i=0;
            $sql = "SELECT `class` FROM `class_information`";
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
                  echo "<option value=$classes[$i] >$classes[$i]</option>";
                }
            }
            ?>
      </select>
          </td>
        </tr>
        <tr>
          <td>
            <input type="submit" value="提交"/>
          </td>
        </tr>
      </table>
      <input type="hidden" name="type" value="del_class" />
      </form>
    </td>
  </tr>

</table>


    </body>
    </html>
    <?php
}
else
{

}
?>
