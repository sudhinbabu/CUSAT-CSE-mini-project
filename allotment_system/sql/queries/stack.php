<?php
class Stack
{
    protected $stack;
    protected $limit;
    protected $temp_array;
     
    public function __construct($limit = 10) {
        // initialize the stack
        $this->stack = array();
        // stack can only contain this many items
        $this->limit = $limit;
    }
 
    public function push($item) {
        // trap for stack overflow
        if (count($this->stack) < $this->limit) {
            // prepend item to the start of the array
            array_push($this->stack, $item);
        } else {
            throw new RunTimeException('Stack is full!'); 
        }
    }
 
    public function pop() {
        if ($this->isEmpty()) {
            // trap for stack underflow
          throw new RunTimeException('Stack is empty!');
      } else {
            // pop item from the start of the array
            return array_pop($this->stack);
        }
    }
 
    public function top() {
        return current($this->stack);
    }

    public function pop_from_index_till_end($index){
    	$this->temp =  array_slice($this->stack, $index);
    }





    public function push_all(){
    	

    }
 
    private function isEmpty() {
        return empty($this->stack);
    }

    public function get_stack(){
    	return $this->stack;
    }
}

$mystack = new Stack(4);
$mystack->push(array('id'=>60,'name'=>'first_come'));
$mystack->push(array('id'=>55,'name'=>'second_come'));
$mystack->push(array('id'=>58,'name'=>'third_come'));
print_r($mystack->get_stack()); 

$temp = $mystack->pop();
$temp2 = $mystack->pop();

$mystack->push($temp);
$mystack->push($temp2);
print_r($mystack->get_stack()); 

$input = array("a", "b", "c", "d", "e");

$output = array_slice($input, 2);      // returns "c", "d", and "e"

print_r($output);
