#Вставить адрес папки с файлами!
#1) для ladder_html формата "C:/Users/<user>/Documents/Python/tangaria/Ladder/"
ladder_html="" #Где будет создаваться .html
#2) для raw_files формата "C:/Users/<user>/Documents/Python/tangaria/Ladder/Исходные_файлы/"
raw_files="chars/" #Файлы Tangaria Character Dump

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
