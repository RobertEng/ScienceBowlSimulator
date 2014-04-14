################################################################################
# Takes the link file and parags file from everything.py and counts the
# frequency with which that word shows up
################################################################################
from __future__ import print_function

f = open('link.txt', 'r')
relate=[]
for line in f:
    relate.append(line)
f.close()

f = open('parags.txt', 'r')
last_pos = f.tell()
frequency=[]
for thingIndex in range(len(relate)):
    frequency.append(0)
for thingIndex in range(len(relate)):
    print(relate[thingIndex])
    for paragraph in f:
        if paragraph.find(relate[thingIndex][:len(relate[thingIndex])-1]) > -1:
            frequency[thingIndex] = frequency[thingIndex]+1
    f.seek(last_pos)
for thing in range(len(frequency)):
    print(str(thing) + " " + str(frequency[thing]))
f.close()

