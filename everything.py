################################################################################
# This gets everything from scraping wikipedia to making new questions


from __future__ import print_function
import urllib2
from bs4 import BeautifulSoup
# check for web connection first
# in this python prompt go to downloads beautifulsoup and import
# go back to sciecencebowlsimulator
import re

keyword = "star"
# Website to scrape
HOME_URL = 'http://en.wikipedia.org/wiki/'+keyword

# Request page content and make BeautifulSoup object
home_req = urllib2.Request(HOME_URL)
home_content = urllib2.urlopen(home_req)
soup = BeautifulSoup(home_content)

f = open('parags.txt', 'w')
for parag in soup.find_all('p'):
    blah = parag.get_text().encode('ascii', 'ignore')
    print(blah, file=f)
f.close()

f = open('link.txt', 'w')
for parag in soup.find_all('p'):
    for link in parag.find_all('a'):
        blah = link.get_text().encode('ascii', 'ignore')
        #Check for [ for references ie [42]
        if blah != None and (len(blah)>2 and blah[0]!='['):
            print(blah, file=f)
f.close()






f = open('parags.txt', 'r')
process = f.read()
f.close()
#pat = re.compile(r'''(?<=[.!?]['"\s])\s*(?=[A-Z])''', re.M)
pat = re.compile(r'([A-Z][^\.!?]*[\.!?])', re.M) #make more complex later
sentences = pat.findall(process)
questions = []
for sent in sentences:
    quest = ""
    sieve = True
    quest = sent.replace("A "+keyword+" ", "what ")
    quest = quest.replace("a "+keyword+" ", "what ")
#    quest = sent.replace(keyword+" ", "What ")
#    quest = quest.replace(keyword+" ", "what ")
    #Get what's later. too lazy
    #Here's the part where some fancy selection happens
    #I don't want ones where it repeats the key word
    if quest.find(keyword) >=0:
        sieve = False
    # hardcode out places where decimals happen. Must fix later with natural language processing
    if(quest[-2]>='0' and quest[-2]<='9' or quest[-2]=='i'):
        sieve = False
    if(sent != quest and sieve):
        quest = quest[:-1] + "?"
        questions.append(quest)
print (questions)
f = open('questions.txt', 'w')
for questIndex in range(len(questions)):
    liner=str(questIndex/2)+"##"
    if questIndex % 2 == 1:
        liner += "10##"
    else:
        liner += "4##"
    liner += "Self Generated##Short Answer##"+questions[questIndex]+"##"+keyword+"##"
    print (liner)
    print(liner, file=f)
f.close()