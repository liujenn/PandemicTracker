<?php require_once('../../private/initialize.php'); ?>
<?php
// Redirects to Login.html if not logged in
if ($_SESSION['Active'] == false) {
    header("location:../../index.php");
    exit;
}
$Did = get_docid($_SESSION['uname']);
$result_set = get_existing_cases_table_set($Did);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Existing Cases</title>
</head>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #000000;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        text-align: left;
        background-color: #dddddd;
    }
</style>
</head>

<body>
    <h2>Existing Cases</h2>
    <a href="index.php">Back</a>
    <br><br>
    <table>
        <tr>
            <th>Case#</th>
            <th>Encounter Date</th>
            <th>Patient Name</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <?php while ($result = mysqli_fetch_assoc($result_set)) { ?>
            <tr>
                <td><?php echo $result['CaseNum']; ?></td>
                <td><?php echo $result['EncounterDate']; ?></td>
                <td><?php echo $result['LastName'] . ", " . $result['FirstName']; ?></td>
                <td><a href="<?php echo "case.php?casenum=" . $result['CaseNum']; ?>">View</a></td>
                <td><a href="<?php echo "editcase.php?casenum=" . $result['CaseNum']; ?>">Edit</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php include(PRIVATE_P . '/footer.php'); ?>