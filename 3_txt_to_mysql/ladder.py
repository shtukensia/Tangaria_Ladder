
# https://stat.tangaria.com/

# 1) add raw files, eg "C:/Users/<user>/Documents/Python/tangaria/Ladder/chars/"

raw_files="chars/"

# 2) To root folder (where script lays) put list of races/classes: race.txt and class.txt

# Player names from .txt files
import os
files_list=os.listdir(path=raw_files)

name_num=0          # N ('#') of player
igroki=[]           # final array where we put all stuff 

for file in files_list:
    l = open(raw_files+file,"r") # put to 'l' opened file with 'r'eading permission
    line=l.readlines()           # put to 'line' array with all lines

    # analyzing lines    
    version_raw=line[0]
    version=version_raw[12:17]
    name=files_list[name_num]
    name_num+=1
    race_raw=line[4]
    race=race_raw[8:22].strip()
    class_name_raw=line[5]
    class_name=class_name_raw[8:22].strip()
    level_raw=line[10]
    level=level_raw[23:25].strip()
    cur_exp_raw=line[11]
    cur_exp=cur_exp_raw[8:25].strip()
    cur_exp=int(cur_exp)
    max_depth_raw=line[18]
    max_depth=max_depth_raw[9:25].strip()
    turns_used_player_raw=line[7]
    turns_used=turns_used_player_raw[28:42].strip()

    # Killed by

    killed = "Killed by"
    death = " "
    for i in range(len(line)): # for each line in file
        if killed in line[i]:  # if found "Killed by" in a line
            death_raw=line[i]  # this line will become death_raw
            death=death_raw[9:].strip()
            death=death.replace(".","")

    # LF Winner
            
    killed_morg_check= "Killed Morgoth, Lord of Darkness"
    killed_morg_status=""
    for i in range(len(line)):
        if killed_morg_check in line[i]:
            killed_morg_status="Winner"

    # LF Player ID
    player_id = "Player ID:"
    account = ""                # some dumps do not have Player ID
    for i in range(len(line)): 
        if player_id in line[i]:
            id_raw = line[i]
            account = id_raw[11:].strip()

    # LF date
    date_mark = "Time: "
    date = ""                # some dumps do not have date
    for i in range(len(line)): 
        if date_mark in line[i]:
            date_raw = line[i]
            date = date_raw[15:].strip()

    # LF Unix timestamp
    timestamp_mark = "Timestamp:"
    timestamp = ""                # some dumps do not have timestamp
    for i in range(len(line)): 
        if timestamp_mark in line[i]:
            timestamp_raw = line[i]
            timestamp = timestamp_raw[11:].strip()            

    # put stuff into final array
    igrok=[version,name,race,class_name,level,cur_exp,max_depth,turns_used,death,
           killed_morg_status,account, date, timestamp]
    igroki.append(igrok)
    l.close()

# Sort data by cur_exp
igroki.sort(key=lambda i :i[5], reverse=1)

# Put data from list of lists to MySQL table
# Prereq: install mysql_connector_python-8.0.25-cp39-cp39-win_amd64.whl
# Insert MySQL BD data for connection (user,pass etc)

import mysql.connector
from mysql.connector import Error
my_bd=None
def connect():
    global my_bd
    try:
        my_bd = mysql.connector.connect(user='',
                                        password='',
                                        host='',
                                        database='')
        
        if my_bd.is_connected():
            print('MySQL Connected!')

    except Error as e:
        print(e)     


connect()

# Wiping MySQL table

drop_table = "DROP TABLE IF EXISTS igroki, race, class;"
cursor = my_bd.cursor()
try:
    cursor.execute(drop_table)
    my_bd.commit()
    print("Tables cleaned!")        
except Error as e:
    print(e)
    

def fill_table(my_bd, query):
    cursor = my_bd.cursor()      
    try:
        cursor.execute(query)
        my_bd.commit()
        print("Query executed!")
    except Error as e:
        print(e)


create_table = """
    CREATE TABLE IF NOT EXISTS igroki (
    N SERIAL PRIMARY KEY,
    Version TEXT NOT NULL,
    Name TEXT NOT NULL,
    Race TEXT NOT NULL,
    Class TEXT NOT NULL,
    Level INTEGER,
    Exp INTEGER,
    MaxDepth TEXT NOT NULL,
    TurnsUsed INTEGER,
    Death TEXT,
    File TEXT,
    Winner TEXT,
    Account TEXT,
    Date TEXT,
    Timestamp TEXT);    
"""

create_race="""
    CREATE TABLE IF NOT EXISTS race(
    Race TEXT NOT NULL);
"""

create_class="""
    CREATE TABLE IF NOT EXISTS class(
    Class TEXT NOT NULL);
"""

fill_table(my_bd,create_table)


# Races and classes from files
race_file=open("race.txt", "r")
class_file=open("class.txt", "r")
race_raw = race_file.readlines()
race=[]
for element in race_raw:
    race.append(element.strip())

class_name_raw=class_file.readlines()
class_name=[]
for element in class_name_raw:
    class_name.append(element.strip())

race_file.close
class_file.close
# End of race/class creation

fill_table(my_bd,create_race)
fill_table(my_bd,create_class)

for igrok in igroki:
    name=str(igrok[1].split('-')[0])
    igrok.append(name)


# Inserting data from list to table:

# Filling 'igroki' table
cursor = my_bd.cursor()
# !! 'Name' must be last !!
cursor.executemany("INSERT INTO igroki(Version,File,Race,Class,Level,Exp,\
            MaxDepth,TurnsUsed,Death,Winner,Account,Date,Timestamp,Name) \
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", igroki)
my_bd.commit()

# Filling 'race'
cursor = my_bd.cursor()
races_values = [[item] for item in race]
cursor.executemany(u"INSERT INTO `race`(`Race`) VALUES (%s)", races_values)
my_bd.commit()

# Filling 'class'
cursor = my_bd.cursor()
class_values = [[item] for item in class_name]
cursor.executemany(u"INSERT INTO `class`(`Class`) VALUES (%s)", class_values)
my_bd.commit()

my_bd.close()





