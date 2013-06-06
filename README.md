Martingale Solver
==================

This is a rudamentary Martingale betting method "solver".
Given an initial bet amount and a target bankroll, the script will provide the bet multiplicand required to reach the target round number.


Usage
------

The script accepts four parameters: the starting bet, available bankroll, the minimum number of rounds, the maximum number of rounds.

    php martin_solve.php INITIAL_BET BANKROLL MIN_ROUNDS MAX_ROUNDS


Example
-------

Say you're placing a $1 bet and have a $100 bankroll.
Yout want to know what to multiply your bet by to survive anywhere from 2 to 10 rounds:

    php martin_solve.php 1 100 2 10

    Start: 1
    Bank:  100
    Min R: 2
    Max R: 10

      Rounds   Incr w/ Fees     Min Reward
    ================================================
           2       99.00000        1.01020
           3        9.46243        1.11817
           4        4.24877        1.30781
           5        2.84110        1.54316
           6        2.23439        1.81011
           7        1.90692        2.10263
           8        1.70536        2.41772
           9        1.57019        2.75380
          10        1.47394        3.10999

The results indicate that in order to survive to exactly round 6, we'd need to multiply our bet by ~2.234 after each loss.  If we lose the 6th round, we're out 100% of our bankroll.
Also worth noting is the Minimum Reward column.  If the game's payout is _less than_ the minimum reward, making profit on a win is no longer guaranteed.
For example, lets assume you're playing a 50/50 game with a payout of exactly 2 but use the multiplicand specified for 10 rounds.  If you win on round 10, you'll be left with _less_ than your initial bankroll.


Notes
-----

The algorithm used to determine the correct multiplicand is based on the [sum of a geometric series](https://en.wikipedia.org/wiki/Geometric_series#Sum).
That is to say, BANKROLL = BET * (1 - INCREMENT^ROUNDS)/(1 - INCREMENT).
Since it's non-trivial to isolate INCREMENT and solve for it, the script uses a binary search to hone in on the correct solution.


Boring Stuff
------------

The script also has two constant values that can be menually tweaked.
* FEE - A flat rate fee per bet.  This is useful for some spplications (bitcoin betting).
* ITER - Number of binary search iterations when solving the geometric sum.

This script will not solve instances where the bet increment is less than 1.  It just doesn't make sense.
No matter what this program suggests, there will always be a possibility of losing your entire bankroll.  That's how gambling works.