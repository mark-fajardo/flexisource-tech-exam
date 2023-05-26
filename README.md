# flexisource-tech-exam
Code Challenge from FlexiSource IT

## Import Customer Data Command
This is an artisan command used to fetch random users and import them into the database. There is an optional parameter `length` with a default value of 100, which allows you to specify the number of users to import.

To use the command, run the following Artisan command:
```
php artisan customers:import --length=10
```

## Customer APIs
Here is a list of the APIs available for Customers:
```
/api/customers
/api/customers/{customerId}
```

The responses from these APIs are based on the requested responses in the Coding Challenge sheet. The response data will be enclosed in a key named `data`.

These APIs do not have separate services as they are directly connected to the Repository class.

### Note:
- The Coding Challenge sheet does not specify any request parameters, so there is no request parameter validation.
- Fractal Transformers are used to transform the responses, making them more readable and easier to manipulate.
