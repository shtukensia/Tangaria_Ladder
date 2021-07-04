
#Проверить необходимые исходные данные для программы:

#Вставить адрес папки с файлами!
#1) для ladder_html формата r"C:/Users/<user>/Documents/Python/tangaria/Ladder/"
#Где будет создаваться .html:

ladder_html=""

#2) вставить адрес для raw_files формата "C:/Users/<user>/Documents/Python/tangaria/Ladder/Исходные_файлы/"
#Файлы Tangaria Character Dump:

raw_files="chars/"

#3)В папку с файлом этой программы положи два текстовых файла рассы-классы:

#race.txt (список расс)
#class.txt (список классов)

#Начало кода программы
#Работа с шапкой html-страницы
html_str = """
<html>
<table border="1">
<tr>
<td>#</td>
<td>Version</td>
<td>Name</td>
<td>Race</td>
<td>Class</td>
<td>Level</td>
<td>Exp</td>
<td>Max Depth</td>
<td>Turns Used</td>
<td>Death</td>
</tr>
"""
Html_file=open(ladder_html+"ladder.html","w")   
Html_file.write(html_str)
Html_file.close()
#Конец работы с шапкой html-страницы

#Имена игроков из названий файлов .txt
import os
files_list=os.listdir(path=raw_files)

#Построение списка данных для сортировки
name_num=0
version_list=[]
race_list=[]
class_name_list=[]
level_list=[]
cur_exp_list=[]
max_depth_list=[]
turns_used_list=[]
death_list=[]
igroki=[]
for file in files_list:
    l = open(raw_files+file,"r")
    line=l.readlines()
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
    killed = "Killed by"
    death = " "
    for i in range(len(line)):
        if killed in line[i]:
            death_raw=line[i]
            death=death_raw[9:].strip()
            death=death.replace(".","")
    version_list.append(version)
    race_list.append(race)
    class_name_list.append(class_name)
    level_list.append(level)
    cur_exp_list.append(cur_exp)
    max_depth_list.append(max_depth)
    turns_used_list.append(turns_used)
    death_list.append(death)
    igrok=[version,name,race,class_name,level,cur_exp,max_depth,turns_used,death]
    igroki.append(igrok)
    l.close()

#Сортировка данных по cur_exp
igroki.sort(key=lambda i :i[5], reverse=1)


#Запись html-тегов для таблицы со ссылками на файлы игроков
output = open(ladder_html+"ladder.html", "a")
nomer=1
for igrok in igroki:
    print("<tr>\n",
          "<td>",nomer,"</td>",
          "<td>",igrok[0],"</td>",

          #Ссылка на файл игрока          
          "<td>","<a href=",raw_files+igrok[1].replace(' ','%20'),'" target="_blank">',igrok[1].split('-')[0],'</a>',"</td>",
          
          "<td>",igrok[2],"</td>",
          "<td>",igrok[3],"</td>",
          "<td>",igrok[4],"</td>",
          "<td>",igrok[5],"</td>",
          "<td>",igrok[6],"</td>",
          "<td>",igrok[7],"</td>",
          "<td>",igrok[8],"</td>",
          "</tr>\n",file=output)
    nomer+=1
output.close()

#Put data from list of lists to MySQL table
#Через CMD Install mysql_connector_python-8.0.25-cp39-cp39-win_amd64.whl
#Insert MySQL BD data for connection (user,pass etc)

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

#Перед запуском программы удалить все из БД MySQL

drop_table = "DROP TABLE igroki, race, class;"
cursor = my_bd.cursor()
try:
    cursor.execute(drop_table)
    my_bd.commit()
    print("Query executed!")        
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
    File TEXT);
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


#Расы и классы из файла
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
#Конец создания списка расс-классов

fill_table(my_bd,create_race)
fill_table(my_bd,create_class)

for igrok in igroki:
    name=str(igrok[1].split('-')[0])
    igrok.append(name)


#Вставляем данные из списков в таблицы:

#Заполнение таблицы igroki   
cursor = my_bd.cursor()
cursor.executemany("INSERT INTO igroki(Version,File,Race,Class,Level,Exp,MaxDepth,TurnsUsed,Death,Name) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", igroki)
my_bd.commit()

#Заполнение таблицы race 
cursor = my_bd.cursor()
races_values = [[item] for item in race]
cursor.executemany(u"INSERT INTO `race`(`Race`) VALUES (%s)", races_values)
my_bd.commit()

#Заполнение таблицы class 
cursor = my_bd.cursor()
class_values = [[item] for item in class_name]
cursor.executemany(u"INSERT INTO `class`(`Class`) VALUES (%s)", class_values)
my_bd.commit()

my_bd.close()





