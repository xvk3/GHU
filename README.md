# GHU - Guess Highest Unique

The Guess Highest Unique number "lottery" involves three processes
* Registration - Lottery participants register and receive a TOKEN
* Guess - Registration period ends and participants guess a number between 1 and NUMBER_OF_PARTICIPANTS
* Results - The highest number only picked by a single participant wins

## TODO:

 - [] Update files based on new PHP knowledge
 - [] Setup "meta" table to hold current mode, NUMBER_OF_PARTICIPANTS, winner's TOKEN
 - [] Rework Crontab to change modes Registration/Guess/Results at correct times (midnight?)
 - [] Write additional script to handle changing the mode (run by Cron)
    - []  Not in /public_html
 - [] Test cases
    - []  Python script to send requests?
 - [] CSV output of previous draw/game
 - [] Payment integration (PayPal?)
 - [] Main overview page

## Files

```
register.php
```
 - [x] Disable Registration during Guess/Result mode
    - META->STATE must be 1 to permit registration

```
token.php
```
 - [x] Prevent Duplicate TOKENS
 - [] Prevent INSERTING new registrations when STATE != 1
```
guess.php
```
 - [] Needs to know NUMBER_OF_PARTICIPANTS and have limits on the form
    - Use JS to limit the input
```
countdown.php
```
 - [] Timezones, Server Time, Local Time
    - See test.php

```
results.php
```
 - [] Create this, can use existing Codepen version
