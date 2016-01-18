<?php
namespace LWS\Palu\Temperature;

use LWS\Palu\Schema\SchemaInterface;

class MaxLandTemperatureSchema implements SchemaInterface
{
    /**
     * @return array
     */
    public function getSchema()
    {
        return [
            "1" => "26", // January
            "2" => "27", // February
            "3" => "28", // March
            "4" => "29", // April
            "5" => "30", // May
            "6" => "31", // June
            "7" => "32", // July
            "8" => "31", // August
            "9" => "30", // September
            "10" => "29", // October
            "11" => "28", // November
            "12" => "27" // December
        ];
    }
}