This is an example for Faqs API request.


## List Faqs

```
curl --request GET \
  --url http://laraveldemo.local/api/faqs \
  --header 'Accept: application/json' \
  --header 'User-Agent: insomnia/11.1.0'
```

## Get specific Faq using id
```
curl --request GET \
  --url http://laraveldemo.local/api/faqs/1 \
  --header 'Accept: application/json' \
  --header 'User-Agent: insomnia/11.1.0'
```

## List Faq with Pagination
```
curl --request GET \
  --url 'http://laraveldemo.local/api/faqs?page=1' \
  --header 'Accept: application/json' \
  --header 'User-Agent: insomnia/11.1.0'
```

## List Faqs pagintation with Search

```
curl --request GET \
  --url 'http://laraveldemo.local/api/faqs?page=1&search=Magni%20vel%20omnis%20sed%20tenetur' \
  --header 'Accept: application/json' \
  --header 'User-Agent: insomnia/11.1.0'
```

# Create Faq

```
curl --request POST \
  --url 'http://laraveldemo.local/api/faqs?page=1&search=Magni%20vel%20omnis%20sed%20tenetur' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/11.1.0' \
  --data '{
        "question": "What is Laravel Rest API?",
        "answer": "A RESTful API (Representational State Transfer) in Laravel allows communication between your Laravel backend and any client (web, mobile, etc.) using standard HTTP methods: GET, POST, PUT, DELETE"
    }'
```


## Update Faq

```
curl --request PUT \
  --url 'http://laraveldemo.local/api/faqs/1?page=1&search=Magni%20vel%20omnis%20sed%20tenetur' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/11.1.0' \
  --data '{
        "question": "What is Laravel?",
        "answer": "Laravel is PHP Framework"
    }'
```

## Delete Faq

```
curl --request DELETE \
  --url http://laraveldemo.local/api/faqs/31 \
  --header 'Accept: application/json' \
  --header 'User-Agent: insomnia/11.1.0'
```