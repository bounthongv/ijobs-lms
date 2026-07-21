<div class="modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ເພີ່ມ Employers ໃໝ່
        </span>
        <button type="button" class="modal-btn-close" onclick="closeModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="save_emp">
        <div class="form-group">
          <label class="form-label">Employer ID</label>
          <input type="text" name="emp_id" class="form-control-custom" value="<?= $emp_id ?>" readonly>
        </div>
        
        <div class="form-group">
          <label class="form-label">Business Type</label>
          <select name="bus_type" id="" class="form-select custom">
            <option value="Personal">Personal</option>
            <option value="Corporate">Corporate</option>
          </select>
        </div>
        
        <div class="form-group">
          <label class="form-label">Employer Name</label>
          <input type="text" name="emp_name" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">Employer Name (Eng)</label>
          <input type="text" name="emp_name_eng" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">Company Name</label>
          <input type="text" name="emp_com" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-control-custom" >
        </div>
        
        <div class="form-group">
          <label class="form-label">Phone NO</label>
          <input type="text" name="emp_phone" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="emp_email" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">Province</label>
          <select name="prok_id" id="prok_id" class="form-select-custom">
            <?php foreach($s_prok as $prok): ?>
              <option value="<?= $prok['prok_id'] ?>"><?= $prok['prok_name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">District</label>
          <select name="disk_id" id="disk_id" class="form-select-custom">
            <option value=""></option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Code</label>
          <input type="text" name="emp_code" class="form-control-custom">
        </div>
        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="btn_emp">
          <i class="bi bi-check-lg"></i> ບັນທຶກ
        </button>
      </div>
      
  </div>
</div>
<script>
  $(document).ready(function () {
    $("#prok_id").change(function (e) { 
      let prok_id = $(this).val();
      $.ajax({
        type: "post",
        url: "get/get_prok.php",
        data: {prok_id:prok_id},
        success: function (response) {
          $("#disk_id").html(response);
        }
      });
      
    });
  });
// ===== ຟັງຊັນ ເປີດ Modal (Clear ຄ່າເກົ່າອອກໃຫ້ເປັນຫວ່າງປ່າວສະເໝີ) =====
function openModal() {

  document.getElementById('modalOverlay').classList.add('show');
}

// ===== ປິດ Modal =====
function closeModal() {
  document.getElementById('modalOverlay').classList.remove('show');
}

// ===== ປິດ Modal ຖ້າຄລິກ Overlay =====
function closeOnOverlay(e) {
  if (e.target === document.getElementById('modalOverlay')) closeModal();
}


</script>