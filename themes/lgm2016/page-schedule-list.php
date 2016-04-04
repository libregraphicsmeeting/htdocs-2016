<?php

$pageTitle = get_the_title();

include(get_stylesheet_directory().'/page-schedule-class.php');
$pageSchedule = new LGMPageSchedule();
?>
<style>
.talk img {
    max-width:300px;
}
</style>
<?php include(get_stylesheet_directory().'/header.php') ?>

    	<div id='wrap'>
            <?= /* "<pre>".print_r($pageSchedule->getTalk(404), 1)."</pre>" */ ""  ?>
            <h1><?= $pageTitle ?></h1>

            <?php
            $currentDay = '';
            while ($item = $pageSchedule->next()) : if ($item['time']) :
            ?>
            <?php if ($currentDay != $item['day']) : $currentDay = $item['day']; ?>
            <h2 class="date"><?= $item['weekday'] ?>, April <?= $item['day'] ?></h2>
            <?php endif; ?>
            <div class="talk">
                <h2><?= $item['title'] ?></h2>
                <?php if ($item['lastname'] != '') : ?>
                <h3><?= $item['speakers'] ?></h3>
                <?php endif; ?>
                <p class="time"><?= sprintf("%s (%s')", $item['time'], $item['duration']) ?></p>
                <?= $item['content'] ?>
            </div>
            <?php endif; endwhile; ?>

    </div>

<?php include(get_stylesheet_directory().'/footer.php') ?>
