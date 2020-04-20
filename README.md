# GHU - Guess Highest Unique

The Guess Highest Unique number "lottery" involves three processes
* Registration - Lottery participants register and receive a TOKEN
* Guess - Registration period ends and participants guess a number between 1 and NUMBER_OF_PARTICIPANTS
* Results - The highest number only picked by a single participant wins

## TODO:

* Update files based on new PHP knowledge
* Setup "meta" table to hold current mode, NUMBER_OF_PARTICIPANTS, winner's TOKEN
* Rework Crontab to change modes Registration/Guess/Results at correct times (midnight?)
* Write additional script to handle changing the mode (run by Cron)
* Test cases
* CSV output of previous draw/game
* Payment integration (PayPal?)

## Files

```
register.php
```

```
token.php
```

```
guess.php
```

```
countdown.php
```
