// ข้อมูลผู้ใช้ที่ล็อกอิน (สามารถดึงข้อมูลจากระบบจริงได้)
const loggedInUser = {
    employeeId: '1234567890123',
    firstName: 'สมชาย',
    lastName: 'ใจดี',
    position: 'เจ้าหน้าที่ฝ่ายบุคคล',
    age: 35,
    dob: '01/01/1989',
    phone: '081-234-5678',
    email: 'somchai@example.com'
  };
  
  // ตัวอย่างข้อมูลการลาของพนักงานในรูปแบบ JSON
  const leaveData = [
    { startDate: '2023-07-01', endDate: '2023-07-05', type: 'ลากิจ', reason: 'ธุระส่วนตัว', status: 'อนุมัติ', approver: 'หัวหน้างาน' },
    { startDate: '2023-08-10', endDate: '2023-08-12', type: 'ลาป่วย', reason: 'ป่วยเป็นไข้หวัด', status: 'ไม่อนุมัติ', approver: 'หัวหน้างาน' },
    { startDate: '2023-09-15', endDate: '2023-09-20', type: 'ลาพักร้อน', reason: 'ไปเที่ยวพักผ่อน', status: 'อนุมัติ', approver: 'หัวหน้างาน' },
    { startDate: '2023-10-01', endDate: '2023-10-02', type: 'ลากิจ', reason: 'ธุระส่วนตัว', status: 'ไม่อนุมัติ', approver: 'หัวหน้างาน' },
    { startDate: '2023-11-05', endDate: '2023-11-07', type: 'ลากรณีพิเศษ', reason: 'ไปร่วมงานแต่ง', status: 'อนุมัติ', approver: 'หัวหน้างาน' }
  ];
  
  // แสดงข้อมูลผู้ใช้ที่ล็อกอิน
  document.getElementById('employee-id').innerText = loggedInUser.employeeId;
  document.getElementById('employee-name').innerText = `${loggedInUser.firstName} ${loggedInUser.lastName}`;
  document.getElementById('employee-position').innerText = loggedInUser.position;
  document.getElementById('employee-age').innerText = loggedInUser.age;
  document.getElementById('employee-dob').innerText = loggedInUser.dob;
  document.getElementById('employee-phone').innerText = loggedInUser.phone;
  document.getElementById('employee-email').innerText = loggedInUser.email;
  
  // ฟังก์ชันเพื่อแสดงสถานะการลา
  function displayLeaveStatus() {
    const leaveStatusTable = document.getElementById('leave-status');
    leaveData.forEach(leave => {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td>${leave.startDate}</td><td>${leave.endDate}</td><td>${leave.type}</td><td>${leave.reason}</td><td>${leave.status}</td><td>${leave.approver}</td>`;
      leaveStatusTable.appendChild(tr);
    });
  }
  
  // เรียกฟังก์ชันเพื่อแสดงสถานะการลา
  displayLeaveStatus();
  