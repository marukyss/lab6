<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/utils/db.php';

class SurveyRepository
{
    public function create(string $name, string $email, string $question1, string $question2, string $question3): int
    {
        $db = SqliteDb::instance();

        $statement = $db->prepare(
            'INSERT INTO "survey" ("name", "email", "question1", "question2", "question3") 
                    VALUES (:name, :email, :question1, :question2, :question3)'
        );

        $statement->bindValue(':name', $name);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':question1', $question1);
        $statement->bindValue(':question2', $question2);
        $statement->bindValue(':question3', $question3);
        $statement->execute();

        return $db->lastInsertRowID();
    }

    function delete(int $id): bool
    {
        $db = SqliteDb::instance();

        $statement = $db->prepare('DELETE FROM "survey" WHERE "id" = :id');
        $statement->bindValue(":id", $id);
        $statement->execute();

        return $db->changes() > 0;
    }

    function read_all(int $i, int $n): array
    {
        $db = SqliteDb::instance();

        $statement = $db->prepare(
            'SELECT id, name, email, question1, question2, question3 FROM "survey"
                    LIMIT :limit OFFSET :offset'
        );

        $statement->bindValue(':limit', $n);
        $statement->bindValue(':offset', $i);
        $result = $statement->execute();

        $output = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC))
        {
            $output[] = $row;
        }

        return $output;
    }
}
