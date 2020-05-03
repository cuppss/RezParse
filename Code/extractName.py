import spacy
# function to extract the name using the spaCy package.  Function takes the txt file and the first line of the txt file as parameters.
def extract_name(text, line):

    name = open(text, "r")
	# clean up the file a little to make it easier for spaCy to find the name
    cap = line.title()
    name = name.read()
    name = name.replace(line, cap)
    name = name.replace("|","")
	#choose the spaCy model to use.  "model3" is the one we trained to read names more accurately
    nlp = spacy.load("model3")
    doc = nlp(name)

    for ent in doc.ents:
        if (str(ent.label_) == "PERSON"):
            n = ent.text
            if n.find("\n") > 0: 
                pos = n.find("\n")
                return (n[:pos])
            else:
                return n

		
name = open("output.txt", "r")
line = name.readline()
name = extract_name("output.txt", line)
print(name)

