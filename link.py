################################################################################
# Finds all the links on a wikipedia page HOME_URL and prints them to a file
################################################################################

from __future__ import print_function
import urllib2
from bs4 import BeautifulSoup

# Website to scrape
HOME_URL = 'http://en.wikipedia.org/wiki/Star'
# Request page content and make BeautifulSoup object
home_req = urllib2.Request(HOME_URL)
home_content = urllib2.urlopen(home_req)
soup = BeautifulSoup(home_content)

f = open('link.txt', 'w')
for parag in soup.find_all('p'):
    for link in parag.find_all('a'):
        blah = link.get_text().encode('ascii', 'ignore')
        #Check for [ for references ie [42]
        if blah != None and (len(blah)>2 and blah[0]!='['):
            print(blah, file=f)
f.close()
