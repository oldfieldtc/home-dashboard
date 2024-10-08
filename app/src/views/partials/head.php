<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.typekit.net/<?php echo getenv('FONT_KIT'); ?>.css">
    <link rel="stylesheet" href="/public/styles/base.css">
    <?php
        if ($styles) {
            foreach ($styles as $style) {
                echo '<link rel="stylesheet" href="/public/styles/' . $style . '.css">';
            }
        }
    ?>

    <script src="/public/scripts/clock.js" defer></script>
    <title><?php echo $title; ?></title>
</head>
<body>
    <?php require(__ROOT__ . '/src/views/partials/navigation.php') ?>
    <main id="content-begin">
