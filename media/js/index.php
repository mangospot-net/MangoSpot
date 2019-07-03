<?php
namespace kcfinder;
chdir("..");
require "core/autoload.php";
$min = new minifier("js");
$min->minify("cache/base.js");
