import re
# using a regular expression with the re package to find the email address in the resume
def extract_email(email):
    email = open(email, "r")
    email = email.read()
    email = re.findall(r"(\b[^:@|\s]+@\w+\.[^:@|\s]+\b)", email)
    if email:
        try:
            return email[0].split()[0].strip(';')
        except Exception as e:
            return e
			

addr = extract_email("output.txt")
if addr is not None:
    print(addr)
else:
    print("None")