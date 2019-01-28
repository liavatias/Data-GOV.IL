<?php
header("Content-Type: text/html; charset=windows-1255");

/*
 *Import all Israeli streets.
 *license: https://data.gov.il/terms
 */

$filename = "https://data.gov.il/dataset/321/resource/a7296d1a-f8c9-4b70-96c2-6ebb4352f8e3/download";
$handle = fopen($filename, "r");

/*
 *Import all streets in city code.
 *@param cityCode : String, 4 digits.
 *return sorted array by key:
 *array: "XXXXXXXX" => array("cityCode"=>XXXX, "cityName"=>"hebrew name" "streetCode"=>XXXX, "streetName"=>"hebrew name")
 */
function importStreets($cityCode) {
    $data = array();
    if ($GLOBALS["handle"]) {
        while (($line = fgetcsv($GLOBALS["handle"])) !== false) {            
            if (sprintf("%04d\n", $line[1]) == $cityCode) {
                $identify = rtrim(sprintf("%04d\n", $line[1])).ltrim(sprintf("%04d\n", $line[3]));
                $data[$identify] = array("cityCode"=>$line[1], "cityName"=>$line[2], "streetCode"=>$line[3], "streetName"=>$line[4]);
            }  
        }
        fclose($GLOBALS["handle"]);
        ksort($data); //sort the array by identify key
    } else { 
        return 0; //error read file 
        }
    return $data;
}

/*
 *Import all streets in file.
 *return array: "XXXXXXXX" => array("cityCode"=>XXXX, "cityName"=>"hebrew name" "streetCode"=>XXXX, "streetName"=>"hebrew name")
 */
function importAll() {
    $row = 1;
    $data = array();
    if ($GLOBALS["handle"]) {
        while (($line = fgetcsv($GLOBALS["handle"])) !== false) {
            
            if ($row == 1 || $row == 2) { $row++; continue; } //skip rows 1 and 2.
            
            $identify = rtrim(sprintf("%04d\n", $line[1])).ltrim(sprintf("%04d\n", $line[3]));
            $data[$identify] = array("cityCode"=>$line[1], "cityName"=>$line[2], "streetCode"=>$line[3], "streetName"=>$line[4]);
            $row++;
        }
        fclose($GLOBALS["handle"]);
    } else {
        return 0; //error read file
    }
    return $data;
}

function printData($data) {
    foreach ($data as $key => $val) {
        echo $key." => ".$val["streetName"].", ".$val["cityName"]."<br>";  
    }
}

?>
