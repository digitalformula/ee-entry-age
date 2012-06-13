<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
$plugin_info       = array(
	'pi_name'        => 'Entry Age',
	'pi_version'     => '1.0',
	'pi_author'      => 'Chris Rasmussen',
	'pi_author_url'  => 'http://digitalformula.net',
	'pi_description' => 'Returns a configurable warning message if the entry being viewed is older than a certain age.',
	'pi_usage'       => entry_age::usage()  
	);  
  
class Entry_age  
{  
  
	function Entry_age()  
	{

		$this->EE =& get_instance();

		$unit = $this->EE->TMPL->fetch_param('unit') != '' ? $this->EE->TMPL->fetch_param('unit') : 'days';
		$max_age_in = $this->EE->TMPL->fetch_param('max_age') != '' ? $this->EE->TMPL->fetch_param('max_age') : 90;
		$entry_epoch = $this->EE->TMPL->fetch_param('entry_date') != '' ? $this->EE->TMPL->fetch_param('entry_date') : 0;
		$current_epoch = date('U');
		
		/*
			get the template that shows the return message, if one has been supplied
			a 'basic' default template is used if a template has not been provided - please edit this as necessary
		*/
		$return_template = $this->EE->TMPL->tagdata != '' ? $this->EE->TMPL->tagdata : 'Warning: This entry is old and should only be used at your own risk!';

		/*
			some numbers to use in calculations
			please note that a month has been set at 31 days as it suits the purposes of the first version of this plugin
		*/
		$one_day = (60*60*24);
		$one_week = $one_day * 7;
		$one_month = $one_day * 31;
		$one_year = $one_day * 365;

		/*
			figure out how long the maximum age is in seconds
			if not unit is specified, the plugin defaults to using days as the unit of measurement
		*/
		
		switch ($unit)
		{
			case 'days':
	            $max_age = $max_age_in * $one_day;
	            break;
			case 'weeks':
				$max_age = $max_age_in * $one_week;
				break;
			case 'months':
				$max_age = $max_age_in * $one_month;
				break;
			case 'years':
				$max_age = $max_age_in * $one_year;
				break;
			default:
				$max_age = $max_age_in * $one_day;
				break;
		}

		$entry_age = $current_epoch - $entry_epoch;
		
		/*
			set the return message based on whether or not the entry is older than the specified age
		*/
		
		$this->return_data = ($entry_age > $max_age) ? $return_template : '';

	}
  
	/*
		plugin usage instructions/documentation
	*/	  
  
	function usage()  
	{  
		ob_start();  
		?>
		
		Example 1, including HTML-formatted warning message:
		
		{exp:entry_age unit="days" max_age="90" entry_date="{entry_date}"}
		   <section class="highlight">Warning: This article is older than 90 days and may contain inaccurate information.&nbsp;&nbsp;Please use the information below at your own risk.</section>
		{/exp:entry_age}
		
		Example 2, using default warning message:
		
		{exp:entry_age unit="days" max_age="90" entry_date="{entry_date}"}
	
		Three parameters can be passed:

		- 'unit' : <days|weeks|months|years> - pick one, based on your requirements.  This setting will default to 'days' if not specified.
		- 'max_age' : <maximum age of entry> - The maximum age of the entry before the warning message will be shown.  This setting will default to 90 if not specified.  Ommiting this parameter may result in fairly useless data if used with anything other than 'days' above.
			
		- 'entry_date' : Because this is my first plugin I'm yet to figure out how to get the current entry's date inside the plugin, forcing me to require it as a parameter.  :(  For that reason, this parameter should only be '{entry_date}' as shown above.

		The text inside the {exp:entry_age}{/exp:entry_age} tag pairs is the text that will be displayed if the entry is older than the specified date.  Ommiting this parameter will display the default value set above, if the entry's age does not meet requirements.
	  
		<?php  
		$buffer = ob_get_contents();
  
		ob_end_clean();   
  
		return $buffer;  
	}  
  
}  
  
/* End of file pi.entry_age.php */  
/* Location: ./system/expressionengine/third_party/entry_age/pi.entry_age.php */
