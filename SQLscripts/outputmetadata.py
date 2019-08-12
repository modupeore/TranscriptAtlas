import MySQLdb.cursors
import sys
import getopt
import SUB

#BIO
#Works for the metadata.php file
#Gets the metadata and sequence information from the mysql database (bird_libraries & transcripts_summary & frnak_metadata tables)

#ARGUMENTS
options, remainder = getopt.getopt(sys.argv[1:], 'h:m:z:i:o', ['in=','output=',
							'help','metadata', 'sequence',])
metadata = False
sequences = False

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - G L O B A L  V A R I A B L E S- - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

# DATABASE VARIABLES
cur = SUB.dbconnect()

for opt,arg in options:
  if opt in ('-o', '--output'):
    output_filename = arg
    parsed_output = open(output_filename,'w')
  if opt in ('-i', '--in'):
    specifics = arg
    headers = specifics.split(',') #specifying libraries of interest
  if opt in ('-h', '--help'):
    #usage()
    sys.exit(2)
  if opt in ('-m','--metadata'): #working with the metadata information
    


    metadata = True
    parsed_output.write("library_id\tbird_id\tspecies\tline\ttissue\tmethod\tindex\tchip_result\tscientist\tdate\tnotes\n")
    syntax = 'select library_id,bird_id,species,line,tissue,method,index_,chip_result,scientist,date,notes from bird_libraries where library_id in (%s)' %specifics #select from database
    cur.execute(syntax)
    myresult = cur.fetchall()
    for x in myresult:
        parsed_output.write(str(x.get("library_id")) + '\t' + str(x.get("bird_id")) + '\t' + str(x.get("species")) + '\t' + str(x.get("line")) + '\t' + str(x.get("tissue")) + '\t' + str(x.get("method")) + '\t' + str(x.get("index_")) + '\t' + str(x.get("chip_result")) + '\t' + str(x.get("scientist")) + '\t' + str(x.get("notes")) + "\n")

  elif opt in ('-z', '--sequence'): #working with the sequence information

    sequences = True
    parsed_output.write("Library id\tLine\tSpecies\tTissue\tTotal reads\tMapped reads\tGenes\tIsoforms\tVariants\tSNPs\tINDELs\tSequences\tDate\n")
    syntax = "select v.library_id,v.line,v.species, v.tissue, t.total_reads, v.mapped_reads, v.genes, v.isoforms,v.total_VARIANTS VARIANTS,v.total_SNPs SNPs, v.total_INDELs INDELs, f.sequences Sequences, t.Date Date from transcripts_summary as t join vw_libraryinfo as v on t.library_id = v.library_id join frnak_metadata as f on f.library_id = v.library_id where v.library_id in (%s)" %specifics
    cur.execute(syntax)
    myresult = cur.fetchall()
    for x in myresult:
        parsed_output.write(str(x.get("library_id")) + '\t' + str(x.get("line")) + '\t' + str(x.get("species")) + '\t' + str(x.get("tissue")) + '\t' + str(x.get("total_reads")) + '\t' + str(x.get("mapped_reads")) + '\t' + str(x.get("genes")) + '\t' + str(x.get("isoforms")) + '\t' + str(x.get("VARIANTS")) + '\t' + str(x.get("SNPs")) + '\t' + str(x.get("INDELs")) + '\t' + str(x.get("Sequences")) + '\t' + str(x.get("Date")) + "\n")


# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - -T H E  E N D - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
exit;
