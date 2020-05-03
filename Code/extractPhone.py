import re
# using a regular expression with the re package to find the phone number in the resume
def extract_phone(text):
    phone = open(text, "r")
    phone = phone.read()
    phone = re.findall(re.compile(r"\(?\b[0-9][0-9][0-9]\)?[-. ]?[0-9][0-9][0-9][-. ]?[0-9]{4}\b"), phone)
    
    if phone:
        number = ''.join(phone[0])
        return re.sub('\D', '', number)

phone = extract_phone("output.txt")
if phone is not None:
    print(phone)
else:
    print("None")