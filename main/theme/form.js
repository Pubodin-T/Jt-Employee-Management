document.getElementById('leave-form').addEventListener('submit', function(event) {
    var leaveType = document.getElementById('leave-type').value;
    var startDate = new Date(document.getElementById('start-date').value);
    var endDate = new Date(document.getElementById('end-date').value);
    var today = new Date();
    var valid = true;

    // Clear previous error messages
    document.getElementById('leave-type-error').textContent = '';
    document.getElementById('attachment-error').textContent = '';
    document.getElementById('reason-error').textContent = '';

    // Validate dates
    if (startDate > endDate) {
        Swal.fire({
            title: 'เกิดข้อผิดพลาด',
            text: 'วันที่สิ้นสุดลาต้องมากกว่าหรือเท่ากับวันที่เริ่มลา',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        valid = false;
    }
    
    // Validate leave type
    if (leaveType === '') {
        Swal.fire({
            title: 'เกิดข้อผิดพลาด',
            text: 'กรุณาเลือกประเภทการลา',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        valid = false;
    } else if (leaveType === 'ลากิจ') {
        var diffDays = Math.ceil((startDate - today) / (1000 * 60 * 60 * 24));
        if (diffDays < 3) {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                text: 'ลากิจต้องขอล่วงหน้าอย่างน้อย 3 วัน',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            valid = false;
        }
    } else if (leaveType === 'ลากรณีพิเศษ') {
        // Optionally, add more checks specific to 'ลากรณีพิเศษ'
    }

    // Validate reason
    var reason = document.getElementById('reason').value.trim();
    if (reason === '') {
        Swal.fire({
            title: 'เกิดข้อผิดพลาด',
            text: 'กรุณาใส่เหตุผลการลา',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        valid = false;
    }

    // Validate attachment (optional check)
    var attachment = document.getElementById('attachment').files.length;
    if (attachment === 0 && leaveType === 'ลากรณีพิเศษ') {
        Swal.fire({
            title: 'เกิดข้อผิดพลาด',
            text: 'กรุณาแนบเอกสารสำหรับการลากรณีพิเศษ',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        valid = false;
    }

    // Prevent form submission if not valid
    if (!valid) {
        event.preventDefault();
    }
});
