<?php
function dd($item)
{
    echo "<pre>";
    var_dump($item);
    echo "</pre>";

    die();
}