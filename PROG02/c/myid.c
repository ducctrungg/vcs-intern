#include <pwd.h>
#include <stdlib.h>
#include <stdio.h>
#include <grp.h>
#include <string.h>
#include <errno.h> 

#define MAX_USERNAME_LENGTH 1024
#define MAX_GROUP_COUNT 128

int main()
{
  char username[MAX_USERNAME_LENGTH];
  char **groups;
  errno = 0;

  printf("Enter username: ");
  scanf("%s", username);

  // Read from /etc/passwd file
  struct passwd *pwd_user = getpwnam(username);
  if (pwd_user == NULL && errno == 0)
  {
    printf("The given name was not found");
    exit(-1);
  }
  else 
  {
    printf("UID: %d\n", pwd_user->pw_uid);
    printf("Username: %s\n", pwd_user->pw_name);
    printf("Home directory: %s\n", pwd_user->pw_dir);
    printf("Groups: ");
  }

  struct group *grp_info;
  // Restart from the beginning of the file if need
  setgrent();
  while((grp_info = getgrent()) != NULL)
  {
    for (int i = 0; grp_info->gr_mem[i] != NULL; i++)
    {
      if(strcmp(grp_info->gr_mem[i], username) == 0)
      {
        printf("%s ", grp_info->gr_name);
      }
    }
  }
  printf("\n");
  // Close file
  endgrent();
  return 0;
}