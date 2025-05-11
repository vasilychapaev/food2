<?php
// Тестовый файл для Xdebug

$numbers = [1, 2, 3, 4, 5];

foreach ($numbers as $i => $num) {
    $square = $num * $num;
    echo "Индекс: $i, Число: $num, Квадрат: $square<br>\n";
}

// Поставь брейкпоинт на строке с $square, чтобы посмотреть значения в цикле 