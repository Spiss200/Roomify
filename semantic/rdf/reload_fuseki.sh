#!/bin/bash
curl -s -o /dev/null -w "clear:%{http_code}\n" -X POST --data-urlencode "update=CLEAR DEFAULT" http://localhost:3030/Roomify/update
curl -s -o /dev/null -w "roomify:%{http_code}\n" -X POST -H "Content-Type: text/turtle" --data-binary @roomify.ttl http://localhost:3030/Roomify/data
curl -s -o /dev/null -w "landmarks:%{http_code}\n" -X POST -H "Content-Type: text/turtle" --data-binary @landmarks.ttl http://localhost:3030/Roomify/data
