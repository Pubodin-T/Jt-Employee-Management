// employee.js

document.addEventListener('DOMContentLoaded', function() {
    const employeeId = '819071028'; // หรือดึงจาก session หรือ query parameter
    fetchEmployeeData(employeeId);
});

function fetchEmployeeData(employeeId) {
    fetch(`fetch_employee.php?id=${employeeId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('employee-id').textContent = data.employee_id;
            document.getElementById('employee-name').textContent = data.employee_name;
            document.getElementById('employee-position').textContent = data.position;
            document.getElementById('employee-age').textContent = data.age || 'ข้อมูลไม่ครบ';
            document.getElementById('employee-dob').textContent = data.dob || 'ข้อมูลไม่ครบ';
            document.getElementById('employee-phone').textContent = data.phone || 'ข้อมูลไม่ครบ';
            document.getElementById('employee-email').textContent = data.email || 'ข้อมูลไม่ครบ';
        })
        .catch(error => console.error('Error fetching employee data:', error));
}
