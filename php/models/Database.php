<?php
class Database
    extends PDO
{

    const PDO_HOST     = '';
    const PDO_DATABASE = '';
    const PDO_USERNAME = '';
    const PDO_PASSWORD = '';
    const PDO_OPTIONS  = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"];

    public function __construct ()
    {
        try {
            parent::__construct(
                "mysql:host=" . self::PDO_HOST . ";dbname=" . self::PDO_DATABASE,
                self::PDO_USERNAME,
                self::PDO_PASSWORD,
                self::PDO_OPTIONS
            );
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch ( PDOException $Exception ) {
            echo $Exception->getMessage();
        }
    }
}
?>
