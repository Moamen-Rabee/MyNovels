<?php
    include "includes/connect.php";

    $novel_id = $_GET['novel_id'];
    $userid = $_GET['userid'];

    $stmt = $con->prepare('INSERT INTO likes (novel_id,u_name) VALUES (?,?)');
    $stmt->execute(array($novel_id,$userid));

?>


<div style="display:inline" id="<?php echo $novel['id'] ?>"><a href="javascript:void(0)" onclick="likes('deslike.php?novel_id=<?php echo $novel_id ?>&userid=<?php echo $userid ?>',<?php echo $novel_id ?>)" class="link"><i class="fas fa-thumbs-up"></i>اعجبنى</a> </div>