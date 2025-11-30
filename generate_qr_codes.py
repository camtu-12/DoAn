import pymysql
import qrcode
from PIL import Image, ImageDraw, ImageFont
import os
from datetime import datetime

# Kết nối database
connection = pymysql.connect(
    host='127.0.0.1',
    user='root',
    password='',
    database='doanchuyennganh',
    charset='utf8mb4',
    cursorclass=pymysql.cursors.DictCursor
)

try:
    with connection.cursor() as cursor:
        # Lấy 3 sinh viên đầu tiên
        sql = "SELECT Mssv, Ho_va_ten, Ngay_Sinh FROM sinhviens ORDER BY Mssv LIMIT 3"
        cursor.execute(sql)
        students = cursor.fetchall()
        
        # Tạo thư mục lưu QR codes
        output_dir = "qr_codes"
        if not os.path.exists(output_dir):
            os.makedirs(output_dir)
        
        print("Đang tạo QR codes cho 3 sinh viên đầu tiên:\n")
        
        for student in students:
            mssv = student['Mssv']
            ho_va_ten = student['Ho_va_ten']
            ngay_sinh_raw = student['Ngay_Sinh']
            
            # Format tên: CHỮ HOA, bỏ dấu
            ten_khong_dau = ho_va_ten.upper()
            ten_khong_dau = ten_khong_dau.replace(' ', '')
            # Đơn giản hóa: bỏ dấu tiếng Việt
            replacements = {
                'Á': 'A', 'À': 'A', 'Ả': 'A', 'Ã': 'A', 'Ạ': 'A',
                'Ă': 'A', 'Ắ': 'A', 'Ằ': 'A', 'Ẳ': 'A', 'Ẵ': 'A', 'Ặ': 'A',
                'Â': 'A', 'Ấ': 'A', 'Ầ': 'A', 'Ẩ': 'A', 'Ẫ': 'A', 'Ậ': 'A',
                'É': 'E', 'È': 'E', 'Ẻ': 'E', 'Ẽ': 'E', 'Ẹ': 'E',
                'Ê': 'E', 'Ế': 'E', 'Ề': 'E', 'Ể': 'E', 'Ễ': 'E', 'Ệ': 'E',
                'Í': 'I', 'Ì': 'I', 'Ỉ': 'I', 'Ĩ': 'I', 'Ị': 'I',
                'Ó': 'O', 'Ò': 'O', 'Ỏ': 'O', 'Õ': 'O', 'Ọ': 'O',
                'Ô': 'O', 'Ố': 'O', 'Ồ': 'O', 'Ổ': 'O', 'Ỗ': 'O', 'Ộ': 'O',
                'Ơ': 'O', 'Ớ': 'O', 'Ờ': 'O', 'Ở': 'O', 'Ỡ': 'O', 'Ợ': 'O',
                'Ú': 'U', 'Ù': 'U', 'Ủ': 'U', 'Ũ': 'U', 'Ụ': 'U',
                'Ư': 'U', 'Ứ': 'U', 'Ừ': 'U', 'Ử': 'U', 'Ữ': 'U', 'Ự': 'U',
                'Ý': 'Y', 'Ỳ': 'Y', 'Ỷ': 'Y', 'Ỹ': 'Y', 'Ỵ': 'Y',
                'Đ': 'D'
            }
            for viet, eng in replacements.items():
                ten_khong_dau = ten_khong_dau.replace(viet, eng)
            
            # Format ngày sinh: DD.MM.YYYY
            if isinstance(ngay_sinh_raw, str):
                # Nếu là string dạng YYYY-MM-DD
                parts = ngay_sinh_raw.split('-')
                ngay_sinh_formatted = f"{parts[2]}.{parts[1]}.{parts[0]}"
            else:
                # Nếu là datetime object
                ngay_sinh_formatted = ngay_sinh_raw.strftime('%d.%m.%Y')
            
            # Tạo QR data theo format: MSSV_TEN_DD.MM.YYYY
            qr_data = f"{mssv}_{ten_khong_dau}_{ngay_sinh_formatted}"
            
            # Tạo QR code
            qr = qrcode.QRCode(
                version=1,
                error_correction=qrcode.constants.ERROR_CORRECT_L,
                box_size=10,
                border=4,
            )
            qr.add_data(qr_data)
            qr.make(fit=True)
            
            # Tạo ảnh QR code
            img = qr.make_image(fill_color="black", back_color="white")
            img = img.convert('RGB')  # Convert to RGB
            
            # Tạo ảnh mới với text phía dưới
            img_width, img_height = img.size
            final_img = Image.new('RGB', (img_width, img_height + 100), 'white')
            final_img.paste(img, (0, 0))
            
            # Thêm text thông tin sinh viên
            draw = ImageDraw.Draw(final_img)
            try:
                font = ImageFont.truetype("arial.ttf", 16)
            except:
                font = ImageFont.load_default()
            
            # Vẽ text
            text_lines = [
                f"MSSV: {mssv}",
                f"Tên: {ho_va_ten}",
                f"Ngày sinh: {ngay_sinh_formatted}"
            ]
            y_offset = img_height + 10
            for line in text_lines:
                draw.text((10, y_offset), line, fill='black', font=font)
                y_offset += 25
            
            # Lưu file
            filename = f"{output_dir}/{mssv}.png"
            final_img.save(filename)
            
            print(f"✓ {mssv} - {ho_va_ten}")
            print(f"  QR Data: {qr_data}")
            print(f"  Saved: {filename}\n")
        
        print(f"\nĐã tạo xong {len(students)} QR codes trong thư mục '{output_dir}/'")
        
finally:
    connection.close()
