#!/usr/bin/perl
use strict;
use DBI;
use Getopt::Long;
use Pod::Usage;
use File::Basename qw(dirname);
use Cwd qw(abs_path);
use lib dirname(abs_path $0) . '/SUB';
use passw;
use routine;

#ARGUMENTS
my($gene,$tissue,$species, $output1,$specs);
my ($col1, $col2, $col3);
GetOptions("1|gene=s"=>\$gene,"2|tissue=s"=>\$tissue,"3|species=s"=>\$species, "col1=s"=>\$col1,"col2=s"=>\$col2,"col3=s"=>\$col3);

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - G L O B A L  V A R I A B L E S- - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# DATABASE VARIABLES
my ($dbh, $sth, $syntax, @row);
our ($VERSION, $DATE, $AUTHOR) = DEFAULTS;
my @THESYNTAX;
$dbh = mysql();
#HASH TABLES
my (%GENES);

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - M A I N  W O R K F L O W - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
#TABLE COLUMNS
#gene_short_name, chrom_no, chrom_start, chrom_stop, fpkm, library_id
my @tissue = split("\,",$tissue);
foreach my $ftissue (@tissue){
	my @genes = split("\,", $gene);
	foreach my $fgene (@genes) {
		if ($species =~ /gallus/) {
			$syntax = "call usp_genedgenedtissue(\"".$fgene."\",\"".$ftissue."\",\"".$species."\")";
		} else {
			$syntax = "call usp_genedgenedtissueless(\"".$fgene."\",\"".$ftissue."\",\"".$species."\")";
		}
		$sth = $dbh->prepare($syntax);
		$sth->execute or die "SQL Error: $DBI::errstr\n";
		while (my ($genename, $line, $max, $avg, $min) = $sth->fetchrow_array() ) {
			if ($col1 && $col2 && $col3){
				push (@THESYNTAX, uc($col1), uc($col2), uc($col3));
				if ($col1 =~ /tissue/){
					if ($col2 =~ /line/) {
						$GENES{$ftissue}{$line}{$genename} = "$max|$avg|$min";
					} else {
						$GENES{$ftissue}{$genename}{$line} = "$max|$avg|$min";
					}
				} elsif ($col1 =~ /line/){
					if ($col2 =~ /tissue/) {
						$GENES{$line}{$ftissue}{$genename} = "$max|$avg|$min";
					} else {
						$GENES{$line}{$genename}{$ftissue} = "$max|$avg|$min";
					}
				} else {
					if ($col2 =~ /tissue/) {
						$GENES{$genename}{$ftissue}{$line} = "$max|$avg|$min";
					} else {
						$GENES{$genename}{$line}{$ftissue} = "$max|$avg|$min";
					}
				}
			} else {
				@THESYNTAX= qw|TISSUE LINE GENENAME|;
				$GENES{$ftissue}{$line}{$genename} ="$max|$avg|$min";
			}
		}
	}
}
my $bcount = 0; my %COUNT;
foreach my $a (sort keys %GENES){
        my $acount = scalar keys(%{$GENES{$a}});
        foreach my $b (sort keys %{$GENES{$a}}){
                $bcount = $bcount + (scalar keys(%{$GENES{$a}{$b}})) - 1;
        }
        $COUNT{$a} = $acount+$bcount;
}
print "
<table class=\"gened\" border=\"1\">
        <tr>
                <th class=\"gened\">$THESYNTAX[0]</th>
                <th class=\"gened\">$THESYNTAX[1]</th>
                <th class=\"gened\">$THESYNTAX[2]</th>
                <th class=\"gened\">Maximum Fpkm</th>
                <th class=\"gened\">Average Fpkm</th>
                <th class=\"gened\">Minimum Fpkm</th>
        </tr>\n";
foreach my $column1 (sort keys %GENES){
print "\t<tr>\n\t\t<td class=\"gened\" rowspan=\"$COUNT{$column1}\">$column1</td>\n";

        my $count = scalar keys(%{$GENES{$column1}});
        my $I = $count;
        foreach my $column2 (sort keys %{$GENES{$column1}}){
                unless ($I <= 0 || $I == $count){ print "\t<tr>\n";}
                my $countsec = scalar keys(%{$GENES{$column1}{$column2}});
print "\t\t<td class=\"gened\" rowspan=\"$countsec\">$column2</td>\n";
                my $J = $countsec;
                foreach my $column3 (sort keys %{$GENES{$column1}{$column2}}){
                        unless ($J <= 0 || $J == $countsec) { print "\n\t<tr>\n";}
                        print "\t\t<td class=\"gened\">$column3</td>\n";
                        my @all = split('\|', $GENES{$column1}{$column2}{$column3}, 3);
print "\t       <td class=\"gened\">$all[0]</td>
                <td class=\"gened\">$all[1]</td>
                <td class=\"gened\">$all[2]</td>
        </tr>\n";
                $I--;$J--;
                }
        }

}
print "</table>";

# DISCONNECT FROM THE DATABASE
$sth->finish();
$dbh->disconnect();

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - -T H E  E N D - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
exit;

