# Sipmel #
Simplifying control over multimedia communication.

## Goals ##
- Provide a unified API for communication via any channel (SMS, Email, Skype etc.).
- Persona management
	- Contact list (each list need to belong to a group, a persona need to be unique per group)
	- User timezone by country, fallback by contact detail (i.e phone country code)
	- Segment each contact's channel (Phone, Email, Skype account etc.) with a verification status:
		- None - Default status
		- Verified - Once passed some internal clearance
	- Rate each contact's channel with a status:
		- Sent: int - amount of messages been sent
		- Delivered: int
		- Undelivered
		- Replay: int - amount of incoming messages
		- Clicked: int - amount of clicks result from this channel
		- Conversion: int - amount of conversions result from this channel
- Content management
	- Create plain text/HTML messages
	- Multiple variants of the same content
		- Send appropriate content by language
	 	- If there is more then 1 variant of the same language - will be prioritized by results
	- Rate each content variant:
		- Used: int
		- Replay: int
		- Clicked: int
		- Conversion
	- Contact provider (Gmail, Hotmail, Verizon etc.)
	- Variables
		- Create placeholders in your templates
		- Variable behavior can be defined globally or per template
		- Variable behavior can be
			- Value override
			- Default value if not provided
			- Default value if empty
			 
- Contact Verification
	- Check contact detail against external sources to determine if its a valid channel (Valid, DNS, Reporter, invalid etc.)
	- Check who is the provider of that contact (Gmail, Voda phone, Verizon etc.)
- Campaign management
	- Select contact list + contact status (verified, replayed, clicked etc.)
	- Sequence of messages (content#1 today, content#2 tomorrow etc.)
	- Working hours, days and countries
	- Rules engine
		- Q: What to do if user clicked
		- Q: What to do if user didn't showed any action in last N# engagements etc.)
		- A: Move to campaign X
		- A: Freeze
- Auto optimization, Use better converting more often
	- Content variants, break down by:
		- Country
		- Channel type
		- Recipient Channel Provider (Gmail, Verizon etc.)
	- Sender senders, break down by:
		- Country
		- Recipient Channel Provider (Gmail, Verizon etc.)


## Generate the SSH keys

```
	$ mkdir app/jwt
	$ openssl genrsa -out app/jwt/private.pem -aes256 4096
	$ openssl rsa -pubout -in app/jwt/private.pem -out app/jwt/public.pem
```


# TODO:
UTests
RequestResponse structure
Start adding methods
	- CRUD list


## User registration
	- Email confirmation
	- Check if email and username are available
	- Capcha
	- Add Company country. state. phone + Google lib phone check
	- Reset Password
	- Exception -> no route found on ^/API