<?php require_once('../../private/initialize.php'); ?>
<?php
if ($_SESSION['Active'] == false) {
    header("location:../../index.php");
    exit;
}
if (!isset($_GET['casenum'])) {
    header("location: viewcases.php");
}

$casenum = $_GET['casenum'] ?? 'null';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete']) && $_POST['delete'] === "Delete Record") {
        delete_case($casenum);
        header("location: viewcases.php");
        exit;
    }
    $case_detail = get_case_detail($casenum);
    $case_med = get_case_medication($casenum);
    $case_detail['EncounterDate'] = $_POST['encounter_date'] ?? '';
    $case_detail['RecoveryStatus'] = $_POST['recovery_status'] ?? '';
    $case_detail['Notes'] = $_POST['notes'] ?? '';
    $case_med['Name'] = $_POST['medication'] ?? '';
    $case_med['Dosage'] = $_POST['dosage'] ?? '';
    if (strlen(trim($case_med['Name'])) == 0){
        $case_med['Name'] = "";
    }
    if (strlen(trim($case_med['Dosage'])) == 0){
        $case_med['Dosage'] = "";
    }
    if (update_cases_table($case_detail) && 
        update_medication($case_detail, $case_med, "update")) {
        header("location: case.php?casenum=" . $casenum);
    }
}

$case_detail = get_case_detail($casenum);
$case_med = get_case_medication($casenum);
$med_name = $case_med['Name'] === 'NULL' ? "" : $case_med['Name'];
$med_dosage = $case_med['Dosage'] === 'NULL' ? "" : $case_med['Dosage'];
?>

<!DOCTYPE html>
<html>

<head>
    <style>
        dl {
            width: 60%;
            overflow: hidden;
            padding: 0;
            margin-top: 1em;
            margin-bottom: 1em;
        }

        dt {
            font-weight: bold;
            float: left;
            width: 40%;
            padding: 0;
            margin: 0;
        }

        dd {
            float: left;
            width: 60%;
            padding: 0;
            margin: 0;
        }
    </style>
    </style>
</head>

<body>

    <h2><?php echo $case_detail['LastName'] . ", " . $case_detail['FirstName']; ?></h2>
    <a href="viewcases.php">Back</a>
    <form action="editcase.php?casenum=<?php echo $casenum; ?>" method="post">
        <dl>
            <dt>DOB:</dt>
            <dd><?php echo $case_detail['DOB']; ?></dd>
        </dl>
        <dl>
            <dt>Case #:</dt>
            <dd><?php echo $case_detail['CaseNum']; ?></dd>
        </dl>
        <dl>
            <dt>Encounter Date:</dt>
            <dd><input type="text" name="encounter_date" value="<?php echo $case_detail['EncounterDate']; ?>" /></dd>
        </dl>
        <dl>
            <dt>Recovery Status:</dt>
            <dd><select name="recovery_status">
                    <option value="Positive" <?php if ($case_detail['RecoveryStatus'] === "Positive")
                                                    echo " selected" ?>>Positive</option>
                    <option value="Negative" <?php if ($case_detail['RecoveryStatus'] === "Negative")
                                                    echo " selected" ?>>Negative</option>
                    <option value="Recovered" <?php if ($case_detail['RecoveryStatus'] === "Recovered")
                                                    echo " selected" ?>>Recovered</option>
                    <option value="Deceased" <?php if ($case_detail['RecoveryStatus'] === "Deceased")
                                                    echo " selected" ?>>Deceased</option>
                </select></dd>
        </dl>
        <dl>
            <dt>Medication:</dt>
            <dd><input type="text" name="medication" value="<?php echo $med_name; ?>" /></dd>
        </dl>
        <dl>
            <dt>Dosage:</dt>
            <dd><input type="text" name="dosage" value="<?php echo $med_dosage; ?>" /></dd>
        </dl>
        <dl>
            <dt>Notes:</dt>
            <dd><textarea name="notes" rows="10" cols="30"><?php echo $case_detail['Notes']; ?></textarea></dd>
        </dl>
        <input type="submit" name="update" value="Update Record" />
        <input type="submit" name="delete" value="Delete Record" 
            onclick="return  confirm('Are you sure you want to delete the case?')" />
    </form>
    <br>
</body>

</html>

<?php include(PRIVATE_P . '/footer.php'); ?>