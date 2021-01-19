<?php require_once('../../private/initialize.php'); ?>
<?php
if ($_SESSION['Active'] == false) {
    header("location:../../index.php");
    exit;
}
$rinfo = [
    "Rid" => '', "FirstName" => '', "LastName" => '', "DOB" => ''
];
$carenum = '';
date_default_timezone_set("America/Vancouver");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $case_detail = [];
    $case_med = [];
    $Did = get_docid($_SESSION['uname']);
    if (isset($_POST['verify']) && $_POST['verify'] === "Verify Care Number") {
        $carenum = $_POST['carenum'];
        $rinfo = get_rinfo_from_carenum($carenum);
        if(!isset($rinfo)) {
            $rinfo = [
                "Rid" => '', "FirstName" => '', "LastName" => '', "DOB" => ''
            ];
        }
    } else {
        $carenum = $_POST['carenum'];
        $rinfo = get_rinfo_from_carenum($carenum);
        if(!isset($rinfo)) {
            $rinfo = ["Rid" => ''];
        }
        $case_detail['EncounterDate'] = date("Y-m-d");
        $case_detail['RecoveryStatus'] = $_POST['recovery_status'] ?? '';
        $case_detail['Notes'] = $_POST['notes'] ?? '';
        $case_detail['Did'] = $Did;
        $case_detail['Rid'] = $rinfo['Rid'];
        $case_med['Name'] = $_POST['medication'] ?? '';
        $case_med['Dosage'] = $_POST['dosage'] ?? '';
        if (create_case($case_detail) === true) {
            $case_detail['CaseNum'] = mysqli_insert_id($conn);
            if (update_medication($case_detail, $case_med, "create") === true) {
                header("location: case.php?casenum=" . $case_detail['CaseNum']);
            }
        }
    }
}
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

    <h2>Create Case</h2>
    <a href="index.php">Back</a>
    <form action="createcase.php" method="post">
        <dl>
            <dt>Health Care Number:</dt>
            <dd><input type="text" name="carenum" value="<?php echo $carenum; ?>" /></dd>
        </dl>
        <input type="submit" name="verify" value="Verify Care Number" />
        <dl>
            <dt>First Name:</dt>
            <dd><?php echo $rinfo['FirstName']; ?></dd>
        </dl>
        <dl>
            <dt>Last Name:</dt>
            <dd><?php echo $rinfo['LastName']; ?></dd>
        </dl>
        <dl>
            <dt>DOB:</dt>
            <dd><?php echo $rinfo['DOB']; ?></dd>
        </dl>
        <dl>
            <dt>Encounter Date:</dt>
            <dd><?php 
                echo date("Y-m-d"); ?></dd>
        </dl>
        <dl>
            <dt>Recovery Status:</dt>
            <dd><select name="recovery_status">
                    <option value="Positive">Positive</option>
                    <option value="Negative" selected>Negative</option>
                    <option value="Recovered">Recovered</option>
                    <option value="Deceased">Deceased</option>
                </select>
            </dd>
        </dl>
        <dl>
            <dt>Medication:</dt>
            <dd><input type="text" name="medication" value="" /></dd>
        </dl>
        <dl>
            <dt>Dosage:</dt>
            <dd><input type="text" name="dosage" value="" /></dd>
        </dl>
        <dl>
            <dt>Notes:</dt>
            <dd><textarea name="notes" rows="10" cols="30"></textarea></dd>
        </dl>
        <input type="submit" name="create" value="Create Case" />
    </form>
    <br>
</body>

</html>

<?php include(PRIVATE_P . '/footer.php'); ?>