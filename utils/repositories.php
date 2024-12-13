<?php

class SurveyRepository
{
    private function id_to_filename(int $id): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/dist/' . $id . '.json';
    }

    private function find_free_id(): int
    {
        $value = 0;
        while (file_exists($this->id_to_filename($value))) {
            $value += 1;
        }

        return $value;
    }

    function create(string $name, string $email, string $question1, string $question2, string $question3): int
    {
        $id = $this->find_free_id();
        $file = $this->id_to_filename($id);

        file_put_contents($file, json_encode([
            'name' => $name,
            'email' => $email,
            'question1' => $question1,
            'question2' => $question2,
            'question3' => $question3
        ]));

        return $id;
    }
}
