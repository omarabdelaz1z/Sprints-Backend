<?php


["fromPage"=> $fromPage, "untilPage"=>$untilPage] = $pagination['navigation'];

["previous" => $previous, "next" =>$next] = $pagination['page'];

$previousDisabled = $previous < 0 ? 'disabled' : '';
$nextDisabled = $next < 0 ? 'disabled' : '';
?>

<ul class="pagination">
    <li class="page-item <?= $previousDisabled ?>">    
        <a  href="index.php?page=<?=$previous?>"
            data-page="<?=$previous?>"
            class="page-link"
            tabindex="-1">Previous</a>
    </li>
        <?php
            for($current = $fromPage; $current <= $untilPage; $current++) {?>
                <?php if($current === $page) { ?>
                    <li data-page="<%= page %>" class="page-item active">
                    <a href="index.php?page=<?=$current?>" class="page-link"><?=$current?></a>
                <?php } else {?>
                    <li data-page="<%= page %>" class="page-item">
                <a href="index.php?page=<?=$current?>" class="page-link"><?=$current?></a>
                </li>
                <?php } ?>
            <?php } 
        ?>
    <li class="page-item <?= $nextDisabled ?>">
        <a  href="index.php?page=<?=$next?>"
            data-page="<?=$previous?>"
            class="page-link">Next</a>
    </li>
</ul>