<!DOCTYPE html>
<html>
<head>
    <title>Điểm danh bằng khuôn mặt</title>
    <style>
        video, canvas { border: 1px solid #ccc; border-radius: 6px; }
        #result { margin-top: 1rem; font-size: 1.2rem; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Điểm danh bằng khuôn mặt</h2>

    <video id="video" width="320" height="240" autoplay></video>
    <button id="scanBtn">Chụp & Nhận diện</button>

    <div id="result"></div>

    <script>
        const video = document.getElementById('video');
        const scanBtn = document.getElementById('scanBtn');
        const result = document.getElementById('result');

        // Start webcam
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => video.srcObject = stream)
            .catch(err => alert("Không thể truy cập webcam: " + err));

        scanBtn.addEventListener('click', async () => {
            const IMG_SIZE = 100;
            const canvas = document.createElement('canvas');
            canvas.width = IMG_SIZE;
            canvas.height = IMG_SIZE;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, IMG_SIZE, IMG_SIZE);

            const dataUrl = canvas.toDataURL('image/jpeg');
            const base64 = dataUrl.split(',')[1]; // strip prefix

            try {
                const resp = await fetch('http://127.0.0.1:5000/predict', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ image: base64 })
                });

                if (!resp.ok) {
                    result.textContent = "Lỗi server: " + resp.status;
                    return;
                }

                const data = await resp.json();
                result.textContent = `Tên: ${data.name} | Độ tin cậy: ${(data.confidence*100).toFixed(2)}%`;
            } catch (err) {
                result.textContent = "Lỗi: " + err.message;
            }
        });
    </script>
</body>
</html>
