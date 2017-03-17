<?php
echo "";
echo "<center>changed OUTPUT folder files permissions</center><br>";
exec("chmod 777 OUTPUT/*");
$show=exec("ls -la OUTPUT/*");
print $show;
?>
