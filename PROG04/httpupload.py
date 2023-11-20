import socket
import argparse
import os
import mimetypes

def send_request(host, port, request):
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        s.connect((host, port))
        s.sendall(request)
        response = b""
        while True:
            chunk = s.recv(4096)
            if len(chunk) == 0:     # No more data received, quitting
                break
            response = response + chunk
    return response.decode()

def craft_request(host, params, files):
    boundary = "----WebKitFormBoundarykpxaM9IPauznl59l"
        
    
    # POST request body
    body = b""
    if params:  
        for key, value in params.items():
            body += f"--{boundary}\r\nContent-Disposition: form-data; name=\"{key}\"\r\n\r\n{value}\r\n".encode()
    
    # ----WebKitFormBoundary
    if files:
        for field_name, file_path in files.items():
            filename = os.path.basename(file_path)
            with open(file_path, "rb") as file:
                body += f"--{boundary}\r\nContent-Disposition: form-data; name=\"{field_name}\"; filename=\"{filename}\"\r\n".encode()
                body += f"Content-Type: {mimetypes.guess_type(filename)[0]}\r\n\r\n".encode()
                body += file.read()
                body += "\r\n".encode()

    body += f"--{boundary}--".encode()

    # POST request headers
    headers = f"POST /index.php HTTP/1.1\r\nHost: {host}\r\nContent-length: {len(body)}\r\nOrigin: http://localweb\r\nContent-Type: multipart/form-data; boundary={boundary}\r\nReferer: http://localweb/index.php\r\nConnection: close\r\n\r\n"

    # Combine headers and body
    request = headers.encode() + body
    return request

def find_status(html):
    start = html.find("<body>") + len("<body>")
    end = html.find("</body>")
    status = html[start:end]
    return status

if __name__ == "__main__":
    host = "localweb"
    port = 80
    username = "test"
    password = "test@123"
    file = "filetest.png"

    parser = argparse.ArgumentParser()
    parser.add_argument("-u", "--url", help="url of website login", default=host)
    parser.add_argument("-U", "--username", help="username of website login", default=username)
    parser.add_argument("-P", "--password", help="password of website login", default=password)
    parser.add_argument("-lf", "--local-file", help="path of file upload", default=file)
    args = parser.parse_args()

    params = {"username": args.username, "password": args.password}
    files = {"file": args.local_file}

    request = craft_request(args.url, params, files)
    response = send_request(args.url, port, request)
    print(find_status(response))

