<?php
if (!isset($_POST['createName'])) {
    header('Location: read.php');
    exit;
}

$xml = new DOMDocument("1.0");
$xml->load("BSIT3GG2G4.xml");

$scamTypes = $xml->getElementsByTagName("scamType");
$newName = $_POST["createName"];

// Check if the name already exists
$nameExists = false;
foreach ($scamTypes as $scamType) {
    $existingName = $scamType->getElementsByTagName("name")->item(0)->nodeValue;
    if (strcasecmp($existingName, $newName) === 0) {
        $nameExists = true;
        break;
    }
}

if ($nameExists) {
    // Redirect back to create.php with an error message
    header("Location: create.php?error=Scam type already exists");
    exit;
} else {
    $id = sizeof($scamTypes) > 0 ? $scamTypes[sizeof($scamTypes) - 1]->getAttribute('scam') + 1 : 1;
    $description = $_POST["createDescription"];
    $information = $_POST["createInformation"];
    $commonTargets = $_POST["createCommonTargets"];
    $consequences = $_POST["createConsequences"];
    $prevention = $_POST["createPrevention"];

    $newScam = $xml->createElement("scamType");
    $newNameNode = $xml->createElement("name", $newName);
    $newDescription = $xml->createElement("description", $description);
    $newInformation = $xml->createElement("information", $information);
    $newCommonTargets = $xml->createElement("commontargets", $commonTargets);
    $newConsequences = $xml->createElement("consequences", $consequences);
    $newPrevention = $xml->createElement("prevention", $prevention);
    $pic = $xml->createElement("picture");
    $imageData = file_get_contents($_FILES["createPicture"]["tmp_name"]);
    $base64 = base64_encode($imageData);
    $cdata = $xml->createCDATASection($base64);
    $pic->appendChild($cdata);

    $newScam->appendChild($newNameNode);
    $newScam->appendChild($newDescription);
    $newScam->appendChild($newInformation);
    $newScam->appendChild($newCommonTargets);
    $newScam->appendChild($newConsequences);
    $newScam->appendChild($newPrevention);
    $newScam->appendChild($pic);
    $newScam->setAttribute("scam", $id);

    $xml->getElementsByTagName("scamTypes")->item(0)->appendChild($newScam);
    $xml->save("BSIT3GG2G4.xml");

    echo "<script> alert('Record saved!'); window.location.href = 'read.php' </script>";
}
?>