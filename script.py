from random import random

start = 0
n = 1000
mnames = ["Jacques", "Maxime", "Quentin", "Jean", "Pierre", "Paul", "Simon", "Thibault", "Samuel",
    "Anthony", "Lucas", "Nathan", "Hugo", "Leo", "Louis", "Raphael", "Jules", "Arthur", "Thomas", "Mathis",
    "Theo", "Clement", "Philippe", "Michel", "Alain", "Nicolas", "Cristophe", "Eric", "Laurent"]
fnames = ["Anne", "Alice", "Sophie", "Lea", "Emma", "Chloe", "Ines", "Manon", "Camille", "Zoe", "Louise",
    "Sarah", "Clara", "Romane", "Juliette", "Marie", "Isabelle", "Christine", "Sandrine", "Stephanie"]

names = ["Dupont", "Dupond", "Durand", "Martin", "Petit", "Roux", "Garnier", "Gautier", "Muller", "Moreau",
    "Laurent", "Lefebvre", "Bertrand", "Fournier", "Vincent", "Girad", "Andre", "Lefevre", "Mercier",
    "Lambert", "Bonnet", "Martinez", "Legrand", "Faure", "Rousseau", "Blanc", "Guerin", "Roussel",
    "Perrin", "Morin", "Dumont", "Fontaine", "Chevalier", "Robin", "Boyer", "Roche", "Meunier", "Marchand",
    "Colin", "Renard", "Lemoine", "Gaillard", "Dupuis", "Olivier", "Moulin", "Berger", "Deschamps",
    "Carpentier"]

g = {}

print("INSERT INTO `user` (`id`, `firstname`, `lastname`, `password`, `email`, `birthdate`, `sex`, `entry`, `contract`, `duration`, `salary`, `superior`, `username`, `is_active`, `permissionlevel`) VALUES")
for i in range(start, n + start):
    sex = 'M' if random() > 0.5 else 'F'
    a = mnames if sex == 'M' else fnames
    fname = a[int(random() * len(a))]
    lname = names[int(random() * len(names))]
    salary = str(int(random() * 5000 + 1200))
    birthdate = str(1950 + int(random() * 50)) + "-" + str(int(random() * 11 + 1)).zfill(2) + "-" + str(int(random() * 27 + 1)).zfill(2)
    entry = str(2010 + int(random() * 6)) + "-" + str(int(random() * 11 + 1)).zfill(2) + "-" + str(int(random() * 27 + 1)).zfill(2)
    contract = 'CDI' if random() * 0.4 > 0.5 else 'CDD'
    duration = str(0) if contract == 'CDI' else str(int(random() * 24 + 1))
    who = fname.lower()+"."+lname.lower()
    if not who in g:
        g[who] = 1
    else:
        g[who] += 1
        who += str(g[who]).zfill(2)
    print("(", end="")
    print(i, end=", ")
    print("'"+fname+"'", end=", ")
    print("'"+lname+"'", end=", ")
    print("'$2y$13$pRAShFuDk8G80zfb82Aq6eZXhHaLQufSV0T6idQQqwkPC.85wSJUa'", end=", ")
    print("'"+who+"@gmail.com'", end=", ")
    print("'"+birthdate+"'", end=", ")
    print("'"+sex+"'", end=", ")
    print("'"+entry+"'", end=", ")
    print("'"+contract+"'", end=", ")
    print(duration, end=", ")
    print(salary, end=", ")
    print(0, end=", ")
    print("'"+who+"'", end=", ")
    print(1, end=", ")
    print(0, end=")")
    if i != n + start - 1:
        print(",")
    else:
        print(";")
