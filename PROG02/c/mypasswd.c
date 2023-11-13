#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <shadow.h>
#include <errno.h>
#include <string.h>
#include <sys/types.h>
#include <pwd.h>

/* Return name corresponding to 'uid', or NULL on error */
char *getUsername()
{
  uid_t uid = geteuid();
  struct passwd *pwd_info;
  pwd_info = getpwuid(uid);
  return (pwd_info == NULL) ? NULL : pwd_info->pw_name;
}

int main()
{
  char *username;
  char *old_plaintext_password;
  char *new_plaintext_password;
  char new_encrypted_password[1024];
  struct spwd *spwd_info;

  username = getUsername();
  if (username == NULL)
  {
    perror("getUsername");
    exit(-1);
  }
  printf("Username: %s\n", username);
  fflush(stdout);
  size_t len = strlen(username);
  // Remove trailling '\n'
  if (username[len - 1] == '\n')
  {
    username[len - 1] = '\0';
  }
  spwd_info = getspnam(username);
  if (spwd_info == NULL)
  {
    perror("getspnam");
    exit(-1);
  }
  for (size_t i = 0; i < 3; i++)
  {
    old_plaintext_password = getpass("Old password: ");
    if (strcmp(spwd_info->sp_pwdp, crypt(old_plaintext_password, spwd_info->sp_pwdp)) != 0)
    {
      printf("Wrong password, enter againn\n");
      if (i == 2)
      {
        printf("%d incorrect password attempts\n", i);
        exit(0);
      }
    }
    else
    {
      new_plaintext_password = getpass("New password: ");
      strcpy(new_encrypted_password, crypt(new_plaintext_password, spwd_info->sp_pwdp));
      spwd_info->sp_pwdp = new_encrypted_password;
      break;
    }
  }
  if (putspent(spwd_info, fopen("/etc/shadow", "w")) != 0)
  {
    perror("putspent");
    exit(-1);
  }
  return 0;
}
