

<?php
	$xml = new domdocument("1.0");
	$xml->load("BSIT3GG2G4.xml");

	$scamTypes = $xml->getElementsByTagName("scamType");

    $scams = [];

    if (isset($_POST['search'])) {
        $searchQuery = trim($_POST['search']);
        $searchQuery = strtolower($searchQuery);

        foreach($scamTypes as $scamType) {
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
        }
    } else {
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
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        body {
            height: 100vh;
            width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        main {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        table {
            max-width: 80%;
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        }

        table, th, td {
            border: 1px solid;
            border-collapse: collapse;
            max-width: 150px;
            word-wrap: break-word;
        }

        th, td {
            padding: 0 5px;
        }

        .modal-background {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);  
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1001;
            width: 50%;
            height: 70%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .edit-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
        }
        .submit {
            padding: 5px;
            background-color: green;
            color: white;
            cursor: pointer;
        }
        .yellow {
            padding: 5px;
            background-color: green;
            color: white;
            cursor: pointer;
        }
        .cancel {
            padding: 5px;
            background-color: red;
            color: white;
            cursor: pointer;
        }
        .create-search-div {
            width: 80%;
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
        a {
            text-decoration: none
        }
        #suggestion-list {
            position: absolute;
            background-color: #fff;
            border: 1px solid #ddd;
            list-style-type: none;
            padding: 0;
            margin: 0;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1;
            top: 22px;
        }

        #suggestion-list li {
            padding: 8px 12px;
            cursor: pointer;
        }

        #suggestion-list li:hover {
            background-color: #f2f2f2;
        }
    </style>
    <script>
            function searchFunction() {
                const searchInput = document.getElementById('search');
                const searchQuery = searchInput.value.trim().toLowerCase();
                const xhr = new XMLHttpRequest();

                xhr.open('GET', 'searchProcess.php?search=' + encodeURIComponent(searchQuery), true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        const results = JSON.parse(xhr.responseText);
                        updateTable(results);
                        showSuggestions(results, searchQuery);
                    }
                };
                xhr.send();
            }

        function showSuggestions(results, searchQuery) {
            const suggestionList = document.getElementById('suggestion-list');
            suggestionList.innerHTML = '';

            if (searchQuery === '' || results.length === 0) {
                suggestionList.style.display = 'none';
            } else {
                suggestionList.style.display = 'block';
                results.forEach(function(result) {
                    const li = document.createElement('li');
                    li.textContent = result.name;
                    suggestionList.appendChild(li);
                });
            }
        }

        function updateTable(results) {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            if (results.length === 0) {
                const row = document.createElement('tr');
                const cell = document.createElement('td');
                cell.colSpan = 8;
                cell.textContent = 'No records found';
                row.appendChild(cell);
                tableBody.appendChild(row);
            } else {
                results.forEach(function(result) {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = result.id;
                    row.appendChild(idCell);

                    const nameCell = document.createElement('td');
                    nameCell.textContent = result.name;
                    row.appendChild(nameCell);

                    const descriptionCell = document.createElement('td');
                    descriptionCell.textContent = result.description;
                    row.appendChild(descriptionCell);

                    const informationCell = document.createElement('td');
                    informationCell.textContent = result.information;
                    row.appendChild(informationCell);

                    const commontargetsCell = document.createElement('td');
                    commontargetsCell.textContent = result.commontargets;
                    row.appendChild(commontargetsCell);

                    const consequencesCell = document.createElement('td');
                    consequencesCell.textContent = result.consequences;
                    row.appendChild(consequencesCell);

                    const preventionCell = document.createElement('td');
                    preventionCell.textContent = result.prevention;
                    row.appendChild(preventionCell);

                    const pictureCell = document.createElement('td');
                    const img = document.createElement('img');
                    img.src = 'data:image;base64,' + result.picture;
                    img.height = 50;
                    img.width = 50;
                    img.alt = 'pic';
                    img.style.paddingTop = '25px';
                    pictureCell.appendChild(img);
                    row.appendChild(pictureCell);

                    tableBody.appendChild(row);
                });
            }
        }
    </script>
</head>
<body>
    <main>
        <?php require("header.php") ?>
        <h1>Scam Types</h1>
        <div class="create-search-div">
            <form method="post" action="" style="display: flex; gap: 10px; margin-right: 10px; position: relative;">
                <button type="submit" class="submit">Search</button>
                <div style="display: flex; flex-direction: column;">
                    <input type="text" name="search" id="search" onkeyup="searchFunction()">
                    <ul id="suggestion-list"></ul>
                </div>
            </form>
            <div style="display: flex; gap: 5px;">
                <a class="submit" href="create.php">Create</a>
                <a class="yellow" href="update.php">Update</a>
                <a class="cancel" href="delete.php">Delete</a>
            </div>
        </div>
       
            <?php 
                if (sizeof($scams) < 1) {
                    echo "<h1>No Records</h1>";
                } else {
                    echo "
                    <table>
                    <thead>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Information</th>
                        <th>Common Targets</th>
                        <th>consequences</th>
                        <th>Prevention</th>
                        <th>Picture</th>
                    </thead> 
                    <tbody id='table-body'>
                    ";  
                    foreach ($scams as $scam) {
                        echo "<tr>";
                        echo "<td>{$scam['id']}</td>";
                        echo "<td>{$scam['name']}</td>";
                        echo "<td>{$scam['description']}</td>";
                        echo "<td>{$scam['information']}</td>";
                        echo "<td>{$scam['commontargets']}</td>";
                        echo "<td>{$scam['consequences']}</td>";
                        echo "<td>{$scam['prevention']}</td>";
                        echo "<td><img src='data:image;base64,".$scam['picture']."' height='50' width='50' alt='pic' style='padding-top: 25px;'><br><br></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            ?>
        
    </main>


</body>
</html>