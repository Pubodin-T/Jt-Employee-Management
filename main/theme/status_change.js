// status_change.js
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // หยุดการส่งฟอร์มปกติ

        // แสดง SweetAlert2 เพื่อยืนยันการเปลี่ยนสถานะ
        Swal.fire({
            title: 'ยืนยันการเปลี่ยนสถานะ?',
            text: "คุณต้องการเปลี่ยนสถานะพนักงานนี้หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, เปลี่ยนสถานะ!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // ส่งฟอร์มถ้าผู้ใช้ยืนยัน
            }
        });
    });
});
