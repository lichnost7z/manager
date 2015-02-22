<?php
class Manager {
	private static $str; //Переменная для хранения файлов и последующего их вывода
    private static $_inst; //Екземпляр одиночки

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}
    
    public static function getInst () {
        if(self::$_inst == null) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }
    //Вывод способов сортировки
    public static function Head($folder) {

    	self::$str .= "Сортировать по: <a href=?f=".$folder."&s=name>Имени</a> |
        <a href=?f=".$folder."&s=type>Типу</a> |
        <a href=?f=".$folder."&s=size>Размеру</a><br />\r\n";

    }
    //Вывод кнопки назад
    public static function Back($folder) {

    	if(isset($folder) && $folder != 'C:/xampp/htdocs/maneger') { 
            $folder = substr($folder, 0,  strripos($folder, '/')); 
            if($folder == '') {
                echo "<a href=?f=/".$folder.">Назад</a><br />\r\n";
            } else {
                 echo "<a href=?f=".$folder.">Назад</a><br />\r\n";
            }
           
        }

    }
    //Здесь получаем папки и если нужно сортируем их
    public static function getFolders($folder,$sort) {

    	$folder = empty($folder) ? $folder = 'C:/xampp/htdocs/maneger' : $folder;

    	if(empty($sort)) {

    		$dirs = scandir($folder);
 			
            //Если папка пустая 
 			if(count($dirs) <= 2) {
 				self::$str .= "Папка пуста";
 			} else {
 				self::Head($folder);
 			}
            //Здесь выводятся папки безз сортировки
    		foreach($dirs as $dir) {
    		if($dir == '.' || $dir == '..') {
    			continue;
    		}

    		if(is_file($folder.'/'.$dir)) {
                    $files[$folder.'/'.$dir] = $dir;
                    continue;
                }
           

            self::$str .= "<a href=?f=".$folder."/".$dir.">".$dir."</a><br />\r\n";

    		}

    		if(!empty($files)) {
		        foreach ($files as $key => $value) {
		       		self::$str .= "<a href=?f=".$key.">".$value."</a><br />\r\n";
		        }
	       }

    	} else {
            //Если сортировка передана
    		switch($sort) {
    			case 'name': //Сортировка по имени

    			$dir = scandir($folder);
    			foreach($dir as $key) {

    				if (is_file($folder.'/'.$key)) {
	                    $files[] = $key;
	                    continue;
	                }
	                $dirs[] = $key;
    			}

    			uasort($files, 'strcasecmp'); 
            	uasort($dirs, 'strcasecmp');
            	$array = array_merge($dirs, $files);
            	self::Head($folder);


            	foreach ($array as $key) {

            		if($key == '.' || $key == '..') {
                		continue;
               		}

               		self::$str .= "<a href=?f=".$folder."/".$key.">".$key."</a><br />\r\n";
            	}

    			break;
    			case 'type': //Сортировка по типу

    			$dir = scandir($folder);

    			foreach($dir as $key) {

    				if(filetype($folder.'/'.$key) == 'dir') {
                        $dirs[$key] = $key; 
                    } else { 
                        //Если найден файл получаем его расшерение и записываем в массив extens, А сами файлы в отдельнов массиве files
                        $extens[substr($key, strripos($key, '.')+1, strlen($key))] = substr($key, strripos($key, '.')+1, strlen($key));
                        $files[] = $key;
                    }

    			}
    			uasort($dirs, 'strcasecmp');
           		uasort($extens, 'strcasecmp');
           		self::Head($folder);


           		foreach($dirs as $val) {

           			if($val == '.' || $val == '..') {
	               		continue;
	                }

	                self::$str .= "<a href=?f=".$folder."/".$val.">".$val."</a><br />\r\n";
           		}
                //Выволим и сортируем файлы
           		foreach($extens as $ext) {

           			foreach($files as $file) {

           				if($ext == substr($file, strripos($file, '.')+1, strlen($file))) {
                            self::$str .= "<a href=?=".$folder."/".$file.">".$file."</a><br />\r\n";
                        }

           			}

           		}

    			break;
    			case 'size': //Сортировка по размеру

    			$dir = scandir($folder);

    			foreach ($dir as $key) {

    				if (is_file($folder.'/'.$key)) {
	                    $files[$key] = filesize($folder.'/'.$key); //Получаем размер файла
	                    continue;
	                }

	                $dirs[$key] = $key; 

    			}

    			asort($files, SORT_NUMERIC);
	            uasort($dirs, 'strcasecmp');
	            $array = array_merge($dirs, $files);
	            self::Head($folder);


	            foreach($array as $key => $value) {

	            	if($key == '.' || $key == '..') {
	               		continue;
	                }

	            	self::$str .= "<a href=?f=".$folder."/".$key.">".$key."</a><br />\r\n";

	            }


    			break;
    		}
    		
    	}

    	return self::$str;
    }
    //Метод для чтения файлов
    public static function writes($file) {
    	echo "<div class='center'>";
    	$file = fopen($file, 'rb');

    	while(!feof($file)) {
    		echo fgets($file)."<br />";
    	}

    	echo "</div>";
    }
}