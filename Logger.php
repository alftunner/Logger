<?php


class Logger // класс упрощающий ведение разного рода журналов
{
    public $f; // открытый фаил
    public $name; // имя журнала
    public $lines = []; // буфер накапливаемых строк

    public function __construct($name, $fname)
    {
        $this->name = $name;
        $this->f = fopen($fname, "a+"); // открываем поток с созданием файла, или с дозаписью в конец существующего
        $this->log("###__construct called!");
    }

    public function log($str)
    {
        $prefix = "[".date("Y-m-d_h:i:s")."{$this->name}]"; // создаём префикс
        $str = preg_replace('/^/m', $prefix, rtrim($str));
        $this->lines[] = $str."\n";
    }

    public function __destruct()
    {
        $this->log("###__destruct called!");
        fputs($this->f, join("", $this->lines)); // передаём в поток строки из буфера
        fclose($this->f); // закрываем поток
    }

}
