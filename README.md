# GHU - Guess Highest Unique

The Guess Highest Unique number "lottery" involves three processes
* Registration - Lottery participants register and receive a TOKEN
* Guess - Registration period ends and participants guess a number between 1 and NUMBER_OF_PARTICIPANTS
* Results - The highest number only picked by a single participant wins

## TODO:

 - [x] Add instructions or guidance to register.php or main overview
 - [ ] Update files based on new PHP knowledge
 - [x] Setup "meta" table to hold current mode, NUMBER_OF_PARTICIPANTS
 - [x] Make table hold data on historial GHU results, HGHU->{ID,DATE,WTOKEN,WGUESS,CSV}
   - [ ] results.php can read the newest row of this table and display results accordingly
 - [x] Rework Crontab to change modes Registration/Guess/Results at correct times (midnight?)
 - [x] Write additional script to handle changing the mode (run by Cron)
    - [x]  Not in /public_html
 - [ ] Test cases
    - [ ] Python script to send registration requests
    - [ ] Python script to send guess requests
 - [x] CSV output of previous draw/game
   - [ ] Needs testing
 - [ ] Payment integration (PayPal?)
 - [ ] Main overview page (maybe just redirect to the current period's page?)
   - Ajax could be used to load the correct form depending on period ?
 - [x] Create the SQL command to generate the GHU and META tables
   - [x] GHU
   - [x] META
   - [x] HGHU
 - [x] If META->NOP == META->NOG then we can end the guessing period early
 - [ ] Persistent registration (Remember me button?)
 - [ ] Email me when GP active button?
 - [ ] If less than 10 participants have registered delay GP until META->NOP >= 10

## Main Files

```
register.php
```
 - [x] Disable Registration during Guess/Result mode
    - META->STATE must be 1 to permit registration
 - [x] Displays META->NOP
 - [x] Checkbox - "Email me when the guessing period begins"
 - [x] Checkbox - "Email me when the results are published"
 - [x] Checkbox - "Email me when I can register for the next round"

```
token.php
```
 - [x] Prevent Duplicate TOKENS
 - [x] Prevent INSERTING new registrations when STATE != 1
 - [x] Should token.php update META->NOP each time a new participant registers ?
   - [x] If this is going ahead then register.php can be updated to use META->NOP instead of $res->num_rows

```
guess.php
```
 - [x] Needs to know META->NOP and prevent guessing of invalid numbers
 - [x] Needs to update META->NOG (Number of guesses)

```
countdown.php
```
 - [ ] Timezones, Server Time, Local Time
    - See test.php
 - [x] Limit form input between 1 - META->NOP
 - [x] Beter display the allowed guessing range
 - [x] Display META->NOP and META->NOG
   - From this it is possible to calculate how many participants have (not) guessed, maybe display a fraction NOG/NOP ?
 - [ ] The use of $success is failing (code scope issue?)


```
results.php
```
 - [x] Create this, can use existing Codepen version
 - [x] Add indication to results page to show tokens
 - [x] Add menu bar (register, countdown, results)
 - [x] Make "The Results Are In" text dynamic (META->STATE)
 - [ ] Link to current results CSV
 - [ ] Link to historical results CSVs

```
panel.php
```
 - [ ] Create this
 - [ ] Show NOP
 - [ ] Show NOG (number of guesses)
 - [ ] Ability to run meta script without remotely accessing xvk3
 - [ ] Security?

## Meta Files

```
wipe_db.php
```

```
permit_registration.php
 - [ ] Send email to all participants who have the GHU->SERP
   - This is harder because GHU is wiped before permit_registration.php can access GHU
```

```
permit_guesses.php
```
 - [ ] Send email to all participants who have the GHU->SEGP


```
calculate_winner.php
```
 - [x] Create this
 - [x] Calculate highest unique
 - [x] Update META->WTOKEN
 - [x] Generate CSV
 - [ ] Send email to winner
 - [ ] Send email to all participants who have the GHU->SEFP
