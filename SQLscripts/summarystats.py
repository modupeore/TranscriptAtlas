import MySQLdb.cursors
import sys
import SUB

cur = SUB.dbconnect()

#initial syntax
syntax = "select a.species Species, format(count(a.species),0) Recorded, format(count(b.species),0) Processed from bird_libraries a left outer join vw_libraryinfo b on a.library_id = b.library_id group by a.species";

cur.execute(syntax)
myresult = cur.fetchall()

#initializing keys
KEY = {}
PRO = {}

print '<table class="summary"><tr><th class="summary">Species</th><th class="summary">Recorded</th><th class="summary">Processed</th></tr>'

for x in myresult:
 KEY[x.get("Species")] = x.get("Recorded")
 PRO[x.get("Species")] = x.get("Processed")

for key, value in sorted(KEY.items()):
 print '<tr><td class="summary"><b>'+ key + '</b></td><td class="summary">' + value + '</td><td class="summary">' + PRO.get(key) + '</td></tr>'


#Final Row
syntax = "select format(count(a.species),0) Recorded, format(count(b.species),0) Processed from bird_libraries a left outer join vw_libraryinfo b on a.library_id = b.library_id";
cur.execute(syntax)
myresult = cur.fetchall()

for x in myresult:
 print '<tr><th class="summary"><b>Total</b></td><td class="summary"><b>' + x.get("Recorded") + '</b></td><td class="summary"><b>' + x.get("Processed") + '</b></td></tr>' 

print '</table>';

# DISCONNECT FROM THE DATABASE

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - -T H E  E N D - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
