<div class="modal-overlay" id="modalOverlayEdit" onclick="closeOnOverlayEdit(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ແກ້ໄຂ Employer 
        </span>
        <button type="button" class="modal-btn-close" onclick="closeEditModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="edit_emp"> 
          <div class="form-group">
          <label class="form-label">Employer ID</label>
          <input type="text" name="emp_id" id="edit_emp_id" class="form-control-custom" readonly>
        </div>
        
        <div class="form-group">
          <label class="form-label">Business Type</label>
          <select name="bus_type" id="edit_bus_type" class="form-select custom">
            <option value="Personal">Personal</option>
            <option value="Corporate">Corporate</option>
          </select>
        </div>
        
        <div class="form-group">
          <label class="form-label">Employer Name</label>
          <input type="text" name="emp_name" id="edit_emp_name" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">Employer Name (Eng)</label>
          <input type="text" name="emp_name_eng" id="edit_emp_name_eng" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">Company Name</label>
          <input type="text" name="emp_com" id="edit_emp_com" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">Location</label>
          <input type="text" name="location" id="edit_location" class="form-control-custom" >
        </div>
        
        <div class="form-group">
          <label class="form-label">Phone NO</label>
          <input type="text" name="emp_phone" id="edit_emp_phone" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="emp_email" id="edit_emp_email" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">Province</label>
          <select name="prok_id" id="edit_prok_id" class="form-select-custom">
            <?php foreach($s_prok as $prok): ?>
              <option value="<?= $prok['prok_id'] ?>"><?= $prok['prok_name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">District</label>
          <select name="disk_id" id="edit_disk_id" class="form-select-custom">
            <option value=""></option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Code</label>
          <input type="text" name="emp_code" id="edit_emp_code" class="form-control-custom">
        </div>

        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeEditModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="e_btn_emp">
          <i class="bi bi-check-lg"></i> ແກ້ໄຂ
        </button>
      </div>
      
  </div>
</div>

<script>
  $(document).ready(function () {
    $("#edit_prok_id").change(function (e) { 
      let prok_id = $(this).val();
      $.ajax({
        type: "post",
        url: "get/get_prok.php",
        data: {prok_id:prok_id},
        success: function (response) {
          $("#edit_disk_id").html(response);
        }
      });
      
    });
  });
// ===== ຕົວແປ Global =====
let currentEditStatus = 'active';

// ===== ຟັງຊັນ ເປີດ Modal Edit ແລະ Load ຂໍ້ມູນເກົ່າມາໃສ່ຟອມ =====
function handleEditButtonClick(button) {
    // ດຶງຄ່າ JSON string ອອກມາຈາກ data-emp ແລ້ວແປງກັບເປັນ Object
    const empData = JSON.parse(button.getAttribute('data-emp'));
    
    // ເຣຍກຟັງຊັນເປີດ Modal
    openModalEdit(empData);
}
function openModalEdit(empData) {
    // 1. ນຳຂໍ້ມູນເກົ່າມາຫຍອດໃສ່ input ແຕ່ລະຕົວ
    document.getElementById('edit_emp_id').value = empData.emp_id || '';
    document.getElementById('edit_bus_type').value = empData.bus_type || '';
    document.getElementById('edit_emp_name').value = empData.emp_name || '';
    document.getElementById('edit_emp_name_eng').value = empData.emp_name_eng || '';
    document.getElementById('edit_emp_com').value = empData.emp_com || '';
    document.getElementById('edit_location').value = empData.location || '';
    document.getElementById('edit_emp_phone').value = empData.emp_phone || '';
    document.getElementById('edit_emp_email').value = empData.emp_email || '';
    document.getElementById('edit_prok_id').value = empData.prok_id || '';
    document.getElementById('edit_disk_id').value = empData.disk_id || '';
    document.getElementById('edit_emp_code').value = empData.emp_code || '';
    

    // 2. ສະແດງ Modal
    document.getElementById('modalOverlayEdit').classList.add('show');
}

// ===== ປິດ Modal Edit =====
function closeEditModal() {
    document.getElementById('modalOverlayEdit').classList.remove('show');
}

// ===== ປິດ Modal ຖ້າຄລິກ Overlay =====
function closeOnOverlayEdit(e) {
    if (e.target === document.getElementById('modalOverlayEdit')) {
        closeEditModal();
    }
}
</script>