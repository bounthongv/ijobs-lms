<?php
require '../../../connect.php';
$cid = $_REQUEST['cid'];
$sql = $conn->prepare("SELECT *,cand.cid FROM candidate_korea as cand 
LEFT JOIN interview_form as inter ON cand.cid=inter.cid
WHERE cand.cid = ?");
$sql->execute([$cid]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

$interviewSql = $conn->prepare("SELECT * FROM interview_form WHERE cid = ?");
$interviewSql->execute([$cid]);
$interview = $interviewSql->fetch(PDO::FETCH_ASSOC) ?: [];

// ---------- Helper functions ----------
// ໃສ່ເຄື່ອງໝາຍ X ໃນ checkbox ຖ້າຄ່າກົງກັນ
function chk($field, $value, $data) {
    return (isset($data[$field]) && $data[$field] == $value) ? '&#10003;' : '&nbsp;';
}
// ພິມຄ່າອອກມາ (ກັນ null / htmlspecialchars)
function val($field, $data, $default = '&nbsp;') {
    if (!isset($data[$field]) || $data[$field] === '' || $data[$field] === null) {
        return $default;
    }
    return htmlspecialchars($data[$field]);
}
// ຮູບແບບວັນທີ ດ/ວ/ປ
function fdate($field, $data) {
    if (empty($data[$field]) || $data[$field] == '0000-00-00') return '……./…..…/……….';
    $t = strtotime($data[$field]);
    return $t ? date('d/m/Y', $t) : '……./…..…/……….';
}

// ຮວມ row ກັບ interview ໄວ້ໃນຊຸດດຽວ ເພື່ອສະດວກໃນການອ້າງອີງ checkbox ຂອງ gender/status ທີ່ຢູ່ໃນ $row
$rowData = $row ?: [];
$interviewData = $interview ?: [];

// ຮູບໂລໂກ້ຖືກເຂົ້າລະຫັດ Base64 ແລະ ຝັງໄວ້ໃນໄຟລ໌ນີ້ ເພື່ອໃຫ້ເປັນໄຟລ໌ດຽວ ບໍ່ຕ້ອງອ້າງອີງໄຟລ໌ຮູບແຍກຕ່າງຫາກ
$logoBase64 = "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";
?>
<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ແບບຟອມສໍາພາດງານ ແຮງງານລະດູການ</title>
<style>
:root{
  --border-color:#000;
  --bar-blue:#1f3c88;
}
*{ box-sizing:border-box; }
body{
  margin:0;
  padding:0;
  background:#dfe3ea;
  font-family:"Noto Sans Lao","Phetsarath OT","Saysettha OT","Segoe UI",Tahoma,sans-serif;
  color:#111;
}
.page{
  width:210mm;
  min-height:297mm;
  margin:12mm auto;
  padding:14mm 12mm;
  background:#fff;
  box-shadow:0 4px 16px rgba(0,0,0,.25);
  position:relative;
}
.page:last-child{ margin-bottom:24mm; }
.form-header{
  display:flex;
  align-items:center;
  gap:14px;
  padding:8px 14px;
  margin-bottom:10px;
}
.form-header img{ width:56px; height:56px; object-fit:contain; }
.form-header .title-box{ flex:1; text-align:center; }
.form-header .title-box .lo{ font-size:17px; font-weight:700; }
.form-header .title-box .en{ font-size:12px; margin-top:2px; }
.section-bar{
  background:var(--bar-blue);
  color:#fff;
  font-weight:700;
  font-size:12.5px;
  padding:5px 10px;
  margin-top:10px;
  margin-bottom:0;
}
table.info-table{
  width:100%;
  border-collapse:collapse;
  font-size:11.5px;
  margin-bottom:0;
}
table.info-table td, table.info-table th{
  border:1px solid var(--border-color);
  padding:5px 7px;
  vertical-align:top;
  line-height:1.5;
}
table.info-table th{
  background:#eef0f6;
  font-weight:700;
  text-align:center;
}
td.label{
  background:#f7f8fb;
  font-weight:700;
  white-space:nowrap;
  width:20%;
}
td.blank{ min-height:16px; }
.fill-line{ display:inline-block; border-bottom:1px dotted #555; min-width:60px; }
table.info-table tr{ page-break-inside:avoid; break-inside:avoid; }
.section-block{ page-break-inside:avoid; break-inside:avoid; }
.page-footer{
  position:absolute;
  bottom:6mm; right:12mm;
  font-size:9.5px;
  color:#666;
}
@media print{
  @page{ size:A4; margin:0; }
  body{ background:#fff; }
  .page{
    margin:0;
    box-shadow:none;
    page-break-after:always;
    break-after:page;
  }
  .page:last-child{ page-break-after:auto; break-after:auto; }
}
</style>
</head>
<body onload="print()">

<!-- ================= ໜ້າທີ 1 ================= -->
<div class="page">

  <div class="form-header">
    <img src="data:image/png;base64,<?php echo $logoBase64; ?>" alt="iJobs logo">
    <div class="title-box">
      <div class="lo">ແບບຟອມສໍາພາດງານ ແຮງງານລະດູການ</div>
      <div class="en">(INTERVIEW FORM FOR THE SEASONAL WORKER)</div>
    </div>
  </div>

  <!-- ---------- ໝວດ 1 ---------- -->
  <div class="section-block">
    <div class="section-bar">1. ຂໍ້ມູນສ່ວນຕົວ (Personal Information)</div>
    <table class="info-table">
      <tr>
        <td class="label" style="width:18%">ຊື່ ແລະ ນາມສະກຸນ:</td>
        <td style="width:32%"><?= val('fname', $rowData) ?> <?= val('lname', $rowData, '') ?></td>
        <td class="label" style="width:18%">ວັນເດືອນປີເກີດ:</td>
        <td style="width:32%"><?= fdate('dob', $rowData) ?></td>
      </tr>
      <tr>
        <td class="label">ເພດ:</td>
        <td>[<?= chk('gender','M',$rowData) ?>] ຊາຍ&nbsp;&nbsp;&nbsp;[<?= chk('gender','F',$rowData) ?>] ຍິງ</td>
        <td class="label">ສະຖານະຄອບຄົວ:</td>
        <td>[<?= chk('status','SINGLE',$rowData) ?>] ໂສດ&nbsp;&nbsp;[<?= chk('status','MARRIED',$rowData) ?>] ແຕ່ງງານ&nbsp;&nbsp;[<?= chk('status','DIVORCED',$rowData) ?>] ຮ້າງ/ໝ້າຍ</td>
      </tr>
      <tr>
        <td class="label">ເບີໂທລະສັບ:</td>
        <td><?= val('phone1', $rowData) ?></td>
        <td class="label">ວອດແອັບ:</td>
        <td><?= val('whatsapp', $interviewData) ?></td>
      </tr>
      <tr>
        <td class="label">ເລກບັດປະຈໍາຕົວ:</td>
        <td><?= val('id_no', $rowData) ?></td>
        <td class="label">ພາດສະປອດ:</td>
        <td><?= val('passport', $rowData) ?></td>
      </tr>
      <tr>
        <td class="label">ຈຳນວນລູກ:</td>
        <td>ຊາຍ <?= val('children_male', $interviewData, '.........') ?> ຄົນ, ຍິງ <?= val('children_female', $interviewData, '.........') ?> ຄົນ</td>
        <td class="label">ບຸກຄົນຕິດຕໍ່ສຸກເສີນ:</td>
        <td>(ໂທ: <?= val('emergency_contact', $interviewData, '.....................................') ?>)</td>
      </tr>
    </table>
  </div>

  <!-- ---------- ໝວດ 2 ---------- -->
  <div class="section-block">
    <div class="section-bar">2. ຄວາມພ້ອມດ້ານຮ່າງກາຍ ແລະ ສຸຂະພາບ (Physical &amp; Health Readiness)</div>
    <table class="info-table">
      <tr>
        <th style="width:44%">ລາຍການປະເມີນ (Evaluation Item)</th>
        <th style="width:32%">ຜົນການປະເມີນ</th>
        <th style="width:24%">ໝາຍເຫດ</th>
      </tr>
      <tr>
        <td>2.1 ສ່ວນສູງ / ນໍ້າໜັກ (Height / Weight)</td>
        <td><?= val('height', $rowData, '…......') ?> cm / <?= val('weight', $rowData, '.........') ?> kg</td>
        <td><?= val('height_weight_remark', $interviewData) ?></td>
      </tr>
      <tr>
        <td>2.2 ໂລກປະຈໍາຕົວ / ປະຫວັດຜ່າຕັດ (Chronic Disease)</td>
        <td>[<?= chk('chronic_disease','none',$interviewData) ?>] ບໍ່ມີ&nbsp;&nbsp;[<?= chk('chronic_disease','yes',$interviewData) ?>] ມີ: <?= val('chronic_disease_detail', $interviewData, '.................') ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>2.3 ການສູບຢາ (Smoking)</td>
        <td>[<?= chk('smoking','no',$interviewData) ?>] ບໍ່&nbsp;&nbsp;[<?= chk('smoking','sometimes',$interviewData) ?>] ສູບ ບາງຄັ້ງ</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>2.4 ດື່ມເຫລົ້າ (Alcohol)</td>
        <td>[<?= chk('alcohol','no',$interviewData) ?>] ບໍ່&nbsp;&nbsp;[<?= chk('alcohol','sometimes',$interviewData) ?>] ດື່ມບາງຄັ້ງ</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>2.5 ສຸຂະພາບທົ່ວໄປ &amp; ຄວາມສາມາດຍົກຂອງໜັກ (20-30kg)</td>
        <td>[<?= chk('health_general','great',$interviewData) ?>] ດີຫຼາຍ&nbsp;&nbsp;[<?= chk('health_general','ok',$interviewData) ?>] ພໍໃຊ້&nbsp;&nbsp;[<?= chk('health_general','bad',$interviewData) ?>] ບໍ່ດີ</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>2.6 ຄວາມຄ່ອງແຄ່ວ maneuverable</td>
        <td>[<?= chk('maneuverable','great',$interviewData) ?>] ດີຫຼາຍ&nbsp;&nbsp;[<?= chk('maneuverable','ok',$interviewData) ?>] ພໍໃຊ້&nbsp;&nbsp;[<?= chk('maneuverable','bad',$interviewData) ?>] ບໍ່ດີ</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </div>

  <!-- ---------- ໝວດ 3 ---------- -->
  <div class="section-block">
    <div class="section-bar">3. ປະສົບການເຮັດວຽກກະສິກໍາ (Agricultural Experience)</div>
    <table class="info-table">
      <tr>
        <th style="width:52%">ທັກສະ / ປະສົບການ (Skill / Experience)</th>
        <th style="width:24%">ປະສົບການ (Yes/No)</th>
        <th style="width:24%">ລະຍະເວລາ (Duration)</th>
      </tr>
      <tr>
        <td>3.1 ປະສົບການເຮັດໄຮ່ / ເຮັດນາ / ປູກຜັກ (Farming/Vegetables)</td>
        <td>[<?= chk('exp_farming','yes',$interviewData) ?>] ມີ&nbsp;&nbsp;&nbsp;[<?= chk('exp_farming','no',$interviewData) ?>] ບໍ່ມີ</td>
        <td><?= val('exp_farming_duration', $interviewData, '........') ?> ປີ/ເດືອນ</td>
      </tr>
      <tr>
        <td>3.2 ປະສົບການເຮັດສວນໝາກໄມ້ (Fruit Orchards)</td>
        <td>[<?= chk('exp_orchard','yes',$interviewData) ?>] ມີ&nbsp;&nbsp;&nbsp;[<?= chk('exp_orchard','no',$interviewData) ?>] ບໍ່ມີ</td>
        <td><?= val('exp_orchard_duration', $interviewData, '........') ?> ປີ/ເດືອນ</td>
      </tr>
      <tr>
        <td>3.3 ປະສົບການເຮັດວຽກໃນເຮືອນຮົ່ມ (Greenhouse)</td>
        <td>[<?= chk('exp_greenhouse','yes',$interviewData) ?>] ມີ&nbsp;&nbsp;&nbsp;[<?= chk('exp_greenhouse','no',$interviewData) ?>] ບໍ່ມີ</td>
        <td><?= val('exp_greenhouse_duration', $interviewData, '........') ?> ປີ/ເດືອນ</td>
      </tr>
      <tr>
        <td>3.4 ການນໍາໃຊ້ອຸປະກອນກະສິກໍາ/ຂັບຮົດໄຖ (Agriculture Machineries)</td>
        <td>[<?= chk('exp_machine','yes',$interviewData) ?>] ມີ&nbsp;&nbsp;&nbsp;[<?= chk('exp_machine','no',$interviewData) ?>] ບໍ່ມີ</td>
        <td><?= val('exp_machine_duration', $interviewData, '........') ?> ປີ/ເດືອນ</td>
      </tr>
      <tr>
        <td>3.5 ຄວາມອົດທົນຕໍ່ສະພາບອາກາດໜາວ / ຮ້ອນຈັດ (Climate Adaptation)</td>
        <td>[<?= chk('exp_climate','yes',$interviewData) ?>] ມີ&nbsp;&nbsp;&nbsp;[<?= chk('exp_climate','no',$interviewData) ?>] ບໍ່ມີ</td>
        <td><?= val('exp_climate_duration', $interviewData, '........') ?> ປີ/ເດືອນ</td>
      </tr>
      <tr>
        <td>3.6 ເຄີຍໄປເຮັດວຽກມາກ່ອນບໍ? Use to work in Korea before?</td>
        <td>[<?= chk('worked_korea_before','yes',$interviewData) ?>] ເຄີຍ&nbsp;&nbsp;&nbsp;[<?= chk('worked_korea_before','no',$interviewData) ?>] ບໍ່ເຄີຍ</td>
        <td>ໄປປີໃດ <?= val('worked_korea_before_year', $interviewData, '..................') ?></td>
      </tr>
    </table>
  </div>

 <!-- ---------- ໝວດ 4 ---------- -->
  <div class="section-block">
    <div class="section-bar">4. ທັດສະນະຄະຕິ ແລະ ທັກສະພາສາ (Attitude &amp; Language Ability)</div>
    <table class="info-table">
      <tr>
        <td class="label" style="width:32%">4.1 ທັກສະພາສາເກົາຫຼີ (Korean Language Skill)</td>
        <td>
          [<?= chk('korean_skill','none',$interviewData) ?>] ບໍ່ໄດ້ເລີຍ&nbsp;&nbsp;&nbsp;[<?= chk('korean_skill','little',$interviewData) ?>] ເວົ້າໄດ້ເລັກນ້ອຍ<br>
          [<?= chk('korean_skill','good',$interviewData) ?>] ຟັງ/ເວົ້າໄດ້ດີ&nbsp;&nbsp;[<?= chk('korean_skill','topik',$interviewData) ?>] ມີໃບຮັບຮອງ EPS-TOPIK
        </td>
      </tr>
      <tr>
        <td class="label">4.2 ເຫດຜົນທີ່ຕ້ອງການໄປເຮັດວຽກຢູ່ເກົາຫຼີ<br>(Reason for working in Korea)</td>
        <td>
          [<?= chk('reason_korea','income',$interviewData) ?>] ຫາລາຍໄດ້ໃຫ້ຄອບຄົວ<br>
          [<?= chk('reason_korea','save',$interviewData) ?>] ຕ້ອງການເກັບເງິນຮຽນ/ສ້າງທຸລະກິດ<br>
          [<?= chk('reason_korea','other',$interviewData) ?>] ອື່ນໆ: <?= val('reason_korea_other', $interviewData, '................................................................') ?>
        </td>
      </tr>
      <tr>
        <td class="label">4.3 ຄວາມສາມາດໃນການເຮັດວຽກລ່ວງເວລາ ແລະ ວຽກໜັກ (Overtime &amp; Hard Work)</td>
        <td>
          [<?= chk('overtime','ok',$interviewData) ?>] ສາມາດເຮັດໄດ້ທຸກມື້ / ບໍ່ມີບັນຫາ<br>
          [<?= chk('overtime','reasonable',$interviewData) ?>] ເຮັດໄດ້ຕາມຄວາມເໝາະສົມ
        </td>
      </tr>
      <tr>
        <td class="label">4.4 ການປະຕິບັດຕາມກົດລະບຽບ ແລະ ຕາມສັນຍາຈ້າງ (Adaptability &amp; Discipline)</td>
        <td>
          [<?= chk('discipline','strict',$interviewData) ?>] ພ້ອມປະຕິບັດຕາມກົດລະບຽບ ແລະ ສັນຍາຢ່າງເຄັ່ງຄັດ<br>
          [<?= chk('discipline','adapt',$interviewData) ?>] ສາມາດປັບຕົວເຂົ້າກັບວັດທະນະທໍາເກົາຫຼີໄດ້ດີ
        </td>
      </tr>
      <tr>
        <td class="label">4.5 ນ້ຳໃຈຮັບຜິດຊອບ ແລະ ຄວາມຊື່ສັດ (Accountability &amp; Integrity)</td>
        <td>
          [<?= chk('integrity','responsible',$interviewData) ?>] ມີນ້ຳໃຈຮັບຜິດຊອບຕໍ່ພັນທະ ແລະ ໜີ້ສິນ<br>
          [<?= chk('integrity','honest',$interviewData) ?>] ມີຄວາມຈິງໃຈ ຕັ້ງໃຈກັບບ້ານພາຍຫຼັງສິ້ນສຸດວີຊ່າ
        </td>
      </tr>
    </table>
  </div>

  <!-- ---------- ໝວດ 5 ---------- -->
  <div class="section-block">
    <div class="section-bar">5. ຜົນການປະເມີນຂອງຜູ້ສຳພາດ (Interviewer Assessment)</div>
    <table class="info-table">
      <tr>
        <td class="label" style="width:32%">ຄະແນນການປະເມີນ:<br>(Evaluation Score)</td>
        <td>
          <?php if (!empty($interviewData['eval_score_num'])): ?>
            ຄະແນນ: <?= val('eval_score_num', $interviewData) ?><br>
          <?php endif; ?>
          [<?= chk('eval_score','A',$interviewData) ?>] ດີຫຼາຍ (A)&nbsp;&nbsp;&nbsp;[<?= chk('eval_score','B',$interviewData) ?>] ດີ (B)&nbsp;&nbsp;&nbsp;[<?= chk('eval_score','C',$interviewData) ?>] ປານກາງ (C)&nbsp;&nbsp;&nbsp;[<?= chk('eval_score','D',$interviewData) ?>] ອ່ອນຫຼາຍ (D)&nbsp;&nbsp;&nbsp;[<?= chk('eval_score','F',$interviewData) ?>] ບໍ່ຜ່ານ (F)
        </td>
      </tr>
      <tr>
        <td class="label">ຄວາມຄິດເຫັນຂອງຜູ້ສຳພາດ:<br>(Interviewer's Comments)</td>
        <td style="height:70px;"><?= nl2br(val('interviewer_comments', $interviewData)) ?></td>
      </tr>
      <tr>
        <td class="label">ສະຫຼຸບຜົນການສຳພາດ:<br>(Final Result)</td>
        <td>
          [<?= chk('final_result','passed',$interviewData) ?>] ຜ່ານການສຳພາດ (Passed)<br>
          [<?= chk('final_result','conditional',$interviewData) ?>] ຜ່ານແບບມີເງື່ອນໄຂ (Conditional Passed: <?= val('final_result_condition', $interviewData, '..............................................') ?>)<br>
          [<?= chk('final_result','failed',$interviewData) ?>] ບໍ່ຜ່ານ (Failed)
        </td>
      </tr>
      <tr>
        <td class="label">ລາຍເຊັນຜູ້ສຳພາດ:<br>(Interviewer Signature)</td>
        <td style="height:60px;">ລາຍເຊັນ: .................................................... ວັນທີ: ....../....../.........<br>ຊື່ຜູ້ສຳພາດ: ....................................................</td>
      </tr>
    </table>
  </div>
</div><!-- /page 1 -->

</body>
</html>