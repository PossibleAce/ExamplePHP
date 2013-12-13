#!/usr/bin/perl
use warnings;
use strict;
BEGIN { $Image::ExifTool::configFile = '/ExifTool_config' } #used to define the config file to be used
use Image::ExifTool ':Public';              #includes Image::ExifTool as a public plugin                                                                      
binmode STDIN;
our $lines = 0;
our $file1;
our $file2;
our $ext;
our $a;
our $start1;
our $start2;
our $start3;
our $end1;
our $end2;
our $end3;
our $leftover_next;
our $leftover;
print "Content-type: text/html\n\n";
while(<STDIN>) {
    if($lines == 1) {
        $a=$_;
		
        #get orig name of file
        $start1=index($a,"filename")+10;
        $end1=index($a,'"',$start1);
        $file1=substr($a,$start1,$end1-$start1);
		
        #get new name of file from php
        $start2=index($a,"name")+6;
        $end2=index($a,'"',$start2);
        $file2=substr($a,$start2,$end2-$start2);
		
        #get file extension
        $start3=index($file1,".");
        $end3=1000;
        $ext=substr($file1,$start3,$end3);
		
        #clean up file's title
        $file1 =~ s/[^A-Za-z0-9\-\.]//g;
		
        #clean up file's extension
        $ext =~ s/[^A-Za-z0-9\-\.]//g;
		
        open(FILE,">$file2") or print "<script>parent.uploader.uploadfailed();</script>";
        binmode FILE;
    } elsif ($lines > 3) { 
        if (not ($_ =~ m/^-+\w+-{2}\r\n$/)) {
            $leftover_next = "";
            if ($_ =~ m/\r\n$/) {
                $leftover_next = "\r\n";
                $_ =~ s/\r\n$//;
            }
            print FILE "$leftover$_";
            $leftover = $leftover_next;		
        }
    } 
$lines++;
}
rename("$file2", "$file2$ext") or print "<script>parent.uploader.uploadfailed();</script>";
close FILE;

#clean up metadata for office files
if(($ext eq ".docx") || $ext eq ".doc") {
    
    
    
#clean up metadata for all other files
} else {
    system("exiftool -overwrite_original -all:all= $file2$ext");
} 
print "<script>parent.uploader.uploadcomplete('$file1,$file2$ext');</script>";

exit;
