# GHU - Guess Highest Unique

The Guess Highest Unique number "lottery" involves three processes
* Registration - Lottery participants register and receive a TOKEN
* Guess - Registration period ends and participants guess a number between 1 and NUMBER_OF_PARTICIPANTS
* Results - The highest number only picked by a single participant wins

## TODO:

 - [] Add instructions or guidance to register.php or main overview
 - [] Update files based on new PHP knowledge
 - [] Setup "meta" table to hold current mode, NUMBER_OF_PARTICIPANTS, winner's TOKEN
 - [] Rework Crontab to change modes Registration/Guess/Results at correct times (midnight?)
 - [] Write additional script to handle changing the mode (run by Cron)
    - []  Not in /public_html
 - [] Test cases
    - []  Python script to send registration requests
    - [] Python script to send guess requests
 - [] CSV output of previous draw/game
 - [] Payment integration (PayPal?)
 - [] Main overview page (maybe just redirect to the current period's page?)
   - Ajax could be used to load the correct form depending on period ?

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
 - [x] Prevent INSERTING new registrations when STATE != 1
 - [] Should token.php update META->NOP each time a new participant registers ?

```
guess.php
```
 - [x] Needs to know META->NOP and prevent guessing of invalid numbers

```
countdown.php
```
 - [] Timezones, Server Time, Local Time
    - See test.php
 - [x] Limit form input between 1 - META->NOP
 - [x] Beter display the allowed guessing range

```
results.php
```
 - [x] Create this, can use existing Codepen version
 - [] Add indication to results page to show tokens (tooltip or popup?)
 - [x] Add menu bar (register, countdown, results)
 - [x] Make "The Results Are In" text dynamic (META->STATE)

```
calculate_winner.php
````
 - [x] Create this
 - [x] Calculate highest unique
 - [x] Update META->WTOKEN
 - [] Send email to winner

```
panel.php
```
 - [] Create this
 - [] Show NOP
 - [] Show NOG (number of guesses)
 - [] Ability to run meta script without remotely accessing xvk3
