<?php 
if (!isset($_POST["editId"])) {
    header('Location: read.php');
    exit;
}
$xml = new domdocument("1.0");
$xml->formatOutput = true;
$xml->preserveWhiteSpace = false;
$xml->load("BSIT3GG2G4.xml");


$id = $_POST["editId"];

$name = $_POST["editName"];
$description = $_POST["editDescription"];
$information = $_POST["editInformation"];
$commonTargets = $_POST["editCommontargets"];
$consequences = $_POST["editConsequences"];
$prevention = $_POST["editPrevention"];
$picture = $_FILES["editPicture"];

$flag = 0;
$scams = $xml->getElementsByTagName("scamType");
foreach ($scams as $scam) {
    $scamId = $scam->getAttribute("scam");
    if ($id === $scamId) {
        $flag = 1;
        $newNode = $xml->createElement("scamType");
        $newName = $xml->createElement("name", $name);
        $newDescription = $xml->createElement("description", $description);
        $newInformation = $xml->createElement("information", $information);
        $newCommonTargets = $xml->createElement("commontargets", $commonTargets);
        $newConsequences = $xml->createElement("consequences", $consequences);
        $newPrevention = $xml->createElement("prevention", $prevention);
        // $pic = $xml->createElement("picture"); 
        // $imageData = file_get_contents($picture["tmp_name"]);
        // $base64 = base64_encode($imageData) ?? "";
        if (isset($picture) && $picture["tmp_name"]) {
            $pic = $xml->createElement("picture"); 
            $imageData = file_get_contents($picture["tmp_name"]);
            $base64 = base64_encode($imageData) ?? "";
            $cdata = $xml->createCDATASection($base64);
            $pic->appendChild($cdata);
            $newNode->appendChild($pic);
        } else {
            $pic = $xml->createElement("picture"); 
            $newNode->appendChild($pic);
        }

        $newNode->appendChild($newName);
        $newNode->appendChild($newDescription);
        $newNode->appendChild($newInformation);
        $newNode->appendChild($newCommonTargets);
        $newNode->appendChild($newConsequences);
        $newNode->appendChild($newPrevention);
        $newNode->setAttribute("scam", $id);

        $oldNode = $scam;
        $xml->getElementsByTagName("scamTypes")->item(0)->replaceChild($newNode,$oldNode);
        $xml->save("BSIT3GG2G4.xml");
        
        break;
    }
}
if ($flag === 1) {
    echo "
        <script>
            alert('Details updated successfully!')
            window.location.href = 'read.php'
        </script>
    ";
} else {
    echo "
        <script>
            alert('Modification failed')
            window.location.href = 'read.php'
        </script>
    ";
}
?>