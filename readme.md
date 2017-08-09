## For Nette Framework

Usage of Email on Acid SDK for email content/spam testing in Nette applications.

## Installation

1. Get the source code from Github or via Composer (`kiwicom/email-testing-extension`).
2. Add to extensions list.

```
extensions:
    emailOnAcid : \EmailOnAcidNette\Nette\EmailOnAcidExtension
```

## Configuration


```
emailOnAcid:
	apiKey: yourapikey
	password: yoursuperstrongpassword
	timeout: 15
```
