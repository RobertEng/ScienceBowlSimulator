################################################################################
# Takes Arizona pdf and extracts the
# text out of the pdf documents.
# 
################################################################################

#import urllib2
from urllib2 import Request, urlopen
from StringIO import StringIO
#import cookielib
#import os
#from bs4 import BeautifulSoup
#import pyPDF2
from pyPdf import PdfFileWriter, PdfFileReader #to import, go to pyPdf and run the program, then go back up a level

# Website to scrape
file_url = 'http://www.wapa.gov/dsw/scibowl/SampleQ/Samples2003.pdf'

f=open('round.txt','w') #Where all the text of every file goes

print(file_url) #Full URL
############################################################
# At this point, I have file_url which leads to a pdf I want
writer = PdfFileWriter()
remoteFile = urlopen(Request(file_url)).read()
memoryFile = StringIO(remoteFile)
pdfFile = PdfFileReader(memoryFile)
                    
############################################################
#Use this to save scraped file to output.pdf
for pageNum in xrange(pdfFile.getNumPages()):
    currentPage = pdfFile.getPage(pageNum)
    #currentPage.mergePage(watermark.getPage(0))
    writer.addPage(currentPage)
outputStream = open("output.pdf","wb")
writer.write(outputStream)
outputStream.close()
                    
############################################################
# Read output.pdf and extract text from each page, spit into
# round.txt
pdf = PdfFileReader(open("output.pdf", "rb"))
for page in pdf.pages:
    pgtxt = page.extractText().encode("ascii", "ignore")
    #remove new lines
    pgtxt = pgtxt.replace('\n', ' ').replace('\r', '')
    print pgtxt
    f.write(pgtxt)
f.close()
