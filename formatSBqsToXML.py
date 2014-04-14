################################################################################
# Interpret the giant chunks of text. This part is unique to SB questions from official website
# Puts it all into an XML file. Takes in the round.txt file from scraper.py
# and spits out set.xml

from xml.etree import ElementTree as ET
import re

f=open('round.txt','r')
bulktext=f.read()
f.close()

#Need to check this works later or if i even need this
#To run XML, text cannot have < or &
#bulktext = bulktext.replace("<", "&lt;").replace("&", "&amp;")

# declare XML root element
root = ET.Element("set")
qNum = 0

#Regular Expressions used to find the round number
rnd = re.compile(r'ROUND\s[0-9]{1,2}') 
rnd2 = re.compile(r'(ROUND\s[0-9]{1,2})') #This one takes advantage of grouping and the split function
# When split rnd2 will create array that includes "ROUND 1" and the questions
roundQs = rnd.split(bulktext)
roundQs.pop(0)
roundNums = rnd.findall(bulktext)
for roundNum in range(len(roundNums)):
    questions = []
    #Need to do the first Tossup and bonus becuase I need both indeces
    currIndex = roundQs[roundNum].find("TOSS-UP")
    prevIndex = currIndex
    while currIndex > 0:
        #Find the bonus first since you're doing from curr up to "bonus"
        currIndex = roundQs[roundNum].find("BONUS", currIndex)
        questions.append(roundQs[roundNum][prevIndex:currIndex])
        prevIndex = currIndex
        #This section gets the Bonus question
        currIndex = roundQs[roundNum].find("TOSS-UP", currIndex)
        questions.append(roundQs[roundNum][prevIndex:currIndex])
        prevIndex=currIndex
   
    for questIndex in range(len(questions)):
        questxml = ET.SubElement(root, "question")
        matchxml = ET.SubElement(questxml, "matchNum")
        matchxml.text = str(qNum)
        #When odd and end of bonus, should add one to qNum
        qNum += questIndex%2
        originxml = ET.SubElement(questxml, "origin")
        originxml.text = roundNums[roundNum]
        typexml = ET.SubElement(questxml, "type")
        pointxml = ET.SubElement(questxml, "pointVal")
        topicInd = 0
        if questions[questIndex][0] == 'B':
            typexml.text = "BONUS"
            pointxml.text = str(10)
            topicInd = 6
        else:
            typexml.text = "TOSS-UP"
            pointxml.text = str(4)
            topicInd = 8
    
        mcInd = questions[questIndex].find("Multiple Choice")
        saInd = questions[questIndex].find("Short Answer")
        if mcInd == saInd and mcInd == -1:
            #Check if Choice or Answer was not capitalized
            mcInd = questions[questIndex].find("Multiple choice")
            saInd = questions[questIndex].find("Short answer")
            if mcInd == saInd and mcInd ==-1:
                print("Uh oh, something bad happened")
                print(questions[questIndex])
        
        topicxml = ET.SubElement(questxml, "topic")
        formatxml = ET.SubElement(questxml, "format")
        qInd = 0
        if mcInd > saInd:
            topicxml.text = questions[questIndex][topicInd:mcInd]
            formatxml.text = "Multiple Choice"
            qInd = mcInd+15
        else:
            topicxml.text = questions[questIndex][topicInd:saInd]
            formatxml.text = "Short Answer"
            qInd = saInd+12

        #remove out the question number before the topic. hardcoded to get rid of it
        topicxml.text = topicxml.text[topicxml.text.find(")")+2:]
        topicxml.text = topicxml.text.strip()
        #print topicxml.text
        
        #checks if the topic is recognized as a standard topic
        acceptableTops = ['MATH','ENERGY','PHYSICS','BIOLOGY','CHEMISTRY','ASTRONOMY','EARTH AND SPACE','EARTH SCIENCE','COMPUTER SCIENCE','GENERAL SCIENCE']
        if not any(word in topicxml.text for word in acceptableTops):
            #fix corner cases
            if topicxml.text == 'GENEAL SCIENCE':
                topicxml.text = 'GENERAL SCIENCE'
            if topicxml.text == 'EARTH  SCIENCE':
                topicxml.text = 'EARTH SCIENCE'
        
        problemxml = ET.SubElement(questxml, "problem")
        solutionxml = ET.SubElement(questxml, "solution")
        ansInd = questions[questIndex][qInd:].find("Answer") +qInd#make sure I don't get Short Answer's "Answer"
        ansInd2 = questions[questIndex][qInd:].find("ANSWER") +qInd
        if ansInd == -1 and ansInd2 == -1:
            ## questions 34 and 220 can't be read. Who knows what else
            problemxml.text = "UNABLE TO INTERPRET QUESTION, SKIP THIS."
            solutionxml.text = "NO ANSWER FOUND"
        else:
            #To fix later: Q1274 has an entire document attached to answer from poor scraping
            if ansInd2>ansInd:
                ansInd = ansInd2
            problemxml.text = questions[questIndex][qInd:ansInd]
            solutionxml.text = questions[questIndex][ansInd+8:]

tree = ET.ElementTree(root)
tree.write("set.xml")