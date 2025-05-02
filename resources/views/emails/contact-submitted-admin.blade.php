<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submitted</title>
</head>
<body>
    <h2>New Contact Form Submission - Admin</h2>
    <p><strong>Name:</strong> {{ $contact->name }}</p>
    <p><strong>Email:</strong> {{ $contact->email }}</p>
    <p><strong>Phone:</strong> {{ $contact->phone }}</p>
    <p><strong>Description:</strong> {{ $contact->description }}</p>

    <h3>Contact Details Summary</h3>
    <p>The customer has submitted a contact request. Review the details above to follow up.</p>
</body>
</html>
