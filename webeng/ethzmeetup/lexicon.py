import re

def validate_password(password):
    if len(password) < 6 or len(password) > 12:
        raise InvalidPasswordError(message='Password must be between 6 and 12 characters.')
    if len(re.findall(r'[A-Z]', password)) == 0:
        raise InvalidPasswordError(message='Password must contain at least one uppercase letter.')
    if len(re.findall(r'\d', password)) == 0:
        raise InvalidPasswordError(message='Password must contain at least one digit.')

class InvalidPasswordError(Exception):
    def __init__(self, message):
        self.message = message