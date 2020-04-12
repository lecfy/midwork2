<?php namespace System;

class Controller
{
    /*
     * empty constructor
     */
    public function __construct(){}

    /*
     * validates input fields
     */
    public function validate($input, $rules)
    {
        $rules = explode('|', $rules);

        foreach ($rules as $rule) {
            $input_name = '<B>' . ucfirst($input) . '</B>';

            if ($rule == 'required') {
                if (empty($_POST[$input])) {
                    set_alert("$input_name is required", 'danger');
                    back_with_input();
                }
                continue;
            } elseif (preg_match('/min:([0-9]+)/', $rule, $matches)) {
                if (strlen($_POST[$input]) < $matches[1]) {
                    set_alert("$input_name must be minimum $matches[1] characters", 'danger');
                    back_with_input();
                }
                continue;
            } elseif (preg_match('/max:([0-9]+)/', $rule, $matches)) {
                if (strlen($_POST[$input]) > $matches[1]) {
                    set_alert("$input_name can only be $matches[1] characters maximum", 'danger');
                    back_with_input();
                }
                continue;
            }
        }

    }



}