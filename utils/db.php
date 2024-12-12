<?php

class SqliteDb
{
    private static ?SQLite3 $db = null;

    static function instance(): SQLite3
    {
        if (is_null(SqliteDb::$db))
        {
            $db = new SQLite3(
                $_SERVER['DOCUMENT_ROOT'] . "/dist/db.sqlite",
                SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE
            );

            $db->enableExceptions(true);
            $db->query(
                'CREATE TABLE IF NOT EXISTS "survey" (
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "name" TEXT NOT NULL,
                    "email" TEXT NOT NULL,
                    "question1" TEXT NOT NULL,
                    "question2" TEXT NOT NULL,
                    "question3" TEXT NOT NULL
                )'
            );

            SqliteDb::$db = $db;
        }

        return SqliteDb::$db;
    }
}
