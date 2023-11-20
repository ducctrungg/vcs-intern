import socket
import argparse

def get_request(host, port, request):
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

def craft_request(host, username, password):
    params = f"username={username}&password={password}"
    request = f"POST / HTTP/1.1\r\nHost: {host}\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: {len(params)}\r\nConnection: close\r\n\r\n{params}"
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

    parser = argparse.ArgumentParser()
    parser.add_argument("-u", "--url", help="url of website login", default=host)
    parser.add_argument("-U", "--username", help="username of website login", default=username)
    parser.add_argument("-P", "--password", help="password of website login", default=password)
    args = parser.parse_args()

    request = craft_request(args.url, args.username, args.password)
    response = get_request(args.url, port, request)
    print(find_status(response))
