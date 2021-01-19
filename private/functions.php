<?php
    require_once('connect.php');

    function logout() {
        // destroy session
        session_destroy();
        // Redirect to login page
        header("location: index.php");
        exit;
    }

    function get_rid($username){
        global $conn;
        $sql_query = "select Rid as ID 
                    from Link_Personal_Account
                    where username='".$_SESSION["uname"]."'";
        $result = mysqli_query($conn,$sql_query);
        $row = mysqli_fetch_assoc($result);
        $Rid = $row['ID'];
        return $Rid;
    }

    function create_health($health_detail){
        global $conn;
        create_fever_logic($health_detail);
        $sql_query = "insert into Input_Health_Status
                    values ('".$health_detail['Date']."', '".$health_detail['Rid']."', 
                           '".$health_detail['Temperature']."', '".$health_detail['ifFlu']."')";
        $result = mysqli_query($conn, $sql_query);
        if ($result){
            return true;
        } else {
            echo mysqli_error($conn);
            CloseCon($conn);
            exit;
        }
    }

    function create_fever_logic($health_detail){
        global $conn;
        $temperature = $health_detail['Temperature'];
        $fever_status = '';
        switch ($temperature){
            case $temperature <= 37:
                $fever_status = "Normal";
            break;
            case $temperature <= 38 && $temperature > 37:
                $fever_status = "Low Fever";
            break;
            case $temperature > 38 && $temperature <= 39:
                $fever_status = "Fever";
            break;
            case $temperature > 39:
                $fever_status = "High Fever";
            break;
        };
        $sql = "INSERT IGNORE into Fever_Logic ";
        $sql .= "VALUES('".$health_detail['Temperature']."', '".$fever_status."');";
        $result = mysqli_query($conn, $sql);
    }

    function get_docid($userid) {
        global $conn;
        $sql = "select Did from Link_Doctor_Account ";
        $sql .= "where UserName = '" .$userid. "';";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            exit("Database query failed.");
        }
        $Did = mysqli_fetch_assoc($result)['Did'];
        return $Did;
    }

    function get_existing_cases_table_set($Did) {
        global $conn;
        $sql = "select CaseNum, EncounterDate, FirstName, LastName ";
        $sql .= "from Cases, Resident ";
        $sql .= "where Did = '" . $Did . "' AND Cases.RID = Resident.Rid;";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            exit("Database query failed.");
        }
        return $result;
    }

    function get_case_detail($casenum) {
        global $conn;
        $sql = "select CaseNum, EncounterDate, RecoveryStatus, Notes, FirstName, LastName, DOB ";
        $sql .= "from Cases, Resident ";
        $sql .= "where CaseNum = '" . $casenum . "' AND Cases.RID = Resident.Rid;";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            exit("Database query failed.");
        }
        $case_detail = mysqli_fetch_assoc($result);
        return $case_detail;
    }

    function get_case_medication($casenum) {
        global $conn;
        $sql = "select * ";
        $sql .= "from Records ";
        $sql .= "where CaseNum = '" . $casenum . "';";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            exit("Database query failed.");
        }
        $med = mysqli_fetch_assoc($result);
        if(empty($med)){
            $med['Name'] = 'NULL';
            $med['Dosage'] = 'NULL';
        }
        return $med;
    }

    function update_cases_table($case_detail) {
        global $conn;
        $sql = "update Cases SET ";
        $sql .= "EncounterDate='" . $case_detail['EncounterDate'] . "', ";
        $sql .= "RecoveryStatus='" . $case_detail['RecoveryStatus'] . "', ";
        $sql .= "Notes='" . $case_detail['Notes'] . "' ";
        $sql .= "where CaseNum='" . $case_detail['CaseNum'] . "' ";
        $sql .= "limit 1;";
        $result = mysqli_query($conn, $sql);
        if($result) {
            return true;
        } else {
            echo mysqli_error($conn);
            CloseCon($conn);
            exit;
        }
    }

    function create_risk($risk_detail){
        global $conn;
        $sql_query = "insert into Input_Risk_Status
                    values ('".$risk_detail['Date']."', '".$risk_detail['Rid']."', 
                           '".$risk_detail['ifExp']."', '".$risk_detail['ifNeigh']."', '".$risk_detail['ifClose']."')";
        $result = mysqli_query($conn, $sql_query);
        if ($result){
            return true;
        } else {
            echo mysqli_error($conn);
            CloseCon($conn);
            exit;
        }
    }

    function create_case($case_detail) {
        global $conn;
        $sql = "INSERT into Cases (EncounterDate, RecoveryStatus, Notes, Did, Rid) ";
        $sql .= "Value ('" . $case_detail['EncounterDate'] . "', '" . $case_detail['RecoveryStatus'] . "', '" . $case_detail['Notes'] . "', '" . $case_detail['Did'] . "', '" . $case_detail['Rid'] . "');";
        $result = mysqli_query($conn, $sql);
        if($result) {
            return true;
        } else {
            echo mysqli_error($conn);
            CloseCon($conn);
            exit;
        }
    }
    
    function check_quarantine($date, $Rid){
        global $conn;
        date_default_timezone_set("America/Vancouver");
        // Get fever status and risk level
        $sql_query = "select F.Fever_Status as FeverStatus, L.RiskLevel as RiskLevel
                    from Input_Health_Status H, Input_Risk_Status R, Fever_Logic F, Risk_Level L
                    where H.Date = R.Date and H.Rid = R.Rid and H.Temperature = F.Temperature
                            and R.ExposureToConfirmedCase = L.ExposureToConfirmedCase 
                            and R.ConfirmedCaseInNeighbourhood = L.ConfirmedCaseInNeighbourhood 
                            and R.CloseContactHasFlu_likeSymptom = L.CloseContactHasFlu_likeSymptom
                            and H.Date = '".$date."' and H.Rid = '".$Rid."'";
        $result = mysqli_query($conn,$sql_query);
        $row = mysqli_fetch_assoc($result);
        $FeverStatus = $row['FeverStatus'];
        $RiskLevel = $row['RiskLevel'];
        // find the latest endDate on the quarantine table
        $sql_query_qua = "select EndDate as quarantineEnd
                        from Resident_Quarantine_Status
                        where Rid ='".$Rid."'";
        $result_qua = mysqli_query($conn,$sql_query_qua);
        $latestEndDate = 0;
        while($row = mysqli_fetch_assoc($result_qua)){
            $quarantineEnd = $row['quarantineEnd'];
            if ($quarantineEnd > $latestEndDate){
                $latestEndDate = $quarantineEnd;
            }
        }
        // if latestEndDate >= date: in quarantine (today - latestEndDate)
        // else: check Fever Status and Risk Level to decide if is needed
        if ($latestEndDate >= $date){
            create_quarantine($date, $latestEndDate, '1', $Rid);
        }else{
            if ($FeverStatus == "Fever" || $FeverStatus == "High Fever" || $RiskLevel == "High"){
                create_quarantine($date, date('Y-m-d', strtotime($date. ' + 14 days')), '1', $Rid);
            } else{
                create_quarantine($date, $date, '0', $Rid);
            }
        }
        return true; 
    }

    function create_quarantine($date, $EndDate, $status, $Rid){
        global $conn;
        $sql_query = "insert into Resident_Quarantine_Status
                    values ('".$date."', '".$EndDate."', '".$status."', '".$Rid."')";
        $result = mysqli_query($conn, $sql_query);
        if (!$result){
            echo mysqli_error($conn);
            CloseCon($conn);
            exit;
        }
    }
    function update_medication($case_detail, $case_med, $update_create) {
        global $conn;
        $sql = "INSERT IGNORE into Medication ";
        $sql .= "VALUES('".$case_med['Name']."'), ('".$case_med['Dosage']."');";
        mysqli_query($conn, $sql);
        if ($update_create === "update") {
            return update_case_medication($case_detail, $case_med);
        } elseif ($update_create === "create"){
            return create_case_medication($case_detail, $case_med);
        }

    }

    function update_case_medication($case_detail, $case_med) {
        global $conn;
        $sql = "update Records ";
        $sql .= "SET Name ='".$case_med['Name']."', Dosage = '".$case_med['Dosage']."' ";
        $sql .= "where CaseNum = '".$case_detail['CaseNum']."' ";
        $sql .= "limit 1;";
        $result = mysqli_query($conn, $sql);
        if($result) {
            return true;
        } else {
            echo mysqli_error($conn);
            CloseCon($conn);
            exit;
        }
    }

    function create_case_medication($case_detail, $case_med) {
        global $conn;
        $sql = "insert into Records ";
        $sql .= "VALUES ('".$case_detail['CaseNum']."', '".$case_med['Name']."', '".$case_med['Dosage']."');";
        $result = mysqli_query($conn, $sql);
        if($result) {
            return true;
        } else {
            echo mysqli_error($conn);
            CloseCon($conn);
            exit;
        }
    }

    function delete_case($casenum) {
        global $conn;
        $sql = "DELETE FROM Cases ";
        $sql .= "WHERE CaseNum='" . $casenum . "' ";
        $sql .= "LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if(!$result) {
            echo mysqli_error($conn);
            CloseCon($conn);
            exit;
        }
    }

    function get_rinfo_from_carenum($carenum) {
        global $conn;
        $sql = "select Rid, FirstName, LastName, DOB from Resident ";
        $sql .= "where HealthCare_num = '" . $carenum . "' ";
        $sql .= "LIMIT 1;";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            exit("Database query failed.");
        } else {
            $rinfo = mysqli_fetch_assoc($result);
        }
        return $rinfo;
    }
?>