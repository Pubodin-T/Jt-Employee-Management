<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400&display=swap" rel="stylesheet">
    <style>
         body {
            font-family: 'Kanit', sans-serif; /* ใช้ฟอนต์ Kanit */
            background-image: url('images/slider-main/j&t22.jpg'); /* เปลี่ยนเป็นเส้นทางรูปภาพของคุณ */
            background-size: cover; /* ทำให้ภาพพื้นหลังครอบคลุมทั้งพื้นที่ */
            background-position: center; /* จัดให้ภาพอยู่กลาง */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff; 
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
            width: 300px; 
            text-align: center; 
        }
        
        h2 {
            color: #333; 
            margin-bottom: 20px; 
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #555; 
            text-align: left; 
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }
        button, .back-button {
            background-color: #4CAF50; 
            color: white; 
            padding: 10px;
            border: none;
            border-radius: 4px; 
            cursor: pointer;
            font-size: 16px; 
            transition: background-color 0.3s; 
            width: 48%; /* ขยายความกว้างปุ่มให้เท่ากัน */
            display: inline-block; /* ทำให้ปุ่มอยู่ในบรรทัดเดียวกัน */
            margin: 5px 1%; /* เพิ่มระยะห่างระหว่างปุ่ม */
            text-align: center; /* จัดข้อความในปุ่มให้กลาง */
            font-family: 'Kanit', sans-serif; /* ใช้ฟอนต์ Kanit */
        }
        button:hover, .back-button:hover {
            background-color: #45a049; 
        }
        .back-button {
            background-color: #e53935; /* สีพื้นหลังของปุ่มย้อนกลับ */
        }
        .back-button:hover {
            background-color: #c62828; /* สีเมื่อเอาเมาส์ไปวางบนปุ่มย้อนกลับ */
        }
    </style>
    </style>
</head>
<body>
    <div class="container">
        <h2>ลืมรหัสผ่าน</h2>
        <form action="send_reset_link.php" method="POST">
            <label for="email">กรอก Email ของคุณ:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">ยืนยัน</button>
            <a href="login.html" class="back-button">ย้อนกลับ</a>
        </form>
    </div>
</body>
</html>
