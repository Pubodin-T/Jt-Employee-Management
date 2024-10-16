$('form').submit(function(event) {
    event.preventDefault(); // ป้องกันการรีเฟรชหน้า

    $.ajax({
        url: '/register',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            // การดำเนินการเมื่อการส่งข้อมูลสำเร็จ
            alert('ลงทะเบียนสำเร็จ!');
        },
        error: function() {
            // การดำเนินการเมื่อเกิดข้อผิดพลาด
            alert('เกิดข้อผิดพลาด!');
        }
    });
});

