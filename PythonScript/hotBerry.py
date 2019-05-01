import mysql.connector
import time

db = mysql.connector.connect(
    user='horyco63_admin',
    password='Cjn4&qpuKfmi',
    host='162.241.203.95',
    database='horyco63_hotberry'
)

go = 1
count = 0

while go:

    intTimed = int(time.time() - 3600)
    timed = str(intTimed)

    mycursor = db.cursor()
    sql = "SELECT schedules.schedule_id FROM schedules INNER JOIN relation ON schedules.relation_id = relation.relation_id where schedules.schedule_start <= " + timed + " and schedules.schedule_end >= " + timed + " and relation.keycode_key = 'SMDB0Y';"
    
    mycursor.execute(sql)
    result = mycursor.fetchall()
    
    # <TempCode>
    print("")
    if result:
        print("ON")
    else:
        print("OFF")
    # <TempCode/>

    count = count + 1
    print(count)
    time.sleep(5)







