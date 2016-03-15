<?php
namespace Racing\Results;

use League\Csv\Writer;

class Csv
{
    public function getCsvString($results, $race)
    {
        $file    = new \SplFileObject('php://temp', 'w');
        $writer = Writer::createFromFileObject($file);
        $writer->insertOne(['SailNo', 'Class', 'HelmName', 'RaceNo', 'Place', 'Code']);
        foreach ($results as $result) {
            $row = [$result['sailNumber'], $result['boatClassName'], $result['helm'], date('d/m/Y', strtotime($race['date'])), $result['position']];

            if (isset($result['unfinishedTypeHandle'])) {
                $row[] = $result['unfinishedTypeHandle'];
            }
            else {
                $row[] = '';
            }

            $writer->insertOne($row);
        }

        return $writer->__toString();
    }
}
