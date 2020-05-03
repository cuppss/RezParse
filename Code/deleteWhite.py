import fileinput
# gets rid of unnecessary whitespace in the txt file using the fileinput package
for line in fileinput.FileInput("output.txt",inplace=1):
    if line.rstrip():
        print(line)