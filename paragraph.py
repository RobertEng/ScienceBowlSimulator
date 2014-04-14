################################################################################
# Takes a website url and prints out all the paragraph elements


from __future__ import print_function
import urllib2
from bs4 import BeautifulSoup

# Website to scrape
HOME_URL = 'http://en.wikipedia.org/wiki/Star'

# Request page content and make BeautifulSoup object
home_req = urllib2.Request(HOME_URL)
home_content = urllib2.urlopen(home_req)
soup = BeautifulSoup(home_content)

f = open('parags.txt', 'w')
for parag in soup.find_all('p'):
    blah = parag.get_text().encode('ascii', 'ignore')
    print(blah, file=f)
f.close()