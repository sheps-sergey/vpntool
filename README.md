# VPN Tool
VPN Tool

## Install
 
1. run `docker-compose build && docker-compose up -d`
2. add to \etc\hosts `127.0.0.1 app.local`
3. go to php-container terminal and type `composer intsall`
4. type app.local in browser window

## How it works
By /registration route you can make a registration. After that will be available
to login by /login route. After logginign you will redirect at the /follow page
who will track your IP-address and store this data in DB. If your user has
"ROLE_ADMIN", you will able to go to /admin route with a list of other users connections

## Current problems
1. We need to find better IP location provider. The current allowes make 350
requests per day and 10 000 per month for free. To avoid the case when we need to
pay for it every result saves in "location" table in DB. But as first is it not enought
and as second we need to actualize this data one time at month at least
2. We need to deploy this service in some place with white IP address to have
an ability to check external connections
3. Nessesary detect how long we should to store session data and how to provide it for an admin