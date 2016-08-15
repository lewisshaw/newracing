<?php
require_once __DIR__ . '/../vendor/autoload.php';
$config = require_once __DIR__ . '/../config/services.config.local.php';

$options = getopt('o:');

function isIntegerish($value)
{
    return isset($value) && preg_match("/^\d+$/", $value);
}

if (!isIntegerish($options['o'])) {
    throw new Exception('Old Id must be a number');

}
$oldId = $options['o'];

$configuration = new Doctrine\DBAL\Configuration();

echo 'Old series Id: ' . $oldId . "\n";

$connNew = Doctrine\DBAL\DriverManager::getConnection($config['db'], $configuration);

$oldConfig = $config['db'];
$oldConfig['dbname'] = 'racing_results';

$connOld = Doctrine\DBAL\DriverManager::getConnection($oldConfig, $configuration);

$oldRaces = $connOld->fetchAll("SELECT * FROM races where race_series = :seriesId", [':seriesId' => $oldId]);

echo 'Found ' . count($oldRaces) . ' races';

$formattedOldRaces = [];

foreach ($oldRaces as $oldRace) {
    $id = $oldRace['race_id'];
    $date = $oldRace['race_date'];
    $type = $oldRace['race_type'];
    //handicap is 0, pursuit is 1
    $type == 0 ? $newType = 1 : $newType = 2;
    $laps = $oldRace['race_laps'];
    $name = "Race 0" . $oldRace['race_num'];
    $race = [
        'raceId' => $id,
        'raceDate' => $date,
        'raceTypeId' => $newType,
        'laps' => empty($laps) ? 1 : $laps,
        'name' => $name,
    ];
    if ($type == 0) {
        $query = "SELECT
            handicap_result_id,
            handicap_result_race,
            handicap_result_competitor,
            handicap_result_crew,
            handicap_result_class,
            handicap_result_time,
            handicap_result_sailnum,
            handicap_result_laps,
            handicap_result_dnf,
            class_id,
            class_name,
            class_py,
            helm.competitor_id AS helm_id,
            helm.competitor_fname AS helm_fname,
            helm.competitor_lname AS helm_lname,
            crew.competitor_id AS crew_id,
            crew.competitor_fname AS crew_fname,
            crew.competitor_lname AS crew_lname,
            sail_num_id,
            sail_num_number,
            race_id,
            race_laps,
            ((handicap_result_time/handicap_result_laps)*race_laps)/(class_py/1000) AS corr_time
            FROM handicap_results
            INNER JOIN classes ON handicap_result_class=class_id
            INNER JOIN competitors AS helm ON handicap_result_competitor=helm.competitor_id
            INNER JOIN competitors AS crew ON handicap_result_crew=crew.competitor_id
            INNER JOIN sail_num ON handicap_result_sailnum=sail_num_id
            INNER JOIN races ON handicap_result_race=race_id
            WHERE `handicap_result_race`= :raceId";
            $results = $connOld->fetchAll($query, [':raceId' => $id]);
            $formattedResults = [];
            foreach ($results as $result) {
                $time = $result['handicap_result_time'];
                $decMins = $time/60;
                $mins = intval($decMins);
                $decSecs = ($decMins - $mins) * 60;
                $secs = round($decSecs);
                $formattedResults[] = [
                    'sailNum' => $result['sail_num_number'],
                    'className' => trim($result['class_name']),
                    'mins' => $result['handicap_result_dnf'] ? null : $mins,
                    'secs' => $result['handicap_result_dnf'] ? null : $secs,
                    'laps' => empty($result['handicap_result_laps']) ? 1 : $result['handicap_result_laps'],
                    'helmName' => trim($result['helm_fname']) . ' ' . trim($result['helm_lname']),
                    'crewName' => trim($result['crew_fname']) . ' ' . trim($result['crew_lname']),
                    'pyNumber' => $result['class_py'],
                    'numberOfCrew' => 2,
                ];
            }

            $race['results'] = $formattedResults;
        } else {
            $query = "SELECT
                class_result_id,
                class_result_race,
                class_result_competitor,
                class_result_crew,
                class_result_class,
                class_result_pos,
                class_result_sailnum,
                class_id,class_name,
                class_result_dnf,
                class_py,helm.competitor_id AS helm_id,
                helm.competitor_fname AS helm_fname,
                helm.competitor_lname AS helm_lname,
                crew.competitor_id AS crew_id,
                crew.competitor_fname AS crew_fname,
                crew.competitor_lname AS crew_lname,
                sail_num_id, sail_num_number
                FROM class_results INNER JOIN classes ON class_result_class=class_id
                INNER JOIN competitors AS helm ON class_result_competitor=helm.competitor_id
                INNER JOIN competitors AS crew ON class_result_crew=crew.competitor_id
                INNER JOIN sail_num ON class_result_sailnum=sail_num_id
             WHERE `class_result_race`= :raceId";
            $results = $connOld->fetchAll($query, [':raceId' => $id]);
            $formattedResults = [];
            foreach ($results as $result) {
                $pos = $result['class_result_pos'];

                $formattedResults[] = [
                    'sailNum' => $result['sail_num_number'],
                    'className' => trim($result['class_name']),
                    'pos' => $result['class_result_dnf'] ? null : $pos,
                    'helmName' => trim($result['helm_fname']) . ' ' . trim($result['helm_lname']),
                    'crewName' => trim($result['crew_fname']) . ' ' . trim($result['crew_lname']),
                    'pyNumber' => $result['class_py'],
                    'numberOfCrew' => 2,
                ];
            }

            $race['results'] = $formattedResults;
        }

        $formattedOldRaces[] = $race;
}

foreach ($formattedOldRaces as $race) {
    $file = fopen(__DIR__ . '/generated_csvs/' . $race['raceDate'] . '_' . $race['raceTypeId'] . '_' . $race['laps'] . '_' . $race['name'], 'w');
    foreach ($race['results'] as $result) {
        fputcsv($file, array_values($result));
    }
    fclose($file);
}
