<?php
    include_once '/style/head.html';
	include_once '/sys/config.php';
    $folder = Manager::getInst();
    //Вывод кнопки назад если нужно
    echo $folder::Back($_GET['f']);
    if(is_file($_GET['f'])) {
        //Если нажали на файл читаем его
        echo $folder::writes($_GET['f']);
    } else {
        //Если папка читаем содержимое папки
       echo "<div class='left'>".$folder::getFolders($_GET['f'], $_GET['s'])."</div>";
    }
?>