<?php

namespace App\Core;

use \PDO;

class Data
{
    private static ?PDO $pdo = null;

    private static function connect(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO('sqlite:' . APP . 'octopus.sqlite');
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$pdo->exec('PRAGMA foreign_keys = ON');
        }

        return self::$pdo;
    }

	public static function insert($t,$vs)
	{
		$f = implode(',',array_keys($vs));
		$v = array_values($vs);
		$h = implode(',',array_fill(0,count($v),'?'));
		$s = self::execute("INSERT INTO $t ($f) VALUES ($h);", $v);
		return $s ? self::connect()->lastInsertId() : false;
	}

    public static function one(string $sql, array $params = []): ?array
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }

	public static function field($t,$f,$cond = '1=1',$v = [])
	{
		$stmt = self::execute("SELECT $f FROM $t WHERE $cond;",$v);
		return $stmt ? $stmt->fetchColumn() : false;
	}

	public static function column($t,$f,$cond = '1=1',$v = [])
	{
		$stmt = self::execute("SELECT $f FROM $t WHERE $cond;",$v);
		$data = $stmt ? $stmt->fetchAll() : false;
		return is_array($data) ? array_column($data,$f) : false;
	}

    public static function all(string $q, array $v = []): array
    {
        $stmt = self::connect()->prepare($q);
        $stmt->execute($v);
        return $stmt->fetchAll();
    }

	public static function update($t,$a,$c,$cvs = [])
	{
		$cvs = is_array($cvs) ? $cvs : [$cvs];
		[$f,$fvs] = self::parameterfy($a);
		$vs = array_merge($fvs,$cvs);
		$stmt = self::execute("UPDATE $t SET $f WHERE $c;",$vs);
		return $stmt ? $stmt->rowCount() : false;
	}

	public static function parameterfy($array)
	{
		foreach ($array as $field => $value) {
			$sets[] = "$field=?";
			$values[] = ($value) ? trim("$value",' \'') : $value;
		}
		return [implode(',',$sets),$values];
	}

    public static function execute(string $sql, array $params = [])
    {
        error_log('[Data] ' . $sql);
        $stmt = self::connect()->prepare($sql);
        return $stmt->execute($params) ? $stmt : false;
    }

    public static function query(string $sql, array $params = [])
    {
        $stmt = self::execute($sql, $params);

        if (!$stmt) {
            return false;
        }

        if ($stmt->columnCount() > 0) {
            return $stmt->fetchAll();
        }

        return true;
    }
}
