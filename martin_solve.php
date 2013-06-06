<?php

$FEE  = 0.00; // Flat fee per bet
$ITER = 24;   // Number of binary search iterations.

if ($argc < 4) { die("ERROR: Expected [START] [BANK] [MIN_ROUND] [MAX_ROUND]\n"); }
$start = floatval($argv[1]);
$bank  = floatval($argv[2]);
$min   = intval(min($argv[3], $argv[4])); // Maybe they put them in backwards?
$max   = intval(max($argv[3], $argv[4]));
if ($min < 1) { die("ERROR: Minimum rounds must be greater than one\n"); }

// Display input info to user
echo "Start: $start\n";
echo "Bank:  $bank\n";
echo "Min R: $min\n";
echo "Max R: $max\n";
echo "\n";

// Build result table
printf("%8s %14s %14s\n", "Rounds", "Incr w/ Fees", "Min Reward");
printf("%s\n", str_repeat("=", 48));
for ($r = $min; $r <= $max; $r++) {
    $ans = solve($start, $bank, $r);
    
    if ($ans > 1) {
        printf("%8d %14.5f %14.5f\n", $r, $ans, 1/($ans-1) + 1);
    }
}

exit(0);



// Binary search to find the answer.
function solve($start, $bank, $rounds) {
    $stop = -1 * $GLOBAL['ITER'];
    $ans  = 2;
    
    // Fees alone will prevent success, don't try solving
    if ($GLOBALS['FEE'] * $rounds >= $bank) { return 0; }
    
    // Get within a ballpark of the correct answer
    while (eq($start, $rounds, $ans) < $bank) { $ans++; }
    
    // Gradually refine the answer
    for ($pow = -1; $pow >= $stop; $pow--) {
        $res = eq($start, $rounds, $ans);
        
        // Fudge downwards
        if ($res > $bank) {
            $ans -= pow(2.0, $pow);
            
        // Fudge upwards
        } elseif ($res < $bank) {
            $ans += pow(2.0, $pow);
        
        // Huh, we're spot on.
        } else {
            return $ans;
        }
    }
    
    // Round the result.  It *should* be accurate to log(2)/log(10) * ITER decimal places.
    return round($ans, 6);
}

function eq($start, $rounds, $incr) {
    return ($start * (1-pow($incr,$rounds))/(1-$incr) + $GLOBALS['FEE']*$rounds);
}
?>