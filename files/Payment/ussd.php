<?php
    include ("../../meet/con.php");
    
    // Initialize response message
    $response = "";
    
    
    function getApplicationFee($access, $trackingId) {
        $sql = $access->prepare("SELECT fname, lname FROM tbl_applicants WHERE code = ?");
        $sql->execute([$trackingId]);
        
        if ($sql->rowCount() === 0) {
            return [
                "first_name" => "NR",
                "last_name" => "NR",
                "fee" => "N/A"
            ];
        }
    
        // Retrieve fixed application fee
        $feeData = $access->prepare("SELECT amount FROM fee_category WHERE id = 7");
        $feeData->execute();
        $fee = $feeData->fetch();
        
        $studentData = $sql->fetch();
        
        return [
            "first_name" => $studentData['fname'],
            "last_name" => $studentData['lname'],
            "fee" => $fee['amount']
        ];
    }
    
    function getStudentBalance($access, $studentId) {
        $sql = $access->prepare("SELECT a.fname, a.lname, r.reg_no, s.splz_full_name, l.level_full_name
            FROM tbl_register_program_ug r
            INNER JOIN tbl_admission a ON r.reg_no = a.reg_no
            INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
            INNER JOIN tbl_level l ON r.level_id = l.level_id
            WHERE r.reg_no = ? ORDER BY r.reg_prg_id DESC LIMIT 1");
        $sql->execute([$studentId]);
        
        if ($sql->rowCount() === 0) {
            return [
                "first_name" => "NR",
                "last_name" => "NR",
                "specialization" => "NR",
                "level" => "NR",
                "balance" => "N/A"
            ];
        }
        
        $studentData = $sql->fetch();

        // Get debt and payments
        $debtData = $access->prepare("SELECT SUM(balance) as debt FROM tbl_invoice WHERE reg_no = ?");
        $debtData->execute([$studentId]);
        $debt = $debtData->fetch()['debt'];
        
        $payHistory = $access->prepare("SELECT SUM(amount) as paid FROM payment WHERE reg_no = ? AND status = 1");
        $payHistory->execute([$studentId]);
        $paid = $payHistory->fetch()['paid'];
        
        $balance = $debt - $paid;

        return [
            "first_name" => $studentData['fname'],
            "last_name" => $studentData['lname'],
            "specialization" => $studentData['splz_full_name'],
            "level" => $studentData['level_full_name'],
            "balance" => $balance
        ];
    }
    
    /**
     * Generate USSD response based on user input.
     *
     * @param array $paramsArray Array of parameters from USSD request.
     * @param PDO $conn Database connection object.
     * @param string $sessionId USSD session ID.
     * @param string $serviceCode USSD service code.
     * @return string USSD response message.
     */
    function generateResponse($paramsArray, $conn, $sessionId, $serviceCode) {
        global $response;
    
        switch ($paramsArray[0]) {
            case "":
                $response = "Welcome to the USSD Service\n1. Tertiary\n2. Schools";
                break;
    
            case "1":
                if (count($paramsArray) == 1) {
                    $response = "Tertiary\n1. Universities\n2. Colleges\n3. TVETS";
                } elseif ($paramsArray[1] == "1") {
                    if (count($paramsArray) == 2) {
                        $response = "Universities\n1. CUMT\n2. USL\n3. Njala University";
                    } elseif ($paramsArray[2] == "3") {
                        if (count($paramsArray) == 3) {
                            $response = "Njala University\n1. Tuition Fees\n2. Application Fees\n3. Online Application Form";
                        } elseif ($paramsArray[3] == "1") {
                            if (count($paramsArray) == 4) {
                                $response = "Enter student ID:";
                            } elseif (count($paramsArray) == 5) {
                                $studentId = $paramsArray[4];
                                
                                $studentData = getStudentBalance($conn, $studentId);
                                $tuitionBalance = $studentData['balance'];
                                $studentNames = $studentData['first_name']." ".$studentData['last_name'];

                                if (is_numeric($tuitionBalance) && $tuitionBalance > 0) {
                                    $response = "$studentNames \nYour Tuition Fees Balance is $tuitionBalance\nEnter the amount you want to pay:";
                                } else {
                                    $response = "$studentNames \nYour Tuition Fees Balance is $tuitionBalance";
                                }
                            } elseif (count($paramsArray) == 6) {
                                $amountToPay = $paramsArray[5];
                                $studentId = $paramsArray[4];
                                $studentData = getStudentBalance($conn, $studentId);
                                $tuitionBalance = $studentData['balance'];
                                $studentNames = $studentData['first_name']." ".$studentData['last_name'];
                                
                                if ($amountToPay <= $tuitionBalance) {
                                    $response = "$studentNames \nYou are about to pay $amountToPay for student ID $studentId\nEnter your PIN to confirm:";
                                } else {
                                    $response = "The amount you entered exceeds the balance. Please try again.";
                                }
                            } elseif (count($paramsArray) == 7) {
                                $pin = $paramsArray[6];
                                // Assuming payment is successful
                                $response = "Payment of $amountToPay was successful!";
                            }
                        } elseif ($paramsArray[3] == "2") {
                            if (count($paramsArray) == 4) {
                                // Prompt for Tracking ID
                                $response = "Enter Tracking ID:";
                            } elseif (count($paramsArray) == 5) {
                                // Retrieve the application fee
                                $trackingId = $paramsArray[4];
                                $applicationFee = getApplicationFee($conn, $trackingId);
                                $studentNames = $applicationFee['first_name']." ".$applicationFee['last_name'];
                                $applicationFee = $applicationFee['fee'];
                                
                                if ($applicationFee === "N/A") {
                                    $response = "Invalid Tracking ID or application fee already paid. Please try again.";
                                } else {
                                    $response = "$studentNames \nYour Application Fee is $applicationFee\nEnter your PIN to confirm payment:";
                                }
                            } elseif (count($paramsArray) == 6) {
                                $pin = $paramsArray[5];
                                // Assuming payment is successful
                                $response = "Payment of $applicationFee was successful!";
                            }
                        } elseif ($paramsArray[3] == "3") {
                            // Online Application Form logic
                            $response = "Online Application Form is under development.";
                        }
                    }
                }
                break;
    
            case "2":
                $response = "Schools menu is under development.";
                break;
    
            default:
                $response = "Invalid option. Please try again.";
                break;
        }
    
        return $response;
    }
    
    // Receive the USSD request parameters
    $sessionId = $_POST['sessionId'];
    $serviceCode = $_POST['serviceCode'];
    $phoneNumber = $_POST['phone'];
    $params = trim($_POST['params']);
    $paramsArray = explode("*", str_replace("#", "*", $params));
    
    // Output the response
    header('Content-type: text/plain');
    echo generateResponse($paramsArray, $conn, $sessionId, $serviceCode);
?>