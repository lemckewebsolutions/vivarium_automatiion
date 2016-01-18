<?php
namespace LWS\Palu\Temperature;

use LWS\Palu\Schema\SchemaInterface;

class MaxWaterTemperatureSchema implements SchemaInterface
{
    /**
     * @return array
     */
    public function getSchema()
    {
        return [
            "1" => "21", // January
            "2" => "22", // February
            "3" => "23", // March
            "4" => "24", // April
            "5" => "25", // May
            "6" => "26", // June
            "7" => "27", // July
            "8" => "26", // August
            "9" => "25", // September
            "10" => "24", // October
            "11" => "23", // November
            "12" => "22" // December
        ];
    }
}