#Вставить адрес!
#Файла с исходными данными:
scores_raw="scores.raw"
#Папки, где будут файлы:
#например, C:/Users/<user>/Documents/Python/tangaria/
scores_html=""
race_txt=""
class_txt=""

#Работа с шапкой html-страницы
html_str = """
<html>
<table border="1">
<tr>
<td>#</td>
<td>Version</td>
<td>Score</td>
<td>Gold</td>
<td>Turns</td>
<td>Date</td>
<td>Name</td>
<td>Sex</td>
<td>Race</td>
<td>Class</td>
<td>Level</td>
<td>Dungeon Level</td>
<td>Max Level</td>
<td>Max Dungeon Level</td>
<td>Method of death</td>
</tr>
"""
Html_file=open(scores_html+"scores.html","w")   
Html_file.write(html_str)
Html_file.close()
#Конец работы с шапкой html-страницы
file=open(scores_raw, "r").read()
file=file.replace('\x00','#')
version=file[0:5]
file=file.split(version)
del file[0]

#Расы и классы из файла
race_file=open(race_txt+"race.txt", "r")
class_file=open(class_txt+"class.txt", "r")
race = race_file.readlines()
class_name=class_file.readlines()

#Построение таблицы
nomer=0
for i in file:
    igrok=i.split('#')
    igrok_clean=[x for x in igrok if x not in ['']]
    nomer+=1
    total_score=igrok_clean[0]
    total_gold=igrok_clean[1]
    turns_taken=igrok_clean[2]
    time_stamp=igrok_clean[3]
    player_name=igrok_clean[4]
    player_uid=igrok_clean[5]
    player_sex=igrok_clean[6]
    
    #Классы и расы считаются с нуля
    player_race_id=int(igrok_clean[7])
    player_race=race[player_race_id]
    player_class_id=int(igrok_clean[8])
    player_class=class_name[player_class_id]
    #Конец рас и классов

    current_player_level=igrok_clean[9]
    current_dungeon_level=igrok_clean[10]
    max_player_level=igrok_clean[11]
    max_dungeon_level=igrok_clean[12]
    method_of_death=igrok_clean[13]
    output = open(scores_html+"scores.html", "a")
    print("<tr>\n",
          "<td>",nomer,"</td>",
          "<td>",version,"</td>",
          "<td>",total_score,"</td>",
          "<td>",total_gold,"</td>",
          "<td>",turns_taken,"</td>",
          "<td>",time_stamp,"</td>",
          "<td>",player_name,"</td>",
          #"<td>",player_uid,"</td>",
          "<td>",player_sex,"</td>",
          "<td>",player_race,"</td>",
          "<td>",player_class,"</td>",
          "<td>",current_player_level,"</td>",
          "<td>",current_dungeon_level,"</td>",
          "<td>",max_player_level,"</td>",
          "<td>",max_dungeon_level,"</td>",
          "<td>",method_of_death,"</td>",
          "</tr>\n",file=output)
    output.close()

Html_file.close()

