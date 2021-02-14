<?php
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "utf-8");
}
