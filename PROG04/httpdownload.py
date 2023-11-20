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
    return response.split(b'\r\n\r\n', 1) # only get content file

def craft_request(host, file_path):
    request = f"GET /{file_path} HTTP/1.1\r\nHost: {host}\r\nConnection: close\r\n\r\n"
    return request

if __name__ == "__main__":
    host = "localweb"   
    port = 80
    download_dir = "downloads"
    file_path = "tmp1.txt"

    parser = argparse.ArgumentParser()
    parser.add_argument("-u", "--url", help="url of website login", default=host)
    parser.add_argument("-rf", "--remote-file", help="path of file to download", default=file_path)
    args = parser.parse_args()

    file_path = args.remote_file
    request = craft_request(host, file_path)
    header, response = send_request(host, port, request)

    status_line = header.split(b'\r\n')[0]
    if not status_line.startswith(b'HTTP/1.1 200'):
        print(f"Failed to download the file")
    else: 
        with open(os.path.join(download_dir, file_path), 'ab') as file:  
            file.write(response)
        print("File Size is :", os.path.getsize(os.path.join(download_dir, file_path)), "bytes")

