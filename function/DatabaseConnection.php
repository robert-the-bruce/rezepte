<?php
/**
 * Created by PhpStorm.
 * User: SARO
 * Date: 17.01.2019
 * Time: 08:00
 */

class DatabaseConnection
{
    private $server; // = "localhost";
    private $user; // ="root";
    private $pwd; // = "";
    private $db; // = "xyz";
    protected $connect;

    public function __construct($server, $user, $pwd, $db)
    {
        $this->server = $server;
        $this->user = $user;
        $this->pwd = $pwd;
        $this->db = $db;
        try {
            $this->connect = new PDO('mysql:host='.$this->server.';dbname='.$this->db.';charset=utf8',$this->user, $this->pwd);
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e)
        {
            switch($e->getCode())
            {
                case 2002:
                    echo 'Verbindung zum Server <b>'.$this->server.'</b> nicht möglich!<br>';
                    break;
                case 1044:
                    echo 'Probleme beim Zugriff mit Benutzer: <b>'.$this->user.'</b>';
                    break;
                case 1045:
                    echo 'Passwort evt. falsch für Benutzer: '.$this->user.'! Zugriff abgelehnt!<br>';
                    break;
                case 1049:
                    echo 'Die Datenbank <b>'.$this->db.'</b> existiert nicht!<br>';
                    break;
                default:
                    echo $e->getMessage();
            }
        }

    }
}