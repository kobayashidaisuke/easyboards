<?php
//マジックナンバーの定数化
const MAXIMUM_LENGTH_50 = 50;
const MAXIMUM_LENGTH_200 = 200;

function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "utf-8");
}
