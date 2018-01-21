<?php

function clean_param($param)
{
    return strtolower($param);
}

function pd($what = "STOP HERE")
{
    echo("<pre>");
    print_r($what);
    echo("</pre>");
    die();
}

function ps($what)
{
    echo("<pre><br>");
    print_r($what);
    echo("</pre>");
}