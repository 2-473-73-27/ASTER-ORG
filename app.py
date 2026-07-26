import os
import random
from flask import Flask, render_template, request, redirect, url_for, session, jsonify

app = Flask(__name__)
app.secret_key = 'astrasms_secure_secret_key'  # Required for session management

# In-memory storage for gallery images and the active logo
gallery_images = [
    "https://astrasms.com/assets/img/logo.png"
]
current_active_logo = "https://astrasms.com/assets/img/logo.png"

@app.route('/')
def index():
    # Generate fresh captcha numbers if not already in session
    if 'num1' not in session or 'num2' not in session:
        session['num1'] = random.randint(1, 10)
        session['num2'] = random.randint(1, 10)
    
    captcha_sum = session['num1'] + session['num2']
    
    return render_template(
        'index.html',
        num1=session['num1'],
        num2=session['num2'],
        captcha_sum=captcha_sum,
        gallery_images=gallery_images,
        current_active_logo=current_active_logo
    )

@app.route('/login', methods=['POST'])
def login():
    username = request.form.get('username', '').strip()
    password = request.form.get('password', '')
    try:
        captcha_answer = int(request.form.get('captchaAnswer', 0))
    except ValueError:
        captcha_answer = -1

    correct_sum = session.get('num1', 0) + session.get('num2', 0)

    if captcha_answer != correct_sum:
        return jsonify({'success': False, 'message': f'Incorrect Captcha Answer! Please enter {correct_sum}.'})

    if username == 'Muhammad' and password == 'Muhammad':
        session['user'] = 'Muhammad'
        session['role'] = 'MANAGER ACCOUNT'
        session['is_manager'] = True
        return jsonify({'success': True, 'role': 'manager', 'username': 'Muhammad'})
    elif username == 'test123' and password == 'test123':
        session['user'] = 'test123'
        session['role'] = 'TEST PORTAL'
        session['is_manager'] = False
        return jsonify({'success': True, 'role': 'test', 'username': 'test123'})
    else:
        return jsonify({'success': False, 'message': 'Invalid credentials! Please use Muhammad / Muhammad or test123 / test123.'})

@app.route('/logout', methods=['POST'])
def logout():
    session.clear()
    return jsonify({'success': True})

@app.route('/upload_image', methods=['POST'])
def upload_image():
    if not session.get('is_manager'):
        return jsonify({'success': False, 'message': 'Unauthorized'}), 403

    if 'image' in request.files:
        file = request.files['image']
        if file.filename != '':
            # For simplicity, convert uploaded file to data URL or save locally
            import base64
            encoded_string = base64.b64encode(file.read()).decode('utf-8')
            mime_type = file.mimetype or 'image/png'
            data_url = f"data:{mime_type};base64,{encoded_string}"
            
            gallery_images.append(data_url)
            return jsonify({'success': True, 'gallery': gallery_images})

    return jsonify({'success': False, 'message': 'No file selected'})

@app.route('/set_logo', methods=['POST'])
def set_logo():
    global current_active_logo
    if not session.get('is_manager'):
        return jsonify({'success': False, 'message': 'Unauthorized'}), 403

    data = request.get_json()
    new_logo = data.get('logo')
    if new_logo in gallery_images:
        current_active_logo = new_logo
        return jsonify({'success': True, 'active_logo': current_active_logo})
    
    return jsonify({'success': False, 'message': 'Invalid image selection'})

if __name__ == '__main__':
    app.run(debug=True, port=5000)