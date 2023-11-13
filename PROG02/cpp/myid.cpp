#include <iostream>
#include <fstream>
#include <sstream>
#include <vector>
#include <map>

using namespace std;

/*
define in <pwd.h>
struct passwd
{
  char *pw_name;   // username
  char *pw_passwd; // user password
  uid_t pw_uid;    // user ID
  gid_t pw_gid;    // group ID
  char *pw_gecos;  // user information
  char *pw_dir;    // home directory
  char *pw_shell;  // shell program
};
*/

struct User
{
  string username;
  string uid;
  string comment;
  string home_directory;
  vector<string> groups;
};

map<string, User> readPasswdFile()
{
  ifstream file("/etc/passwd");

  if (!file.good())
  {
    cout << "Can't open file, file does not exist or dont't have permission to read file\n";
    exit(1);
  }

  string line;
  map<string, User> users; // string: username, User: struct User

  while (getline(file, line))
  {
    stringstream ss(line); // read each word from string as stream
    string token;
    vector<string> tokens;
    User user;

    while (getline(ss, token, ':'))
    {
      tokens.push_back(token);
    }

    user.username = tokens[0];
    user.uid = tokens[2];
    user.comment = tokens[4];
    user.home_directory = tokens[5];
    users[user.username] = user;
  }
  return users;
}

map<string, vector<string>> readGroupFile()
{
  ifstream file("/etc/group");

  if (!file.good())
  {
    cout << "Can't open file, file does not exist or dont't have permission to read file\n";
    exit(1);
  }

  string line;
  map<string, vector<string>> groups;

  while (getline(file, line))
  {
    stringstream ss(line);
    string token;
    vector<string> tokens;

    while (getline(ss, token, ':'))
    {
      tokens.push_back(token);
    }

    string group_name = tokens[0];
    stringstream users_ss(tokens[3]);
    string user;

    while (getline(users_ss, user, ','))
    {
      groups[user].push_back(group_name);
    }
  }

  return groups;
}

int main()
{
  string username;
  cout << "Enter username: ";
  cin >> username;

  auto users = readPasswdFile();
  auto groups = readGroupFile();

  // Find username based on users key
  if (users.find(username) != users.end())
  {
    User user = users[username];
    user.groups = groups[username];

    cout << "Username: " << user.username << "\n";
    cout << "UID: " << user.uid << "\n";
    cout << "Home Directory: " << user.home_directory << "\n";
    cout << "Groups: ";

    for (const auto &group : user.groups)
    {
      cout << group << " ";
    }
    cout << "\n";
  }
  else
  {
    cout << "User not found.\n";
  }

  return 0;
}
