<?php
/**
 * 64 bit GUID for Minds-Elgg
 * 
 * 64 bits:
 * 
 * time - 41 bits (millisecond precision w/ a custom epoch gives us 69 years)
 * configured machine id - 10 bits - gives us up to 1024 machines
 * sequence number - 12 bits - rolls over every 4096 per machine (with protection to avoid rollover in the same ms)
 * 
 * 32 bits + 9 = 41 bits of time
 * 2199023255552 < milliseconds = 2199023255 seconds
 *                                2147483647 < max 31 bit int (signed)
 * @author Mark harding / @davegardnerisme
 */

class GUID{
    /**
     * Max timestamp
     */
    const MAX_ADJUSTED_TIMESTAMP = 2199023255551;
    
    /**
     * Hexdec lookup
     * 
     * @staticvar array
     */
    private static $hexdec = array(
        "0" => 0,
        "1" => 1,
        "2" => 2,
        "3" => 3,
        "4" => 4,
        "5" => 5,
        "6" => 6,
        "7" => 7,
        "8" => 8,
        "9" => 9,
        "a" => 10,
        "b" => 11,
        "c" => 12,
        "d" => 13,
        "e" => 14,
        "f" => 15
        );
    
    /**
     * Timer
     * 
     * @var TimerInterface
     */
    private $timer;
    
    /**
     * Configured machine ID - 10 bits (dec 0 -> 1023)
     *
     * @var integer
     */
    private $machine;
    
    /**
     * Epoch - in UTC millisecond timestamp
     *
     * @var integer
     */
    private $epoch = 1325376000000;
    
    /**
     * Sequence number - 12 bits, we auto-increment for same-millisecond collisions
     *
     * @var integer
     */
    private $sequence = 1;
    
    /**
     * The most recent millisecond time window encountered
     *
     * @var integer
     */
    private $lastTime = NULL;
    
    /**
     * Constructor
     * 
     */
    public function __construct(){
		global $CONFIG;
        $this->machine = isset($CONFIG->machine_id) ? $CONFIG->machine_id : 1;
        if (!is_int($this->machine) || $this->machine < 0 || $this->machine > 1023){
			$this->machine = 1;
        }
    }
    
    /**
     * Generate ID
     *
     * @return string A 64 bit integer as a string of numbers (so we can deal
     *      with this on 32 bit platforms) 
     */
    public function generate(){
        $t = floor($this->getUnixTimestamp() - $this->epoch);
        if(!$this->lastTime){
            
        }
        if ($t !== $this->lastTime) {
            if ($t < $this->lastTime) {
                throw new \UnexpectedValueException(
                        'Time moved backwards. We cannot generate IDs for '
                        . ($this->lastTime - $t) . ' milliseconds'
                        );
            } elseif ($t < 0) {
                throw new \UnexpectedValueException(
                        'Time is currently set before our epoch - unable '
                        . 'to generate IDs for ' . (-$t) . ' milliseconds'
                        );
            } elseif ($t > self::MAX_ADJUSTED_TIMESTAMP) {
                throw new \OverflowException(
                        'Timestamp overflow (past end of lifespan) - unable to generate any more IDs'
                        );
            }
            //$this->sequence = mt_rand(0,4094);
            $this->sequence = 0;
            $this->lastTime = $t;
        } else {
            $this->sequence++;
            if ($this->sequence > 4095) {
                throw new \OverflowException(
                        'Sequence overflow (too many IDs generated) - unable to generate IDs for 1 milliseconds'
                        );
            }
        }
        
        if (PHP_INT_SIZE === 4) {
            return $this->mintId32($t, $this->machine, $this->sequence);
        } else {
            return $this->mintId64($t, $this->machine, $this->sequence);
        }
    }

	public function __toString(){
		return $this->generate();
	}
	
    public function getUnixTimestamp(){
        return floor(microtime(TRUE) * 1000);
    }   
 
    /**
     * Get stats
     *
     * @return array
     */
    public function status()
    {
        return array(
            'machine'   => $this->machine,
            'lastTime'  => $this->lastTime,
            'sequence'  => $this->sequence,
            'is32Bit'   => (PHP_INT_SIZE === 4)
            );
    }
    
    private function mintId32($timestamp, $machine, $sequence)
    {
        $hi = (int)($timestamp / pow(2,10));
        $lo = (int)($timestamp * pow(2, 22));
        
        // stick in the machine + sequence to the low bit
        $lo = $lo | ($machine << 12) | $sequence;

        // reconstruct into a string of numbers
        $hex = pack('N2', $hi, $lo);
        $unpacked = unpack('H*', $hex);
        $value = $this->hexdec($unpacked[1]);
        return (string)$value;
    }
    
    private function mintId64($timestamp, $machine, $sequence)
    {
        $timestamp = (int)$timestamp;
        $value = ($timestamp << 22) | ($machine << 12) | $sequence;
        return (string)$value;
    }
    
    private function hexdec($hex)
    {
        $dec = 0;
        for ($i = strlen($hex) - 1, $e = 1; $i >= 0; $i--, $e = bcmul($e, 16)) {
            $factor = self::$hexdec[$hex[$i]];
            $dec = bcadd($dec, bcmul($factor, $e));
        }
        return $dec;
    }

/** 
 * Convert an old style Minds GUID to the new structure (make it a minimum 18 characters)
 */
public function migrate($old_guid){
	$count = strlen($old_guid);
	if($count >= 18){ return (int) $old_guid; }

	$base_int = "100000000000000000";
	
	$prepend = (int) substr($base_int, 0, 18-$count);
	
	$new_guid = $prepend . $old_guid; 

	return (int) $new_guid;
}
}
