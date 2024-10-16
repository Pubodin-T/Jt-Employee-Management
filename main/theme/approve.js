function submitReject() {
  var reason = document.getElementById('reason-input').value;
  if (reason.trim() === '') {
      Swal.fire({
          title: 'กรุณากรอกเหตุผล!',
          icon: 'warning',
          confirmButtonText: 'ตกลง'
      });
      return;
  }
  // ส่งข้อมูลเหตุผล
  Swal.fire({
      title: 'ยืนยันการปฏิเสธ?',
      text: "คุณแน่ใจหรือว่าต้องการปฏิเสธการลา?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'ตกลง',
      cancelButtonText: 'ยกเลิก'
  }).then((result) => {
      if (result.isConfirmed) {
          // ส่งฟอร์มที่มีเหตุผล
          var form = document.createElement('form');
          form.method = 'post';
          form.action = 'approve_leave.php';

          var inputId = document.createElement('input');
          inputId.type = 'hidden';
          inputId.name = 'leave_requests_id';
          inputId.value = document.querySelector('input[name="leave_requests_id"]').value;
          form.appendChild(inputId);

          var inputAction = document.createElement('input');
          inputAction.type = 'hidden';
          inputAction.name = 'action';
          inputAction.value = 'reject';
          form.appendChild(inputAction);

          var inputReason = document.createElement('input');
          inputReason.type = 'hidden';
          inputReason.name = 'reason';
          inputReason.value = reason;
          form.appendChild(inputReason);

          document.body.appendChild(form);
          form.submit();
      }
  });
}
