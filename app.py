from flask import Flask, render_template, request, redirect

app = Flask(__name__)

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/register', methods=['POST'])
def register():
    # Retrieve form data
    email = request.form['email']
    password = request.form['password']
    confirm_password = request.form['confirm_password']

    # Perform validation
    if not email or not password or not confirm_password:
        return 'Please fill in all the fields'

    if password != confirm_password:
        return 'Passwords do not match'

    # Store the user information in the database or any other data store
    # Add your code here

    return 'Registration successful'

if __name__ == '__main__':
    app.run(debug=True)
