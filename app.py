from flask import Flask, request, jsonify
import numpy as np
import tensorflow as tf
import cv2, base64
from flask_cors import CORS
from io import BytesIO
from PIL import Image

app = Flask(__name__)
CORS(app, origins=["http://127.0.0.1:8000"])

# Load model and labels
model = tf.keras.models.load_model("ai_model/face_recognition_cnn.keras")
label_classes = np.load("ai_model/label_classes.npy")

IMG_SIZE = 100

@app.route("/predict", methods=["POST"])
def predict():
    data = request.json
    if "image" not in data:
        return jsonify({"error": "No image provided"}), 400

    img_data = base64.b64decode(data["image"])
    img = Image.open(BytesIO(img_data)).convert("RGB")
    img = img.resize((IMG_SIZE, IMG_SIZE))
    img = np.array(img).reshape(1, IMG_SIZE, IMG_SIZE, 3) / 255.0 

    pred = model.predict(img)
    idx = np.argmax(pred)
    name = label_classes[idx]
    confidence = float(np.max(pred))

    return jsonify({"name": name, "confidence": confidence})

if __name__ == "__main__":
    app.run(debug=True)

