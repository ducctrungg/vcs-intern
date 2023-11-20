import socket
import argparse

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

def craft_request(host):
    request = f"GET / HTTP/1.1\r\nHost: {host}\r\nConnection: close\r\n\r\n"
    return request

def find_title(html):
    start = html.find("<title>") + len("<title>")
    end = html.find("</title>")
    title = html[start:end]
    return title

if __name__ == "__main__":
    host = "example.com"
    port = 80

    parser = argparse.ArgumentParser()
    parser.add_argument("-u", "--url", help="url of website download", default=host)
    args = parser.parse_args()

    request = craft_request(args.url)
    response = send_request(args.url, port, request)
    print("Title:", find_title(response))
