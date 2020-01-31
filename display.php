<?php
$display=isset($_GET['display'])?urldecode($_GET['display']):'';
$jumpto=isset($_GET['jumpto'])?urldecode($_GET['jumpto']):'';
$from=isset($_GET['from'])?$_GET['from']:'';
if ($jumpto != '')
{
    header("Refresh:3;url=$jumpto");
}
if ($from == 'file')
{
    $file=fopen("/tmp/$display", "r");
    echo urldecode(fread($file, filesize("/tmp/$display")));
    unlink("/tmp/$display");
}
else
{
    echo "$display";
}
?>