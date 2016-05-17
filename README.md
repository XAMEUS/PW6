# PW6
Web programming project, using Symfony 3.

see: http://164.132.102.236:8000 (or http://osxia.org:8000)

sources: https://github.com/XAMEUS/PW6

authors: Quentin Perrachon & Maxime Gourgoulhon

# How to install

## Normal

```
git clone https://github.com/XAMEUS/PW6.git
cd PW6
php bin/console doctrine:schema:update --force
```

Import an admin using db.sql (username: admin, password: admin)

script.py can help you to generate random users.

## With minimal .zip

Try to paste all files in your symfony project.

---

* responsive website
* users management (formations, paybils, search, etc.)
* chat
* files hosting
* spreadsheet
