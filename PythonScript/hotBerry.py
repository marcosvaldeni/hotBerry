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
    sql = "SELECT * FROM schedule WHERE schedule_start <= " + timed + " having schedule_end >= " + timed + " order by schedule_end;"
    mycursor.execute(sql)
    result = mycursor.fetchall()
    
    print("")
    if result:
        print("ON")
    else:
        print("OFF")

    count = count + 1
    print(count)
    time.sleep(5)







