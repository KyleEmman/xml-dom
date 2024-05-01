<!doctype html>
<html>
<head>
    <title>Create Page</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 2px;
            width: 500px;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php require("header.php") ?>
    <?php
    $error = isset($_GET['error']) ? $_GET['error'] : '';
    if (!empty($error)) {
        echo '<p class="error">' . $error . '</p>';
    }
    ?>
    <form method="post" action="createProcess.php" enctype="multipart/form-data">
        <label for="createName">Name:</label>
        <input type="text" id="createName" name="createName" required>
        <label for="createDescription">Description:</label>
        <input type="text" id="createDescription" name="createDescription" required>
        <label for="createInformation">Information:</label>
        <input type="text" id="createInformation" name="createInformation" required>
        <label for="createCommonTargets">Common Targets:</label>
        <select name="createCommonTargets" id="createCommonTargets">
            <option value="value1" selected>value1</option>
            <option value="value2">value2</option>
            <option value="value3">value3</option>
            <option value="value4">value4</option>
        </select>
        <!-- <input type="text" id="createCommonTargets" name="createCommonTargets" required> -->
        <label for="createConsequences">Consequences:</label>
        <input type="text" id="createConsequences" name="createConsequences" required>
        <label for="createPrevention">Prevention:</label>
        <input type="text" id="createPrevention" name="createPrevention" required>
        <label for="createPicture">Picture:</label>
        <input type="file" name="createPicture" id="createPicture" required>
        <input type="submit" value="Save">
    </form>
</body>
</html>