import socket
import argparse
import os

def send_request(host, port, request):
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        s.connect((host, port))
        s.sendall(request.encode())
        response = b""
        while True:
            chunk = s.recv(4096)
            if len(chunk) == 0:     # No more data received, quitting
                break
            response = response + chunk
    return response.decode()

def craft_request(host, params, files):
    boundary = "----WebKitFormBoundary7MA4YWxkTrZu0gW"
        
    # POST request headers
    headers = f"POST / HTTP/1.1\r\nHost: {host}\r\nContent-Type: multipart/form-data; boundary={boundary}\r\nConnection: close\r\n\r\n"

    # POST request body
    body = ""
    if params:  
        for key, value in params.items():
            body += f"--{boundary}\r\nContent-Disposition: form-data; name=\"{key}\"\r\n\r\n{value}\r\n"
    
    # ----WebKitFormBoundary
    if files:
        for field_name, file_path in files.items():
            filename = os.path.basename(file_path)
            with open(file_path, "r") as file:
                body += f"--{boundary}\r\nContent-Disposition: form-data; name=\"{field_name}\"; filename=\"{filename}\"\r\n"
                body += f"Content-Type: application/octet-stream\r\n\r\n"
                body += file.read()
                body += "\r\n"

    body += f"--{boundary}--\r\n"

    # Combine headers and body
    request = headers + body
    return request

def find_status(html):
    start = html.find("Login")
    end = html.find("</p>")
    status = html[start:end]
    return status

if __name__ == "__main__":
    host = "localweb"
    port = 80
    username = "test"
    password = "test@123"
    files = {"file": "text1.txt"}
    params = {"username": username, "password": password}

    parser = argparse.ArgumentParser()
    parser.add_argument("-u", "--url", help="url of website login", default=host)
    parser.add_argument("-U", "--username", help="username of website login", default=username)
    parser.add_argument("-P", "--password", help="password of website login", default=password)
    parser.add_argument("-lf", "--local-file", help="path of file upload")
    args = parser.parse_args()

    request = craft_request(host, params, files)
    response = send_request(host, port, request)
    print(response)

