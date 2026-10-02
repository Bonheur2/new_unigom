<?php
                                                    ini_set('display_errors', 1);
                                                    ini_set('display_startup_errors', 1);
                                                    error_reporting(E_ALL);
                                                    $insert="INSERT INTO tbl_fee_category (camp_id, prg_type_id, fac_id, dept_id, splz_id, level_id, name, amount)
                                                    SELECT camp_id,prg_type_id,fac_id,dept_id,splz_id,level_id,'Tution Fee' AS name,SUM(amount) AS amount FROM fee_category
                                                    GROUP BY camp_id,prg_type_id,fac_id,dept_id,splz_id,level_id";
                                                    $cinsert=$conn->prepare($insert);
                                                    $cinsert->execute();
                                                    $row_cinsert=$cinsert->fetch(PDO::FETCH_ASSOC);
                                                    if($row_cinsert){
                                                        echo "Well";
                                                    }
                                                    else{
                                                       echo "Fail"; 
                                                    }
                                                    
                                                    
                                                    ?>