<?php
date_default_timezone_set('UTC');

class FileLogger // Данный класс удобен для ведения журналов, добаляет имя записи и дату перед записью.
{
    public static $loggers = []; // В этом массиве мы будем хранить все созданные объекты.
    private $buffer = []; // Массив для накопления записей
    private $name; // Имя записи
    private $f; // Переменная файлового потока

    private function __construct($name, $fname) // Конструктор делаем приватным, чтобы не было возможности плодить одинаковые объекты
    {
        $this->name = $name;
        $this->f = fopen($fname, "a+"); // Создаём фаил, либо если он уже есть, то открываем его для дозаписи в конец.
    }

    public static function create($name, $fname) // Статический метод для создания объектов
    {
        if (isset(self::$loggers[$fname])) // если объект с таким именем файла уже существует, то используем его же.
        {
            return self::$loggers[$fname];
        }else{
            return self::$loggers[$fname] = new FileLogger($name, $fname); // если такого объекта нет, то создаём его, и записываем в массив.
        }
    }

    public function log($str) // Метод принимает строку, вставляет перед ней дату и имя записи и записывает в буффер.
    {
        $prefix = "[".date("F j, Y, g:i a") . $this->name . "]";
        $str = preg_replace("/^/m", $prefix, $str);
        $this->buffer[] = $str . "\n";
    }

    public function __destruct() // При удалении объекта записываем данные из буффера в фаил и закрываем поток.
    {
        fputs($this->f, join("", $this->buffer));
        fclose($this->f);
    }
}