<?php

$caseId = $_GET['case_id'];
$result = "";
$result .= "<dev class='table table-responsive'>
<table class='table table-bordered'>";


$result .= '<tr>
                <td>เลขที่</td>
                <td><input type="text" value=' . $caseId . ' placeholder=' . $caseId . ' disabled name="case_id" id="case_id" class="form-control" ></td>
                </tr>';

$result .= '<tr>
                <td>สถานะ</td>
                    <td>
                        <select class="form-control " id="status" ">
                                    <option  value="0">อยู่ระหว่างการซ่อมแซ่ม</option>
                                    <option value="1">สำเร็จ</option>
                        </select>
                    </td>
                </tr>';

echo $result;

?>
