document.getElementById('addEmployeeForm').onsubmit = function(event) {
    event.preventDefault(); // Prevent the form from submitting the traditional way
    
    // Show success message with customization
    Swal.fire({
        title: 'เพิ่มพนักงานใหม่เรียบร้อยแล้ว!', // เปลี่ยน title
        text: 'ข้อมูลพนักงาน: \n' + // เพิ่มรายละเอียดเพิ่มเติม
              'รหัสพนักงาน: ' + document.getElementById('employee_id').value + '\n' + 
              'ชื่อพนักงาน: ' + document.getElementById('employee_name').value + '\n' +
              'ตำแหน่ง: ' + document.getElementById('employee_position').value,
        icon: 'success',
        confirmButtonText: 'ตกลง',
        background: '#f8f9fa', // เปลี่ยนสีพื้นหลัง
        color: '#333', // เปลี่ยนสีข้อความ
        iconColor: '#28a745', // เปลี่ยนสีของไอคอน
        confirmButtonColor: '#007bff', // เปลี่ยนสีปุ่มยืนยัน
        customClass: {
            title: 'swal-title', // เปลี่ยนสไตล์ของ title
            content: 'swal-content', // เปลี่ยนสไตล์ของ content
            confirmButton: 'swal-confirm-button' // เปลี่ยนสไตล์ของปุ่มยืนยัน
        },
        showClass: {
            popup: 'animate__animated animate__fadeInDown' // เพิ่มการแสดงผลที่น่าดึงดูด
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp' // เพิ่มการซ่อนที่น่าดึงดูด
        }
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit(); // Submit the form after the alert is closed
        }
    });
}
