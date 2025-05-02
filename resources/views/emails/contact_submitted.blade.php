<!DOCTYPE html>
<html>
<head>
    <title>Contact Received</title>
</head>
<body>
    <h2>Hello {{ $contact->name }},</h2>
    <p>Thanks for contacting us. Here's the info we received:</p>

    <ul>
        <li><strong>Name:</strong> {{ $contact->name }}</li>
        <li><strong>Phone:</strong> {{ $contact->phone }}</li>
        <li><strong>Email:</strong> {{ $contact->email }}</li>
        <li><strong>Description:</strong> {{ $contact->description }}</li>
    </ul>

    <p>We'll get back to you soon.</p>
</body>
</html>
