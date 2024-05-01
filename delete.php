<?php
    $xml = new domdocument("1.0");
    $xml->load("BSIT3GG2G4.xml");
    $scamTypes = $xml->getElementsByTagName("scamType");
    $scams = [];
    foreach($scamTypes as $scamType) {
        $id = $scamType->getAttribute("scam");
        $name = $scamType->getElementsByTagName("name")->item(0)->nodeValue;
        $description = $scamType->getElementsByTagName("description")->item(0)->nodeValue;
        $information = $scamType->getElementsByTagName("information")->item(0)->nodeValue;
        $commontargets = $scamType->getElementsByTagName("commontargets")->item(0)->nodeValue;
        $consequences = $scamType->getElementsByTagName("consequences")->item(0)->nodeValue;
        $prevention = $scamType->getElementsByTagName("prevention")->item(0)->nodeValue;
        $picture = $scamType->getElementsByTagName("picture")->item(0)->nodeValue;
        $scam = [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'information' => $information,
            'commontargets' => $commontargets,
            'consequences' => $consequences,
            'prevention' => $prevention,
            'picture' => $picture
        ];
        $scams[] = $scam;
    }
?>

<!doctype html>
<html>
	<head>
		<title>Delete Page</title>
		<script>
			function deletion(){
				var choice = confirm("Do you really want to delete this record?");
				if(choice==false)
					return false;
			}
            function changeDetails(event) {
                const scam = <?php echo json_encode($scams); ?>;
                const scamDetails = scam.find(s => s.id === event.target.value);
                let detailsDivs = ''
                Object.keys(scamDetails).forEach((detail) => {
                    if (detail == 'picture') {
                        detailsDivs += `<div><img src='data:image;base64,${scamDetails[detail]}' height='50' width='50'></div>`
                        return
                    }
                    detailsDivs += `<div>${detail}: ${scamDetails[detail]}</div>`
                })
                console.log(detailsDivs)
                document.querySelector('#details-div').innerHTML = detailsDivs
            }
		</script>
	</head>
	<body>
        <?php require("header.php") ?>
		<form method="post" action="deleteProcess.php" onsubmit="return deletion()">
			Scam Id: <select name="deleteId" onchange="changeDetails(event)">
				<option>Select ID</option>
				<?php
					foreach($scamTypes as $scamType){
						$id = $scamType->getAttribute("scam");
						echo "<option>" .$id. "</option>";
					}
				?>
			</select><br>
            <div id="details-div">

            </div>
			<br/><input type="submit" value="Delete">
		</form>
	</body>
</html>