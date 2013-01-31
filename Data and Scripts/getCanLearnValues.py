'''
Created on Jan 31, 2013

@author: olsonmc
'''

import urllib.request
import re

file = open('text.txt', 'w')
start = 'http://bulbapedia.bulbagarden.net/wiki/Bulbasaur_(Pok%C3%A9mon)'
inLimit = 649;

while(inLimit > 0):
    print(start)
    print(str(inLimit)+'/649')
    f = urllib.request.urlopen(start)
    s = f.read()
    f.close()
    
    title = re.search(b'<title>.*</title', s).group()
    title = title[7:]
    title = title.decode("utf-8")
    title = title.split(' ')[0]
    Next = re.findall(b'href=\"/wiki/[a-zA-Z_].*?_[(]Pok%C3%A9mon[)]"', s)
    Next = Next[4].decode("utf-8")
    Next = Next[6:-1]
    Next = 'http://bulbapedia.bulbagarden.net'+Next

    prog = re.compile(b'href=\"/wiki/[a-zA-Z_].*?_[(]move[)]"')
    a = prog.findall(s)
    for b in a:
        b = b[12:-8]
        if(len(b) < 20):
            b= b.decode("utf-8")
            if(title=='Nidoran♀'):
                title='NidoranF'
            if(title=='Nidoran♂'):
                title='NidoranM'
            s = title + ',' + b + '\n'
            
            file.write(s)
            
    start=Next
    inLimit-=1

file.close()
