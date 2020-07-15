<?php

    include "includes/connect.php";

    $novel_id = $_GET['novel_id'];
    $userid = $_GET['userid'];


    $stmt = $con->prepare('DELETE FROM likes WHERE novel_id = ? AND u_name = ?');
    $stmt->execute(array($novel_id,$userid));

?>

<div style="display:inline" id="<?php echo $novel_id ?>"><a href="javascript:void(0)" onclick="likes('like.php?novel_id=<?php echo $novel_id ?>&userid=<?php echo $userid ?>',<?php echo $novel_id ?>)" class="link"><i class="far fa-thumbs-up"></i>اعجبنى</a> </div>
