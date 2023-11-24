#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <shadow.h>
#include <errno.h>
#include <string.h>
#include <sys/types.h>
#include <pwd.h>

const char *SHADOW_FILE = "/etc/shadow";
const char *SHADOW_TEMP = "/etc/shadow_tmp";

/* Get the username of the currnent process */
char *getUsername()
{
    uid_t uid = getuid();
    struct passwd *pwd_info;
    pwd_info = getpwuid(uid);
    return (pwd_info == NULL) ? NULL : pwd_info->pw_name;
}

void removeLineFile(const char *username)
{
    char *line = NULL;
    ssize_t read;
    size_t len = 0;
    FILE *fsrc = fopen(SHADOW_FILE, "r");
    FILE *ftmp = fopen(SHADOW_TEMP, "w");
    if (fsrc == NULL || ftmp == NULL)
    {
        perror("fopen");
    }
    while ((read = getline(&line, &len, fsrc)) != -1)
    {
        if (feof(fsrc))
        {
            break;
        }
        if (strstr(line, username) == NULL)
        {
            fprintf(ftmp, "%s", line);
        }
    }
    fclose(fsrc);
    fclose(ftmp);
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
            printf("Wrong password, enter again\n");
            if (i == 2)
            {
                printf("%lu incorrect password attempts\n", i + 1);
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
    removeLineFile(username);

    // Add new entry (password change) to shadow temporary file
    FILE *fpwd = fopen(SHADOW_TEMP, "a");
    if (fpwd == NULL)
    {
        perror("fopen");
    }
    putspent(spwd_info, fpwd);

    remove(SHADOW_FILE);
    rename(SHADOW_TEMP, SHADOW_FILE);
    return 0;
}
