import docx2txt
# function to convert docx to txt using the docx2txt package, and stores the txt content in "output.txt".  Function takes the docx file as a parameter.
def convertWord(doc):
	# extract text
	text = docx2txt.process(doc)

	with open("output.txt", "w") as text_file:
		print(text, file=text_file)


convertWord("word2Convert.docx")