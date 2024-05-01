<?php 
    $xml = new domdocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("BSIT3GG2G4.xml");
    
    if (!isset($_POST["deleteId"])) {
        header('Location: read.php');
        exit;
    }
    $scams = $xml->getElementsByTagName("scamType");
    $id = $_POST["deleteId"];

    foreach($scams as $scam) 
	{
		$searchId = $scam->getAttribute("scam");
		
		if ($searchId === $id)
		{
			$xml->getElementsByTagName("scamTypes")->item(0)->removeChild($scam);
			$xml->save("BSIT3GG2G4.xml");
            echo "
                <script>
                    alert('Scam deleted successfully!')
                    window.location.href = 'read.php'
                </script>
            ";
			break;
		}
	}	

?>