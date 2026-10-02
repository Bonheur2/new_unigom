<?php
    function yearToWords($year) {
        $digits = array(
            1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine'
        );
    
        $tens = array(
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        );
    
        if ($year < 10) {
            return $digits[$year];
        } elseif ($year < 20) {
            return $tens[$year];
        } elseif ($year < 100) {
            if($year % 10 != 0){
                return $tens[$year - $year % 10] . '-' . $digits[$year % 10];
            }else{
                return $tens[$year - $year % 10];
            }
            
        } else {
            return "Year out of range";
        }
    }
    
    function translateYear($year){
        $yearInWords = '';
        
        if ($year >= 1000 && $year < 10000) {
            $thousands = (int)($year / 1000);
            $yearInWords .= yearToWords($thousands) . ' Thousand ';
            $year %= 1000;
        }
        
        $yearInWords .= yearToWords($year);
        return $yearInWords;
    }
    
    function translateDay($day) {
        $days = array(
            1 => 'First', 2 => 'Second', 3 => 'Third', 4 => 'Fourth', 5 => 'Fifth',
            6 => 'Sixth', 7 => 'Seventh', 8 => 'Eighth', 9 => 'Ninth', 10 => 'Tenth',
            11 => 'Eleventh', 12 => 'Twelfth', 13 => 'Thirteenth', 14 => 'Fourteenth', 15 => 'Fifteenth',
            16 => 'Sixteenth', 17 => 'Seventeenth', 18 => 'Eighteenth', 19 => 'Nineteenth', 20 => 'Twentieth',
            21 => 'Twenty-First', 22 => 'Twenty-Second', 23 => 'Twenty-Third', 24 => 'Twenty-Fourth', 25 => 'Twenty-Fifth',
            26 => 'Twenty-Sixth', 27 => 'Twenty-Seventh', 28 => 'Twenty-Eighth', 29 => 'Twenty-Ninth', 30 => 'Thirtieth',
            31 => 'Thirty-First'
        );

        return $days[$day];
    }
?>