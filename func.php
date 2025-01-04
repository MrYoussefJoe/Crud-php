<?php
function noEnj($str){
	global $conn;
    $str = strip_tags($str);  // إزالة العلامات HTML
    $str = trim($str);        // إزالة الفراغات الزائدة
    $str = htmlentities($str); // تحويل الأحرف الخاصة إلى HTML entities (لحماية XSS)
    return $str;
}
