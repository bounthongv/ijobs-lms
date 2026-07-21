<div class="modal-overlay" id="modalOverlayEdit" onclick="closeOnOverlayEdit(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ແກ້ໄຂ Village 
        </span>
        <button type="button" class="modal-btn-close" onclick="closeEditModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="edit_vill"> 
          <input type="hidden" name="old_vill_id" id="old_vill_id">
        <div class="form-group">
          <label class="form-label">
             Village ID
          </label>
          <input type="text" name="vill_id" id="edit_vill_id" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">
            Province
          </label>
          <select name="pro_id" id="edit_pro_id" class="form-select-custom">
            <option value="">ເລືອກ</option>
            <?php foreach($pro as $p): ?>
              <option value="<?= $p['pro_id'] ?>"><?= $p['pro_name_lao'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">
            District
          </label>
          <select name="dis_id" id="edit_dis_id" class="form-select-custom">
            <option value="">ເລືອກ</option>
            <?php foreach($dis as $d): ?>
              <option value="<?= $d['dis_id'] ?>"><?= $d['dis_name_lao'] ?></option>
            <?php endforeach ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">
            Village Name (Lao)
          </label>
          <input type="text" name="vill_name_lao" id="edit_vill_name_lao" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">
             Village Name (Eng)
          </label>
          <input type="text" name="vill_name" id="edit_vill_name" class="form-control-custom">
        </div>

        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeEditModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="e_btn_vill">
          <i class="bi bi-check-lg"></i> ແກ້ໄຂ
        </button>
      </div>
      
  </div>
</div>

<script>
  $(document).ready(function () {
    $("#edit_pro_id").change(function(e) {
            let pro_id = $(this).val();
            $.ajax({
                type: "post",
                url: "get/get_pro.php",
                data: {
                    pro_id: pro_id
                },
                success: function(response) {
                    $("#edit_dis_id").html(response);
                }
            });

        });
  });
// ===== ຕົວແປ Global =====
let currentEditStatus = 'active';

// ===== ຟັງຊັນ ເປີດ Modal Edit และโหลดข้อมูลเก่ามาใส่ในฟอร์ม =====
// คุณสามารถส่ง Object ข้อมูล user เข้ามาในฟังก์ชันนี้ได้เลย เช่น openModalEdit({id: 1, fname: 'John', ...})
function handleEditButtonClick(button) {
    // ดึงค่า JSON string ออกมาจาก data-user แล้วแปลงกลับเป็น Object
    const userData = JSON.parse(button.getAttribute('data-user'));
    
    // เรียกฟังก์ชันเปิด Modal ตัวเดิมที่คุณมีอยู่แล้ว
    openModalEdit(userData);
}
function openModalEdit(userData) {
    // 1. นำข้อมูลเก่ามาหยอดใส่ input แต่ละตัว
    document.getElementById('edit_pro_id').value = userData.pro_id || '';
    document.getElementById('edit_dis_id').value = userData.dis_id || '';
    document.getElementById('old_vill_id').value = userData.vill_id || '';
    document.getElementById('edit_vill_id').value = userData.vill_id || '';
    document.getElementById('edit_vill_name_lao').value = userData.vill_name_lao || '';
    document.getElementById('edit_vill_name').value = userData.vill_name || '';
    

    // 2. แสดง Modal
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