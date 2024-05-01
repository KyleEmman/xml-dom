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
		<title>Update Page</title>
		<script>
			const scams = <?php echo json_encode($scams); ?>;
			function changeDetails(event) {
				console.log(scams)
				const scamDetails = scams.find(s => s.id === event.target.value);
                let detailsDivs = ''
                Object.keys(scamDetails).forEach((detail) => {
					if (detail === 'id') return
                    if (detail == 'picture') {
                        detailsDivs += `Old Picture: <div><img src='data:image;base64,${scamDetails[detail]}' height='50' width='50'></div>`
                        return
                    }
					if (detail === 'commontargets') {
						detailsDivs += `New ${detail}: 
						<select value='${scamDetails[detail]}' name='edit${capitalizeFirstLetter(detail)}'>
							<option>value1</option>
							<option>value2</option>
							<option>value3</option>
							<option>value4</option>
						</select> <br/>`
					}
                    detailsDivs += `New ${detail}: <input type='text' value='${scamDetails[detail]}' name='edit${capitalizeFirstLetter(detail)}'/> <br/>`
                })
                console.log(detailsDivs)
                document.querySelector('#details-div').innerHTML = detailsDivs
			}
			function capitalizeFirstLetter(str) {
				return str.charAt(0).toUpperCase() + str.slice(1);
			}	
		</script>
	</head>
	<body>
		<?php require("header.php") ?>
		<form method="post" action="updateProcess.php" enctype="multipart/form-data">
			Student No.: <select name="editId" onchange="changeDetails(event)">
				<option>Select ID</option>
				<?php

					$scamTypes = $xml->getElementsByTagName("scamType");
					foreach($scamTypes as $scamType){
						$id = $scamType->getAttribute("scam");
						echo "<option>" .$id. "</option>";
					}
				?>
			</select><br>
			<div id="details-div">
				New Name: <input type="text" name="editName"/><br/>
				New Description: <input type="text" name="editDescription"/><br/>
				New Information: <input type="text" name="editInformation"/><br/>
				New Common Targets: <input type="text" name="editCommontargets"/><br/>
				New Consequences: <input type="text" name="editConsequences"/><br/>
				New Prevention: <input type="text" name="editPrevention"/><br/>
			</div>
				New Picture: <input type="file" name="editPicture" required/><br/>
			<br/><input type="submit" value="Update">
		</form>
	</body>
</html>