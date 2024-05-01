<?php
$xml = new DOMDocument("1.0");
$xml->load("BSIT3GG2G4.xml");

$scamTypes = $xml->getElementsByTagName("scamType");
$searchQuery = strtolower(trim($_GET['search']));
$results = array();

foreach ($scamTypes as $scamType) {
    $id = $scamType->getAttribute("scam");
    $name = strtolower($scamType->getElementsByTagName("name")->item(0)->nodeValue);
    $description = strtolower($scamType->getElementsByTagName("description")->item(0)->nodeValue);
    $information = strtolower($scamType->getElementsByTagName("information")->item(0)->nodeValue);
    $commontargets = strtolower($scamType->getElementsByTagName("commontargets")->item(0)->nodeValue);
    $consequences = strtolower($scamType->getElementsByTagName("consequences")->item(0)->nodeValue);
    $prevention = strtolower($scamType->getElementsByTagName("prevention")->item(0)->nodeValue);
    $picture = $scamType->getElementsByTagName("picture")->item(0)->nodeValue;

    if (strpos($name, $searchQuery) !== false ||
        strpos($description, $searchQuery) !== false ||
        strpos($information, $searchQuery) !== false ||
        strpos($commontargets, $searchQuery) !== false ||
        strpos($consequences, $searchQuery) !== false ||
        strpos($prevention, $searchQuery) !== false) {
        $result = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'information' => $information,
            'commontargets' => $commontargets,
            'consequences' => $consequences,
            'prevention' => $prevention,
            'picture' => $picture
        );
        $results[] = $result;
    }
}

echo json_encode($results);
?>