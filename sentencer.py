################################################################################
# Takes the parags file and the keyword and replaces "A "+keyword with "What" 
# whenever appropriate.
################################################################################

from __future__ import print_function
import re

keyword = "star"

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
    quest = sent.replace("A "+keyword+" ", "What ")
    quest = quest.replace("a "+keyword+" ", "what ")
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