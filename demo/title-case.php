<?php
require __DIR__.'/../vendor/autoload.php';

$titleCase = new CaseConvert\TitleCase;
echo $titleCase->convert('i am the other one');