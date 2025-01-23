# Request structure

http://localhost:{port}/taxes?country=COUNTRY_CODE&state=FULL_STATE_NAME

Example - http://localhost:{port}/taxes?country=CA&state=Quebec

# Request scenarios

This project support these requests:

| Country   | State      | Taxes                     |
|-----------|------------|---------------------------|
| CA        | Quebec     | GST/HST: 5% , PST: 9.975% |
 | CA        | Ontario    | GST/HST: 13%              |
| US        | California | VAT: 7.25%                |
| LT        |            | VAT: 21%                  |
| LV        |            | VAT: 21%                  |
| EE        |            | VAT: 20%                  |
| PL        |            | Error: could not retrieve |
| DE        |            | VAT: 19%                  |


# Response structure

```json
[{"taxType": "VAT", "percentage": 19}, {"taxType": "GST", "percentage": 6}]
```

# Project setup

Run `make setup` to initialize local environment

How to run the tests: `make test-phpunit` (on template project)

Ssh to the container: `make bash` (on template project)

`make start`
