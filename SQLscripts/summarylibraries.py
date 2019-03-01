import MySQLdb.cursors
import sys
import SUB

# DATABASE VARIABLES
cur = SUB.dbconnect()

#initializing keys
GEN = {}
VAR = {}

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - M A I N  W O R K F L O W - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

#TABLE COLUMNS
syntax = "select species Species, format(sum(genes),0) Genes, format(sum(total_VARIANTS ),0) Variants from vw_libraryinfo group by species";

cur.execute(syntax)
myresult = cur.fetchall()
 
for x in myresult:
 GEN[x.get("Species")] = x.get("Genes")
 VAR[x.get("Species")] = x.get("Variants")

#print table
print '<table class="summary"><tr><th class="summary">Species</th><th class="summary">Genes</th><th class="summary">Variants</th></tr>';
for key, value in sorted(GEN.items()):
 print '<tr><td class="summary"><b>' + key + '</b></td><td class="summary">' + value + '</td><td class="summary">' + VAR.get(key) + '</td></tr>'

#Final Row
syntax = "select format(sum(genes),0) Genes, format(sum(total_VARIANTS ),0) Variants from vw_libraryinfo";
cur.execute(syntax)
myresult = cur.fetchall()
for key, value in sorted(GEN.items()):
 print '<tr><th class="summary"><b>Total</b></td><td class="summary"><b>' + key + '</b></td><td class="summary"><b>' + value + '</b></td></tr>'
print '</table>'

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - -T H E  E N D - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
exit;
