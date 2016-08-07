<?php
namespace Racing\Import\Csv;

class Validator
{
    public static function validateHandicapRow(array $row)
    {
        $errors = [];
        $row = array_map(function ($value) {
            return trim($value);
        }, $row);

        \Assert\that($row['sailNumber'], 'Sail number must be a non empty integer value')->integerish();
        \Assert\that($row['class'], 'Class must be a non empty string')->string()->notEmpty();
        if (!empty($row['minutes'])) {
            \Assert\that($row['minutes'], 'Minutes must be blank or an integer')->integerish();
        }
        if (!empty($row['seconds'])) {
            \Assert\that($row['seconds'], 'Seconds must be blank or an integer')->integerish();
        }
        if (!empty($row['laps'])) {
            \Assert\that($row['laps'], 'Laps must be blank or an integer')->nullOr()->integerish();
        }
        \Assert\that($row['helm'], 'Helm must be a non empty string')->notEmpty()->string();
        \Assert\that($row['crew'], 'Crew must be empty or a string')->nullOr()->string();
        \Assert\that($row['pyNumber'], 'PY Number must be an integer')->integerish();
        \Assert\that($row['numberOfCrew'], 'Number of crew must be an integer')->integerish();

    }

    public static function validateClassRow(array $row)
    {
        $errors = [];
        $row = array_map(function ($value) {
            return trim($value);
        }, $row);

        \Assert\that($row['sailNumber'], 'Sail number must be a non empty integer value')->integerish();
        \Assert\that($row['class'], 'Class must be a non empty string')->string()->notEmpty();
        if (!empty($row['position'])) {
            \Assert\that($row['position'], 'Position must be blank or an integer')->integerish();
        }
        \Assert\that($row['helm'], 'Helm must be a non empty string')->notEmpty()->string();
        \Assert\that($row['crew'], 'Crew must be empty or a string')->nullOr()->string();
        \Assert\that($row['pyNumber'], 'PY Number must be an integer')->integerish();
        \Assert\that($row['numberOfCrew'], 'Number of crew must be an integer')->integerish();
    }
}
